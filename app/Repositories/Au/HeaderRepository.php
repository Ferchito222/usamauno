<?php

namespace Sibas\Repositories\Au;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Au\Detail;
use Sibas\Entities\Au\Facultative;
use Sibas\Entities\Client;
use Sibas\Entities\Au\Header;
use Sibas\Entities\De\Header as HeaderDe;
use Sibas\Entities\RetailerProduct;
use Sibas\Repositories\BaseRepository;

class HeaderRepository extends BaseRepository
{

    public function getHeaderById($header_id)
    {
        $this->model = Header::with([
            'client',
            'details.vehicleType',
            'details.vehicleMake',
            'details.vehicleModel',
            'details.category',
        ])->where('id', '=', $header_id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     *
     * Store Header AU
     *
     * @param Request      $request
     * @param Model|Client $client
     *
     * @return bool
     */
    public function storeHeader($request, $client)
    {
        $this->data = $request->all();

        $this->model  = new Header();
        $quote_number = $this->getNumber('Q');

        $date = $this->carbon->createFromTimestamp(strtotime(str_replace('/', '-', $this->data['validity_start'])));

        $this->model->id             = date('U');
        $this->model->ad_user_id     = $request->user()->id;
        $this->model->op_client_id   = $client->id;
        $this->model->type           = 'Q';
        $this->model->quote_number   = $quote_number;
        $this->model->warranty       = true;
        $this->model->validity_start = $date->format('Y-m-d');
        $this->model->validity_end   = $date->addYear(1)->format('Y-m-d');
        //$this->model->payment_method = $this->data['payment_method'];
        //$this->model->currency       = $this->data['currency'];
        $this->model->term           = $this->data['term'];
        $this->model->type_term      = $this->data['type_term'];
        $this->model->capital        = $this->data['capital'];
        $this->model->capital        = $this->data['capital'];
        $this->model->ad_retailer_city_agency_id = $request->user()->agency->retailerCityAgencies->first()->id;

        if ( ! $this->checkNumber('Q', $quote_number)) {
            return $this->saveModel();
        }

        return false;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|Header          $header
     *
     * @return array
     */
    public function setVehicleResult($retailerProduct = null, $header)
    {
        $premium_total = 0;

        if ($retailerProduct instanceof RetailerProduct) {
            $rates = $retailerProduct->rates()->where('active', true)->get();
            $max_year = $retailerProduct->rates()->max('year');
            
            foreach ($rates as $key => $rate) {      
                if ($header->full_year == $rate->year || ( $header->full_year > $max_year  && $rate->year == $max_year)) {
                    $rate_city = $rate->auRates->where('ad_city_id',request()->user()->city->id);
                    
                    if($rate_city->count()){
                        foreach ($header->details as $detail) {
                            $rate_vh    = $rate_city->first()->rate;
                            $premium_vh = ( $detail->insured_value * $rate_vh) / 100;
        
                            #minimum premium
                            if($header->currency=='BS')
                                $min = $rate_city->first()->minimumPremium->minimum_premium * $retailerProduct->retailer->exchangeRate->bs_value;
                            else
                                $min = $rate_city->first()->minimumPremium->minimum_premium;
                            
                            $premium_vh = ($premium_vh<=$min)?$min:$premium_vh;

                            $premium_total += $premium_vh;
        
                            try {
                                $detail->update([
                                    'rate' => $rate_vh,
                                    'rate_month' => $rate_vh / 12,
                                    'premium' => $premium_vh,
                                    'premium_month' => $premium_vh / 12,
                                ]);
                            } catch (QueryException $e) {
                                $this->errors = $e->getMessage();
        
                                return false;
                            }
                        }
                    }else
                        return false;
                }          
                
            }
        } else {
            foreach ($header->details as $detail) {
                $premium_total += $detail->premium;
            }
        }

        if ($premium_total > 0) {
            $share = [];

            $full_year = $header->full_year;

            if ($header->payment_method === 'PT') {
                $full_year = 1;
            }

            $date       = Carbon::createFromDate(null, null, 15)->addMonth(1)->subYear();
            $percentage = number_format(( 100 / $full_year ), 2, '.', ',');

            for ($i = 1; $i <= $full_year; $i++) {
                array_push($share, [
                    'number'     => $i,
                    'date'       => $date->addYear()->toDateString(),
                    'percentage' => $percentage,
                    'share'      => number_format(( $premium_total * $percentage ) / 100, 2),
                ]);
            }

            try {
                $header->update([
                    'ad_retailer_product_id' => $retailerProduct->id,
                    'total_premium'          => $premium_total,
                    'total_premium_month'    => $premium_total/12,
                    'share'                  => json_encode($share),
                ]);

                return true;
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return false;
    }


    /**
     * Set facultative
     *
     * @param $header_id
     */
    public function setHeaderFacultative($header_id)
    {
        if ($this->getHeaderById($header_id)) {
            $facultative = false;
            $reason      = '';

            foreach ($this->model->details as $detail) {
                if ($detail->facultative instanceof Facultative) {
                    $facultative = true;
                    $reason .= $detail->facultative->reason;
                }
            }

            try {
                $this->model->update([
                    'facultative'             => $facultative,
                    'facultative_observation' => $reason,
                ]);
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }
    }
    
    public function updateCurrency($currency)
    {
        try {
            $this->model->update([
                'currency' => $currency
            ]);
            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }
        return false;
    }


    /**
     * Update Header AU
     *
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     *
     * @return bool
     */
    public function updateHeader(Request $request, $retailerProduct)
    {
        if ($this->getCertificate($retailerProduct)) {
            $this->data = $request->all();

            try {
                $issue_number = $this->getNumber('I');

                //--edw--> if ( ! $this->checkNumber('I', $issue_number)) {
                if (1) {
                    $this->model->update([
                        'type'              => 'I',
                        'issue_number'      => $issue_number,
                        'prefix'            => 'AU',
                        'policy_number'     => $this->data['policy_number'],
                        'operation_number'  => $this->data['operation_number'],
                        'ad_certificate_id' => $this->certificate->id,
                    ]);
                    
                    return true;
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return false;
    }


    /**
     * Issuance Header AU
     */
    public function issuanceHeader()
    {
        try {
            $this->model->update([
                'issued'     => true,
                'date_issue' => date('Y-m-d H:i:s'),
                'approved'   => true,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * Store Header facultative
     *
     * @param string $request
     *
     * @return bool
     */
    public function storeFacultative($request)
    {
        $this->data = $request->all();

        $this->model->facultative_observation = $this->data['facultative_observation'];

        return $this->saveModel();
    }


    /**
     * Store Sent Header facultative
     *
     * @return bool
     */
    public function storeSent()
    {
        $this->model->facultative_sent = true;

        return $this->saveModel();
    }


    /**
     * @param Header $header
     *
     * @return bool
     */
    public function setApproved($header)
    {
        if ($header instanceof Header) {
            $this->model = $header;
            $details     = $this->model->details;
            $approved    = 0;
            $rejected    = 0;

            foreach ($details as $detail) {
                if ($detail->approved) {
                    $approved += 1;
                } elseif ($detail->rejected) {
                    $rejected += 1;
                }
            }

            if ($details->count() === $rejected) {
                $this->model->rejected = true;
            } elseif ($details->count() === ( $approved + $rejected )) {
                $this->model->approved = true;
            }

            return $this->saveModel();
        }

        return false;
    }


    /**
     * @param Request $request
     * @param string  $header_id
     *
     * @return bool
     */
    public function updateHeaderFacultative($request, $header_id)
    {
        $this->data = $request->all();

        if ($this->getHeaderById($header_id)) {
            $this->model->policy_number    = $this->data['policy_number'];
            $this->model->operation_number = $this->data['operation_number'];

            return $this->saveModel();
        }

        return false;
    }


    /**
     *
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     *
     * @return bool
     */
    public function storeCoverage(Request $request, $retailerProduct)
    {
        $this->data = $request->all();
        $user       = $request->user();

        try {
            $this->model = Header::create([
                'id'                     => date('U'),
                'ad_user_id'             => $user->id,
                'ad_retailer_product_id' => $retailerProduct->id,
                'op_client_id'           => decode($this->data['client']),
                'type'                   => 'Q',
                'warranty'               => true,
                'payment_method'         => $this->data['payment_method']['id'],
                'currency'               => $this->data['currency']['id'],
                'term'                   => $this->data['term'],
                'type_term'              => $this->data['type_term']['id'],
            ]);

            return $this->saveModel();
        } catch (QueryException $e) {

        }

        return false;
    }


    /**
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|HeaderDe        $de
     *
     * @return bool
     */
    public function updateCoverage(Request $request, $retailerProduct, $de)
    {
        if ($this->getCertificate($retailerProduct)) {
            $this->data = $request->all();

            try {
                $issue_number = $this->getNumber('I');

                if ( ! $this->checkNumber('I', $issue_number)) {
                    $date = $this->carbon->createFromTimestamp(strtotime(str_replace('/', '-',
                        $this->data['validity_start'])));

                    $this->model->update([
                        'type'              => 'I',
                        'issue_number'      => $issue_number,
                        'prefix'            => 'AU',
                        'policy_number'     => $this->data[ 'policy_number' ],
                        'operation_number'  => $this->data[ 'operation_number' ],
                        'payment_method'    => $this->data[ 'payment_method' ],
                        'currency'          => $this->data[ 'currency' ],
                        'term'              => $this->data[ 'term' ],
                        'type_term'         => $this->data[ 'type_term' ],
                        'validity_start'    => $date->format('Y-m-d'),
                        'validity_end'      => $date->addYear(1)->format('Y-m-d'),
                        'issued'            => true,
                        'date_issue'        => date('Y-m-d H:i:s'),
                        'approved'          => true,
                        'ad_certificate_id' => $this->certificate->id,
                    ]);

                    return $this->setCoverage($de);
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return false;
    }


    /**
     * @param Model|HeaderDe $de
     *
     * @return bool
     */
    public function setCoverage($de)
    {
        try {
            $de->coverageWarranty()->updateOrCreate([
                'op_de_header_id' => $de->id,
            ], [
                'op_au_header_id' => $this->model->id,
            ]);

            return true;
        } catch (QueryException $e) {

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
    
    public function saveCertificate($filename)
    {
        $this->model->file = $filename;
        $this->model->file_date = $this->carbon->format('Y-m-d H:i:s');
        $this->model->read = true;

        return $this->saveModel();
    }
    }
