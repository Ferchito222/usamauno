<?php

namespace Sibas\Repositories\De;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\De\Detail;
use Sibas\Entities\De\Facultative;
use Sibas\Entities\De\Observation;
use Sibas\Entities\ProductParameter;
use Sibas\Entities\RetailerProduct;
use Sibas\Http\Controllers\MailController;
use Sibas\Repositories\BaseRepository;

class FacultativeRepository extends BaseRepository
{

    /**
     * @var ProductParameter
     */
    private $parameter = null;

    /**
     * @var array
     */
    protected $props = [
        'reason' => '',
        'state'  => '',
    ];


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

        $fa = Facultative::with([
            'user',
            'detail.header.user',
            'detail.client',
            'observations.state',
            'observations.user',
            'detail.header.creditProduct',
        ]);

        switch ($user_type) {
            case 'SEP':
                $fa->whereHas('detail.header', function ($query) use ($user) {
                    $query->where('ad_user_id', $user->id)
                        ->where('type', 'I')
                        ->where('issued', false)
                        ->where('canceled', false);
                });
                break;
            case 'COP':
                $fa->whereHas('detail.header', function ($query) use ($user, $header_id) {
                    $query->where('type', 'I')
                        ->where('issued', false)
                        ->where('canceled', false)
                        ->whereHas('user.city', function ($query) use ($user) {
                            if($user->permissions->where('slug', 'RR')->count() > 0)
                                $query->where('slug', 'like', '%' . $user->city->slug . '%');
                    });

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
     * @param Request $request
     *
     * @return bool
     */
    public function storeFacultative(Request $request, $rp_id)
    {
        $header          = $request['header'];
        $detail          = $request['detail'];
        $retailer        = $request['retailer'];
        $credit_product  = in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ]) ? 'PMO' : null;
        $retailerProduct = $retailer->retailerProducts()->where('id', $rp_id);
        
        $retailerProduct->with([
            'parameters',
            'retailer.exchangeRate',
        ]);
            
        $retailerProduct = $retailerProduct->first();
        
        if ($retailerProduct->facultative) {
            if ($this->getParameter($retailerProduct, $detail)) {
                $evaluation = $this->evaluation($detail);

                try {
                    $detail->update([
                        'company_approval' => $this->parameter->slug,
                    ]);

                    if ($detail->facultative instanceof Facultative) {
                        if ($evaluation) {
                            $detail->facultative()->update($this->props);

                            return true;
                        } else {
                            $detail->facultative()->delete();
                        }
                    } else {
                        if ($evaluation) {
                            $detail->facultative()->create([
                                'id'     => date('U'),
                                'reason' => $this->props['reason'],
                                'state'  => $this->props['state'],
                            ]);

                            return true;
                        }
                    }
                } catch (QueryException $e) {
                    $this->errors = $e->getMessage();
                }
            } else {
                $detail->update([
                    'company_approval' => 'FC',
                ]);
            }
        }

        return false;
    }


    private function evaluation($detail)
    {
        switch ($this->parameter->slug) {
            case 'FC':

                break;
            case 'AE':
                return $this->setAeEvaluation($detail);
                break;
            case 'FA':
                return $this->setAeEvaluation($detail);
                break;
        }

        return false;
    }


    /**
     * @param Model|Detail $detail
     *
     * @return bool
     */
    private function setAeEvaluation($detail)
    {
        $words = [
            'MINERO',
            'BOCA DE POZO',
            'BOMBERO',
            'POLICÍA',
            'FFAA',
            'MOTOCICLETA',
            'PETRÓLEO',
            'PETROLERO',
            'PETROLERA',
            'MINERÍA',
            'MINERALES',
            'INTERIOR MINA',
        ];

        $facultative = false;
        $response    = $this->getEvaluationResponse($detail->response);
        $imc         = $detail->client->imc;
        $reason      = '';
        $activity    = ( $detail->client->activity->category === 'danger' ? true : false );
        $occupation  = false;

        foreach ($words as $word) {
            if (strpos($detail->client->occupation_description, $word)) {
                $occupation = true;

                break;
            }
        }

        if ($imc) {
            $reason .= str_replace([ ':name' ], [ $detail->client->full_name ], $this->reasonImc) . '<br>';

            $facultative = true;
        }
        
        if ($response) {
            $reason .= str_replace([ ':name' ], [ $detail->client->full_name ], $this->reasonResponse) . '<br>';

            $facultative = true;
        }

        if ($activity || $occupation) {
            $reason .= str_replace([ ':name' ], [ $detail->client->full_name ], $this->reasonActivity) . '<br>';

            $facultative = true;
        }

        $amount_min = $this->parameter->amount_min;
            
        if ($this->parameter->slug == 'FA') {
            $titular = ($detail->header->details->first()->id == $detail->id)?'Titular':'Codeudor';
            $reason .= str_replace([ ':name', ':cumulus', ':amount_max' ], [
                    $titular.' '.$detail->client->full_name,
                    number_format($detail->cumulus, 2),
                    number_format(( $amount_min - 1 ), 2)
                ], $this->reasonCumulus) . '<br>';

            $facultative = true;
        }

        if ($facultative) {
            $this->props['reason'] = $reason;
            $this->props['state']  = 'PE';
        }

        return $facultative;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|Detail          $detail
     *
     * @return bool
     */
    private function getParameter($retailerProduct, $detail)
    {
        /*
         * Cumulus to USD
         */
        // $exchangeRate = $retailerProduct->retailer->exchangeRate;
        // $cumulus      = $detail->cumulus / $exchangeRate->bs_value; 

        /*
         * Cumulus to BS
         */
        $cumulus = $detail->cumulus;

        foreach ($retailerProduct->parameters as $parameter) {
            /*if ($detail->header->creditProduct->slug === 'PMO') {
                if (( $cumulus >= $parameter->amount_min && $cumulus <= $parameter->amount_max )
                    || ( $parameter->slug === 'FA' && $cumulus > $parameter->amount_max )
                ) {
                    $this->parameter = $parameter;
                }
            } else {
                if (( $detail->client->birthdate->age >= $parameter->age_min
                      && $detail->client->birthdate->age <= $parameter->age_max )
                    && $parameter->slug !== 'GE'
                    && ( ( $cumulus >= $parameter->amount_min && $cumulus <= $parameter->amount_max )
                         || ( $parameter->slug === 'FA' && $cumulus > $parameter->amount_max ) )
                ) {
                    $this->parameter = $parameter;
                }
            }*/
            $amount_min = $parameter->amount_min;
            
            if ( ! in_array($parameter->slug, ['GE'])
                && ( ( $cumulus >= $amount_min && $cumulus <= $parameter->amount_max )
                     || ( $parameter->slug === 'FA' && $cumulus > $parameter->amount_max ) )
            ) {
                $this->parameter = $parameter;
            }
        }

        if ($this->parameter instanceof ProductParameter) {
            return true;
        }

        return false;
    }


    public function getFacultativeById($id)
    {
        $this->model = Facultative::with([
            'detail.header.user.city.careCenters',
            'detail.client',
            'detail.coverages',
            'observations',
        ])
            ->where('id', $id)
            ->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
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

        $_obs = $this->data['observation'];

        if ($this->data['approved'] === 1 || $this->data['approved'] == 0 || $this->data['approved'] == 3) {
            $this->model->ad_user_id  = $user->id;
            $this->model->state       = 'PR';
            $this->model->observation = $_obs;

            if ($this->data['approved'] === 1 || $this->data['approved'] === 3) {
                $this->model->approved = true;

                if($this->data['approved'] === 3){#muerte accidental
                    $this->model->surcharge         = false;
                    $this->model->current_rate      = $this->data['current_rate_accidental'];
                    $this->model->final_rate        = 0;
                    $this->model->accidental_death  = true;
                }else{
                    if ($this->data['surcharge']) {
                        $this->model->surcharge    = true;
                        $this->model->percentage   = $this->data['percentage'];
                        $this->model->current_rate = $this->data['current_rate'];
                        $this->model->final_rate   = $this->data['final_rate'];

                        if(in_array($this->model->detail->header->creditProduct->slug, ['PMO', 'PLT','PLNT','PLNT','PCA','PCM'])){
                            $this->model->percentage_bank   = $this->data['percentage_bank'];
                            $this->model->percentage_cia    = $this->data['percentage_cia'];
                        }
                    } else {
                        $this->model->surcharge    = false;
                        $this->model->current_rate = $this->data['current_rate'];
                        $this->model->final_rate   = $this->data['final_rate'];
                    }
                }
                
                if(isset($this->data['monetary_pricing']))
                                $this->model->monetary_pricing = $this->data['monetary_pricing'];

                if(isset($this->data['preference_field']))
                    $this->model->preference_rate = $this->data['preference_field'];

                $this->model->detail()->update([
                    'approved' => true
                ]);

                if (count($this->data['coverages']) > 0) {
                    $coverages   = [];
                    $coverage_id = date('U');
                    $created_at  = new Carbon();

                    foreach ($this->data[ 'coverages' ] as $key => $coverage) {
                        $coverage_id -= 1;

                        array_push($coverages, [
                            'id'              => $coverage_id,
                            'op_de_detail_id' => $this->model->detail->id,
                            'coverage'        => $key,
                            'comment'         => '',
                            'granted'         => filter_var($coverage, FILTER_VALIDATE_BOOLEAN),
                            'created_at'      => $created_at,
                            'updated_at'      => $created_at,
                        ]);
                    }

                    $this->model->detail->coverages()->insert($coverages);
                }
            } else {
                $this->model->detail()->update([
                    'rejected' => true
                ]);

                $this->model->approved = false;
            }
        } elseif ($this->data['approved'] === 2) {
            $observation = new Observation([
                'id'          => date('U'),
                'ad_user_id'  => $user->id,
                'ad_state_id' => $this->data['state']['id'],
                'observation' => $_obs,
            ]);

            if ($this->data['state']['data_slug'] === 'me') {
                $observation->op_mc_answer_id = $this->data['mc_id'];
            }

            try {
                $this->model->observations()->save($observation);
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        $this->model->read = false;

        return $this->saveModel();
    }


    /**
     * @param Request $request
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
            $this->client = $this->fa->detail->client;

            $this->sendMail($mail, $rp_id, $response);
        }

        return false;
    }

}
