<?php
namespace Sibas\Repositories\Tj;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Client;
use Sibas\Entities\Tj\Header;
use Sibas\Entities\RetailerProduct;
use Sibas\Repositories\BaseRepository;

class HeaderRepository extends BaseRepository
{
    public function getHeaderById($header_id)
    {
        $this->model = Header::with([
            'details.client',
            'details.beneficiaries',
            'details.response',
            'user.city', 
            'retailerCityAgency.retailerCity.agencies', 
            'retailerCityAgency.retailerCity.city', 
            'retailerCityAgency.agency', 
        ])->where('id', '=', $header_id)->get();
        
        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    public function getHeaderByIssueNumber($issue_number)
    {
        $this->model = Header::with([
            'details.client',
            'details.beneficiaries',
            'details.response',
            'user.city', 
            'retailerCityAgency.retailerCity.agencies', 
            'retailerCityAgency.retailerCity.city', 
            'retailerCityAgency.agency', 
        ])
        ->whereHas('details', function ($q) {
            $q->where('card', 'DC');
        })
        ->where('issue_number', '=', $issue_number)->get();
        
        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }
    
    public function getHeaderByCardExists($type_card, $customer_id, $card_number)
    {
        $model = Header::with([
            'details.client',
            'details.beneficiaries',
            'details.response',
            'user.city', 
        ])
                ->where('type', 'I')
                ->where('issued', true)
                ->where('canceled', false)
                ->whereHas('details', function ($q) use ($type_card, $customer_id, $card_number) {
                    $q->where('card', $type_card);
                    $q->where('card_customer_id', $customer_id);
                    $q->where('card_number', $card_number);
            })->get();
        
        if ($model->count() > 0)
            return true;

        return false;
    }
    
    public function storeHeader($request)
    {
        $this->data = $request->all();

        $this->model  = new Header();
        $quote_number = $this->getNumber('Q');

        $date = $this->carbon->createFromTimestamp(strtotime(str_replace('/', '-', date('Y-m-d'))));
        
        $this->model->id             = date('U');
        $this->model->ad_user_id     = $request->user()->id;
        $this->model->type           = 'Q';
        $this->model->quote_number   = $quote_number;
        $this->model->warranty       = true;
        $this->model->validity_start = $date->format('Y-m-d');
        $this->model->validity_end   = $date->addYear(1)->format('Y-m-d');
        $this->model->currency       = 'BS';
        $this->model->period         = 'M';
        $this->model->ad_retailer_city_agency_id = $request->user()->agency->retailerCityAgencies->first()->id;
        
        if ( ! $this->checkNumber('Q', $quote_number)) {
            return $this->saveModel();
        }

        return false;
    }
    
    public function checkOldOperationNumber($operation_number)
    {
        $old = Header::where('type', 'I')
                    ->where('issued', true)
                    ->where('operation_number', $operation_number)
                    ->where('canceled', false);

        $old = $old->count();

        return ($old === 0 ? true : false);
    }
    
    public function updateHeader(Request $request, $retailerProduct)
    {
        if ($this->getCertificate($retailerProduct)) {
            $this->data = $request->all();
            
            try {
                $issue_number = $this->getNumberTj('I',1, $this->model->details->first()->card,$this->data['policy_number']);
                //dd(! $this->checkNumberTj('I', $issue_number, $this->model->details->first()->card));
                $cardType = $this->model->details->first()->card;
                if ( ! $this->checkNumberTj('I', $issue_number, $cardType) || $cardType == "CC") {
                    
                    $this->model->update([
                        'type'              => 'I',
                        'issue_number'      => $issue_number,
                        'prefix'            => strtoupper($this->model->retailerProduct->companyProduct->product->code),
                        'bill_name'         => $this->data['bill_name'],
                        'bill_dni'          => $this->data['bill_dni'],
                        'ad_certificate_id' => $this->certificate->id,
                        'policy_number'     => $this->data['policy_number'],
                    ]);
                    
                    return true;
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
                
            }
        }

        return false;
    }

    public function getNumberTj($field, $default = 1, $card, $policyNumber = null)
    {
        
        if($field == 'I') {
            if($card == "CC"){

                $cityArr =(in_array(request()->user()->ad_city_id,[1,10])) ? [1,10]:[request()->user()->ad_city_id];

                $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->join('ad_users', $this->model['table'] . '.ad_user_id', '=', 'ad_users.id')
                    ->join('op_tj_details', $this->model['table'] . '.id', '=', 'op_tj_details.op_tj_header_id')
                    ->whereIn('ad_users.ad_city_id', $cityArr)
                    ->where('type', 'I')
                    ->where('op_tj_details.card', $card)
                    ->whereDate($this->model['table'].'.created_at', '>', '2022-04-17')
                    ->where('op_tj_headers.policy_number', $policyNumber)
                    ->max($this->fieldName[$field]);

                //dd($max->toSql(), $max->getBindings());
            }else{ 
            $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->join('ad_users', $this->model['table'] . '.ad_user_id', '=', 'ad_users.id')
                    ->join('op_tj_details', $this->model['table'] . '.id', '=', 'op_tj_details.op_tj_header_id')
                    ->where('type', 'I')
                    ->where('op_tj_details.card', $card)
                    ->max($this->fieldName[$field]);
            }
        } else
            $max = $this->model->max($this->fieldName[$field]);

        return is_null($max) ? $default : $max + 1;
    }
    
    public function storePremium()
 {
        $total_premium = 0;
        $premium = 14;
        foreach ($this->model->details as $key => $value) {
            $value->update([
                'premium' => $premium
            ]);
            $total_premium += $premium;
        }

        try {
            $this->model->update([
                'total_premium' => $total_premium,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }
        return false;
    }

    public function setRetailerProduct($retailerProduct)
    {
        try {   
            $this->model->update([
                'ad_retailer_product_id' => $retailerProduct->id,
            ]);

            return true;

        } catch (QueryException $e) {
            $this->errors = $e->getMessage();

        }

        return false;
    }
    
    public function issuanceHeader()
    {
        try {
            $this->model->update([
                'issued'                        => true,
                'date_issue'                    => date('Y-m-d H:i:s'),
                'ad_retailer_city_agency_id'    => $this->model->user->agency->retailerCityAgencies->first()->id,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
    
    public function saveCertificate($filename)
    {
        $this->model->file = $filename;
        $this->model->file_date = $this->carbon->format('Y-m-d H:i:s');
        $this->model->read = true;

        return $this->saveModel();
    }
    
    public function getHeaderNotSendCompany()
    {
        
        $this->model = Header::with([
            'details.client',
            'details.beneficiaries',
            'details.response',
            'user.city', 
        ])
                ->where('issued',true)
                ->where('canceled',false)
                ->where('cpn_send_company',false)
                ->get();
        
        if ($this->model->count()>0) 
            return true;
        

        return false;
    }
}
