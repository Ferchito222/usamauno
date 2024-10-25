<?php

namespace Sibas\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\RetailerProduct;

class DataPolicyRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $queries = [];


    /**
     * List of Policies
     *
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     * @param string                $code
     *
     * @return Collection
     */
    public function getHeaderList(Request $request, $retailerProduct, &$code)
    {
        $code = $retailerProduct->companyProduct->product->code;
        $this->setModel($code);
        $this->getRelations($code);

        $headers = $this->model->with($this->relations)->where('type', 'I');

        $this->filters($request, $headers);

        return $headers->paginate(25);
    }


    /**
     * @param Request $request
     * @param Builder $builder
     */
    protected function filters(Request $request, $builder)
    {
        if ($request->has('number')) {
            $builder->where('issue_number', 'like', '%' . $request->get('number') . '%');
        }

        if ($request->has('client')) {
            $builder->whereHas($this->relations[0], function ($q) use ($request) {
                $client = $request->get('client');

                $q->where(function ($q1) use ($request, $client) {
                    $q1->orWhere('first_name', 'like', '%' . $client . '%')->orWhere('last_name', 'like',
                        '%' . $client . '%')->orWhere('mother_last_name', 'like', '%' . $client . '%');
                });
            });
        }

        if ($request->has('dni')) {
            $builder->whereHas($this->relations[0], function ($q) use ($request) {
                $q->where('dni', 'like', '%' . $request->get('dni') . '%');
            });
        }

        if ($request->has('username')) {
            $builder->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->get('username') . '%');
            });
        }

        if ($request->has('date-start') && $request->has('date-end')) {
            $builder->whereBetween('created_at', [ $request->get('date-start'), $request->get('date-end') ]);
        }
    }


    /**
     * Get Policy resource
     *
     * @param Model|RetailerProduct $retailerProduct
     * @param string                $id
     *
     * @return bool
     */
    public function getPolicyResource($retailerProduct, $id)
    {
        $code = $retailerProduct->companyProduct->product->code;
        $this->setModel($code);
        $this->getRelations($code);

        $this->model = $this->model->with($this->relations)->where([
            'id'   => decode($id),
            'type' => 'I',
        ])->first();

        if ($this->model !== null) {
            return true;
        }

        return false;
    }


    /**
     * Update Policy
     *
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     * @param string                $id
     *
     * @return bool
     */
    public function updatePolicy(Request $request, $retailerProduct, $id)
    {
        if ($this->getPolicyResource($retailerProduct, $id)) {
            $code = $retailerProduct->companyProduct->product->code;
            $data = $request->all();

            try {
                DB::enableQueryLog();

                if ($request->has('e-policy')) {
                    switch ($code) {
                        case 'de':
                            $this->model->ad_coverage_id       = $data['coverage'];
                            $this->model->amount_requested     = $data['amount_requested'];
                            $this->model->currency             = $data['currency'];
                            $this->model->term                 = $data['term'];
                            $this->model->type_term            = $data['type_term'];
                            $this->model->ad_credit_product_id = $data['credit_product'];
                            $this->model->movement_type        = $data['movement_type'];
                            $this->model->operation_number     = $data['operation_number'];
                            $this->model->policy_number        = $data['policy_number'];
                            $this->model->save();

                            break;
                        case 'au':
                        case 'td':
                        case 'ema':
                        case 'rma':
                            $this->model->validity_start    = $data['validity_start'];
                            $this->model->term              = $data['term'];
                            $this->model->type_term         = $data['type_term'];
                            $this->model->payment_method    = $data['payment_method'];
                            $this->model->operation_number  = $data['operation_number'];
                            $this->model->policy_number     = $data['policy_number'];
                            if($code != 'td')
                                $this->model->capital = $data['capital'];
                            
                            $this->model->save();
                            break;
                    }
                }

                if ($request->has('e-client')) {
                    
                    switch ($code) {
                        case 'de':
                            $this->updateClients($code, $data);
                            break;
                        case 'au':
                        case 'td':
                        case 'ema':
                        case 'rma':
                            $this->updateClientsGarantias($code, $data);
                            break;
                    }
                }

                if ($request->has('e-vehicle'))
                    $this->updateAu($code, $data, $retailerProduct);
                 
                if ($request->has('e-risk')) 
                    $this->updateTd($code, $data, $retailerProduct);
                 
                if ($request->has('e-ema')) 
                    $this->updateEma($code, $data, $retailerProduct);
                
                if ($request->has('e-rma')) 
                    $this->updateEma($code, $data, $retailerProduct);
                

                if ($request->has('e-beneficiary')) {
                    $this->updateBeneficiaries($code, $data);
                }

                $this->queries = DB::getQueryLog();

                return true;
            } catch (QueryException $e) {
                $this->queries = [
                    'query'   => $e->getSql(),
                    'message' => $e->getMessage(),
                ];
            }

        }

        return false;
    }


    public function getQueries()
    {
        return $this->queries;
    }


    /**
     * Update Clients
     *
     * @param string $code
     * @param array  $data
     */
    private function updateClients($code, $data)
    {
        foreach ($this->model->details as $detail) {
            foreach ($data['clients'] as $client) {
                
                if ($detail->client->id == decode($client['id'])) {
                        $this->setClient($detail->client, $client);
                    break;
                }
            }
        }
    }
    
    private function updateClientsGarantias($code, $data)
    {
        foreach ($data['clients'] as $client) {
            if ($this->model->client->id == decode($client['id'])) {
                $this->setClient($this->model->client, $client);
                break;
            }
        }
    }
    
    public function setClient($c_model, $client)
    {
        try {
                $c                         = $c_model;
                $c->first_name             = $client['first_name'];
                $c->last_name              = $client['last_name'];
                $c->mother_last_name       = $client['mother_last_name'];
                $c->married_name           = $client['married_name'];
                $c->birthdate              = $client['birthdate'];
                $c->age                    = $this->carbon->createFromTimestamp(strtotime($client['birthdate']))->age;
                $c->birth_place            = $client['birth_place'];
                $c->dni                    = $client['dni'];
                $c->extension              = $client['extension'];
                $c->complement             = $client['complement'];
                $c->document_type          = $client['document_type'];
                $c->civil_status           = $client['civil_status'];
                $c->gender                 = $client['gender'];
                $c->place_residence        = $client['place_residence'];
                $c->locality               = $client['locality'];
                $c->home_address           = $client['home_address'];
                $c->home_number            = $client['home_number'];
                $c->avenue_street          = $client['avenue_street'];
                $c->business_address       = $client['business_address'];
                $c->country                = $client['country'];
                $c->ad_activity_id         = $client['ad_activity_id'];
                $c->occupation_description = $client['occupation_description'];
                $c->phone_number_home      = $client['phone_number_home'];
                $c->phone_number_office    = $client['phone_number_office'];
                $c->phone_number_mobile    = $client['phone_number_mobile'];
                $c->email                  = $client['email'];
                $c->weight                 = $client['weight'];
                $c->height                 = $client['height'];
                $c->hand = empty( $client['hand'] ) ? null : $client['hand'];

                $c->save();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
    }
    
    private function updateAu($code, $data, $retailerProduct)
    {
        foreach ($this->model->details as $detail) {
            foreach ($data['vehicles'] as $vehicle) {
                if ($detail->id == decode($vehicle['id'])) {
                    $category = \Sibas\Entities\RetailerProductCategory::where('category',$vehicle['category'])->first();
                    
                    try {
                        $c                                  = $detail;
                        $c->ad_vehicle_type_id              = $vehicle['vehicle_type'];
                        $c->ad_retailer_product_category_id = $category->id;
                        $c->year                            = $vehicle['year'];
                        $c->brand                           = $vehicle['brand'];
                        $c->color                           = $vehicle['color'];
                        $c->model                           = $vehicle['model'];
                        $c->circulation_plaza               = $vehicle['circulation_plaza'];
                        $c->displacement                    = $vehicle['displacement'];
                        $c->origin                          = $vehicle['origin'];
                        $c->license_plate                   = $vehicle['license_plate'];
                        $c->use                             = $vehicle['use'];
                        $c->mileage                         = $vehicle['mileage'];
                        $c->engine                          = $vehicle['engine'];
                        $c->chassis                         = $vehicle['chassis'];
                        $c->tonnage_capacity                = $vehicle['tonnage_capacity'];
                        $c->seat_number                     = $vehicle['seat_number'];
                        $c->insured_value                   = $vehicle['insured_value'];
                       
                        $c->save();
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                    break;
                }
            }
        }
    }
    
    private function updateTd($code, $data, $retailerProduct)
    {
        foreach ($this->model->details as $detail) {
            foreach ($data['risks'] as $risk) {
                if ($detail->id == decode($risk['id'])) {
                    try {
                        $c                      = $detail;
                        $c->nro_folio_real      = $risk['nro_folio_real'];
                        $c->locality            = $risk['locality'];
                        $c->matter_insured      = $risk['matter_insured'];
                        $c->address             = $risk['address'];
                        $c->matter_description  = $risk['matter_description'];
                        $c->insured_value       = $risk['insured_value'];
                        $c->use                 = $risk['use'];
                        $c->city                = $risk['city'];
                        $c->state               = $risk['state'];
                        $c->zone                = $risk['zone'];
                        $c->save();
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                    break;
                }
            }
        }
    }
    
    private function updateEma($code, $data, $retailerProduct)
    {
        foreach ($this->model->details as $detail) {
            foreach ($data['risks'] as $risk) {
                if ($detail->id == decode($risk['id'])) {
                    try {
                        $c                      = $detail;
                        $c->property_use = $risk['property_use'];
                        $c->city = $risk['city'];
                        $c->description = $risk['description'];
                        $c->insured_value = $risk['insured_value'];
                        $c->serial_number = $risk['serial_number'];
                        $c->save();
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                    break;
                }
            }
        }
    }

    /**
     * Update Beneficiaries
     *
     * @param string $code
     * @param array  $data
     */
    private function updateBeneficiaries($code, $data)
    {
        foreach ($this->model->details as $detail) {
            foreach ($data['beneficiaries'] as $beneficiary) {
                if ($code === 'de') {
                    $b = $detail->beneficiary;

                    if ($b->id === decode($beneficiary['id'])) {
                        $this->setDataBeneficiary($code, $beneficiary, $b);

                        break;
                    }
                } elseif ($code === 'vi') {
                    foreach ($detail->beneficiaries as $b) {
                        if ($b->id === decode($beneficiary['id'])) {
                            $this->setDataBeneficiary($code, $beneficiary, $b);

                            break;
                        }
                    }
                }
            }
        }
    }


    /**
     * @param string $code
     * @param array  $beneficiary
     * @param Model  $b
     */
    private function setDataBeneficiary($code, $beneficiary, $b)
    {
        $b->first_name       = $beneficiary['first_name'];
        $b->last_name        = $beneficiary['last_name'];
        $b->mother_last_name = $beneficiary['mother_last_name'];
        $b->dni              = $beneficiary['dni'];
        $b->relationship     = $beneficiary['relationship'];

        if ($code === 'de') {
            $b->extension = $beneficiary['extension'];
        } elseif ($code === 'vi') {
            $b->participation = $beneficiary['participation'];
        }

        $b->save();
    }

}