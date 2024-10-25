<?php

namespace Sibas\Repositories\Au;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Au\Detail;
use Sibas\Entities\Au\Header;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{
    public $message_old = '';

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
            'vehicleType',
            'vehicleMake',
            'vehicleModel',
            'category',
            'header',
            'facultative',
        ])->where('id', $detail_id)->first();

        if ($this->model instanceof Detail) {
            return true;
        }

        return false;
    }
    
    /**
     * data by ruat
     * @param type $detail_id
     * @return boolean
     */
    public function getDetailByRuat($nro_ruat)
    {
        $this->model = Detail::with([
            'vehicleType',
            'vehicleMake',
            'vehicleModel',
            'category',
            'header',
            'facultative',
        ])->where('nro_ruat', $nro_ruat)->first();

        if ($this->model instanceof Detail) {
            return true;
        }

        return false;
    }


    /**
     * Store Detail vehicle
     *
     * @param Request      $request
     * @param Model|Header $header
     * @param bool         $coverage
     *
     * @return bool
     */
    public function storeVehicle($request, $header, $coverage = false)
    {
        $this->data = $request->all();

        try {
            if ($this->data['year'] === 'old') {
                $this->data['year'] = $this->data['year_old'];
            }

            $id = date('U');

            $detail = [
                'id'                              => $id,
                'ad_vehicle_type_id'              => $this->data['vehicle_type']['id'],
                'ad_retailer_product_category_id' => $this->data['category']['id'],
                'year'                            => $this->data['year'],
                'license_plate'                   => $this->data['license_plate'],
                'use'                             => $this->data['use'],
                'mileage'                         => (boolean) $this->data['mileage'],
                'insured_value'                   => $this->data['insured_value'],
                'engine'                    => $this->data['engine'],
                'chassis'                   => $this->data['chassis'],
                'color'                     => $this->data['color'],
                'tonnage_capacity'          => $this->data['tonnage_capacity'],
                'seat_number'               => $this->data['seat_number'],
                'circulation_plaza'         => $this->data['circulation_plaza'],
                'displacement'              => $this->data['displacement'],
                'origin'                    => $this->data['origin'],
                'brand'                     => $this->data['brand'],
                'brand_id'                  => $this->data['brand_id'],
                'model'                     => $this->data['model'],
                'guarantee_id'              => $this->data['guarantee_id'],
                'own_damage_insurance'      => ($this->data['own_damage_insurance'] != '')?$this->data['own_damage_insurance']:1,
            ];

            if ($coverage) {
                $detail['color']            = $this->data['color'];
                $detail['engine']           = $this->data['engine'];
                $detail['chassis']          = $this->data['chassis'];
                $detail['tonnage_capacity'] = $this->data['tonnage_capacity'];
                $detail['seat_number']      = $this->data['seat_number'];
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
     * Remove vehicle from storage.
     */
    public function removeVehicle()
    {
        try {
            $this->model->delete();

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * Update vehicle
     *
     * @param Request $request
     *
     * @return bool
     */
    public function updateVehicle(Request $request)
    {
        $this->data = $request->all();

        try {
            if ($this->data['year'] === 'old') {
                $this->data['year'] = $this->data['year_old'];
            }

            $this->model->update([
                'ad_vehicle_type_id'                => $this->data['vehicle_type']['id'],
                //'ad_vehicle_make_id'              => $this->data['vehicle_make']['id'],
                //'ad_vehicle_model_id'             => $this->data['vehicle_model']['id'],
                'ad_retailer_product_category_id'   => $this->data['category']['id'],
                'year'                              => $this->data['year'],
                'license_plate'                     => $this->data['license_plate'],
                'use'                               => $this->data['use'],
                'mileage'                           => (boolean) $this->data['mileage'],
                'insured_value'                     => $this->data['insured_value'],
                'engine'                            => $this->data['engine'],
                'chassis'                           => $this->data['chassis'],
                'color'                             => $this->data['color'],
                'tonnage_capacity'                  => $this->data['tonnage_capacity'],
                'seat_number'                       => $this->data['seat_number'],
                'circulation_plaza'                 => $this->data['circulation_plaza'],
                'displacement'                      => $this->data['displacement'],
                'origin'                            => $this->data['origin'],
                'brand'                             => $this->data['brand'],
                'model'                             => $this->data['model'],
                'own_damage_insurance'              => (isset($this->data['own_damage_insurance']))?$this->data['own_damage_insurance']:0,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * Update vehicle issuance
     *
     * @param Request $request
     *
     * @return bool
     */
    public function updateVehicleIssuance(Request $request)
    {
        $this->data = $request->all();

        try {
            if ($this->data['year'] === 'old') {
                $this->data['year'] = $this->data['year_old'];
            }

            $this->model->update([
                'ad_vehicle_type_id'              => $this->data['vehicle_type']['id'],
                //'ad_vehicle_make_id'              => $this->data['vehicle_make']['id'],
                //'ad_vehicle_model_id'             => $this->data['vehicle_model']['id'],
                'ad_retailer_product_category_id' => $this->data['category']['id'],
                'year'                            => $this->data['year'],
                'license_plate'                   => $this->data['license_plate'],
                'use'                             => $this->data['use'],
                'mileage'                         => (boolean) $this->data['mileage'],
                'color'                           => $this->data['color'],
                'engine'                          => $this->data['engine'],
                'chassis'                         => $this->data['chassis'],
                'tonnage_capacity'                => $this->data['tonnage_capacity'],
                'seat_number'                     => $this->data['seat_number'],
                'insured_value'                   => $this->data['insured_value'],
                'brand'                           => $this->data['brand'],
                'model'                           => $this->data['model'],
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }

    
    public function verifyEngineChassis($engine, $chassis)
    {
        try{
            $engine_chassis = Detail::with([
                'header',
            ])->whereHas('header', function($q){
                $q->where('op_au_headers.canceled', false);
                $q->where('op_au_headers.issued', true);
            })->where('engine','=',$engine)->where('chassis','=',$chassis)->get();
           
            if($engine_chassis->count()==0){
                return true;
            }

        }catch (QueryException $e){
            $this->errors = $e->getMessage();
        }

        return false;
    }
    
    public function verifyEngineChassisHeader($header)
    {
        foreach ($header->details as $key => $value) {
            if($this->verifyEngineChassis($value['engine'],$value['chassis']) == false)
                return false;
        }
        return true;
    }
    
    public function setDataWs($result, $data)
    {
        $typeV = $data['vehicle_types']->where('equivalence', $result['response']['type_vehicle']);

        $detail                     = new Detail;
        $detail->year               = $result['response']['year'];
        $detail->license_plate      = $result['response']['number_plate'];
        $detail->engine             = $result['response']['engine'];
        $detail->chassis            = $result['response']['chassis'];
        $detail->color              = $result['response']['color'];
        $detail->tonnage_capacity   = $result['response']['tonnage_capacity'];
        $detail->seat_number        = $result['response']['number_seat'];
        $detail->circulation_plaza  = $result['response']['circulation_plaza'];
        $detail->displacement       = $result['response']['displacement'];
        $detail->origin             = $result['response']['origin'];
        $detail->brand              = $result['response']['brand'];
        $detail->brand_id           = $result['response']['brand_id'];
        $detail->model              = $result['response']['model'];
        $detail->insured_value      = $result['response']['insured_value'];
        $detail->guarantee_id       = $result['response']['guaranteeId'];
        $detail->currency           = config('base.currencies_ws.' . $result['response']['currency']);

        if (count($typeV) > 0) {
            $detail->vehicle_type = $typeV->first();
        }
        if ($result['response']['use'] != '')
            $detail->use = ($result['response']['use'] == 'PRIVADO') ? 'PR' : 'PU';
        
        return $detail;
    }

    public function amountMaxAndYearMaxVerified($request, $retailerProduct, $category, $insured_value, $currency, $year)
    {
        $data           = $request->all();
        $parameter      = $retailerProduct->parameters()->where('slug', 'GE')->first();
        $amount_max     = 'amount_max_'.strtolower($category);
        $year_max     = 'year_max_'.strtolower($category);
        
        $exchange_rate  = $retailerProduct->retailer->exchangeRate->bs_value;
        $amount         = ($currency=='BS')?$insured_value/$exchange_rate:$insured_value;
        $exclusive_plates = json_decode($parameter->exclusive_plates, true);
        
        if($amount > $parameter->$amount_max)
            $this->message_old = 'El Valor asegurado supera el monto maximo permitido "Monto maximo permitido '.$parameter->$amount_max.' USD", favor de comunicarse con Omar Gonzalez';

        if(!in_array($data['license_plate'], $exclusive_plates) && (date('Y')-$year) > $parameter->$year_max)
            $this->message_old = 'El año del vehículo supera la antiguedad máxima permitida "Antiguedad máxima '.$parameter->$year_max.' años", favor de comunicarse con Omar Gonzalez';

        if($this->message_old)
            return true;

        return false;
    }

    public function getMessageOld()
    {
        return $this->message_old;
    }
}