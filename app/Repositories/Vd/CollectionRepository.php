<?php

namespace Sibas\Repositories\Vd;

use Illuminate\Http\Request;
use Sibas\Entities\Vd\Collection;
use Sibas\Repositories\BaseRepository;

class CollectionRepository extends BaseRepository
{
    public function storeCollections($header, $cobro = 0)
    {
        $id = date('U');

        switch ($header->period){
            case 'Y':
                $m = 1;
                $add = 0;
                $premium = $header->total_premium;
                break;
            case 'S':
                $m = round($header->term / 6);
                $add = 6;
                $premium = ($header->total_premium / $m);
                break;
            case 'M':
                $m = 12;
                $add = 1;
                $premium = $header->premium;
                break;
        }

        $date = date('Y-m-d');

        for ($x = 1; $x <= $m; $x++) {
            $this->model = new Collection();
            $this->model->id = $id;
            $this->model->op_vd_header_id = $header->id;
            $this->model->fee_number = $x;
            $this->model->fee_date = $date;
            $this->model->fee_amount = $premium;

            if ($cobro > 0){
                $this->model->charged = true;
                $this->model->transaction_number = $cobro;
                $this->model->transaction_date = $date;
                $this->model->transaction_amount = $premium;
            }
            
            $date = date('Y-m-d',strtotime($date.'+'.$add.' month'));
            //dd($this->model, $id, $this->saveModel());
            $this->saveModel();
            $id++;
        }
    }
}