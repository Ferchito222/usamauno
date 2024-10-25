<?php

namespace Sibas\Repositories\Tj;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Tj\Collection;
use Sibas\Repositories\BaseRepository;

class CollectionRepository extends BaseRepository
{
    
    public function getCollectionsById($collection_id)
    {
        $this->model = Collection::with([
            'header.user.city',
            'header.details.beneficiaries',
            'header.cancellation',
            'header.retailerProduct',
        ])->where('id', '=', $collection_id)->get();
        
        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }  
        
    public function collectionsByTransactionNumber($transaction_number)
    {
         return Collection::with([
            'header.user.city',
            'header.details.beneficiaries',
            'header.cancellation',
            'header.retailerProduct',
        ])->where('transaction_number', '=', $transaction_number)->get();
    }
    
    public function getCollectionsByIdNoCharged($collection_id)
    {
        $this->model = Collection::with([
            'header.user.city',
            'header.details.beneficiaries',
            'header.cancellation',
            'header.retailerProduct',
        ])
        ->where('id', $collection_id)
        ->where('charged', false)
        ->get();
        
        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }
    
    public function collectionsByHeaderNotCharged($header_id)
    {
        return Collection::with([
            'header.user.city',
            'header.details.beneficiaries',
            'header.cancellation',
            'header.retailerProduct',
        ])
                ->where('op_tj_header_id', '=', $header_id)
                ->where('charged', false)
                ->where('send_fortaleza', false)
                ->get();
    }
    
    public function getCollectionsByNotCharged($card)
    {
        $this->model = Collection::with([
            'header.user.city',
            'header.details.beneficiaries',
            'header.cancellation',
            'header.retailerProduct',
        ])
                ->whereDate('fee_date', '<=', date('Y-m-d'))
                ->where('charged', false)
                ->where('send_fortaleza', false)
                ->where(function ($q) use ($card) {
                    $q->whereHas('header.details', function ($q) use ($card) {
                        $q->where(function ($q) use ($card) {
                            $q->where('card', $card);
                        });
                    });
                })
                ->where(function ($q) {
                    $q->whereHas('header', function ($q) {
                        $q->where(function ($q) {
                            $q->where('canceled', false);
                            $q->where('issued', true);
                        });
                    });
                })
                ->get();
        
        if ($this->model->count() > 0)
            return true;

        return false;
    }
    
    public function storeCollections($header, $cobro = 0)
    {
        
        switch ($header->period){
            case 'Y':
                $m = 1;
                $add = 0;
                $premium = ($header->total_premium * $header->term);
                break;
            case 'S':
                $m = round($header->term / 6);
                $add = 6;
                $premium = ($header->total_premium / $m);
                break;
            case 'M':
                $m = 12;
                $add = 1;
                $premium = $header->total_premium;
                break;
        }

        $date = date('Y-m-d');

        for ($x = 1; $x <= $m; $x++) {
            $this->model = new Collection();
            
            $this->model->op_tj_header_id = $header->id;
            $this->model->fee_number = $x;
            $this->model->fee_date = $date;
            $this->model->fee_amount = $premium;

            if ($cobro > 0){
                $this->model->charged = true;
                $this->model->transaction_number = $cobro;
                $this->model->transaction_date = $date;
                $this->model->transaction_amount = $premium;
            }

            $date = date("Y-m-d",strtotime($date."+ ".$add." month"));
            
            $this->saveModel();
            
        }   
    }
    
    public function updateCollectionByFortaleza($array)
    {
        $this->errors = '';
        if($this->verifiedKeysWsFortaleza($array)){
            if(!is_null($array['transactionNumber'])){
                
                if($this->collectionsByTransactionNumber($array['transactionNumber'])->count()==0){
                    if($array['transactionAmount'] > 0){

                        if($this->getCollectionsByIdNoCharged($array['paymentCode'])){
                            
                            if($this->collectionsByHeaderNotCharged($this->model->header->id)->count() > 0){
                                $exchange_rates = DB::table('ad_exchange_rates')->first();
                                $FechaTransaccion = Carbon::parse($array['transactionDate']);
                                
                                $arr = [
                                        'transaction_amount'        => ($array['currencyCode']==2)?round($array['transactionAmount']*$exchange_rates->bs_value):$array['transactionAmount'],
                                        'transaction_amount_usd'    => ($array['currencyCode']==2)?$array['transactionAmount']:0,
                                        'currency_fortaleza'        => $array['currencyCode'],
                                        'transaction_number'        => $array['transactionNumber'],
                                        'transaction_date'          => $FechaTransaccion->format('Y-m-d'),
                                        'exchange_rate'             => ($array['currencyCode']==2)?$exchange_rates->bs_value:0,
                                        'send_fortaleza'            => true,
                                        'result_fortaleza'          => json_encode($array),
                                        'charged'                   => true,
                                        'cardNumber'                => $array['cardNumber'],
                                        'customerName'              => $array['customerName'],
                                        'accountNumber'             => $array['accountNumber'],
                                        'transactionDate'           => $array['transactionDate'],
                                        'observation'               => isset($array['observation'])?$array['observation']:'',
                                        'paymentStatus'             => isset($array['paymentStatus'])?$array['paymentStatus']:'',
                                       ]; 
                                 
                                if($this->model->update($arr))
                                    return true;
                                else{
                                    $this->errors = 'Error al actualizar la cobranza, nro de cuota:'.$this->model->fee_number.', nro de emision: '.$this->model->header->prefix.'-'.$this->model->header->issue_number;
                                    return false;
                                }
                            }else
                                $this->errors = 'No existen cuotas para actualizar, nro de emision: '.$this->model->header->prefix.'-'.$this->model->header->issue_number;
                        }else
                            $this->errors = 'No existen el codigo de cobro / o ya fue registrado anterior mente';
                    }else
                        $this->errors = 'El monto debitado no puede ser 0';
                }else
                    $this->errors = 'El nro de transaccion ya fue registrado anteriormente';
            }else
                $this->errors = 'No existe el nro de transaccion';   
        }else
            $this->errors = 'No existen los campos requeridos';
        
        return false;
    }

    public function verifiedKeysWsFortaleza($array)
    { 
        if(
            array_key_exists('paymentCode', $array) && 
        array_key_exists('customerCode', $array) && 
        array_key_exists('certificateNumber', $array) && 
        array_key_exists('cardNumber', $array) && 
        array_key_exists('customerName', $array) && 
        array_key_exists('currencyCode', $array) && 
        array_key_exists('accountNumber', $array) && 
        array_key_exists('transactionNumber', $array) && 
        array_key_exists('transactionDate', $array) && 
        array_key_exists('transactionAmount', $array)
        )
        return true;
        else
        return false;
    }
}