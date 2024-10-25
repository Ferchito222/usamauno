<?php

namespace Sibas\Repositories\De;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\Detail;
use Sibas\Entities\De\Facultative;
use Sibas\Entities\De\Header;
use Sibas\Entities\Rate;
use Sibas\Entities\RetailerProduct;
use Sibas\Repositories\BaseRepository;
use Sibas\Repositories\Client\ClientRepository;

class HeaderRepository extends BaseRepository
{

    /** Store a newly created Header in DB.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function createHeader($request)
    {
        $user       = $request->user();
        $this->data = $request->all();

        $this->model  = new Header();
        $quote_number = $this->getNumber('Q');

        $this->model->id                   = date('U');
        $this->model->ad_user_id           = $user->id;
        $this->model->type                 = 'Q';
        $this->model->quote_number         = $quote_number;
        $this->model->ad_coverage_id       = $this->data['coverage'];
        $this->model->amount_requested     = $this->data['amount_requested'];
        $this->model->currency             = $this->data['currency'];
        $this->model->term                 = $this->data['term'];
        $this->model->type_term            = $this->data['type_term'];
        $this->model->ad_credit_product_id = $this->data['credit_product'];
        $this->model->product_description  = $this->data['product_description'];
        $this->model->issued               = false;
        $this->model->ad_retailer_city_agency_id = $request->user()->agency->retailerCityAgencies->first()->id;

        if ($request->has('movement_type')) {
            $this->model->movement_type = $this->data['movement_type'];
        }

        if ($request->has('credit_line')) {
            $this->model->credit_line = true;
        }

        if ($request->has('number_line')) {
            $this->model->data_records = ['number_line' => $this->data[ 'number_line' ]];
        }

        if ( ! $this->checkNumber('Q', $quote_number)) {
            return $this->saveModel();
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function updateHeader($request)
    {
        $user            = $request->user();
        $retailerProduct = $this->model->retailerProduct;

        if ($retailerProduct instanceof RetailerProduct) {
            if ($this->getCertificate($retailerProduct, $this->model->creditProduct->slug)) {
                $this->data = $request->all();
                $initial = \Illuminate\Support\Facades\DB::table('ad_policies')->where('number',$this->data['policy_number'])->where('active',true);
                
                if($this->model->creditProduct->slug=='PCA')
                    $initial = $initial->where('modality','TJ')->first();
                else
                    $initial = $initial->where('modality','!=','TJ')->first();
                
                //$initial = $this->model->retailerProduct->policies->where('number',$this->data['policy_number'])->first();
                if(in_array($this->model->creditProduct->slug, ['PMO', 'PLT','PCM','PCA','PLNT'])){
                    $this->model->issue_number      = $this->getNumberByPolicy('I', $initial->initial, $initial->id);#hipotecaria/no licitada
                    $this->model->version_number    = ($initial->number_renewal)?'- '.$initial->number_renewal:'-1';
                    $this->model->ad_policy_id      = $initial->id;
                }else
                    $this->model->issue_number = $this->getNumber('I', $initial->initial);#No licitada
                
                $this->model->ad_user_id        = $user->id;
                $this->model->type              = 'I';
                $this->model->prefix            = 'DE';
                $this->model->policy_number     = $this->data['policy_number'];
                $this->model->facultative       = $this->setFacultative();
                $this->model->ad_certificate_id = $this->certificate->id;
                $this->model->type_of_request   = $this->data['type_of_request'];
                
                if($this->data['credit_product'] == 4){
                    $this->model->issue_number = ($initial->modality != null && $initial->modality == 'TJ')?$this->getNumberTj('I', $initial->initial,$initial->id):$this->getNumber('I', $initial->initial);
                    $this->model->card_number = $this->data['card_number'];
                }else
                    $this->model->operation_number  = $this->data['operation_number'];

                if ($request->has('place_issuance')) {
                    $this->model->place_issuance = $this->data['place_issuance'];
                }

                //if ( ! $this->checkNumber('I', $issue_number)) {
                if (1) {
                    return $this->saveModel();
                }
            }
        }

        return false;
    }
    
    public function getNumberTj($field, $default = 1, $ad_policy_id)
    {
        if($field == 'I') {
            $max = \Illuminate\Support\Facades\DB::table($this->model['table'])
                    ->where($this->model['table'].'.ad_credit_product_id', '=', 4)
                    ->where('type', 'I')
                    ->where('ad_policy_id', $ad_policy_id)
                    ->max($this->fieldName[$field]);
        } else
            $max = $this->model->max($this->fieldName[$field]);

        return (is_null($max) || $max == 0) ? $default : $max + 1;
    }


    public function setFacultative($header = null)
    {
        if ($header instanceof Header) {
            $this->model = $header;
        }

        $facultative = false;

        foreach ($this->model->details as $detail) {
            if ($detail->facultative instanceof Facultative) {
                $facultative = true;

                break;
            }
        }

        return $facultative;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|Header $header
     * @param bool $facultative
     *
     * @return bool
     */
    public function setHeaderResult($retailerProduct, $header, $facultative = false)
    {
        if (( $retailerProduct instanceof RetailerProduct ) && $retailerProduct->rates->count() > 0) {
            $rate_final = 0;
            $details    = $header->details()->count();
            
            if (in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])
                && ( $retailerProduct->type === 'RP' || $retailerProduct->type === 'MP' )
            ) {
                $rates = $retailerProduct->rates()->whereHas('creditProduct', function ($q) {
                    $q->whereIn('slug', [ 'PMO', 'PLT' ]);
                })->get();
                
                $n    = $rates->count() === 1 ? 1 : ( $details > 4 ? 4 : $details );
                $rate = $rates->filter(function ($item) use ($n) {
                    return ($item->year == $n);
                })->first();
                
                if ($rate instanceof Rate) {
                    $rate_final = $rate->rate_final;
                   
                    /*
                     * Formula APS
                     */
                    if ($header->coverage->slug !== 'IC') {
                        if ($facultative) {
                            $details = $header->details->where('approved', true)->count();
                        }
                        
                        $rate_final = $this->getRateTendered($rate->rate_final, $details);
                    }
                }
            } elseif ($retailerProduct->type === 'MP') {
                $rates = $retailerProduct->rates();

                //if ($header->creditProduct->slug === 'PCA') {
                if (false) {
                    $rates->whereHas('creditProduct', function ($q) use ($header) {
                        $q->where('slug', $header->creditProduct->slug);
                    });
                } else {
                    $rates->doesntHave('creditProduct');
                }

                $rates = $rates->get();
                
                /**if ($rates->count() === 1) {
                    $rate       = $rates->first();
                    $rate_final = $rate->rate_final;
                }/**/

                /*
                * Formula APS
                */
                if ($header->coverage->slug !== 'IC') {
                    if ($facultative) {
                        $details = $header->details->where('approved', true)->count();
                    }
                    
                    $rate_final = $this->getRateTendered($rates->first()->rate_final, $details);
                }else
                    $rate_final = $rates->first()->rate_final;
            }
            
            if ($rate_final > 0) {
                foreach ($header->details as $detail) {
                    $rc = $rate_final;

                    if ($header->coverage->slug === 'CO' && ! in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])) {
                        $rc = ( $rate_final * $detail->percentage_credit ) / 100;
                    } elseif (in_array($header->creditProduct->slug, ['PMO', 'PLT', 'PCM', 'PCA', 'PLNT'])) {

                        $details = ($details>4)?4:$details;

                        $rc = $rate_final / $details;
                        
                        /**if ($facultative) {
                            if ($detail->facultative instanceof Facultative
                                && $detail->facultative->state === 'PR'
                                && $detail->facultative->approved)
                            {
                                $rf = $rc + ($detail->facultative->percentage * $rc / 100);

                                $detail->facultative()->update([
                                    'current_rate' => $rc,
                                    'final_rate'   => $rf,
                                ]);
                            } elseif ($detail->rejected) {
                                $rc = 0;
                            }
                        }/**/
                    }

                    $detail->update([
                        'rate' => $rc,
                    ]);
                }
                /**/
                $header->ad_retailer_product_id = $retailerProduct->id;
                $header->total_rate             = $rate_final;
                $header->total_premium          = ( $header->amount_requested * $rate_final ) / 100;
                
                return $this->saveModel();
            }
        }

        return false;
    }
    
    /**
     * Funcion recalculo de tasa al rechazar un cliente facultativo
     * @param RetailerProduct $retailerProduct
     * @param type $header
     * @return boolean
     */
    public function recalculateRate($retailerProduct, $header)
    {
        if (( $retailerProduct instanceof RetailerProduct ) && $retailerProduct->rates->count() > 0) {
            $rate_final = 0;
            $details    = $header->details()
                            ->where('rejected', false)->count();
            
            if (in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])
                && ( $retailerProduct->type === 'RP' || $retailerProduct->type === 'MP' )
            ) {
                
                $rates = $retailerProduct->rates()->whereHas('creditProduct', function ($q) {
                    $q->whereIn('slug', [ 'PMO', 'PLT' ]);
                })->get();
                
                $n    = ($rates->count() == 1)? 1 : ( $details > 4 ? 4 : $details );
                
                $rate = $rates->filter(function ($item) use ($n) {
                    return ($item->year == $n);
                })->first();
                
                if ($rate instanceof Rate) {
                    $rate_final = $rate->rate_final;
                   
                    /*
                     * Formula APS
                     */
                    if ($header->coverage->slug !== 'IC') 
                        $rate_final = $this->getRateTendered($rate->rate_final, $details);
                }
            } elseif ($retailerProduct->type === 'MP') {
                
                $rates = $retailerProduct->rates();

                //if ($header->creditProduct->slug === 'PCA') {
                if (false) {
                    $rates->whereHas('creditProduct', function ($q) use ($header) {
                        $q->where('slug', $header->creditProduct->slug);
                    });
                } else {
                    $rates->doesntHave('creditProduct');
                }

                $rates = $rates->get();
                /**
                if ($rates->count() === 1) {
                    $rate       = $rates->first();
                    $rate_final = $rate->rate_final;
                }
                /**/
                /*
                * Formula APS
                */
                if ($header->coverage->slug !== 'IC') {
                    $rate_final = $this->getRateTendered($rates->first()->rate_final, $details);
                }else
                    $rate_final = $rates->first()->rate_final;
            }

            if ($rate_final > 0) {
                foreach ($header->details as $detail) {
                    if($detail->rejected == false){
                        $rc = $rate_final;

                        if ($header->coverage->slug === 'CO' && ! in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ])) {
                            $rc = ( $rate_final * $detail->percentage_credit ) / 100;
                        } elseif (in_array($header->creditProduct->slug, [ 'PMO', 'PLT', 'PCM', 'PCA', 'PLNT' ])) {

                            $details = ($details>4)?4:$details;
                                
                            $rc = $rate_final / $details;
                            
                            if ($detail->facultative instanceof Facultative
                                && $detail->facultative->state === 'PR'
                                && $detail->facultative->approved)
                            {
                                $rf = ($detail->facultative->percentage * $rc / 100);

                                $detail->facultative()->update([
                                    'current_rate' => $rc,
                                    'final_rate'   => $rf,
                                ]);
                            }
                        }

                        $detail->update([
                            'rate' => $rc,
                        ]);
                    }
                    
                }
                
                return $this->saveModel();
            }
        }

        return false;
    }


    public function issueHeader()
    {
        if ($this->model->credit_line) {
            if (is_array($this->model->data_records) && key_exists('number_line', $this->model->data_records)) {
                try {
                    CreditLine::where('number_line', $this->model->data_records['number_line'])
                              ->update([
                                  'op_de_header_id' => $this->model->id
                              ]);

                    goto Issuance;
                } catch (QueryException $e) {
                    $this->errors = $e->getMessage();
                }
            }

            return false;
        }

        Issuance:
        $this->model->issued       = true;
        $this->model->date_issue   = $this->carbon->format('Y-m-d H:i:s');
        $this->model->approved     = true;
        $coverage_id               = date('U');
        $created_at                = $this->carbon->now();
        $ccs                       = config('base.client_coverages');
        $data                      = $this->model->data_records;
        $this->model->data_records = null;
        $id                        = date('U');

        foreach ($this->model->details as $detail) {
            if ($this->model->credit_line) {
                $id = $id - 1;

                $detail->update([
                    'company_approval' => 'FC',
                ]);
            }

            if (is_null($detail->facultative)) {
                $detail->coverages()->delete();

                $coverages = [];
                $age       = $detail->client->birthdate->age;

                foreach ($ccs as $key => $cc) {
                    $coverage_id -= 1;
                    $granted      = ( $key !== 'CTPD' ? true : ( $age < 66 ? true : false ) );

                    array_push($coverages, [
                        'id'              => $coverage_id,
                        'op_de_detail_id' => $detail->id,
                        'coverage'        => $key,
                        'comment'         => '',
                        'granted'         => $granted,
                        'created_at'      => $created_at,
                        'updated_at'      => $created_at,
                    ]);
                }

                $detail->coverages()->insert($coverages);
            }
        }

        return $this->saveModel();
    }


    /** Find Header by Id
     *
     * @param $header_id
     *
     * @return bool
     */
    public function getHeaderById($header_id)
    {
        $this->model = Header::with([
            'details.client.detailsVi',
            'details.beneficiary',
            'details.facultative',
            'details.coverages',
            'details.response',
            'user.city',
            'coverageWarranty',
            'creditProduct',
            'coverage',
            'guest',
            'creditLine',
            'placeIssuance',
            'rejetedCustomers',
        ])->where('id', '=', $header_id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    public function getHeader($value)
    {
        $isssue = explode('-',$value['nro_de_certificado']);
        $term = explode(' ',$value['plazo_del_credito']);
        $amount_requested = explode(' ', $value['monto_solicitado_moneda']);
        $numericValue = str_replace(',', '', $amount_requested[0]);
        $numericValue = (float) $numericValue;
        
        $credit_product = DB::table('ad_credit_products')->where('name',$value['producto'])->first();
        
        $this->model = DB::table('op_de_headers')->where('issue_number', $isssue[1])
                ->where('operation_number', $value['numero_de_operacion'])
                ->where('term', $term[0])
                ->where('amount_requested', $numericValue)
                ->where('currency', str_replace('.','',$amount_requested[1]))
                ->where('ad_credit_product_id', $credit_product->id)
                //->whereDate('date_issue', $date_issue)
                ->get();

        if(count($this->model)==0){
            $this->model = DB::table('op_de_headers')->where('issue_number', $isssue[1])
                ->where('term', $term[0])
                ->where('amount_requested', $numericValue)
                ->where('currency', str_replace('.','',$amount_requested[1]))
                ->where('ad_credit_product_id', $credit_product->id)
                //->whereDate('date_issue', $date_issue)
                ->get();
        }
        
        if (count($this->model) === 1) {
            $this->model = $this->model[0];

            return true;
        }else{
            dd($this->model);

        }

        return false;
    }

    public function updateCert($id_header, $cert_id)
    {
        $data = [
            'ad_certificate_id'=>$cert_id,
        ];
        return Header::where('id',$id_header)->update($data);
    }


    /**
     * @param Request $request
     * @param         $header_id
     *
     * @return bool
     */
    public function storeFacultative($request, $header_id)
    {
        if ($this->getHeaderById($header_id)) {
            $this->data = $request->all();

            $this->model->facultative_observation = $this->data['facultative_observation'];

            return $this->saveModel();
        }

        return false;
    }


    /**
     *
     * @return bool
     */
    public function storeSent()
    {
        $this->model->facultative_sent = true;

        return $this->saveModel();
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
            $this->model->term             = $this->data['term'];
            $this->model->type_term        = $this->data['type_term'];

            return $this->saveModel();
        }

        return false;
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
     * @param string $q
     *
     * @return bool
     */
    public function getPolicies($product, $q = '')
    {
        try {
            $this->model = Header::select('op_de_headers.id', 'issue_number',
                'prefix')->leftJoin('op_de_coverage_warranties', function ($q1) {
                $q1->on('op_de_headers.id', '=', 'op_de_coverage_warranties.op_de_header_id');
            });

            switch ($product) {
                case 'au':
                    $this->model->whereNull('op_au_header_id');
                    break;
                case 'td':
                    $this->model->whereNull('op_td_header_id');
                    break;
            }

            $this->model = $this->model->where('issued', true)->get();

            if ($this->model->count() > 0) {
                return true;
            }
        } catch (QueryException $e) {

        }

        return false;
    }


    /**
     * @return bool
     */
    public function checkPercentageParticipation()
    {
        if ($this->model->coverage->slug === 'CO') {
            $total = $this->model->details()->sum('percentage_credit');

            if ($total == 100) {
                return true;
            }
        } else {
            return true;
        }

        return false;
    }


    /**
     * @param array  $data
     * @param string $header_id
     * @param int    $rp_id
     *
     * @return bool
     */
    public function headerStoreGuest($data, $header_id, $rp_id)
    {
        try {
            $clientRepository = new ClientRepository();
            $this->model      = new Header();

            $quote_number                   = $this->getNumber('Q');
            $data['header']['ad_user_id']   = $data['user_id'];
            $data['header']['type']         = 'Q';
            $data['header']['quote_number'] = $quote_number;
            $data['header']['issued']       = false;
            $data['header']['ad_retailer_product_id'] = $rp_id;

            $header  = $this->model->create($data['header']);
            $details = [];

            foreach ($data['header']['details'] as $key => $dt) {
                $dt['id']              = $data['header']['id'] - $key;
                $dt['op_de_header_id'] = $header->id;

                $dt['client']['birthdate'] = $this->carbon->createFromFormat('Y-m-d',
                    $dt['client']['birthdate'])->format('d/m/Y');

                $client_request = new Request($dt['client']);

                if ($clientRepository->createClient($client_request)) {
                    $dt['response']['id'] = $dt['id'];

                    $client = $clientRepository->getModel();
                    $detail = new Detail($dt);
                    $detail->client()->associate($client);
                    $detail->save();
                    $detail->response()->create($dt['response']);

                    array_push($details, $detail);
                }
            }

            if (count($details) > 0) {
                $header->details()->saveMany($details);
                $this->model = $header->fresh(['details.client']);

                return true;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    public function saveCertificate($filename)
    {
        $this->model->file = $filename;
        $this->model->file_date = $this->carbon->format('Y-m-d H:i:s');

        return $this->saveModel();
    }


    /**
     * @param int $operation_number
     *
     * @return bool
     */
    public function checkOldOperationNumber($operation_number)
    {
        $old = Header::where('type', 'I')
                    ->where('issued', true)
                    ->where('operation_number', $operation_number)
                    ->where('canceled', false);

        $old = $old->count();

        return ($old === 0 ? true : false);
    }
    
    public function checkCardNumber($card_number)
    {
            $old = Header::where('type', 'I')
                    ->where('issued', true)
                    ->where('card_number', $card_number)
                    ->where('canceled', false);

        if($old->count()>0)
            return false;
        else
            return true;
    }
}
