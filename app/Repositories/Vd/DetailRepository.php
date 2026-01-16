<?php

namespace Sibas\Repositories\Vd;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sibas\Entities\Vd\Detail;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{
    public function createDetail($request, $id_referred)
    {
        $this->data = $request->all();
        $header     = $this->data['header'];
        $client     = $this->data['client'];

        $this->model = new Detail();

        $this->model->id                = date('U');
        $this->model->op_vd_header_id   = $header->id;
        $this->model->op_client_id      = $client->id;
        $this->model->reference         = $id_referred;
        $this->model->holder            = true;
        $this->model->type              = 'A';
        
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

    public function editDetail($request)
    {
        $this->data = $request->all();
        $header     = $this->data['header'];
        
        return $this->saveModel();
    }

    public function updateHolder($holder, $type)
    {
        $this->model->holder = $holder;
        $this->model->type   = $type;
        
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
        $this->model->card_number       = substr($this->data[ 'card_number' ], 0, 4).'-'.substr($this->data[ 'card_number' ], -4);
        $this->model->card_crypt        = trim($tarjetaencriptada);
        
        if($this->data[ 'expiration_card_month' ])
            $this->model->expiration_card_month   = $this->data[ 'expiration_card_month' ];
        
        if($this->data[ 'expiration_card_year' ])
            $this->model->expiration_card_year   = $this->data[ 'expiration_card_year' ];
        
        return $this->saveModel();
    }

    public function checkQuestion($detail)
    {
        if(is_null($detail->response))
            return false;
        
        foreach (json_decode($detail->response->response) as $key => $value) {
            if($value->response == true)
                return false;
        }
        return true;
    }
    
    public function checkIssueByClient($detail)
    {
        $model = Detail::whereHas('header', function ($q) {
                            $q->where('type', 'I')
                                ->where('issued', true)
                                ->where('canceled', false);
                        })
            ->where('op_client_id', $detail->client->id)
            ->get();
                        
        if($model->count()>0)
            return true;
        else
            return false;
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