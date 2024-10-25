<?php

namespace Sibas\Repositories\Td;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Td\Detail;
use Sibas\Entities\Td\Header;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{

    public $message_old = '';
    /**
     * Create a newly created Detail.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function createDetail($request)
    {
        $this->data = $request->all();
        if ($this->getDetailById($this->data['id_detail'])) {
            return $this->updateDetail($this->model);
        }

        return $this->storeDetail($request);
    }


    public function updateRate($header, $rate, $exchangeRate)
    {
        $totalPremium = 0;
        
        foreach ($header->details as $key => $value) {
            if ($this->getDetailById($value->id)) { 
                if ($this->model instanceof Detail) {
                    $this->model->rate          = $rate->rate_final;
                    $this->model->rate_month    = $rate->rate_final/12;
                    $this->model->premium       = ($value->insured_value*$rate->rate_final)/100;
                    $this->model->premium_month = $this->model->premium/12;
                    
                    
                    $premium = $this->model->premium;
                    $this->saveModel();
                }
                $totalPremium += $premium;
            }
        }

        return $totalPremium;
    }
    
    public function returnPremium($type_term,$value,$rate,$mil,$term) {
        $premium = 0;
        
        switch ($type_term) {
            case 'Y':
                $premium = (( $value * $rate ) / $mil) * $term;
                break;
            case 'M':
                $premium = ( $value * (($rate / 12) * $term) ) / $mil;
                break;
            case 'D':
                //--edw--> $premium = ( $value * (($rate / (365+5)) * $term) ) / $mil;
                //--edw--> $premium = ( $value * (($rate / (365)) * $term) ) / $mil;
                $premium = ( $value * (($rate / 12) * $term) ) / $mil;
                break;

            default:
                break;
        }
        
        return $premium;
    }

    /**
     * Detail store.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function storeDetail($request, $header)
    {
        $this->data = $request->all();
        
        $this->model     = new Detail();
        $this->model->id = date('U');
        
        $this->model->matter_insured        = $this->data['matter_insured']['id'];
        $this->model->matter_description    = $this->data['matter_description'];
        $this->model->number                = $this->data['number'];
        $this->model->use                   = $this->data['use']['id'];
        $this->model->state                 = $this->data['state']['id'];
        $this->model->city                  = $this->data['city']['slug'];
        $this->model->op_td_header_id       = $header->id;
        $this->model->zone                  = $this->data['zone'];
        $this->model->locality              = $this->data['locality'];
        $this->model->address               = $this->data['address'];
        $this->model->nro_folio_real        = $this->data['nro_folio_real'];
        
        if($this->data['state']['id'] == 'P'){//remodelacion
            $this->model->construction_term     = $this->data['construction_term'];
            $this->model->construction_value    = $this->data['construction_value'];
            $this->model->remodeling_value      = $this->data['remodeling_value'];
            $this->model->insured_value         = $this->data['construction_value'] + $this->data['remodeling_value'];
        }elseif($this->data['state']['id'] == 'C'){//construccion
            $this->model->construction_term     = $this->data['construction_term'];
            $this->model->insured_value         = $this->data['insured_value'];
        }else
            $this->model->insured_value         = $this->data['insured_value'];
            

        return $this->saveModel();
    }


    /**
     * Client edit
     *
     * @param Request $request
     * @param         $client
     *
     * @return bool
     */
    public function editClient($request, $client)
    {
        $this->data = $request->all();

        return $this->updateClient($client);
    }


    /** Update Client
     *
     * @param null $client
     *
     * @return bool
     */
    /**private function updateDetail($detail = null)
    {
        if ($detail instanceof Detail) {
            $this->model = $detail;
            $this->setData();

            return $this->saveModel();
        }

        return false;
    }/**/


    /** Set complementary data on Issue
     *
     * @param Request      $request
     * @param Model|Client $client
     *
     * @return bool
     */
    public function updateIssueClient($request, $client)
    {
        $this->data  = $request->all();
        $this->model = $client;

        $this->data['document_type'] = $this->model->document_type;
        $this->data['complement']    = $this->model->complement;
        $this->data['gender']        = $this->model->gender;
        $this->data['weight']        = $this->model->weight;
        $this->data['height']        = $this->model->height;

        $this->setData();

       

        return $this->saveModel();
    }


    /** Set data to Client
     *
     */
    private function setData()
    {
        $this->model->matter_insured     = $this->data['matter_insured'];
        $this->model->matter_description = $this->data['matter_description'];
        $this->model->number             = $this->data['number'];
        $this->model->use                = $this->data['use'];
        $this->model->city            = $this->data['city'];
        $this->model->zone            = $this->data['zone'];
        $this->model->locality        = $this->data['locality'];
        $this->model->address         = $this->data['address'];
        $this->model->op_td_header_id = $this->data['id_header'];

        if ($this->data['matter_insured'] == 'PR') {
            $this->model->insured_value = ( $this->data['construction_value'] );
        } else {
            $this->model->insured_value = $this->data['insured_value'];
        }
    }


    /**
     * actualiza prima en base a la tasa
     */
    private function setDataRate()
    {
        $this->model->rate    = $this->rate_final;
        $this->model->premium = ( $this->data['insured_value'] * $this->rate_final ) / 100;
    }


    /**
     * Get Detail by Id
     *
     * @param $detail_id
     *
     * @return bool
     */
    public function getDetailById($detail_id)
    {
        $this->model = Detail::with([
            'header',
            'facultative',
        ])->where('id', $detail_id)->first();

        if ($this->model instanceof Detail) {
            return true;
        }

        return false;
    }
    
    public function getDetailByNroFolioReal($nro_folio_real)
    {
        $this->model = Detail::with([
            'header',
            'facultative',
        ])->where('nro_folio_real', $nro_folio_real)->first();

        if ($this->model instanceof Detail) {
            return true;
        }

        return false;
    }


    /**
     * funcion retorna registros mediante id de hader
     *
     * @param type $idHeader
     *
     * @return boolean
     */
    public function getDetailByHeader($idHeader)
    {
        $query       = Detail::where('op_td_header_id', '=', $idHeader);
        $this->model = $query->get();

        if ($this->model->count() == 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * funcion elimina resitros de facultativo por el id
     *
     * @param type $id_detail
     *
     * @return boolean
     */
    public function delFacultativeById($id_detail)
    {
        if ($this->getDetailById($id_detail)) {
            if ($this->model->facultative) {
                $this->model->facultative->delete();

                return true;
            }
        }

        return false;
    }


    /** Find Client by Id
     *
     * @param $client_id
     *
     * @return bool
     */
    public function getClientById($client_id)
    {
        $this->model = Client::where('id', '=', $client_id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * @param Request $request
     * @param string  $header
     * @param bool    $coverage
     *
     * @return bool
     */
    public function storeProperty(Request $request, $header, $coverage = false)
    {
        $this->data = $request->all();

        try {
            $id = date('U');

            $detail = [
                'id'                 => $id,
                'matter_insured'     => $this->data['matter_insured'],
                'matter_description' => $this->data['matter_description'],
                'number'             => $this->data['number'],
                'use'                => $this->data['property_use'],
                'city'               => $this->data['city'],
                'zone'               => $this->data['zone'],
                'locality'           => $this->data['locality'],
                'address'            => $this->data['address'],
                'insured_value'      => $this->data['insured_value'],
            ];

            if ($coverage) {
            }

            $header->details()->create($detail);

            if ($coverage && $this->getDetailById($id)) {
                return true;
            }

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function updateDetail(Request $request)
    {
        $this->data = $request->all();
        
        try {
            $fields = [
                'matter_insured'        => $this->data['matter_insured']['id'],
                'matter_description'    => $this->data['matter_description'],
                'number'                => $this->data['number'],
                'use'                   => $this->data['use']['id'],
                'state'                 => $this->data['state']['id'],
                'city'                  => $this->data['city']['slug'],
            ];
            
            if($this->data['state']['id'] == 'P'){
                $fields['construction_value']    = $this->data['construction_value'];
                $fields['remodeling_value']      = $this->data['remodeling_value'];
                $fields['insured_value']         = $this->data['construction_value'] + $this->data['remodeling_value'];
            }elseif($this->data['state']['id'] == 'C'){
                $fields['construction_value']    = 0;
                $fields['remodeling_value']      = 0;
                $fields['insured_value']         = $this->data['insured_value'];
                $fields['construction_term']         = $this->data['construction_term'];
            }else{
                $fields['construction_value']    = 0;
                $fields['remodeling_value']      = 0;
                $fields['insured_value']         = $this->data['insured_value'];
                $fields['construction_term']     = 0;
            }
            
            $this->model->update($fields);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }
        return false;
    }
    
    public function updateRiskIssuance($request)
    {
        $this->data = $request->all();
        
        try {
            $this->model->update([
                'make'                  => strtoupper($this->data['make']),
                'model'                 => strtoupper($this->data['model']),
                'chassis_number'        => $this->data['chassis_number'],
                'zone'                  => $this->data['zone'],
                'locality'              => $this->data['locality'],
                'address'               => $this->data['address'],        
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }
        return false;
    }
    
    public function removeDetail()
    {
        try {
            $this->model->delete();

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
    
    public function updateNumberpolicy($header, $policies, $exchangeRate) {
        
        foreach ($header->details as $key => $value) {
            if ($this->getDetailById($value->id)) {
                if ($this->model instanceof Detail) {
                    
                    switch ($value->state) {
                        case 'F'://Terminado
                            $this->model->policy_number_mr = $policies->where('modality','MR')->first()->number;
                            
                            break;
                        case 'C'://Construccion
                            $insured_value = ($header->currency == 'BS')?($value->insured_value/$exchangeRate->bs_value):$value->insured_value;
                            
                            if($insured_value > 50000){//si es mayor que 50000 USD
                                $this->model->policy_number_mr = $policies->where('modality','MR')->first()->number;
                                $this->model->policy_number_td = $policies->where('modality','TD')->first()->number;
                            }else{
                                //--a solicitud de Juan pablo--> $this->model->policy_number_td = $policies->where('modality','TD')->first()->number;
                                $this->model->policy_number_mr = $policies->where('modality','MR')->first()->number;
                            }
                            break;
                        case 'P'://Remodelacion
                            $remodeling_value = ($header->currency == 'BS')?($value->remodeling_value/$exchangeRate->bs_value):$value->remodeling_value;
                            
                            if($remodeling_value > 50000){//si es mayor que 50000 USD
                                $this->model->policy_number_mr = $policies->where('modality','MR')->first()->number;
                                $this->model->policy_number_td = $policies->where('modality','TD')->first()->number;
                            }else{
                                $this->model->policy_number_mr = $policies->where('modality','MR')->first()->number;
                            }
                                
                            break;
                        default:
                            $this->model->policy_number_mr = $policies->where('modality','MR')->first()->number;
                            break;
                    }
                    
                    $this->saveModel();
                }
                
            }
        }
        
    }
    
    public function verifyCatastralCode($catastral_code)
    {
        try{
            $engine_chassis = Detail::with([
                'header',
            ])->whereHas('header', function($q){
                $q->where('op_td_headers.canceled', false);
                $q->where('op_td_headers.issued', true);
            })->where('nro_folio_real','=',$catastral_code)->get();
           
            if($engine_chassis->count()==0){
                return true;
            }

        }catch (QueryException $e){
            $this->errors = $e->getMessage();
        }

        return false;
    }
    
    public function verifyCatastralCodeHeader($header)
    {
        foreach ($header->details as $key => $value) {
            if($this->verifyCatastralCode($value['nro_folio_real']) == false)
                return false;
        }
        return true;
    }
    
    public function setDataWs($result, $data)
    {
        $detail = new Detail;
        $detail->matter_insured     = 'PR';//Inmueble
        $detail->matter_description = $result['response']['type'];
        $detail->nro_folio_real     = $result['response']['cadastral_code'];
        $detail->zone               = $result['response']['zone'];
        $detail->locality           = $result['response']['location'];
        $detail->address            = $result['response']['situation'].' - '.$result['response']['city'];
        $detail->insured_value      = $result['response']['insured_value'];
        $detail->currency           = config('base.currencies_ws.'.$result['response']['currency']);
        $detail->use                = 'ID'; //Inmueble Comiciliario
        $detail->state              = 'F'; //Terminado
        
        return $detail;
    }
    
    public function validUseAndInsuredValue($header, $detail, $retailerProduct)
    {
        $parameter       = $retailerProduct->parameters->filter(function ($item) {
            return $item->slug === 'GE';
        })->first();
        
        if($detail->use == 'IP'){
            $this->message_old = 'Inmuebles cuyo uso sea tipo INDUSTRIAL requiere aprobacion de la compañia, favor comunicarse con Omar Gonzalez.';
            return true;
        }else{
            $amount = ($header->currency=='BS')?$detail->insured_value/$retailerProduct->retailer->exchangeRate->bs_value:$detail->insured_value;
            
            if($amount > $parameter->amount_max){
                $this->message_old = 'El Riesgo excedió el monto máximo permitido "Monto maximo permitido  
                '.$parameter->amount_max.' USD", favor de comunicarse con Omar Gonzalez.';
                return true;
            }
        }

        return false;
    }

    public function getMessageOld()
    {
        return $this->message_old;
    }
}