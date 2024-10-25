<?php

namespace Sibas\Repositories\Elp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Elp\Beneficiary;
use Sibas\Entities\Elp\Detail;
use Sibas\Repositories\BaseRepository;

class BeneficiaryRepository extends BaseRepository
{

    /**
     * Store Beneficiary
     *
     * @param Request      $request
     * @param Model|Detail $detail
     *
     * @return bool
     */
    public function storeBeneficiary($request, $detail)
    {
        $this->data                   = $request->all();
        $this->data['beneficiary_id'] = date('U');

        try {
            $detail->beneficiary()->create($this->setData());

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Request      $request
     * @param Model|Detail $detail
     *
     * @return bool
     */
    public function updateBeneficiary($request, $detail)
    {
        $this->data = $request->all();

        try {
            $detail->beneficiary()->update($this->setData());

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    public function getBeneficiaryById($beneficiary_id)
    {
        $this->model = Beneficiary::where('id', $beneficiary_id)->first();

        if ( ! is_null($this->model)) {
            return true;
        }

        return false;
    }


    private function setData()
    {
        return [
            'id'                => $this->data['beneficiary_id'],
            'coverage'          => 'VI',
            'first_name'        => mb_strtoupper($this->data['first_name']),
            'last_name'         => mb_strtoupper($this->data['last_name']),
            'mother_last_name'  => mb_strtoupper($this->data['mother_last_name']),
            'dni'               => mb_strtoupper($this->data['dni']),
            'relationship'      => mb_strtoupper($this->data['relationship']),
            'percentage_credit' => $this->data['percentage_credit'],
        ];
    }
}