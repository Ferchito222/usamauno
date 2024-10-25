<?php

namespace Sibas\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Sibas\Entities\Agency;
use Sibas\Entities\City;
use Sibas\Entities\Permission;
use Sibas\Entities\Profile;
use Sibas\Entities\Retailer;
use Sibas\Entities\User;
use Sibas\Repositories\Retailer\AgencyRepository;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\UserRepository;

trait ReportTrait
{

    /**
     * @var AgencyRepository
     */
    protected $agencyRepository;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var Retailer
     */
    protected $retailer;

    /**
     * @var Collection
     */
    protected $cities;

    /**
     * @var Collection
     */
    protected $agencies;

    /**
     * @var Collection
     */
    protected $users;


    protected function getInstance()
    {
        $this->cityRepository   = new CityRepository();
        $this->agencyRepository = new AgencyRepository();
        $this->userRepository   = new UserRepository();
        $this->dataRepository     = new \Sibas\Repositories\De\DataRepository();
    }


    /**
     * @param Model|User $user
     * @param string     $code
     *
     * @return array
     */
    public function data(User $user, $code = null)
    {
        $this->getInstance();

        $this->user     = $user;
        $this->profile  = $this->user->profile()->first();
        $this->retailer = $this->user->retailerUser->retailer;

        $this->getDataForReport();

        $data = [
            'cities'   => $this->cities,
            'agencies' => $this->agencies,
            'users'    => $this->users,
            'account_number_types'  => $this->dataRepository->getAccountNumberTypes(),
            'types_of_requests'     => $this->dataRepository->getTypesOfRequests(),
            'yes_no'                => $this->dataRepository->getYesNo(),
        ];

        if ($code === 'de') {
            $retailerProduct = $this->retailer->retailerProducts()
                ->with([ 'creditProducts' ])
                ->has('creditProducts')
                ->whereHas('companyProduct.product', function ($q) {
                    $q->where('code', 'de')
                        ->where('active', true);
                })
                ->where('type', 'MP')
                ->first();

            $data['retailerProduct'] = $retailerProduct;
        }

        return $data;
    }


    /**
     * @param Request $request
     * @param Builder $builder
     * @param string|null $product
     * @param string $type
     */
    protected function filtersByHeader(Request $request, $builder, $product = null, $type = '')
    {
        ini_set('memory_limit', -1);
        set_time_limit(0);
        $user     = $request->user();
        $client   = 'details.client';
        $detail   = 'details';
        $username = '';
        $agency   = '';
        $city     = '';

        if (! $request->route()->hasParameter('guest')) {
            if ($request->has('city')) {
                $city = $request->get('city');
            } elseif ($user instanceof User) {
                $p = $user->permissions->contains(function ($key, $item) {
                    return $item->slug === 'RR';
                });

                if ($p) {
                    $c = $user->city;

                    if ($c instanceof City) {
                        $city = $c->slug;
                    }
                }
            }
        }

        if (! $request->route()->hasParameter('guest')) {
            if ($request->has('agency')) {
                $agency = $request->get('agency');
            } elseif ($user instanceof User) {
                $p = $user->permissions->contains(function ($key, $item) {
                    return $item->slug === 'RA';
                });

                if ($p) {
                    $a = $user->agency;

                    if ($a instanceof Agency) {
                        $agency = $a->slug;
                    }
                }
            }
        }

        if (! $request->route()->hasParameter('guest')) {
            if ($request->has('username')) {
                $username = $request->get('username');
            } elseif ($user instanceof User) {
                $p = $user->permissions->contains(function ($key, $item) {
                    return $item->slug === 'RU';
                });

                $username = $p ? $user->username : '';
            }
        }

        if ($product === 'au' || $product === 'td' || $product === 'ema' || $product === 'rma') {
            $client = 'client';
        } elseif ($product === 'elp' || $product === 'eb') {
            $client = 'detail.client';
            $detail = 'detail';
        }

        if ($request->has('policy_number')) {
            if ($type === 'OP') {
                $builder->whereHas('header', function ($q) use ($request) {
                    $q->where('issue_number', $request->get('policy_number'));
                });
            } else {
                $builder->where('issue_number', $request->get('policy_number'));
            }
        } elseif ($request->has('quote_number')) {
            $builder->where('quote_number', $request->get('quote_number'));
        }

        if ($request->has('operation_number')) {
            $builder->where('operation_number', 'like', '%' . $request->get('operation_number') . '%');
        }

        if ($product === 'de' && $request->has('type_of_request'))
            $builder->where('type_of_request', $request->get('type_of_request'));

        if($product === 'tj'){
            if($request->has('card'))
                $builder->whereHas('details', function ($q) use ($request) {
                    $q->where('card', $request->get('card'));
                });   
        }

        if($product === 'de'){
            if($request->has('cesantia'))
                $builder->whereHas('details', function ($q) use ($request) {
                    $q->where('cesantia', $request->get('cesantia'));
                });   
        }

        if ($type === 'OP') {
            $builder->where(function ($q) use ($request, $city) {
                $q->whereHas('header.user.city', function ($q) use ($request, $city) {
                    $q->where('slug', 'like', '%' . $city . '%');
                })
                    ->orWhereHas('user.city', function ($q) use ($request, $city) {
                        $q->where('slug', 'like', '%' . $city . '%');
                    });
            });

            $builder->where(function ($q) use ($request, $agency, $user) {
                $q->whereHas('header.user.agency', function ($q) use ($request, $agency, $user) {
                    $q->where(function ($q) use ($request, $agency, $user) {
                        $q->where('slug', 'like', '%' . $agency . '%');

                        if (! $request->has('agency')) {
                            $q->orWhereIn('id', $user->additional_agencies);
                        }
                    });
                })
                    ->orWhereHas('user.agency', function ($q) use ($request, $agency) {
                        $q->where(function ($q) use ($request, $agency) {
                            $q->where('slug', 'like', '%' . $agency . '%');
                        });
                    });
            });

            $builder->where(function ($q) use ($request, $username) {
                $q->whereHas('header.user', function ($q) use ($request, $username) {
                    $q->where('username', 'like', '%' . $username . '%');
                })
                    ->orWhereHas('user', function ($q) use ($request, $username) {
                        $q->where('username', 'like', '%' . $username . '%');
                    });
            });
        } else {
            $builder->whereHas('user.city', function ($q) use ($request, $city) {
                $q->where('slug', 'like', '%' . $city . '%');
            });

            $builder->whereHas('user.agency', function ($q) use ($request, $agency, $user) {
                $q->where(function ($q) use ($request, $agency, $user) {
                    $q->where('slug', 'like', '%' . $agency . '%');

                    if (! $request->has('agency')) {
                        $q->orWhereIn('id', $user->additional_agencies);
                    }
                });
            });
            
            $builder->whereHas('user', function ($q) use ($request, $username) {
                if($username != '')
                    $q->where('username', $username);
                else
                    $q->where('username', 'like','%'.$username.'%');

                if ($request->route()->getName() === 'de.issue.lists' && $request->route()->hasParameter('guest')) {
                    $q->where('username', 'guest');
                } else {
                    $q->where('username', '!=', 'guest');
                }
            });
        }

        if ($request->route()->getName() === 'de.issue.lists' && $request->route()->hasParameter('guest')) {
            $builder->whereHas('guest', function ($q) use ($user) {
                if ($user instanceof User) {
                    $q->where('ad_city_id', $user->ad_city_id);
                }
            });
        }

        if ($request->has('client')) {
            if ($type === 'OP') {
                $builder->whereHas("header.$client", function ($q) use ($request) {
                    $q->where(function ($q1) use ($request) {
                        $q1->orWhere('first_name', 'like', '%' . $request->get('client') . '%')->orWhere('last_name',
                            'like', '%' . $request->get('client') . '%')->orWhere('mother_last_name', 'like',
                            '%' . $request->get('client') . '%');
                    });
                });
            } else {
                $builder->whereHas($client, function ($q) use ($request) {
                    $q->where(function ($q1) use ($request) {
                        $q1->orWhere('first_name', 'like', '%' . $request->get('client') . '%')->orWhere('last_name',
                            'like', '%' . $request->get('client') . '%')->orWhere('mother_last_name', 'like',
                            '%' . $request->get('client') . '%');
                    });
                });
            }
        }

        if ($request->has('dni')) {
            if ($type === 'OP') {
                $builder->where(function ($q) use ($request, $client) {
                    $q->whereHas("header.$client", function ($q) use ($request) {
                        $q->where('dni', 'like', '%' . $request->get('dni') . '%');
                    })
                        ->orWhereHas('clients', function ($q) use ($request) {
                            $q->where('dni', 'like', '%' . $request->get('dni') . '%');
                        });
                });
            } else {
                $builder->whereHas($client, function ($q) use ($request) {
                    $q->where('dni', 'like', '%' . $request->get('dni') . '%');
                });
            }
        }

        if ($request->has('extension')) {
            if ($type === 'OP') {
                $builder->where(function ($q) use ($request, $client) {
                    $q->whereHas("header.$client", function ($q) use ($request) {
                        $q->where('extension', 'like', '%' . $request->get('extension') . '%');
                    })
                        ->orWhereHas('clients', function ($q) use ($request) {
                            $q->where('extension', 'like', '%' . $request->get('extension') . '%');
                        });
                });
            } else {
                $builder->whereHas($client, function ($q) use ($request) {
                    $q->where('extension', 'like', '%' . $request->get('extension') . '%');
                });
            }
        }

        if ($request->has('date_begin') && $request->has('date_end')) {
            $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

            if (preg_match($regex, $request->get('date_begin')) && preg_match($regex, $request->get('date_end'))) {
                $date_begin = $this->carbon->createFromFormat('Y-m-d', $request->get('date_begin'));
                $date_end   = $this->carbon->createFromFormat('Y-m-d', $request->get('date_end'));
            } else {
                $date_begin = $this->carbon->createFromFormat('d/m/Y', $request->get('date_begin'));
                $date_end   = $this->carbon->createFromFormat('d/m/Y', $request->get('date_end'));
            }

            if ($type === 'OP') {
                $builder->where(function ($q) use ($date_begin, $date_end) {
                    $q->whereHas('header', function ($q) use ($date_begin, $date_end) {
                        $q->whereBetween('created_at', [$date_begin->startOfDay(), $date_end->endOfDay()]);
                    })
                        ->orWhereBetween('created_at', [$date_begin->startOfDay(), $date_end->endOfDay()]);
                });
            } else {
                $date_condition = ($type=='ISS')?'date_issue':'created_at';
                $builder->whereBetween($date_condition, [$date_begin->startOfDay(), $date_end->endOfDay()]);
            }
        }

        /*
         * Estado
         */
        if ($request->has('cc2.state1')) {
            $builder->where(function ($q) use ($request) {
                if ($request->has('cc2.state1.pe')) {
                    $q->orWhere(function ($q) {
                        $q->where('issued', false)
                            ->where('facultative', true)
                            ->whereHas('details.facultative', function ($q) {
                                $q->where('state', 'PE')
                                    ->has('observations', '=', 0);
                            });
                    });
                }

                if ($request->has('cc2.state1.ob')) {
                    $q->orWhere(function ($q) {
                        $q->where('issued', false)
                            ->where('facultative', true)
                            ->whereHas('details.facultative', function ($q) {
                                $q->where('state', 'PE')
                                    ->has('observations', '>', 0);
                            });
                    });
                }

                if ($request->has('cc2.state1.sp')) {
                    $q->orWhere(function ($q) {
                        $q->where('issued', false)
                            ->where('facultative', true)
                            ->whereHas('details.facultative', function ($q) {
                                $q->where('state', 'PE')
                                    ->whereHas('observations', function ($q) {
                                        $q->latest()->where('response', true);
                                    });
                            });
                    });
                }
            });

            if ($request->has('cc2.state2')) {
                $builder->orWhere(function ($q) use ($request) {
                    $q->orWhere(function ($q) use ($request) {
                        $q->where('issued', false)
                            ->where('facultative', true)
                            ->whereHas('details.facultative', function ($q) use ($request) {
                                $q->where('state', 'PE')
                                    ->whereHas('observations', function ($q) use ($request) {
                                        $q->latest()->whereHas('state', function ($q) use ($request) {
                                            $q->where(function ($q) use ($request) {
                                                foreach ($request->get('cc2')['state2'] as $key => $item) {
                                                    $q->orWhere('slug', $key);
                                                }
                                            });
                                        });
                                    });
                            });
                    });
                });
            }
        }

        if ($request->has('cc2.state2')) {
            $builder->where(function ($q) use ($request) {
                $q->orWhere(function ($q) use ($request) {
                    $q->where('issued', false)
                        ->where('facultative', true)
                        ->whereHas('details.facultative', function ($q) use ($request) {
                            $q->where('state', 'PE')
                                ->whereHas('observations', function ($q) use ($request) {
                                    $q->latest()->whereHas('state', function ($q) use ($request) {
                                        $q->where(function ($q) use ($request) {
                                            foreach ($request->get('cc2')['state2'] as $key => $item) {
                                                $q->orWhere('slug', $key);
                                            }
                                        });
                                    });
                                });
                        });
                });
            });
        }

        /*
         * Aprobado
         */
        if ($request->has('cc1')) {
            $builder->where(function ($q) use ($request, $detail) {
                /*
                 * Free Cover
                 */
                if ($request->has('cc1.free_cover')) {
                    $q->where(function ($q) use ($request) {
                        if ($request->has('cc1.free_cover.1')) {
                            $q->orWhere(function ($q) {
                                $q->where('type', 'I')
                                    ->where('issued', true)
                                    ->where('facultative', false);
                            });
                        }

                        if ($request->has('cc1.free_cover.0')) {
                            $q->orWhere(function ($q) {
                                $q->where('type', 'I')
                                    ->where('facultative', true)
                                    ->whereHas('details.facultative', function ($q) {
                                        $q->where('state', 'PR')
                                            ->where('approved', true);
                                    });
                            });
                        }

                        if ($request->has('cc1.free_cover.2')) {
                            $q->orWhere(function ($q) {
                                $q->where('type', 'I')
                                    ->where('facultative', true)
                                    ->whereHas('details.facultative', function ($q) {
                                        $q->where('state', 'PR')
                                            ->where('approved', true)
                                            ->where('accidental_death', true);
                                    });
                            });
                        }
                    });
                }

                /*
                 * Extraprima
                 */
                if ($request->has('cc1.extra')) {
                    $q->where(function ($q) use ($request) {
                        if ($request->has('cc1.extra.1')) {
                            $q->orWhere(function ($q) {
                                $q->where('type', 'I')
                                    ->where('facultative', true)
                                    ->whereHas('details.facultative', function ($q) {
                                        $q->where('state', 'PR')
                                            ->where('approved', true)
                                            ->where('surcharge', true);
                                    });
                            });
                        }

                        if ($request->has('cc1.extra.0')) {
                            $q->orWhere(function ($q) {
                                $q->where('type', 'I')
                                    ->where('facultative', true)
                                    ->whereHas('details.facultative', function ($q) {
                                        $q->where('state', 'PR')
                                            ->where('approved', true)
                                            ->where('surcharge', false);
                                    });
                            });
                        }
                    });
                }

                /*
                 * Emitido
                 */
                if ($request->has('cc1.issued_gen')) {
                    $q->where(function ($q) use ($request) {
                        if ($request->has('cc1.issued_gen.1')) {
                            $q->orWhere('issued', true);
                        }

                        if ($request->has('cc1.issued_gen.0')) {
                            $q->orWhere('issued', false);
                        }
                    });
                }

                /*
                 * Rechazado
                 */
                if ($request->has('cc1.rejected')) {
                    $q->where(function ($q) {
                        $q->where('type', 'I')
                            ->where('facultative', true)
                            ->whereHas('details.facultative', function ($q) {
                                $q->where('state', 'PR')
                                    ->where('approved', false);
                            });
                    });
                }

                /*
                 * Anulados
                 */
                if ($request->has('cc1.canceled_gen')) {
                    $q->where(function ($q) use ($request) {
                        if ($request->has('cc1.canceled_gen.1')) {
                            $q->orWhere('canceled', true);
                        }

                        if ($request->has('cc1.canceled_gen.0')) {
                            $q->orWhere('canceled', false);
                        }
                    });
                }

                /*
                 * Hipotecario - Cesantía
                 */
                if ($request->has('cc1.pmo_gen') || $request->has('cc1.7x5')) {
                    $q->whereHas('creditProduct', function ($q) use ($request) {
                        $q->where(function ($q) use ($request) {
                            if ($request->has('cc1.pmo_gen')) {
                                if ($request->has('cc1.pmo_gen.1')) {
                                    $q->orWhere('slug', 'PMO');
                                }

                                if ($request->has('cc1.pmo_gen.0')) {
                                    $q->orWhere('slug', '!=', 'PMO');
                                }
                            }

                            if ($request->has('cc1.7x5')) {
                                if ($request->has('cc1.7x5.1')) {
                                    $q->orWhere('slug', '7x5');
                                }

                                if ($request->has('cc1.7x5.0')) {
                                    $q->orWhere('slug', '!=', '7x5');
                                }
                            }
                        });
                    });
                }

                /*
                 * Others
                 */
                if ($request->has('cc1.issued.1')) {
                    $q->orWhere('issued', true);
                }

                if ($request->has('cc1.issued.0')) {
                    $q->orWhere('issued', false);
                }

                if ($request->has('cc1.canceled.1')) {
                    $q->orWhere('canceled', true);
                }

                if ($request->has('cc1.canceled.0')) {
                    $q->orWhere('canceled', false);
                }

                /*
                 * Certificado Adjunto
                 */
                if ($request->has('cc1.attached')) {
                    $q->where(function ($q) use ($request) {
                        if ($request->has('cc1.attached.1')) {
                            $q->orWhereNotNull('file');
                        }

                        if ($request->has('cc1.attached.0')) {
                            $q->orWhereNull('file');
                        }
                    });
                }

                if ($request->has('cc1.is_client')) {
                    $q->orWhereHas($detail, function ($q1) use ($request) {
                        $q1->where(function ($q2) use ($request) {
                            if ($request->has('cc1.is_client.1')) {
                                $q2->orWhere('client_eco', true);
                            }

                            if ($request->has('cc1.is_client.0')) {
                                $q2->orWhere('client_eco', false);
                            }
                        });
                    });
                }
            });
        }


        if ($request->has('cc3')) {
            /*
             * Coberturas
             */
            if ($request->has('cc3.coverage')) {
                $builder->whereHas($detail . '.coverages', function ($q) use ($request) {
                    $coverages = $request->input('cc3.coverage');

                    $q->whereIn('coverage', array_keys($coverages));
                });
            }

            /*
             * Producto Crediticio
             */
            if ($request->has('cc3.creditProduct')) {
                $builder->whereHas('creditProduct', function ($q) use ($request) {
                    $creditProducts = $request->input('cc3.creditProduct');

                    $q->whereIn('slug', array_keys($creditProducts));
                });
            }
        }

        if ($request->has('state')) {
            if (key_exists('elp', $request->get('state'))) {
                $builder->whereHas('state', function ($q) use ($request) {
                    $q->where(function ($q2) use ($request) {
                        if ($request->has('state.elp.pe')) {
                            $q2->orWhere('slug', 'pe');
                        }

                        if ($request->has('state.elp.np')) {
                            $q2->orWhere('slug', 'np');
                        }

                        if ($request->has('state.elp.va')) {
                            $q2->orWhere('slug', 'va');
                        }

                        if ($request->has('state.elp.ca')) {
                            $q2->orWhere('slug', 'ca');
                        }
                    });
                });
            }
        }
    }

    protected function filtersByCollection(Request $request, $builder, $product = null, $type = null)
    {
        
        $user     = $request->user();
        $username = '';
        $agency   = '';
        $city     = '';
        $date_begin = '';
        $date_end = '';

        if ($request->has('city')) {
            $city = $request->get('city');
        } elseif ($user instanceof User) {
            $p = $user->permissions->contains(function ($key, $item) {
                return $item->slug === 'RR';
            });
            if ($p) {
                $c = $user->city;
                if ($c instanceof City) {
                    $city = $c->slug;
                }
            }
        }

        if ($request->has('agency')) {
            $agency = $request->get('agency');
        } elseif ($user instanceof User) {
            $p = $user->permissions->contains(function ($key, $item) {
                return $item->slug === 'RA';
            });
            if ($p) {
                $a = $user->agency;
                if ($a instanceof Agency) {
                    $agency = $a->slug;
                }
            }
        }

        if ($request->has('username')) {
            $username = ($request->get('username') == 'preapproved') ? '' : $request->get('username');
        } elseif ($user instanceof User) {
            $p = $user->permissions->contains(function ($key, $item) {
                return $item->slug === 'RU';
            });
            $username = $p ? $user->username : '';
        }

        $builder->whereHas('header.user.city', function ($q) use ($request, $city) {
            $q->where('slug', 'like', '%' . $city . '%');
        });

        $builder->whereHas('header.user.agency', function ($q) use ($request, $agency) {
            $q->where('slug', 'like', '%' . $agency . '%');
        });

        $builder->whereHas('header.user', function ($q) use ($request, $username) {
            $q->where('username', 'like', '%' . $username . '%');
        });

        $builder->whereHas('header', function ($q) use ($request) {
            $q->where('issued', '=', true);
        });
        
        if($request->has('issue_number'))
            $builder->whereHas('header', function ($q) use ($request) {
                $q->where('issue_number', $request->get('issue_number'));
            });
        
        if ($product == 'tj') {
            if ($request->has('code'))
                $builder->whereHas('header.details.client', function ($q) use ($request) {
                    $q->where('code', $request->get('code'));
                });
            if ($request->has('card'))
                $builder->whereHas('header.details', function ($q) use ($request) {
                    $q->where('card', $request->get('card'));
                });
            if ($request->has('card_number'))
                $builder->whereHas('header.details', function ($q) use ($request) {
                    $q->where('card_number', $request->get('card_number'));
                });
            if ($request->has('account_number'))
                $builder->whereHas('header.details', function ($q) use ($request) {
                    $q->where('account_number', $request->get('account_number'));
                });
            if ($request->has('savings_bank'))
                $builder->whereHas('header.details', function ($q) use ($request) {
                    $q->where('savings_bank', $request->get('savings_bank'));
                });
            if ($request->has('canceled'))
                $builder->whereHas('header', function ($q) use ($request) {
                    $q->where('canceled', $request->get('canceled'));
                });
        }

        if($request->has('charged'))
            $builder->where('charged', $request->get('charged')); 

        if($request->has('transaction_number'))
            $builder->where('transaction_number', $request->get('transaction_number')); 

        if ($request->has('date_begin') && $request->has('date_end')) {
            $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

            if (preg_match($regex, $request->get('date_begin')) && preg_match($regex, $request->get('date_end'))) {
                $date_begin = $this->carbon->createFromFormat('Y-m-d', $request->get('date_begin'));
                $date_end   = $this->carbon->createFromFormat('Y-m-d', $request->get('date_end'));
            } else {
                $date_begin = $this->carbon->createFromFormat('d/m/Y', $request->get('date_begin'));
                $date_end   = $this->carbon->createFromFormat('d/m/Y', $request->get('date_end'));
            }

            $builder->whereBetween('transaction_date', [$date_begin->startOfDay(), $date_end->endOfDay()]);
        }

        if ($request->has('date_begin2') && $request->has('date_end2')) {
            $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

            if (preg_match($regex, $request->get('date_begin2')) && preg_match($regex, $request->get('date_end2'))) {
                $date_begin2 = $this->carbon->createFromFormat('Y-m-d', $request->get('date_begin2'));
                $date_end2   = $this->carbon->createFromFormat('Y-m-d', $request->get('date_end2'));
            } else {
                $date_begin2 = $this->carbon->createFromFormat('d/m/Y', $request->get('date_begin2'));
                $date_end2   = $this->carbon->createFromFormat('d/m/Y', $request->get('date_end2'));
            }

            $builder->whereBetween('fee_date', [$date_begin2->startOfDay(), $date_end2->endOfDay()]);
        }

    }

    protected function getDataForReport()
    {
        $permission = $this->getPermissionForReport($this->user);

        /*
         * Cities
         */
        if ($permission === 'RU' || $permission === 'RA' || $permission === 'RR') {
            $this->cities = $this->cityRepository->getCityBySlug($this->retailer->id, $this->user->city->slug);
        } elseif ($permission === 'RN') {
            $this->cities = $this->cityRepository->getCitiesByRetailer($this->retailer->id);
        }

        /*
         * Agencies
         */
        if (( ! ( $this->user->agency instanceof Agency ) && $this->profile->slug === 'COP' ) || $permission === 'RN') {
            $this->agencies = $this->agencyRepository->getAgenciesByRetailer($this->retailer->id);
        } elseif ($permission === 'RU' || $permission === 'RA') {
            $this->agencies = $this->agencyRepository->getAgencyBySlug($this->retailer->id, $this->user->agency->slug);
        } elseif ($permission === 'RR') {
            $this->agencies = $this->agencyRepository->getAgenciesByCity($this->retailer->id, $this->user->city->id);
        }

        $aas = Agency::select(['slug as id', 'name'])
            ->whereIn('id', $this->user->additional_agencies)
            ->get();

        $this->agencies = $this->agencies->merge($aas);

        /*
         * Users
         */
        if ($permission === 'RU') {
            $this->users = $this->userRepository->getUserByUsername($this->retailer->id, $this->user->username);
        } elseif ($permission === 'RA') {
            $this->users = $this->userRepository->getUsersByAgency($this->retailer->id, $this->user->agency->id);
        } elseif ($permission === 'RR') {
            $this->users = $this->userRepository->getUsersByCity($this->retailer->id, $this->user->city->id);
        } elseif ($permission === 'RN') {
            $this->users = $this->userRepository->getAllUsers($this->retailer->id);
        }

        $uas = User::select(['username as id', 'full_name as name',])
            ->whereHas('agency', function ($q) {
                $q->whereIn('id', $this->user->additional_agencies);
            })
            ->get();

        $this->users = $this->users->merge($uas);

        $this->cities   = $this->cities->toArray();
        $this->agencies = $this->agencies->toArray();
        $this->users    = $this->users->toArray();

        if ($permission !== 'RU') {
            $users       = $this->getSelectOption();
            $this->users = $users->merge($this->users)->toArray();

            if ($permission === 'RN' || $permission === 'RR' || ($permission === 'RA' && count($this->user->additional_agencies) > 0)) {
                $agencies       = $this->getSelectOption();
                $this->agencies = $agencies->merge($this->agencies)->toArray();

                if ($permission === 'RN') {
                    $cities       = $this->getSelectOption();
                    $this->cities = $cities->merge($this->cities)->toArray();
                }
            }
        }
    }


    /**
     * @return Collection
     */
    protected function getSelectOption()
    {
        return collect([
            [
                'id'   => '',
                'name' => 'Todos',
            ],
        ]);
    }


    protected function getPermissionForReport($user)
    {
        $permission = null;

        foreach ($user->permissions as $permission) {
            if ($permission->slug === 'RN') {
                break;
            } elseif ($permission->slug === 'RR') {
                break;
            } elseif ($permission->slug === 'RA') {
                break;
            } elseif ($permission->slug === 'RU') {
                break;
            }
        }

        return ($permission instanceof Permission) ? $permission->slug : 'RU';
    }


    /**
     * REPORTE regla consulta
     *
     * @param type $pref
     * @param type $request
     * @param type $rp_state
     *
     * @return type
     */
    public function role($pref, $request, $rp_state = [])
    {
        $consult = [];
        $arr     = [];
        foreach ($consult as $key => $value) {
            if ($key == 0) {
                $arr['and'][] = $value;
            } else {
                $arr['or'][] = $value;
            }
        }
        $arr = $this->roleEstado($pref, $rp_state, $request);
        $arr = $this->roleAprobado($pref, $arr, $request);

        return $arr;
    }


    /**
     * REPORTE validacion consulta estado
     *
     * @param type $rp_state
     * @param type $request
     *
     * @return string
     */
    public function roleEstado($pref, $rp_state, $request)
    {
        $est1 = [];
        $con  = '`op_' . $pref . '_headers`.`issued` = 0 AND `op_' . $pref . '_headers`.`facultative` = 1 AND `op_' . $pref . '_facultatives`.`state` = "PE" AND ';
        # pendiente
        if ($request->get('pendiente')) {
            $est1['pendiente'] = '(' . $con . '(SELECT COUNT(odo.id) FROM op_' . $pref . '_observations as odo
                    WHERE odo.op_' . $pref . '_facultative_id = op_' . $pref . '_facultatives.id) = 0)';
        }
        # subsanado
        if ($request->get('subsanado')) {
            $est1['subsanado'] = '(' . $con . '(SELECT odo.response
                                    FROM op_' . $pref . '_observations AS odo
                                    WHERE odo.op_' . $pref . '_facultative_id = op_' . $pref . '_facultatives.id 
                                    ORDER BY odo.id DESC limit 0,1) = true) ';
        }
        # observado
        if ($request->get('observado')) {
            $est1['observado'] = '(' . $con . '(SELECT COUNT(odo.id) FROM op_' . $pref . '_observations as odo
                        WHERE odo.op_' . $pref . '_facultative_id = op_' . $pref . '_facultatives.id) > 0) ';
        }

        $est2 = [];
        # validacion con and
        foreach ($rp_state as $key => $value) {
            if ($request->get($value->states->slug)) {
                $est2[] = '(`op_' . $pref . '_headers`.`issued` = 0 AND `op_' . $pref . '_headers`.`facultative` =1 and `op_' . $pref . '_facultatives`.`state`="PE" and (SELECT stat.slug
                    FROM op_' . $pref . '_observations as odo
                    
                    inner join ad_states as stat on (odo.ad_state_id = stat.id)
                    WHERE odo.op_' . $pref . '_facultative_id = op_' . $pref . '_facultatives.id order by odo.id DESC limit 0,1) = "' . $value->states->slug . '")';
            }
        }

        $res = [];
        if (count($est1) > 0 && count($est2) > 0) {
            #ambos
            if ($request->has('observado')) {
                $observado = $est1['observado'];
                unset($est1['observado']);
                if (count($est1) > 0) {
                    $res['and'][] = [
                        '((' . implode($est1, 'OR') . ') OR (' . $observado . ' AND (' . implode($est2,
                            'OR') . ')) )' => 'block',
                    ];
                } else {
                    $res['and'][] = ['(' . $observado . ' AND (' . implode($est2, 'OR') . '))' => 'block'];
                }

            } else {
                $res['and'][] = ['((' . implode($est1, 'OR') . ') OR (' . implode($est2, 'OR') . '))' => 'block'];
            }

        } elseif (count($est1) > 0 && count($est2) === 0) {
            #estados
            $res['and'][] = ['(' . implode($est1, 'OR') . ')' => 'block'];
        } elseif (count($est1) == 0 && count($est2) > 0) {
            #subestados
            $res['and'][] = ['(' . implode($est2, 'OR') . ')' => 'block'];
        }

        return $res;
    }


    /**
     * REPORTE
     *
     * @param type $pref
     * @param type $arr
     * @param type $request
     *
     * @return int
     */
    public function roleAprobado($pref, $arr, $request)
    {

        if ($request->get('freecover') && $request->get('no_freecover')) {
            $fre = 'or';
        } else {
            $fre = 'and';
        }

        if ($request->get('freecover')) {
            $arr[ $fre ][] = [
                '`op_' . $pref . '_headers`.`issued`'      => 1,
                '`op_' . $pref . '_headers`.`facultative`' => 0,
            ];
        }

        # no freecover
        if ($request->get('no_freecover')) {
            $arr[ $fre ][] = [
                '`op_' . $pref . '_headers`.`facultative`'   => 1,
                '`op_' . $pref . '_facultatives`.`state`'    => 'PR',
                '`op_' . $pref . '_facultatives`.`approved`' => 1,
            ];
        }

        if ($request->get('emitido') && $request->get('no_emitido')) {
            $emi = 'or';
        } else {
            $emi = 'and';
        }

        # emitido
        if ($request->get('emitido')) {
            $arr[ $emi ][] = ['`op_' . $pref . '_headers`.`issued`' => 1];
        }

        # no emitido
        if ($request->get('no_emitido')) {
            $arr[ $emi ][] = ['`op_' . $pref . '_headers`.`issued`' => 0];
        }

        if ($request->get('extraprima') && $request->get('no_extraprima')) {
            $ext = 'or';
        } else {
            $ext = 'and';
        }

        # extraprima
        if ($request->get('extraprima')) {
            $arr[ $ext ][] = [
                '`op_' . $pref . '_facultatives`.`state`'     => 'PR',
                '`op_' . $pref . '_headers`.`facultative`'    => 1,
                '`op_' . $pref . '_facultatives`.`surcharge`' => 1,
            ];
        }

        # no extraprima
        if ($request->get('no_extraprima')) {
            $arr[ $ext ][] = [
                '`op_' . $pref . '_facultatives`.`state`'     => 'PR',
                '`op_' . $pref . '_headers`.`facultative`'    => 1,
                '`op_' . $pref . '_facultatives`.`surcharge`' => 0,
            ];
        }

        if ($request->get('rechazado') && $request->get('anulado')) {
            $rec = 'or';
        } else {
            $rec = 'and';
        }

        # rechazados
        if ($request->get('rechazado')) {
            $arr[ $rec ][] = [
                '`op_' . $pref . '_facultatives`.`approved`' => 0,
                '`op_' . $pref . '_facultatives`.`state`'    => 'PR',
            ];
        }

        # polizas anuladas
        if ($request->get('anulado')) {
            $arr[ $rec ][] = ['`op_' . $pref . '_headers`.`issued`' => 1, '`op_' . $pref . '_headers`.`canceled`' => 1];
        }

        return $arr;
    }

}
