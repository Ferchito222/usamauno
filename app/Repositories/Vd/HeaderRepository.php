<?php
namespace Sibas\Repositories\Vd;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Vd\Header;
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
        ])->where('id', '=', $header_id)->get();
        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

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
        $this->model->ad_plan_id     = isset($this->data['ad_plan_id']) ? $this->data['ad_plan_id'] : null;
        $this->model->currency       = 'BS';
        
        if (!$this->checkNumber('Q', $quote_number)) {
            return $this->saveModel();
        }

        return false;
    }

    public function updateHeader(Request $request, $retailerProduct)
    {
        if ($this->getCertificate($retailerProduct)) {
            $this->data = $request->all();

            try {
                if($this->data['payment_method'] == 'TJ'){
                    $prefix = 'VITC';
                }else {
                    $prefix = strtoupper($retailerProduct->companyProduct->product->code);
                }

                //$issue_number = $this->getNumber('I');
                $issue_number = $this->getNumberPrefix($prefix,'I');
                $policy_number = $this->getPolicyNumber($issue_number, $prefix);

                if ( ! $this->checkNumber('I', $issue_number, $prefix)) {
                    $this->model->update([
                        'type'              => 'I',
                        'issue_number'      => $issue_number,
                        'policy_number'     => $policy_number,
                        'prefix'            => $prefix,
                        'bill_name'         => $this->data['bill_name'],
                        'bill_dni'          => $this->data['bill_dni'],
                        'pre_printed'       => false,
                        'pre_printed_number'=> '',
                        'ad_certificate_id' => $this->certificate->id,
                        'payment_method'    => $this->data['payment_method'],
                        'period'            => $this->data['period'],
                        'term'              => $this->data['term'],
                        'case_number'       => $this->data['case_number'],
                        'premium'           => $this->model->plan->monthly_premium,
                        'total_premium'     => round(($this->data['term'] * $this->model->plan->monthly_premium)),
                    ]);
                    
                    return true;
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
                
            }
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
                'issued'     => true,
                'date_issue' => date('Y-m-d H:i:s'),
                'ad_retailer_city_agency_id' => $this->model->user->agency->retailerCityAgencies->first()->id,
                //'approved'   => true,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}