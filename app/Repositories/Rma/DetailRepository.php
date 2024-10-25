<?php

namespace Sibas\Repositories\Rma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Rma\Detail;
use Sibas\Entities\Rma\Header;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{

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
    
    /**
     * data by ruat
     * @param type $detail_id
     * @return boolean
     */
    public function getDetailByRuat($nro_ruat)
    {
        $this->model = Detail::with([
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
    public function store($request, $header, $coverage = false)
    {
        $this->data = $request->all();
        try {
            $id = date('U');

            $detail = [
                'id'            => $id,
                'property_use'  => $this->data['property_use'],
                'description'   => $this->data['description'],
                'serial_number' => $this->data['serial_number'],
                'city'          => $this->data['city'],
                'insured_value' => $this->data['insured_value'],
                'guarantee_id'  => $this->data['guarantee_id'],
            ];

            if ($coverage) {
                //$detail['color']            = $this->data['color'];
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
     * remove detail
     */
    public function remove()
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
    public function update(Request $request)
    {
        $this->data = $request->all();

        try {
            $this->model->update([
                'property_use'  => $this->data['property_use'],
                'description'   => $this->data['description'],
                'serial_number' => $this->data['serial_number'],
                'city'          => $this->data['city'],
                'insured_value' => $this->data['insured_value'],
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
    
    /**
     * update issuance
     * @param Request $request
     * @return boolean
     */
    public function updateDetailIssuance(Request $request)
    {
        $this->data = $request->all();
        try {
            $this->model->update([
                'property_use'  => $this->data['property_use'],
                'description'   => $this->data['description'],
                'serial_number' => $this->data['serial_number'],
                'city'          => $this->data['city'],
                'insured_value' => $this->data['insured_value'],
            ]);
            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }

    
    public function verifySerialNumber($serial_number)
    {
        try{
            $engine_chassis = Detail::with([
                'header',
            ])->whereHas('header', function($q){
                $q->where('op_rma_headers.canceled', false);
                $q->where('op_rma_headers.issued', true);
            })->where('serial_number','=',$serial_number)->get();
           
            if($engine_chassis->count()==0){
                return true;
            }

        }catch (QueryException $e){
            $this->errors = $e->getMessage();
        }

        return false;
    }
    
    public function verifySerialHeader($header)
    {
        foreach ($header->details as $key => $value) {
            if($this->verifySerialNumber($value['serial_number']) == false)
                return false;
        }
        return true;
    }
    
    public function setDataWs($result, $data)
    {
        $detail = new Detail;
        $detail->property_use   = $result['response']['property_use'];
        $detail->description    = $result['response']['description'];
        $detail->serial_number  = $result['response']['serial_number'];
        $detail->city           = $result['response']['city'];
        $detail->insured_value  = $result['response']['insured_value'];
        $detail->guarantee_id  = $result['response']['guaranteeId'];
        $detail->currency       = config('base.currencies_ws.'.$result['response']['currency']);
        
        return $detail;
    }
}