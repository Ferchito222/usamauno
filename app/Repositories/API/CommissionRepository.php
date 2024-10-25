<?php

namespace Sibas\Repositories\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Retailer\Cashier;
use Sibas\Entities\Retailer\Commission;
use Sibas\Repositories\BaseRepository;

class CommissionRepository extends BaseRepository
{
    /**
     * @var Collection
     */
    protected $headers;
    /**
     * @var Collection
     */
    protected $campaigns;
    /**
     * @var array
     */
    protected $info;

    /**
     * @return Collection
     */
    public function getCashiers()
    {
        return Cashier::get();
    }

    /**
     * @param Request $request
     * @param string  $code
     * @param string  $type
     *
     * @return Collection
     */
    public function getDataCommissions($request, $code, $type)
    {
        $this->setModel($code);
        $this->getRelations($code);

        $this->headers = collect();
        $this->info    = [
            'bg' => [
                'daily'    => [ 1 => '#30ad94', 2 => '#068A72', 3 => 'Diario' ],
                'monthly'  => [ 1 => '#F39C11', 2 => '#D1820C', 3 => 'Mensual' ],
                'campaign' => [ 1 => '#4891bf', 2 => '#2A80B9', 3 => 'Campaña' ],
            ],
        ];

        if ($this->model instanceof Model) {
            try {
                switch ($code) {
                    case 'elp':
                        switch ($type) {
                            case 'daily':
                                $this->dataElpDaily($request);
                                break;
                            case 'monthly':
                                $this->dataElpMonthly($request);
                                break;
                            case 'campaign':
                                $this->dataElpCampaign($request);
                                break;
                        }

                        break;
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
                dd($this->errors);
            } catch (\BadMethodCallException $e) {
                $this->errors = $e->getMessage();
                dd($this->errors);
            }
        }

        return $this->headers;
    }


    /**
     * @param Request $request
     */
    private function dataElpDaily($request)
    {
        $date = $this->carbon->createFromFormat('Y-m-d', $request->get('day'));

        $this->headers = $this->model
            ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
            ->join('op_clients', 'op_elp_details.op_client_id', '=', 'op_clients.id')
            ->leftJoin('ad_cashiers as reference', 'op_elp_details.reference_id', '=', 'reference.id')
            ->leftJoin('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
            ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id')
            ->join('ad_cities', 'op_elp_headers.ad_city_id', '=', 'ad_cities.id')
            ->join('ad_agencies', 'op_elp_headers.ad_agency_id', '=', 'ad_agencies.id')
            ->join('ad_states', 'op_elp_headers.ad_state_id', '=', 'ad_states.id')
            ->join('ad_retailer_products', 'op_elp_headers.ad_retailer_product_id', '=', 'ad_retailer_products.id')
            ->join('ad_users', 'op_elp_headers.ad_user_id', '=', 'ad_users.id')
            ->join('ad_user_targets', 'ad_users.ad_user_target_id', '=', 'ad_user_targets.id')
            ->select([
                'op_elp_headers.id',
                DB::raw('IF(op_elp_details.client_eco = true, "Ecoprotección", "Ecovida") as product_text'),
                'ad_cities.name as city_name',
                'ad_agencies.name as agency_name',
                'ad_agencies.type as agency_type',
                'ad_users.full_name as user_full_name',
                DB::raw('IF(op_elp_details.client_eco = false and ad_cashiers.id is not null, "CAJERO ->", ad_user_targets.position) as user_position'),
                'ad_users.code as user_code',
                'op_elp_details.payment_method as payment_method',
                DB::raw('IF(op_elp_details.client_eco = false, 
                        IF(reference.id is not null, reference.full_name, op_elp_details.reference_other), 
                        "") as reference_full_name'),
                DB::raw('concat(op_clients.first_name, " ", op_clients.last_name, " ", op_clients.mother_last_name) 
                        as client_full_name'),
                'op_clients.dni as client_dni',
                'op_clients.extension as client_extension',
                'op_clients.gender as client_gender',
                'ad_states.state as state_name',
                DB::raw('date_format(op_elp_headers.date_issue, "%d/%m/%Y") as date_of_issue'),
            ])
            ->where('op_elp_headers.renewal', false)
            ->whereDate('op_elp_headers.date_issue', '=', $date->toDateString())
            ->where(function ($q) use ($date) {
                $q->where('op_elp_headers.canceled', false)
                    ->orWhere(function ($q) use ($date) {
                        $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->month)
                            ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                    });
            })
            ->orderBy('ad_cities.slug', 'asc')
            ->orderBy('ad_users.full_name', 'asc')
            ->get();
    }


    /**
     * @param Request $request
     */
    private function dataElpMonthly($request)
    {
        $month    = $request->get('month');
        $year     = $request->get('monthly_year');
        $c        = $this->campaigns->search(function ($item, $key) use ($month) {
            return $item['months']->keys()->contains($month);
        });
        /*
         * Resolvemos en que mes de la campaña estamos
         */
        $date              = $this->carbon->createFromDate($year, $month, 1);
        $campaign          = $this->campaigns[$c];
        $date_previous     = $date->copy()->subMonths(3);
        $c                 = $this->campaigns->search(function ($item, $key) use ($date_previous) {
            return $item['months']->keys()->contains($date_previous->month);
        });
        $campaign_previous = $this->campaigns[$c];
        $date_semester     = $date->copy()->subMonths(6);
        $c                 = $this->campaigns->search(function ($item, $key) use ($date_semester) {
            return $item['months']->keys()->contains($date_semester->month);
        });
        $campaign_semester = $this->campaigns[$c];

        $this->info[ 'date' ]              = $date;
        $this->info[ 'campaign' ]          = $campaign;
        $this->info[ 'date_previous' ]     = $date_previous;
        $this->info[ 'campaign_previous' ] = $campaign_previous;
        $this->info[ 'date_semester' ]     = $date_semester;
        $this->info[ 'campaign_semester' ] = $campaign_semester;

        $builder = $this->model
            ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
            ->leftJoin('ad_cashiers as reference', 'op_elp_details.reference_id', '=', 'reference.id')
            ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id')
            ->select([
                'op_elp_headers.id',
                DB::raw('IF(op_elp_details.client_eco = true, "Ecoprotección", "Ecovida") as product_text'),
                'op_elp_details.client_eco',
                'op_elp_headers.renewal',
            ]);

        $ep  = clone $builder;
        $el  = clone $builder;
        $el2 = clone $builder;

        $ep->join('op_elp_collections', 'op_elp_headers.id', '=', 'op_elp_collections.op_elp_header_id')
            ->leftJoin('ad_cities', 'op_elp_headers.ad_city_id', '=', 'ad_cities.id')
            ->join('ad_agencies', 'op_elp_headers.ad_agency_id', '=', 'ad_agencies.id')
            ->leftJoin('ad_retailer_city_agencies as arca', 'ad_agencies.id', '=', 'arca.ad_agency_id')
            ->join('ad_retailer_cities as arc', 'arca.ad_retailer_city_id', '=', 'arc.id')
            ->join('ad_cities as agency_city', 'arc.ad_city_id', '=', 'agency_city.id')
            ->join('ad_users', 'op_elp_headers.ad_user_id', '=', 'ad_users.id')
            ->join('ad_user_targets', 'ad_users.ad_user_target_id', '=', 'ad_user_targets.id')
            ->leftJoin('ad_user_assignments', 'ad_users.id', '=', 'ad_user_assignments.ad_user_id')
            ->addSelect([
                'ad_users.id as user_id',
                DB::raw('IF(ad_cities.name is null, agency_city.name, ad_cities.name) as city_name'),
                DB::raw('IF(ad_cities.slug is null, agency_city.slug, ad_cities.slug) as city_slug'),
                'ad_agencies.name as agency_name',
                'ad_agencies.type as agency_type',
                'ad_users.full_name as user_full_name',
                'ad_users.code as user_code',
                'ad_user_targets.position as user_position',
                DB::raw('ad_user_assignments.assignment as user_assignment'),
                DB::raw('IF(ad_user_targets.id = 1, 
                        IF(reference.id is null, op_elp_details.reference_other, reference.full_name), 
                        "") as reference_full_name'),
                DB::raw('IF(op_elp_details.client_eco = true, (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.ep_a
                            WHEN "B" THEN ad_user_targets.ep_b
                            END), (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.el_a
                            WHEN "B" THEN ad_user_targets.el_b
                            END)) as target'),
            ])
            ->where('op_elp_details.client_eco', true)
            ->where(function ($q) use ($date) {
                $q->where(function ($q) use ($date) {
                    $q->whereMonth('op_elp_headers.date_issue', '=', $date->month)
                        ->whereYear('op_elp_headers.date_issue', '=', $date->year);
                })->orWhere(function ($q) use ($date) {
                    $q->whereMonth('op_elp_headers.date_issue', '=', $date->copy()->subMonths(6)->month)
                        ->whereYear('op_elp_headers.date_issue', '=', $date->copy()->subMonths(6)->year);
                })->orWhere(function ($q) use ($date) {
                    $q->whereMonth('op_elp_headers.date_issue', '=', $date->copy()->subYears(1)->month)
                        ->whereYear('op_elp_headers.date_issue', '=', $date->copy()->subYears(1)->year);
                })->orWhere(function ($q) use ($date) {
                    $q->whereMonth('op_elp_collections.transaction_date', '=', $date->month)
                        ->whereYear('op_elp_collections.transaction_date', '=', $date->year)
                        ->where('op_elp_collections.charged', true)
                        ->where('op_elp_headers.renewal', true);
                });
            })
            ->where(function ($q) use ($date) {
                $q->where('canceled', false)
                    ->orWhere(function ($q) use ($date) {
                        $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->month)
                            ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                    });
            })
            ->whereDate('op_elp_headers.date_issue', '>', '2015-12-31')
        ->groupBy('ad_users.username');

        $el->join('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
            ->join('ad_cities as cashier_city', 'ad_cashiers.ad_city_id', '=', 'cashier_city.id')
            ->addSelect([
                DB::raw('null as user_id'),
                'cashier_city.name as city_name',
                'cashier_city.slug as city_slug',
                'ad_cashiers.agency as agency_name',
                DB::raw('"A" as agency_type'),
                'ad_cashiers.full_name as user_full_name',
                'ad_cashiers.code as user_code',
                DB::raw('"CAJERO" as user_position'),
                DB::raw('ad_cashiers.assignment as user_assignment'),
                DB::raw('IF(reference.id is null, 
                        IF(op_elp_details.reference_other > "", op_elp_details.reference_other, ad_cashiers.full_name), 
                        reference.full_name) as reference_full_name'),
                DB::raw('8 as target'),
            ])
            ->where('op_elp_details.client_eco', false)
            ->where(function ($q) use ($date) {
                $q->whereMonth('op_elp_headers.date_issue', '=', $date->month)
                    ->whereYear('op_elp_headers.date_issue', '=', $date->year)
                    ->where(function ($q) use ($date) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($date) {
                                $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->month)
                                    ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                            });
                    });
            })
            ->whereDate('op_elp_headers.date_issue', '>', '2015-12-31')
        ->groupBy('ad_cashiers.full_name');

        $this->headers = $el2
            ->leftJoin('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
            ->leftJoin('ad_cities', 'op_elp_headers.ad_city_id', '=', 'ad_cities.id')
            ->join('ad_agencies', 'op_elp_headers.ad_agency_id', '=', 'ad_agencies.id')
            ->leftJoin('ad_retailer_city_agencies as arca', 'ad_agencies.id', '=', 'arca.ad_agency_id')
            ->join('ad_retailer_cities as arc', 'arca.ad_retailer_city_id', '=', 'arc.id')
            ->join('ad_cities as agency_city', 'arc.ad_city_id', '=', 'agency_city.id')
            ->join('ad_users', 'op_elp_headers.ad_user_id', '=', 'ad_users.id')
            ->join('ad_user_targets', 'ad_users.ad_user_target_id', '=', 'ad_user_targets.id')
            ->leftJoin('ad_user_assignments', 'ad_users.id', '=', 'ad_user_assignments.ad_user_id')
            ->addSelect([
                'ad_users.id as user_id',
                DB::raw('IF(ad_cities.name is null, agency_city.name, ad_cities.name) as city_name'),
                DB::raw('IF(ad_cities.slug is null, agency_city.slug, ad_cities.slug) as city_slug'),
                'ad_agencies.name as agency_name',
                'ad_agencies.type as agency_type',
                'ad_users.full_name as user_full_name',
                'ad_users.code as user_code',
                'ad_user_targets.position as user_position',
                DB::raw('ad_user_assignments.assignment as user_assignment'),
                DB::raw('IF(ad_user_targets.id = 1, 
                        IF(reference.id is null, op_elp_details.reference_other, reference.full_name), 
                        "") as reference_full_name'),
                DB::raw('IF(op_elp_details.client_eco = true, (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.ep_a
                            WHEN "B" THEN ad_user_targets.ep_b
                            END), (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.el_a
                            WHEN "B" THEN ad_user_targets.el_b
                            END)) as target'),
            ])
            ->where('op_elp_details.client_eco', false)
            ->where(function ($q) use ($date) {
                $q->whereMonth('op_elp_headers.date_issue', '=', $date->month)
                    ->whereYear('op_elp_headers.date_issue', '=', $date->year);
            })->where(function ($q) use ($date) {
                $q->where('op_elp_headers.canceled', false)
                    ->orWhere(function ($q) use ($date) {
                        $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->month)
                            ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                    });
            })
            ->whereNull('ad_cashiers.id')
            ->whereDate('op_elp_headers.date_issue', '>', '2015-12-31')
            ->groupBy('ad_users.username')
            ->union($ep)
            ->union($el)
            ->orderBy('city_slug', 'asc')
            ->orderBy('user_full_name', 'asc')
            ->orderBy('product_text', 'asc')
            ->get();

        $d = [
            'months_current'  => $campaign['months']->keys(),
            'months_previous' => $campaign_previous['months']->keys(),
            'months_semester' => $campaign_semester['months']->keys(),
        ];

        $sql = $this->model
            ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
            ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id');

        $this->headers->transform(function ($item, $key) use (
            $sql, $d, $date, $date_previous, $date_semester, $campaign, $campaign_previous, $campaign_semester
        ) {
            $item['client_eco'] = filter_var($item['client_eco'], FILTER_VALIDATE_BOOLEAN);

            /*
             * Seguros vendidos campaña anterior
             */
            $query = clone $sql;

            $query
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', false)
                ->whereRaw('month(op_elp_headers.date_issue) between '
                           . $d['months_previous']->first() . ' and ' . $d['months_previous']->last())
                ->whereYear('op_elp_headers.date_issue', '=', $date_previous->year)
                ->where(function ($q) use ($d, $date_previous) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($d, $date_previous) {
                            $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                         . $d['months_previous']->first()
                                         . ' and ' . $d['months_previous']->last())
                                ->whereYear('op_elp_cancellations.created_at', '!=', $date_previous->year);
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            $item['campaign_previous'] = $query->count();

            /*
             * Metas del mes
             */
            if (! empty($item['user_assignment'])) {
                $assignments = explode('|', trim($item['user_assignment'], '|'));

                foreach ($assignments as $assignment) {
                    $c = explode(':', $assignment);

                    if ($c[0] == $campaign['number'] && $c[1] == $date->year) {
                        if ($campaign['number'] === 1 && $date->year === 2016) {
                            $item['target'] = $c[3];
                        } else {
                            $item['target'] = $item['client_eco'] ? $c[3] : $c[5];
                            // $item['agency_type'] = $c[2];

                            if ($item['user_position'] !== "CAJERO") {
                                // $item['user_position'] = cargo($campos[7], $conexion);
                            }
                        }
                    }
                }
            }

            /*
             * Ventas por mes
             */
            $sales = collect([]);

            foreach ($d['months_current'] as $month) {
                $query = clone $sql;
                $query
                    ->where('op_elp_details.client_eco', $item['client_eco'])
                    ->where('op_elp_headers.renewal', false)
                    ->whereMonth('op_elp_headers.date_issue', '=', $month)
                    ->whereYear('op_elp_headers.date_issue', '=', $date->year)
                    ->where(function ($q) use ($date, $month) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($date, $month) {
                                $q->whereMonth('op_elp_cancellations.created_at', '!=', $month)
                                    ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                            });
                    });

                if (is_null($item['user_id'])) {
                    $query->where('op_elp_details.reference_other', $item['user_full_name']);
                } else {
                    $query->where('op_elp_headers.ad_user_id', $item['user_id']);

                    if (! $item['client_eco']) {
                        $query
                            ->leftJoin('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
                            ->whereNull('ad_cashiers.id');
                    }
                }

                $sales[$month] = $query->count();

                /*
                 * Cumplimientos
                 */
                if ($month === $date->month) {
                    if ($item['target'] > 0) {
                        $item['compliment'] = round((($sales[$month] * 100) / $item['target']), 2);
                    } else {
                        $item['compliment'] = 0;
                    }
                }
            }

            $item['sales'] = $sales;

            /*
             * Tipo de ventas
             */
            $query = DB::table('op_elp_headers')
                ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
                ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id')
                ->select([
                    'op_elp_details.payment_method',
                    DB::raw('count(*) as Total'),
                ])
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', false)
                ->whereRaw('month(op_elp_headers.date_issue) between ' . $d['months_current']->first()
                           . ' and ' . $d['months_current']->last())
                ->whereYear('op_elp_headers.date_issue', '=', $date->year)
                ->where(function ($q) use ($d, $date) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($d, $date) {
                            $q->where(function ($q) use ($d) {
                                $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                             . $d['months_current']->first()
                                             . ' and ' . $d['months_current']->last())
                                    ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                            })->where(function ($q) use ($date) {
                                $q->whereYear('op_elp_cancellations.created_at', '!=', $date->year)
                                    ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                            });
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            $rs         = collect($query->groupBy('op_elp_details.payment_method')->get());
            $sale_types = collect([]);

            foreach ($rs as $row) {
                $sale_types[$row->payment_method] = $row->Total;
            }

            if ($sale_types->count() === 0) {
                $sale_types['CO'] = 0;
                $sale_types['DA'] = 0;
            } elseif ($sale_types->count() === 1) {
                if ($sale_types->keys()->first() === 'CO') {
                    $sale_types['DA'] = 0;
                } else {
                    $sale_types['CO'] = 0;
                }
            }

            $item['sale_types'] = $sale_types;

            /*
             * Cumplimiento por Campaña
             */
            $item['compliment_campaign'] = 0;

            if ($item['target'] > 0) {
                $item['compliment_campaign'] = round((($item['sales']->sum() * 100) / ($item['target'] * 3)), 2);
            }

            /*
             * Persistencia semestral / Venta Semestral
             */
            $item['persistence_semester'] = 0;
            $item['sale_semester']        = 0;

            if ($item['client_eco']) {
                $query = clone $sql;
                $query
                    ->where('op_elp_details.client_eco', true)
                    ->where('op_elp_headers.ad_user_id', $item['user_id'])
                    ->whereRaw('month(op_elp_headers.date_issue) between '
                               . $d['months_semester']->first()
                               . ' and ' . $d['months_semester']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date_semester->year)
                    ->where(function ($q) use ($d) {
                        $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                   . $d['months_semester']->first()
                                   . ' and ' . $d['months_semester']->last())
                            ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                    })
                    ->where(function ($q) use ($date_semester) {
                        $q->whereYear('op_elp_cancellations.created_at', '!=', $date_semester->year)
                            ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                    });

                $item['sale_semester'] = $query->count();
            }

            if ($d['months_semester']->first() >= 1 && $date_semester->year >= 2016) {
                $_target = $item['target'];

                if (! empty($item['user_assignment'])) {
                    $assignments = explode('|', trim($item['user_assignment'], '|'));

                    foreach ($assignments as $assignment) {
                        $c = explode(':', $assignment);

                        if ($c[0] == $campaign_semester['number'] && $c[1] == $date_semester->year) {
                            $_target = $c[3];
                        }
                    }
                }

                $pss = 0;

                if ($_target > 0) {
                    $pss = ( $item['sale_semester'] * 100 ) / $_target;
                }

                if ($pss > 74) {
                    $query = clone $sql;
                    $query
                        ->where('op_elp_headers.ad_user_id', $item['user_id'])
                        ->where('op_elp_details.client_eco', $item['client_eco'])
                        ->where('op_elp_details.payment_method', 'DA');

                    if ($item['client_eco']) {
                        $query->whereRaw('month(op_elp_headers.date_issue) between '
                                         . $d['months_semester']->first()
                                         . ' and ' . $d['months_semester']->last())
                            ->whereYear('op_elp_headers.date_issue', '=', $date_semester->year);
                    } else {
                        $query->whereMonth('op_elp_headers.date_issue', '=', $date->copy()->subMonths(6)->month)
                            ->whereYear('op_elp_headers.date_issue', '=', $date->copy()->subMonths(6)->year);
                    }

                    $query->where(function ($q) use ($item, $date, $d) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($item, $date, $d) {
                                if ($item['client_eco']) {
                                    $q->whereMonth('op_elp_cancellations.created_at', '>', $d['months_current']->last())
                                        ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                                } else {
                                    $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->copy()->subMonths(6)->month)
                                        ->whereYear('op_elp_cancellations.created_at', '!=', $date->copy()->subMonths(6)->year);
                                }
                            });
                    });

                    $item['persistence_semester'] = $query->count();
                }
            }

            /*
             * Persistencia anual / Venta Anual
             */
            $item['persistence_annual'] = 0;
            $item['sale_annual']        = 0;

            if ($item['client_eco']) {
                $query = clone $sql;
                $query
                    ->where('op_elp_headers.renewal', false)
                    ->where('op_elp_details.client_eco', true)
                    ->where('op_elp_headers.ad_user_id', $item['user_id'])
                    ->whereRaw('month(op_elp_headers.date_issue) between '
                               . $d['months_current']->first()
                               . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date->copy()->subYear()->year)
                    ->where(function ($q) use ($d) {
                        $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                     . $d['months_current']->first()
                                     . ' and ' . $d['months_current']->last())
                            ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                    })
                    ->where(function ($q) use ($date) {
                        $q->whereYear('op_elp_cancellations.created_at', '!=', $date->copy()->subYear()->year)
                            ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                    });

                $item['sale_annual'] = $query->count();
            }

            if ($d['months_current']->first() >= 1 && $date->copy()->subYear()->year >= 2016) {
                $_target = $item['target'];

                if (! empty($item['user_assignment'])) {
                    $assignments = explode('|', trim($item['user_assignment'], '|'));

                    foreach ($assignments as $assignment) {
                        $c = explode(':', $assignment);

                        if ($c[0] == $campaign['number'] && $c[1] == $date->copy()->subYear()->year) {
                            $_target = $c[3];
                        }
                    }
                }

                $psa = 0;

                if ($_target > 0) {
                    $psa = ( $item['sale_annual'] * 100 ) / $_target;
                }

                if ($psa > 74) {
                    $query = clone $sql;
                    $query
                        ->where('op_elp_headers.ad_user_id', $item['user_id'])
                        ->where('op_elp_details.client_eco', $item['client_eco'])
                        ->where('op_elp_details.payment_method', 'DA');

                    if ($item['client_eco']) {
                        $query->whereRaw('month(op_elp_headers.date_issue) between '
                                         . $d['months_current']->first()
                                         . ' and ' . $d['months_current']->last())
                            ->whereYear('op_elp_headers.date_issue', '=', $date->copy()->subYear()->year);
                    } else {
                        $query->whereMonth('op_elp_headers.date_issue', '=', $date->copy()->subYear()->month)
                            ->whereYear('op_elp_headers.date_issue', '=', $date->copy()->subYear()->year);
                    }

                    $query->where(function ($q) use ($item, $date, $d) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($item, $date, $d) {
                                if ($item['client_eco']) {
                                    $q->whereMonth('op_elp_cancellations.created_at', '>', $d['months_current']->last())
                                        ->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                                } else {
                                    $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->copy()->subYear()->month)
                                        ->whereYear('op_elp_cancellations.created_at', '!=', $date->copy()->subYear()->year);
                                }
                            });
                    });

                    $item['persistence_annual'] = $query->count();
                }
            }

            /*
             * Renovaciones Mensuales
             */
            $item['renovations_monthly'] = 0;

            if ($item['client_eco']) {
                $query = clone $sql;
                $query
                    ->where('op_elp_headers.ad_user_id', $item['user_id'])
                    ->where('op_elp_details.client_eco', true)
                    ->where('op_elp_headers.renewal', true)
                    ->whereRaw('month(op_elp_headers.date_issue) between '
                               . $d['months_current']->first()
                               . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date->year)
                    ->where('op_elp_details.payment_method', 'DA')
                    ->where(function ($q) use ($d, $date) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($d, $date) {
                                $q->where(function ($q) use ($d, $date) {
                                    $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                                 . $d['months_current']->first()
                                                 . ' and ' . $d['months_current']->last())
                                        ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                                })->where(function ($q) use ($date) {
                                    $q->whereYear('op_elp_cancellations.created_at', '!=', $date->year)
                                        ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                                });
                            });
                    });

                $item['renovations_monthly'] = $query->count();
            }

            /*
             * Renovaciones Anuales
             */
            $query = clone $sql;
            $query
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', true)
                ->where('op_elp_details.payment_method', 'CO')
                ->where(function ($q) use ($item, $d, $date) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($item, $d, $date) {
                            if ($item['client_eco']) {
                                $q->where(function ($q) use ($date) {
                                    $q->whereMonth('op_elp_cancellations.created_at', '!=', $date->month)
                                        ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                                })->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                            } else {
                                $q->where(function ($q) use ($d, $date) {
                                    $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                                 . $d['months_current']->first()
                                                 . ' and ' . $d['months_current']->last())
                                        ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                                })->whereYear('op_elp_cancellations.created_at', '>=', $date->year);
                            }
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            if ($item['client_eco']) {
                $query
                    ->join('op_elp_collections', 'op_elp_headers.id', '=', 'op_elp_collections.op_elp_header_id')
                    ->whereMonth('op_elp_collections.transaction_date', '=', $date->month)
                    ->whereYear('op_elp_collections.transaction_date', '=', $date->year)
                    ->where('op_elp_collections.charged', true);
            } else {
                $query->whereRaw('month(op_elp_headers.date_issue) between '
                                 . $d['months_current']->first()
                                 . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date->year);
            }

            $item['renovations_annual'] = $query->count();

            return $item;
        });

        $query = DB::table('op_elp_headers')
            ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
            ->select([
                'op_elp_headers.renewal',
                DB::raw('IF(op_elp_details.client_eco = true, "Ecoprotección", "Ecovida") as product'),
                DB::raw('count(op_elp_headers.id) as Total'),
            ])
            ->where('op_elp_headers.renewal', true)
            ->whereMonth('op_elp_headers.created_at', '=', $date->month)
            ->whereRaw('month(op_elp_headers.date_issue) != month(op_elp_headers.created_at)')
            ->groupBy('op_elp_details.client_eco');

        $this->headers['renovations'] = collect($query->get());
    }


    /**
     * @param Request $request
     */
    private function dataElpCampaign($request)
    {
        $year              = $request->get('campaign_year');
        $c                 = $request->get('campaign');
        // $date              = $this->carbon->createFromDate($year, null, 1);
        $campaign          = $this->campaigns[$c];
        $date_campaign     = $this->carbon->createFromDate($year, $campaign['months']->keys()->first(), 1);
        $c                 = ($c - 1) < 1 ? 4 : ($c - 1);
        $campaign_previous = $this->campaigns[$c];
        $date_previous     = $date_campaign->copy()->subMonths(3);
        $c                 = ($c - 1) < 1 ? 4 : ($c - 1);
        $campaign_semester = $this->campaigns[$c];
        $date_semester     = $date_previous->copy()->subMonths(3);

        $this->info[ 'date' ]              = $this->carbon->createFromDate(null, null, 1);
        $this->info[ 'date_campaign' ]     = $date_campaign;
        $this->info[ 'campaign' ]          = $campaign;
        $this->info[ 'date_previous' ]     = $date_previous;
        $this->info[ 'campaign_previous' ] = $campaign_previous;
        $this->info[ 'date_semester' ]     = $date_semester;
        $this->info[ 'campaign_semester' ] = $campaign_semester;

        $commissions = Commission::get();
        $commissions = $commissions->groupBy(function ($item, $key) {
            return $item['code'];
        });

        $d = [
            'months_current'  => $campaign['months']->keys(),
            'months_previous' => $campaign_previous['months']->keys(),
            'months_semester' => $campaign_semester['months']->keys(),
            'commissions'     => $commissions,
        ];

        $builder = $this->model
            ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
            ->leftJoin('ad_cashiers as reference', 'op_elp_details.reference_id', '=', 'reference.id')
            ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id')
            ->select([
                'op_elp_headers.id',
                DB::raw('IF(op_elp_details.client_eco = true, "Ecoprotección", "Ecovida") as product_text'),
                'op_elp_details.client_eco',
                'op_elp_headers.renewal',
            ]);

        $ep  = clone $builder;
        $el  = clone $builder;
        $el2 = clone $builder;

        $ep->join('op_elp_collections', 'op_elp_headers.id', '=', 'op_elp_collections.op_elp_header_id')
            ->leftJoin('ad_cities', 'op_elp_headers.ad_city_id', '=', 'ad_cities.id')
            ->join('ad_agencies', 'op_elp_headers.ad_agency_id', '=', 'ad_agencies.id')
            ->leftJoin('ad_retailer_city_agencies as arca', 'ad_agencies.id', '=', 'arca.ad_agency_id')
            ->join('ad_retailer_cities as arc', 'arca.ad_retailer_city_id', '=', 'arc.id')
            ->join('ad_cities as agency_city', 'arc.ad_city_id', '=', 'agency_city.id')
            ->join('ad_users', 'op_elp_headers.ad_user_id', '=', 'ad_users.id')
            ->join('ad_user_targets', 'ad_users.ad_user_target_id', '=', 'ad_user_targets.id')
            ->leftJoin('ad_user_assignments', 'ad_users.id', '=', 'ad_user_assignments.ad_user_id')
            ->addSelect([
                'ad_users.id as user_id',
                DB::raw('IF(ad_cities.name is null, agency_city.name, ad_cities.name) as city_name'),
                DB::raw('IF(ad_cities.slug is null, agency_city.slug, ad_cities.slug) as city_slug'),
                'ad_agencies.name as agency_name',
                'ad_agencies.type as agency_type',
                'ad_users.full_name as user_full_name',
                'ad_users.code as user_code',
                'ad_user_targets.position as user_position',
                DB::raw('ad_user_assignments.assignment as user_assignment'),
                DB::raw('IF(ad_user_targets.id = 1, 
                        IF(reference.id is null, op_elp_details.reference_other, reference.full_name), 
                        "") as reference_full_name'),
                DB::raw('IF(op_elp_details.client_eco = true, (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.ep_a
                            WHEN "B" THEN ad_user_targets.ep_b
                            END), (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.el_a
                            WHEN "B" THEN ad_user_targets.el_b
                            END)) as target'),
            ])
            ->where('op_elp_details.client_eco', true)
            ->where(function ($q) use ($d, $date_campaign, $date_semester) {
                $q->where(function ($q) use ($d, $date_campaign) {
                    $q->whereRaw('month(op_elp_headers.date_issue) between '
                                 . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                        ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year);
                })->orWhere(function ($q) use ($d, $date_semester) {
                    $q->whereRaw('month(op_elp_headers.date_issue) between '
                                 . $d['months_semester']->first() . ' and ' . $d['months_semester']->last())
                        ->whereYear('op_elp_headers.date_issue', '=', $date_semester->year);
                })->orWhere(function ($q) use ($d, $date_campaign) {
                    $q->whereRaw('month(op_elp_headers.date_issue) between '
                                 . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                        ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->copy()->subYear()->year);
                })->orWhere(function ($q) use ($d, $date_campaign) {
                    $q->whereRaw('month(op_elp_collections.transaction_date) between '
                                 . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                        ->whereYear('op_elp_collections.transaction_date', '=', $date_campaign->year)
                        ->where('op_elp_collections.charged', true)
                        ->where('op_elp_details.payment_method', 'CO')
                        ->where('op_elp_collections.fee_number', '>', 1);
                });
            })
            ->where(function ($q) use ($d, $date_campaign) {
                $q->where('canceled', false)
                    ->orWhere(function ($q) use ($d, $date_campaign) {
                        $q->where(function ($q) use ($d) {
                            $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                         . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                                ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                        })->where(function ($q) use ($date_campaign) {
                            $q->whereYear('op_elp_cancellations.created_at', '!=', $date_campaign->copy()->subYear()->year)
                                ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                        });
                    });
            })
            ->whereDate('op_elp_headers.date_issue', '>', '2015-12-31')
            ->groupBy('ad_users.username');

        $el->join('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
            ->join('ad_cities as cashier_city', 'ad_cashiers.ad_city_id', '=', 'cashier_city.id')
            ->addSelect([
                DB::raw('null as user_id'),
                'cashier_city.name as city_name',
                'cashier_city.slug as city_slug',
                'ad_cashiers.agency as agency_name',
                DB::raw('"A" as agency_type'),
                'ad_cashiers.full_name as user_full_name',
                'ad_cashiers.code as user_code',
                DB::raw('"CAJERO" as user_position'),
                DB::raw('ad_cashiers.assignment as user_assignment'),
                DB::raw('IF(reference.id is null,
                        IF(op_elp_details.reference_other > "", op_elp_details.reference_other, ad_cashiers.full_name),
                        reference.full_name) as reference_full_name'),
                DB::raw('8 as target'),
            ])
            ->where('op_elp_details.client_eco', false)
            ->where(function ($q) use ($d, $date_campaign) {
                $q->whereRaw('month(op_elp_headers.date_issue) between '
                             . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year)
                    ->where(function ($q) use ($d, $date_campaign) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($d, $date_campaign) {
                                $q->where(function ($q) use ($d) {
                                    $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                                 . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                                        ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                                })->where(function ($q) use ($date_campaign) {
                                    $q->whereYear('op_elp_cancellations.created_at', '!=', $date_campaign->year)
                                        ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                                });
                            });
                    });
            })
            ->whereDate('op_elp_headers.date_issue', '>', '2015-12-31')
            ->groupBy('ad_cashiers.full_name');

        $this->headers = $el2
            ->leftJoin('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
            ->leftJoin('ad_cities', 'op_elp_headers.ad_city_id', '=', 'ad_cities.id')
            ->join('ad_agencies', 'op_elp_headers.ad_agency_id', '=', 'ad_agencies.id')
            ->leftJoin('ad_retailer_city_agencies as arca', 'ad_agencies.id', '=', 'arca.ad_agency_id')
            ->join('ad_retailer_cities as arc', 'arca.ad_retailer_city_id', '=', 'arc.id')
            ->join('ad_cities as agency_city', 'arc.ad_city_id', '=', 'agency_city.id')
            ->join('ad_users', 'op_elp_headers.ad_user_id', '=', 'ad_users.id')
            ->join('ad_user_targets', 'ad_users.ad_user_target_id', '=', 'ad_user_targets.id')
            ->leftJoin('ad_user_assignments', 'ad_users.id', '=', 'ad_user_assignments.ad_user_id')
            ->addSelect([
                'ad_users.id as user_id',
                DB::raw('IF(ad_cities.name is null, agency_city.name, ad_cities.name) as city_name'),
                DB::raw('IF(ad_cities.slug is null, agency_city.slug, ad_cities.slug) as city_slug'),
                'ad_agencies.name as agency_name',
                'ad_agencies.type as agency_type',
                'ad_users.full_name as user_full_name',
                'ad_users.code as user_code',
                'ad_user_targets.position as user_position',
                DB::raw('ad_user_assignments.assignment as user_assignment'),
                DB::raw('IF(ad_user_targets.id = 1, 
                        IF(reference.id is null, op_elp_details.reference_other, reference.full_name), 
                        "") as reference_full_name'),
                DB::raw('IF(op_elp_details.client_eco = true, (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.ep_a
                            WHEN "B" THEN ad_user_targets.ep_b
                            END), (
                        CASE ad_agencies.type
                            WHEN "A" THEN ad_user_targets.el_a
                            WHEN "B" THEN ad_user_targets.el_b
                            END)) as target'),
            ])
            ->where('op_elp_details.client_eco', false)
            ->where(function ($q) use ($d, $date_campaign) {
                $q->whereRaw('month(op_elp_headers.date_issue) between '
                             . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year);
            })->where(function ($q) use ($d, $date_campaign) {
                $q->where('op_elp_headers.canceled', false)
                    ->orWhere(function ($q) use ($d, $date_campaign) {
                        $q->where(function ($q) use ($d, $date_campaign) {
                            $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                         . $d['months_current']->first() . ' and ' . $d['months_current']->last())
                                ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                        })->where(function ($q) use ($date_campaign) {
                            $q->whereYear('op_elp_cancellations.created_at', '!=', $date_campaign->year)
                                ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                        });
                    });
            })
            ->whereNull('ad_cashiers.id')
            ->whereDate('op_elp_headers.date_issue', '>', '2015-12-31')
            ->groupBy('ad_users.username')
            ->union($ep)
            ->union($el)
            ->orderBy('city_slug', 'asc')
            ->orderBy('user_full_name', 'asc')
            ->orderBy('product_text', 'asc')
            ->get();

        $total = [
            'commissions_annual'               => 0,
            'commissions_monthly'              => 0,
            'commissions_persistence_semester' => 0,
            'commissions_persistence_annual'   => 0,
            'commissions_total'                => 0,
            'renovations_commission_monthly'   => 0,
            'renovations_commission_annual'    => 0,
            'renovations_commission_total'     => 0,
        ];

        $sql = $this->model
            ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
            ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id');

        $this->headers->transform(function ($item, $key) use (
            $sql, $d, $date_campaign, $date_previous, $date_semester, $campaign, $campaign_previous, $campaign_semester,
            &$total
        ) {
            $item[ 'client_eco' ] = filter_var($item[ 'client_eco' ], FILTER_VALIDATE_BOOLEAN);

            /*
             * Seguros vendidos campaña anterior
             */
            $query = clone $sql;

            $query
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', false)
                ->whereRaw('month(op_elp_headers.date_issue) between '
                           . $d['months_previous']->first() . ' and ' . $d['months_previous']->last())
                ->whereYear('op_elp_headers.date_issue', '=', $date_previous->year)
                ->where(function ($q) use ($d, $date_previous) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($d, $date_previous) {
                            $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                         . $d['months_previous']->first()
                                         . ' and ' . $d['months_previous']->last())
                                ->whereYear('op_elp_cancellations.created_at', '!=', $date_previous->year);
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            $item['campaign_previous'] = $query->count();

            /*
             * Metas del mes
             */
            if (! empty($item['user_assignment'])) {
                $assignments = explode('|', trim($item['user_assignment'], '|'));

                foreach ($assignments as $assignment) {
                    $c = explode(':', $assignment);

                    if ($c[0] == $campaign['number'] && $c[1] == $date_campaign->year) {
                        if ($campaign['number'] === 1 && $date_campaign->year === 2016) {
                            $item['target'] = $c[3];
                        } else {
                            $item['target'] = $item['client_eco'] ? $c[3] : $c[5];
                            // $item['agency_type'] = $c[2];

                            if ($item['user_position'] !== "CAJERO") {
                                // $item['user_position'] = cargo($campos[7], $conexion);
                            }
                        }
                    }
                }
            }

            /*
             * Ventas por mes
             */
            $sales = collect([]);

            foreach ($d['months_current'] as $month) {
                $query = clone $sql;
                $query
                    ->where('op_elp_details.client_eco', $item['client_eco'])
                    ->where('op_elp_headers.renewal', false)
                    ->whereMonth('op_elp_headers.date_issue', '=', $month)
                    ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year)
                    ->where(function ($q) use ($date_campaign, $month) {
                        $q->where('op_elp_headers.canceled', false)
                            ->orWhere(function ($q) use ($date_campaign, $month) {
                                $q->whereMonth('op_elp_cancellations.created_at', '!=', $month)
                                    ->whereYear('op_elp_cancellations.created_at', '>=', $date_campaign->year);
                            });
                    });

                if (is_null($item['user_id'])) {
                    $query->where('op_elp_details.reference_other', $item['user_full_name']);
                } else {
                    $query->where('op_elp_headers.ad_user_id', $item['user_id']);

                    if (! $item['client_eco']) {
                        $query
                            ->leftJoin('ad_cashiers', 'op_elp_details.reference_other', '=', 'ad_cashiers.full_name')
                            ->whereNull('ad_cashiers.id');
                    }
                }

                $sales[$month] = $query->count();

                /*
                 * Cumplimientos
                 */
                if ($month === date('n')) {
                    if ($item['target'] > 0) {
                        $item['compliment'] = round((($sales[$month] * 100) / $item['target']), 2);
                    } else {
                        $item['compliment'] = 0;
                    }
                } else {
                    $item['compliment'] = 0;
                }
            }

            $item['sales'] = $sales;

            /*
             * Tipo de ventas
             */
            $query = DB::table('op_elp_headers')
                ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
                ->leftJoin('op_elp_cancellations', 'op_elp_headers.id', '=', 'op_elp_cancellations.op_elp_header_id')
                ->select([
                    'op_elp_details.payment_method',
                    DB::raw('count(*) as Total'),
                ])
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', false)
                ->whereRaw('month(op_elp_headers.date_issue) between ' . $d['months_current']->first()
                           . ' and ' . $d['months_current']->last())
                ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year)
                ->where(function ($q) use ($d, $date_campaign) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($d, $date_campaign) {
                            $q->where(function ($q) use ($d) {
                                $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                             . $d['months_current']->first()
                                             . ' and ' . $d['months_current']->last())
                                    ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                            })->where(function ($q) use ($date_campaign) {
                                $q->whereYear('op_elp_cancellations.created_at', '!=', $date_campaign->year)
                                    ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                            });
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            $rs         = collect($query->groupBy('op_elp_details.payment_method')->get());
            $sale_types = collect([]);

            foreach ($rs as $row) {
                $sale_types[$row->payment_method] = $row->Total;
            }

            if ($sale_types->count() === 0) {
                $sale_types['CO'] = 0;
                $sale_types['DA'] = 0;
            } elseif ($sale_types->count() === 1) {
                if ($sale_types->keys()->first() === 'CO') {
                    $sale_types['DA'] = 0;
                } else {
                    $sale_types['CO'] = 0;
                }
            }

            $item['sale_types'] = $sale_types;

            /*
             * Cumplimiento por Campaña
             */
            $item['compliment_campaign'] = 0;

            if ($item['target'] > 0) {
                $item['compliment_campaign'] = round((($item['sales']->sum() * 100) / ($item['target'] * 3)), 2);
            }

            /*
             * Persistencia semestral / Venta Semestral
             */
            $item['persistence_semester'] = 0;
            $item['sale_semester']        = 0;

            if ($item['client_eco']) {
                $query = clone $sql;
                $query
                    ->where('op_elp_details.client_eco', true)
                    ->where('op_elp_headers.ad_user_id', $item['user_id'])
                    ->whereRaw('month(op_elp_headers.date_issue) between '
                               . $d['months_semester']->first()
                               . ' and ' . $d['months_semester']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date_semester->year)
                    ->where(function ($q) use ($d) {
                        $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                     . $d['months_semester']->first()
                                     . ' and ' . $d['months_semester']->last())
                            ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                    })
                    ->where(function ($q) use ($date_semester) {
                        $q->whereYear('op_elp_cancellations.created_at', '!=', $date_semester->year)
                            ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                    });

                $item['sale_semester'] = $query->count();
            }

            if ($d['months_semester']->first() >= 1 && $date_semester->year >= 2016) {
                $_target = $item['target'];

                if (! empty($item['user_assignment'])) {
                    $assignments = explode('|', trim($item['user_assignment'], '|'));

                    foreach ($assignments as $assignment) {
                        $c = explode(':', $assignment);

                        if ($c[0] == $campaign_semester['number'] && $c[1] == $date_semester->year) {
                            $_target = $c[3];
                        }
                    }
                }

                $pss = 0;

                if ($_target > 0) {
                    $pss = ( $item['sale_semester'] * 100 ) / $_target;
                }

                if ($pss > 74) {
                    if ($item['client_eco']) {
                        $query = clone $sql;
                        $query
                            ->where('op_elp_headers.ad_user_id', $item['user_id'])
                            ->where('op_elp_details.client_eco', true)
                            ->where('op_elp_details.payment_method', 'DA')
                            ->whereRaw('month(op_elp_headers.date_issue) between '
                                       . $d['months_semester']->first()
                                       . ' and ' . $d['months_semester']->last())
                            ->whereYear('op_elp_headers.date_issue', '=', $date_semester->year)
                            ->where(function ($q) use ($item, $date_campaign, $d) {
                                $q->where('op_elp_headers.canceled', false)
                                    ->orWhere(function ($q) use ($item, $date_campaign, $d) {
                                        $q->whereMonth('op_elp_cancellations.created_at', '>', $d['months_current']->last())
                                            ->whereYear('op_elp_cancellations.created_at', '>=', $date_campaign->year);
                                    });
                            });

                        $item['persistence_semester'] = $query->count();
                    }
                }
            }

            /*
             * Persistencia anual / Venta Anual
             */
            $item['persistence_annual'] = 0;
            $item['sale_annual']        = 0;

            if ($item['client_eco']) {
                $query = clone $sql;
                $query
                    ->where('op_elp_headers.renewal', false)
                    ->where('op_elp_details.client_eco', true)
                    ->where('op_elp_headers.ad_user_id', $item['user_id'])
                    ->whereRaw('month(op_elp_headers.date_issue) between '
                               . $d['months_current']->first()
                               . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->copy()->subYear()->year)
                    ->where(function ($q) use ($d) {
                        $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                     . $d['months_current']->first()
                                     . ' and ' . $d['months_current']->last())
                            ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                    })
                    ->where(function ($q) use ($date_campaign) {
                        $q->whereYear('op_elp_cancellations.created_at', '!=', $date_campaign->copy()->subYear()->year)
                            ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                    });

                $item['sale_annual'] = $query->count();
            }

            if ($d['months_current']->first() >= 1 && $date_campaign->copy()->subYear()->year >= 2016) {
                $_target = $item['target'];

                if (! empty($item['user_assignment'])) {
                    $assignments = explode('|', trim($item['user_assignment'], '|'));

                    foreach ($assignments as $assignment) {
                        $c = explode(':', $assignment);

                        if ($c[0] == $campaign['number'] && $c[1] == $date_campaign->copy()->subYear()->year) {
                            $_target = $c[3];
                        }
                    }
                }

                $psa = 0;

                if ($_target > 0) {
                    $psa = ( $item['sale_annual'] * 100 ) / $_target;
                }

                if ($psa > 74) {
                    if ($item['client_eco']) {
                        $query = clone $sql;
                        $query
                            ->where('op_elp_headers.ad_user_id', $item['user_id'])
                            ->where('op_elp_details.client_eco', true)
                            ->where('op_elp_details.payment_method', 'DA')
                            ->whereRaw('month(op_elp_headers.date_issue) between '
                                       . $d['months_current']->first()
                                       . ' and ' . $d['months_current']->last())
                            ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->copy()->subYear()->year)
                            ->where(function ($q) use ($item, $date_campaign, $d) {
                                $q->where('op_elp_headers.canceled', false)
                                    ->orWhere(function ($q) use ($item, $date_campaign, $d) {
                                        $q->whereMonth('op_elp_cancellations.created_at', '>', $d['months_current']->last())
                                            ->whereYear('op_elp_cancellations.created_at', '>=', $date_campaign->year);
                                    });
                            });

                        $item['persistence_annual'] = $query->count();
                    }
                }
            }

            /*
             * Renovaciones Mensuales
             */
            $query = clone $sql;
            $query
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', true)
                ->whereRaw('month(op_elp_headers.date_issue) between '
                           . $d['months_current']->first()
                           . ' and ' . $d['months_current']->last())
                ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year)
                ->where('op_elp_details.payment_method', 'DA')
                ->where(function ($q) use ($d, $date_campaign) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($d, $date_campaign) {
                            $q->where(function ($q) use ($d, $date_campaign) {
                                $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                             . $d['months_current']->first()
                                             . ' and ' . $d['months_current']->last())
                                    ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                            })->where(function ($q) use ($date_campaign) {
                                $q->whereYear('op_elp_cancellations.created_at', '!=', $date_campaign->year)
                                    ->orWhereRaw('year(op_elp_cancellations.created_at) is null');
                            });
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            $item['renovations_monthly'] = $query->count();

            /*
             * Renovaciones Anuales
             */
            $query = clone $sql;
            $query
                ->where('op_elp_details.client_eco', $item['client_eco'])
                ->where('op_elp_headers.renewal', true)
                ->where('op_elp_details.payment_method', 'CO')
                ->where(function ($q) use ($item, $d, $date_campaign) {
                    $q->where('op_elp_headers.canceled', false)
                        ->orWhere(function ($q) use ($item, $d, $date_campaign) {
                            $q->where(function ($q) use ($d, $date_campaign) {
                                $q->whereRaw('month(op_elp_cancellations.created_at) not between '
                                             . $d['months_current']->first()
                                             . ' and ' . $d['months_current']->last())
                                    ->orWhereRaw('month(op_elp_cancellations.created_at) is null');
                            })->whereYear('op_elp_cancellations.created_at', '>=', $date_campaign->year);
                        });
                });

            if (is_null($item['user_id'])) {
                $query->where('op_elp_details.reference_other', $item['user_full_name']);
            } else {
                $query->where('op_elp_headers.ad_user_id', $item['user_id']);
            }

            if ($item['client_eco']) {
                $query
                    ->join('op_elp_collections', 'op_elp_headers.id', '=', 'op_elp_collections.op_elp_header_id')
                    ->whereRaw('month(op_elp_collections.transaction_date) between '
                               . $d['months_current']->first()
                               . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_collections.transaction_date', '=', $date_campaign->year)
                    ->where('op_elp_collections.charged', true);
            } else {
                $query->whereRaw('month(op_elp_headers.date_issue) between '
                                 . $d['months_current']->first()
                                 . ' and ' . $d['months_current']->last())
                    ->whereYear('op_elp_headers.date_issue', '=', $date_campaign->year);
            }

            $item['renovations_annual'] = $query->count();

            /*
             * Comisiones
             */
            $commission = $d['commissions'][($item['client_eco'] ? 'ep' : 'el')][0];

            $item['commission_annual']  = round(($commission->annual * $item['sale_types']['CO']), 2);
            $item['commission_monthly'] = round(($commission->monthly * $item['sale_types']['DA']), 2);

            $item['commission_persistence_semester'] = round(($commission->persistence_monthly
                                                              * $item['persistence_semester']), 2);
            $item['commission_persistence_annual']   = round(($commission->persistence_annual
                                                             * $item['persistence_annual']), 2);

            $item['commission_total'] = $item['commission_annual']
                                        + $item['commission_monthly']
                                        + $item['commission_persistence_semester']
                                        + $item['commission_persistence_annual'];

            $item['commission_renovation_monthly'] = round(($item['commission_monthly']
                                                            * $item['renovations_monthly']), 2);
            $item['commission_renovation_annual']  = round(($item['commission_annual']
                                                            * $item['renovations_annual']), 2);
            $item['commission_renovation_total']   = round(($item['commission_renovation_monthly']
                                                            * $item['commission_renovation_annual']), 2);

            $total['commissions_annual']               += $item['commission_annual'];
            $total['commissions_monthly']              += $item['commission_monthly'];
            $total['commissions_persistence_semester'] += $item['commission_persistence_semester'];
            $total['commissions_persistence_annual']   += $item['commission_persistence_annual'];
            $total['commissions_total']                += $item['commission_total'];
            $total['renovations_commission_monthly']   += $item['commission_renovation_monthly'];
            $total['renovations_commission_annual']    += $item['commission_renovation_annual'];
            $total['renovations_commission_total']     += $item['commission_renovation_total'];

            return $item;
        });

        $this->headers['total'] = $total;
    }

    /**
     * @return Collection
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * Campaigns
     */
    public function setCampaigns()
    {
        $this->campaigns = [
            1 => [
                'number' => 1,
                'name'   => '1 [Enero/Febrero/Marzo]',
                'months' => collect([
                    1 => 'ENERO',
                    2 => 'FEBRERO',
                    3 => 'MARZO',
                ]),
            ],
            2 => [
                'number' => 2,
                'name'   => '2 [Abril/Mayo/Junio]',
                'months' => collect([
                    4 => 'ABRIL',
                    5 => 'MAYO',
                    6 => 'JUNIO',
                ]),
            ],
            3 => [
                'number' => 3,
                'name'   => '3 [Julio/Agosto/Septiembre]',
                'months' => collect([
                    7 => 'JULIO',
                    8 => 'AGOSTO',
                    9 => 'SEPTIEMBRE',
                ]),
            ],
            4 => [
                'number' => 4,
                'name'   => '4 [Octubre/Noviembre/Diciembre]',
                'months' => collect([
                    10 => 'OCTUBRE',
                    11 => 'NOVIEMBRE',
                    12 => 'DICIEMBRE',
                ]),
            ],
        ];

        $this->campaigns = collect($this->campaigns);
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }
}