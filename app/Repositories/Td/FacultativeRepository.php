<?php

namespace Sibas\Repositories\Td;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Td\Detail;
use Sibas\Entities\Td\Facultative;
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


    public function getFacultativeByHeaderId($header_id)
    {
        $this->model = Facultative::with('detail.header.user', 'detail.header.client',
            'observations')->where('op_td_detail_id', '=', $header_id)->get();

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
     *
     * @return bool
     */
    public function storeFacultative($detailFac, $user)
    {
        $this->observation = false;
        try {
            $idDetails = [ ];
            $i         = 0;
            $r         = 0;
            if (isset( $detailFac['roles']['role1'] ) && ! isset( $detailFac['roles']['role2'] )) {
                #solo role 1
                foreach ($detailFac['roles']['role1'] as $key => $value) {
                    $idDetails[$i]['detail']  = $value;
                    $idDetails[$i]['combine'] = 0;
                    $i++;
                }
                $r = 1;
            } elseif ( ! isset( $detailFac['roles']['role1'] ) && isset( $detailFac['roles']['role2'] )) {
                #solo role 2
                foreach ($detailFac['roles']['role2']['details'] as $key => $value) {
                    $idDetails[$i]['detail']  = $value;
                    $idDetails[$i]['combine'] = 0;
                    $i++;
                }
                $r = 2;
            } elseif (isset( $detailFac['roles']['role1'] ) && isset( $detailFac['roles']['role2'] )) {
                #role 1 y role2  combinar
                $role1 = [ ];
                foreach ($detailFac['roles']['role1'] as $key => $value) {
                    $role1[] = $value->id;
                }

                foreach ($detailFac['roles']['role2']['details'] as $key2 => $value2) {
                    $idDetails[$i]['detail'] = $value2;
                    if (in_array($value2->id, $role1)) {
                        $idDetails[$i]['combine'] = 1;
                    } else {
                        $idDetails[$i]['combine'] = 0;
                    }
                    $i++;
                }
                $r = 2;
            }
            # actualiza tabla facultativos
            $h = 0;
            foreach ($idDetails as $key => $value) {
                if ($value['combine'] == 1) {
                    $reason = $this->returnReason($value['detail'], $detailFac,
                            1) . ' ' . $this->returnReason($value['detail'], $detailFac, 2);
                } else {
                    $reason = $this->returnReason($value['detail'], $detailFac, $r);
                }
                if ($value['detail']->facultative instanceof Facultative) {
                    $value['detail']->facultative->update([
                        'reason' => $reason,
                        'state'  => 'PE',
                        'read'   => false,
                    ]);
                } else {
                    $value['detail']->facultative()->create([
                        'id'         => date('U') + $h,
                        'ad_user_id' => $user->id,
                        'reason'     => $reason,
                        'state'      => 'PE',
                        'read'       => false,
                    ]);
                }

                $this->idsFactultative[] = $value['detail']->id;
                $this->observation .= $reason;
                $h++;
            }

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * funcion retorna mensaje de error facultativos
     *
     * @param type $object
     * @param type $detailFac
     * @param type $role
     *
     * @return string
     */
    public function returnReason($object, $detailFac, $role)
    {
        $reason = '';
        switch ($role) {
            case 1:
                if($object->use == 'IP' && $object->matter_insured == 'PR')
                    $mountt=200000;
                else
                    $mountt=$detailFac['parameter']['FA']->amount_max;
                $reason = str_replace([ ':riesgo_asegurado', ':name', ':cumulus', ':amount_max' ], [
                        $object->matter_description,
                        $object->header->client->full_name,
                        number_format($object->insured_value, 2),
                        number_format(($mountt), 2)
                    ], $this->reasonInmueble) . '<br>';
                break;
            case 2:
                $reason = str_replace([ ':name', ':cumulus', ':amount_max',':type_money' ], [
                        $object->header->client->full_name,
                        number_format($detailFac['roles']['role2']['total_amount'], 2),
                        number_format($detailFac['roles']['role2']['amount_max'], 2),
                        $object->header->currency
                    ], $this->reasonCumulus) . '<br>';
                break;
            default:
                break;
        }

        return $reason;
    }


    /**
     * funcion retorna obaservacion variable global
     * @return type
     */
    public function returnObservation()
    {
        return $this->observation;
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


    /**
     * fucion determina regla facultativo mediante el valor asegurado.
     *
     * @param type $idHeader
     */
    public function roleFacultative($rpId, $idHeader, $header)
    {
        $moneda = $this->returnTipoCambio($rpId, $header);

        $ge = ProductParameter::where('ad_retailer_product_id', $rpId)->where('slug', 'GE')->first();
        $fa = ProductParameter::where('ad_retailer_product_id', $rpId)->where('slug', 'FA')->first();

        $detail       = Detail::where('op_td_header_id', $idHeader)->get();
        
        $totalInsured = 0;
        $facultative  = [ ];
        $arrayFac     = [ ];
        $keyFac       = 0;

        # validacion facultativos por riesgo
        foreach ($detail as $key => $value) {
            
            if ($value->matter_insured == 'PR' && $value->use == 'IP') {
                //if ($value->insured_value >= ( $fa->amount_min * $moneda ) && $value->insured_value <= ( $fa->amount_max * $moneda )) {
                if ($value->insured_value > (200000 * $moneda)) {
                    $arrayFac['role1'][] = $value;
                    $keyFac++;
                }
            }else{
                if ($value->insured_value > ( $fa->amount_max * $moneda )) {
                    $arrayFac['role1'][] = $value;
                    $keyFac++;
                }
            }
            $totalInsured += $value->insured_value;
        }
        

        # validacion facultativos generales
        if ($totalInsured > ( $ge->amount_max * $moneda )) {
            $arrayFac['role2']['total_amount'] = $totalInsured;
            $arrayFac['role2']['amount_max']   = ( $ge->amount_max * $moneda );
            $arrayFac['role2']['details']      = $detail;
            $keyFac++;
        }

        $facultative['facultative']     = $keyFac;
        $facultative['roles']           = $arrayFac;
        $facultative['parameter']['FA'] = $fa;
        $facultative['parameter']['GE'] = $ge;

        return $facultative;
    }


    /**
     * Store vehicle Facultative TD
     *
     * @param Model|Detail          $detail
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|User            $user
     * @param bool                  $coverage
     *
     * @return bool
     * @throws \Exception
     */
    public function storeTdFacultative($detail, $retailerProduct, $user, $coverage = false)
    {
        $parameters    = $retailerProduct->parameters;
        $parameter     = null;
        $exchange_rate = $retailerProduct->retailer->exchangeRate;
        $reason        = '';
        $slug          = ( $detail->matter_insured === 'PR' && $detail->use === 'IP' ) ? 'FA' : 'GE';

        $parameter = $parameters->filter(function ($item) use ($slug) {
            return ( $item->slug === $slug );
        })->first();

        if ($parameter instanceof ProductParameter) {
            $insured_value = $detail->insured_value;

            if ($detail->header->currency === 'BS') {
                $insured_value = $detail->insured_value / $exchange_rate->bs_value;
            }

            $amount     = false;
            $amount_max = 0;

            switch ($parameter->slug) {
                case 'GE':
                    $amount     = ( $insured_value > $parameter->amount_max ) ? true : false;
                    $amount_max = $parameter->amount_max;
                    break;
                case 'FA':
                    $amount     = ( $insured_value >= $parameter->amount_min || $insured_value > $parameter->amount_max ) ? true : false;
                    $amount_max = $parameter->amount_min;
                    break;
            }

            $reason .= $amount ? str_replace([ ':matter_insured', ':amount_max' ], [
                    config('base.property_types.' . $detail->matter_insured),
                    number_format($amount_max, 2)
                ], $this->reasonProperty) . '<br>' : '';

            if ($coverage) {
                if ($amount) {
                    $this->errors = [ 'reason' => $reason ];

                    $detail->delete();

                    return 428;
                }

                $detail->update([
                    'approved' => true,
                ]);

                return 202;
            }

            /*try {
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
            }*/

            return true;
        }

        return false;
    }


    /**
     * retorna tipo de cambio
     *
     * @param type $rpId
     * @param type $header
     *
     * @return type
     */
    function returnTipoCambio($rpId, $header)
    {
        $retailerProduct = RetailerProduct::where('id', $rpId)->first();
        $moneda          = 1;
        switch ($header->currency) {
            case 'USD':
                $moneda = $retailerProduct->retailer->exchangeRate->usd_value;
                break;
            case 'BS':
                $moneda = $retailerProduct->retailer->exchangeRate->bs_value;
                break;
            default:
                break;
        }

        return $moneda;
    }

}