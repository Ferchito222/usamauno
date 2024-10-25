<?php

namespace Sibas\Repositories\Tj;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sibas\Entities\Tj\Detail;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{
    public function createDetail($request)
    {
        $this->data = $request->all();
        $header     = $this->data['header'];
        $client     = $this->data['client'];
        $retailer   = $request->user()->retailerUser->retailer;

        $this->model = new Detail();

        $this->model->id                = date('U');
        $this->model->op_tj_header_id   = $header->id;
        $this->model->op_client_id      = $client->id;
        $this->model->holder            = true;
        $this->model->card              = $this->data['card'];
        $this->model->card_number       = $this->data['card_number'];
        $this->model->accounts_ws       = base64_decode($this->data['accounts']);
        
        $collect = ($this->data['card']=='DC')?json_decode($this->data['debit_cards']):json_decode($this->data['credit_cards']);
        
        $filtered = collect($collect)->filter(function ($value) {
            if($value->cardNumber == $this->data['card_number'] && $value->customerId == $this->data['code'])
                return $value;
        });

        if($filtered->count()==0)
            return false;

        $this->model->card_customer_id      = $filtered->first()->customerId; 
        $this->model->card_account_number   = $filtered->first()->accountNumber; 
        $this->model->card_customer_name    = $filtered->first()->customerName; 
        $this->model->card_due_date         = $filtered->first()->dueDate; 
        $this->model->card_customer_type    = $filtered->first()->customerType; 
        $this->model->card_product_type     = ($filtered->first()->productType)?$filtered->first()->productType:''; 

        if($this->data['card']=='DC'){
            $this->model->card_card_name        = $filtered->first()->cardName; 
            $this->model->card_card_type_code   = $filtered->first()->cardTypeCode; 
            $this->model->card_card_type        = $filtered->first()->cardType;
            $this->model->debit_cards_ws        = $this->data['debit_cards'];
        
        }
        
        if($this->data['card']=='CC'){
            $this->model->credit_cards_ws = $this->data['credit_cards'];
        }
        
        return $this->saveModel();
    }
    
    public function getDetailById($detail_id)
    {
        $this->model = Detail::with([ 'client', 'response', 'beneficiaries', 'beneficiary' ])
            ->where('id', $detail_id)
            ->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }
    
    public function updateDetail($request,$detail)
    {
        $this->data = $request->all();
        
        if(base64_decode($this->data['card'])=='CC')#tarjeta de credito
            $arr = ['account_number'=>$this->data['account_number']];
        
        if(base64_decode($this->data['card'])=='DC')#tarjeta de debito
            $arr = ['savings_bank'=>$this->data['savings_bank']];
        
        if($detail->update($arr))
            return true;
        
        return false;
    }
    
    public function getDetailByClient($client_id, $type_card=false)
    {
        $this->model = Detail::with(['header', 'response', 'beneficiaries', 'beneficiary' ])
            ->where('op_client_id', $client_id)
            ->whereHas('header', function ($q){
                $q->where('issued', true);
                $q->where('canceled', false);
            });

        if($type_card)
            $this->model = $this->model->where('card', $type_card);

        $this->model = $this->model->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }
    
    public function updateHolder($holder)
    {
        $this->model->holder = $holder;
        
        return $this->saveModel();
    }
    
    public function updateAccount($request)
    {
        $this->data = $request->all();

        if ($this->data[ 'card_number' ])
            $tarjetaencriptada = shell_exec("java -jar ".base_path('public/assets/encrypt/Criptografia.jar')." E ". base_path('public/assets/encrypt/linkser/llave_lks/publica.rsa '. $this->data[ 'card_number' ]));
        else
            $tarjetaencriptada = 0;
        
        $this->model->account_number    = $this->data[ 'account_number' ];
        $this->model->card_number       = substr($this->data[ 'card_number' ], -4);
        $this->model->card_crypt        = trim($tarjetaencriptada);
        
        if($this->data[ 'expiration_card_month' ])
            $this->model->expiration_card_month   = $this->data[ 'expiration_card_month' ];
        
        if($this->data[ 'expiration_card_year' ])
            $this->model->expiration_card_year   = $this->data[ 'expiration_card_year' ];
        
        return $this->saveModel();
    }
    
    public function deleteHolder($header_id,$prefix)
    {
        $model = Detail::where('holder', true)->where('op_'.$prefix.'_header_id',$header_id)->get();
        
        try {
            if($model->count()>0)
                $model->first()->delete();

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }
        return false;
    }

}
