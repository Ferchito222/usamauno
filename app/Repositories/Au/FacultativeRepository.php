<?php

namespace Sibas\Repositories\Au;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Au\Detail;
use Sibas\Entities\Au\Facultative;
use Sibas\Entities\ProductParameter;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;
use Sibas\Http\Controllers\MailController;
use Sibas\Repositories\BaseRepository;

class FacultativeRepository extends BaseRepository
{

    /**
     * @param string $id
     *
     * @return bool
     */
    public function getFacultativeById($id)
    {
        $this->model = Facultative::with('detail.header.user', 'detail.header.client', 'observations')->where('id', '=',
            $id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * Get Facultative records
     *
     * @param        $user
     * @param string $inbox
     * @param int    $header_id
     *
     * @return mixed
     */
    public function getRecords($user, $inbox, $header_id = null)
    {
        $user_type = $user->profile->first()->slug;

        $fa = Facultative::with('user', 'detail.header.user', 'observations.state', 'observations.user');

        switch ($user_type) {
            case 'SEP':
                $fa->whereHas('detail.header', function ($query) use ($user) {
                    $query->where('ad_user_id', $user->id)->where('type', 'I')->where('issued', 0);
                });
                break;
            case 'COP':
                $fa->whereHas('detail.header', function ($query) use ($user, $header_id) {
                    $query->where('type', 'I')->where('issued', 0);

                    if ( ! is_null($header_id)) {
                        $query->where('id', $header_id);
                    }
                })->where('state', 'PE');
                break;
        }

        $fa = $fa->orderBy('created_at', 'desc')->get();

        $this->records['all'] = $fa;

        $fa->each(function ($item, $key) use ($user_type) {
            // All
            if ($user_type === 'SEP') {
                if ( ! $item->read) {
                    $this->records['all-unread']->push($item);
                }
            } else {
                $this->records['all-unread']->push($item);
            }

            // Approved
            if ($item->state === 'PR' && $item->approved) {
                $this->records['approved']->push($item);

                if ( ! $item->read) {
                    $this->records['approved-unread']->push($item);
                }

                return true;
            }

            // Observed
            if ($item->state === 'PE' && $item->observations->count() > 0) {
                $this->records['observed']->push($item);

                if ( ! $item->read) {
                    $this->records['observed-unread']->push($item);
                }

                return true;
            }

            // Rejected
            if ($item->state === 'PR' && ! $item->approved) {
                $this->records['rejected']->push($item);

                if ( ! $item->read) {
                    $this->records['rejected-unread']->push($item);
                }

                return true;
            }
        });

        $this->records['inbox'] = $this->records[$inbox];

        return $this->records;
    }


    /**
     * Store vehicle Facultative
     *
     * @param Model|Detail          $detail
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|User            $user
     * @param bool                  $coverage
     *
     * @return bool
     * @throws \Exception
     */
    public function storeFacultative($detail, $retailerProduct, $user, $coverage = false)
    {
        $parameter     = $retailerProduct->parameters()->where('slug', 'GE')->first();
        $exchange_rate = $retailerProduct->retailer->exchangeRate;
        $reason        = '';

        if ($parameter instanceof ProductParameter) {
            $year_max      = date('Y') - $parameter->old_car;
            $insured_value = $detail->insured_value;

            if ($detail->header->currency === 'BS') {
                $insured_value = $detail->insured_value / $exchange_rate->bs_value;
            }

            $year   = ( $detail->year < $year_max ) ? true : false;
            $amount = ( $insured_value > $parameter->amount_max ) ? true : false;

            $reason .= $year ? str_replace([ ':license_plate', ':year_max' ],
                    [ $detail->license_plate, $parameter->old_car ], $this->reasonYear) . '<br>' : '';
            $reason .= $amount ? str_replace([ ':license_plate', ':amount_max' ],
                    [ $detail->license_plate, number_format($parameter->amount_max, 2) ],
                    $this->reasonAmount) . '<br>' : '';

            if ($coverage) {
                if ($year || $amount) {
                    $this->errors = [ 'reason' => $reason ];

                    $detail->delete();

                    return 428;
                }

                $detail->update([
                    'approved' => true,
                ]);

                return 202;
            }

            try {
                if ($year || $amount) {
                    if ($detail->facultative instanceof Facultative) {
                        $detail->facultative->update([
                            'reason' => $reason,
                            'state'  => 'PE',
                            'read'   => false,
                        ]);
                    } else {
                        $detail->facultative()->create([
                            'id'         => date('U'),
                            'ad_user_id' => $user->id,
                            'reason'     => $reason,
                            'state'      => 'PE',
                            'read'       => false,
                        ]);
                    }
                } elseif ($detail->facultative instanceof Facultative) {
                    $detail->facultative->delete();
                }

                return true;
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function updateFacultative($request)
    {
        $user       = $request->user();
        $this->data = $request->all();

        $this->data['approved']  = (int) $this->data['approved'];
        $this->data['surcharge'] = (boolean) $this->data['surcharge'];

        if ($this->data['approved'] === 1 || $this->data['approved'] == 0) {
            $this->model->ad_user_id  = $user->id;
            $this->model->state       = 'PR';
            $this->model->observation = $this->data['observation'];

            if ($this->data['approved'] === 1) {
                $this->model->approved = true;

                if ($this->data['surcharge']) {
                    $this->model->surcharge    = true;
                    $this->model->percentage   = $this->data['percentage'];
                    $this->model->current_rate = $this->data['current_rate'];
                    $this->model->final_rate   = $this->data['final_rate'];

                    if ($this->model->percentage > 0) {
                        try {
                            $premium = ( $this->model->detail->insured_value * $this->model->final_rate ) / 100;

                            $this->model->detail->update([
                                'premium' => $premium
                            ]);
                        } catch (QueryException $e) {
                            $this->errors = $e->getMessage();
                        }
                    }
                } else {
                    $this->model->surcharge    = false;
                    $this->model->current_rate = $this->data['current_rate'];
                    $this->model->final_rate   = $this->data['final_rate'];
                }

                $this->model->detail()->update([
                    'approved' => true
                ]);
            } else {
                $this->model->detail()->update([
                    'rejected' => true
                ]);

                $this->model->approved = false;
            }
        } elseif ($this->data['approved'] === 2) {
            try {
                $this->model->observations()->create([
                    'id'          => date('U'),
                    'ad_user_id'  => $user->id,
                    'ad_state_id' => $this->data['state']['id'],
                    'observation' => $this->data['observation'],
                ]);
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        $this->model->read = false;

        return $this->saveModel();
    }


    /**
     * @param MailController $mail
     * @param string         $rp_id
     * @param string         $id
     * @param bool           $response
     *
     * @return bool
     */
    public function sendProcessMail(MailController $mail, $rp_id, $id, $response = false)
    {
        if ($this->getFacultativeById(decode($id)) && is_int($this->approved)) {
            $this->fa     = $this->getModel();
            $this->header = $this->fa->detail->header;
            $this->client = $this->header->client;

            $this->sendMail($mail, $rp_id, $response);
        }

        return false;
    }


    /**
     * @param Request $request
     * @param int     $id_observation
     *
     * @return bool
     */
    public function storeAnswer($request, $id_observation)
    {
        $user       = $request->user();
        $this->data = $request->all();

        $this->model->observations()->where('id', $id_observation)->update([
            'response'             => true,
            'observation_response' => $this->data['observation_response'],
            'date_response'        => new Carbon()
        ]);

        return $this->saveModel();
    }


    /**
     * @param Request $request
     * @param int     $id
     *
     * @return bool
     */
    public function readUpdate(Request $request, $id)
    {
        if ($this->getFacultativeById($id)) {
            $this->data = $request->all();
            $read       = filter_var($this->data['read'], FILTER_VALIDATE_BOOLEAN);

            $this->model->read = $read;

            return $this->saveModel();
        }

        return false;
    }

}