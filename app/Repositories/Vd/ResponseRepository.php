<?php

namespace Sibas\Repositories\Vd;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;
use Sibas\Entities\Vd\Response;
use Sibas\Repositories\BaseRepository;

class ResponseRepository extends BaseRepository
{
    public function createResponse($detail_id)
    {
        $this->model = New Response();
        $this->model->id = date('U');
        $this->model->op_vi_detail_id = $detail_id;
        $this->model->response = '{"1":{"id":"9","question":"Cancer","expected":false,"type":"","response_text":false,"response":false,"response_specification":""},"2":{"id":"23","question":"Sida","expected":false,"type":"","response_text":false,"response":false,"response_specification":""},"3":{"id":"10","question":"Diabetes","expected":false,"type":"","response_text":false,"response":false,"response_specification":""},"4":{"id":"24","question":"Enfermedades del coraz\u00f3n","expected":false,"type":"","response_text":false,"response":false,"response_specification":""},"5":{"id":"11","question":"Insuficiencia Renal","expected":false,"type":"","response_text":false,"response":false,"response_specification":""},"6":{"id":"25","question":"Enfermedades Cerebro Vasculares","expected":false,"type":"","response_text":false,"response":false,"response_specification":""}}';
        $this->model->observation = '';

        $this->saveModel();
    }
}