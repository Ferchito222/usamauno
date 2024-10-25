<?php

namespace Sibas\Repositories\De;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\De\Detail;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{

    /** Create a newly created Detail.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function createDetail($request)
    {
        $this->data = $request->all();
        $header     = $this->data['header'];
        $client     = $this->data['client'];
        $retailer   = $request->user()->retailerUser->retailer;

        $this->model = new Detail();

        $amount = $this->getAmountInBs($header->currency, $header->amount_requested, $retailer->exchangeRate->bs_value);

        $this->model->id                = date('U');
        $this->model->op_de_header_id   = $header->id;
        $this->model->op_client_id      = $client->id;
        $this->model->rate              = 0;
        $this->model->balance           = 0;
        $this->model->cumulus           = 0;
        $this->model->amount            = $amount;
        $this->model->approved          = false;
        $this->model->headline          = $this->getHeadlineType($header);
        $this->model->detail_operations = $this->data['detail_operations'];

        if ($header->coverage->slug === 'CO' && $request->has('percentage_credit')) {
            $this->model->percentage_credit = $this->data[ 'percentage_credit' ];
        } else {
            $this->model->percentage_credit = $this->getPercentage($header);
        }

        if ($header->creditProduct->slug === '7x5' && $request->has('involuntary_unemployment')) {
            $this->model->involuntary_unemployment = filter_var($this->data[ 'involuntary_unemployment' ],
                FILTER_VALIDATE_BOOLEAN);
        }
        
        if(key_exists('cesantia', $this->data) && $this->data['cesantia'] == 1)
            $this->model->cesantia = $this->data['cesantia'];
        else    
            $this->model->cesantia = false;
            

        return $this->saveModel();
    }


    /**
     * Update Detail
     *
     * @param Request $request
     *
     * @return bool
     */
    public function editDetail($request)
    {
        $this->data = $request->all();
        $header     = $this->data['header'];

        if ($header->coverage->slug === 'CO' && $request->has('percentage_credit')) {
            $this->model->percentage_credit = $this->data[ 'percentage_credit' ];
        } else {
            $this->model->percentage_credit = $this->getPercentage($header);
        }

        if ($header->creditProduct->slug === '7x5' && $request->has('involuntary_unemployment')) {
            $this->model->involuntary_unemployment = filter_var($this->data[ 'involuntary_unemployment' ],
                FILTER_VALIDATE_BOOLEAN);
        }

        $this->model->cesantia = $this->data['cesantia'];

        return $this->saveModel();
    }


    public function updateBalance(Request $request, $detail_id)
    {
        $this->data  = $request->all();
        $header      = $this->data['header'];
        $this->model = $header->details()
            ->with([ 'client.activity', 'header.creditProduct' ])
            ->where('id', $detail_id)
            ->first();

        if ($this->model instanceof Detail) {
            // $cumulus = $this->model->amount + $this->data['balance'];

            $this->model->balance         = $this->data['balance'];
            $this->model->cumulus         = $this->data['cumulus'];
            $this->model->refinancing     = $this->data['refinancing'];
            $this->model->parallel_credit = $this->data['parallel_credit'];

            return $this->saveModel();
        }

        return false;
    }


    public function setApprovedDetail($approved = true, $facultative = false)
    {
        $this->model->approved = $approved;

        $this->model->header()->update([
            'facultative' => $facultative,
        ]);

        $this->saveModel();
    }


    public function getDetailById($detail_id)
    {
        $this->model = Detail::with([ 'client', 'response', 'beneficiary', 'facultative' ])
            ->where('id', $detail_id)
            ->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function removeDetail()
    {
        try {
            $this->model->response()->delete();
            $this->model->delete();

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /** Returns Headline Type for Client
     *
     * @param $header
     *
     * @return string
     */
    public function getHeadlineType($header)
    {
        if ($header->coverage->slug === 'MC' || $header->coverage->slug === 'CO') {
            $details = Detail::where('op_de_header_id', $header->id)->count();

            if ($details > 0) {
                return 'C';
            }
        }

        return 'D';
    }


    /** Returns Percentage Credit for Client
     *
     * @param $header
     *
     * @return int
     */
    public function getPercentage($header)
    {
        $percentage = 0;

        switch ($header->coverage->slug) {
            case 'IC':
                $percentage = 100;
                break;
            case 'MC':
                $percentage = 100;
                break;
            case 'BC':
                $percentage = 10;
                break;
        }

        return $percentage;
    }


    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * @param $dni
     * @param $extension
     * @param string $complement
     * @return int
     */
    public function checkDetailPending($dni, $extension, $complement = '')
    {
        $p = Detail::
            whereHas('client', function ($q) use ($dni, $extension, $complement) {
                $q->where('dni', $dni)
                    ->where('extension', $extension);

                if (! empty($complement)) {
                    $q->where('complement', $complement);
                }
            })
            ->whereHas('facultative', function ($q) {
                $q->where('state', 'PR')
                    ->where('approved', false);
            })
            ->whereHas('header', function ($q) {
                $q->where('type', 'I')
                    ->where('issued', false)
                    ->where('canceled', false);
            })
            ->count();

        return $p;
    }

    public function setRateCesantiaByRetarilerProduct($details, $retailerProduct)
    {
        foreach ($details as $key => $value){
            if($value->cesantia == true){
                $data = [
                    'rate_cesantia' => $retailerProduct->rates->first()->rate_final,
                    'ad_retailer_product_id' => $retailerProduct->id
                ];
                $value->update($data);
            }
        }
        
    }
}