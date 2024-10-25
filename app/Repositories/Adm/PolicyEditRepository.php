<?php

namespace Sibas\Repositories\Adm;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\City;
use Sibas\Entities\RetailerProduct;
use Sibas\Repositories\BaseRepository;

class PolicyEditRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $queries;

    /**
     * @return array
     */
    public function dataSearch()
    {
        $rps      = RetailerProduct::with([ 'companyProduct.product' ])->where('active', true)->get();
        $products = $rps->pluck('companyProduct.product.name', 'companyProduct.product.code');
        $users    = DB::table('ad_users')
                        ->select([ 'ad_users.username', 'ad_users.full_name' ])
                        ->join('ad_user_types', 'ad_users.ad_user_type_id', '=', 'ad_user_types.id')
                        ->whereNotIn('ad_users.username', [ 'admin', 'guest' ])
                        ->whereIn('ad_user_types.code', [ 'UST' ])
                        ->orderBy('ad_users.full_name', 'asc')
                        ->get();

        return [
            'products' => $products,
            'users'    => $users,
        ];
    }


    /**
     * @param Request $request
     *
     * @return Collection
     */
    public function getHeaders($request)
    {
        $code = $request->get('product');

        $this->setModel($code);
        $this->getRelations($code);

        $headers = $this->model->with($this->relations)
                               ->where('type', 'I')
                               ->orderBy('issue_number', 'desc')
                               ->get();

        $api = collect([
            'headers' => collect([]),
            'details' => collect([]),
        ]);

        $headers->each(function ($h) use ($api) {
            $models = $this->getApiModel($h);

            $api['headers']->push($models['header']);
            $api['details'] = $api['details']->merge($models['details']);
        });

        return $api;
    }


    public function getApiModel($h = null)
    {
        $h = (is_null($h) ? $this->model : $h);

        $header = [
            'idx'                => $h->idc,
            'rpIdx'              => $h->retailerProduct->idc,
            'coverageIdx'        => $h->coverage->id,
            'creditProductIdx'   => $h->creditProduct->id,
            'productCode'        => $h->retailerProduct->companyProduct->product->code,
            'certificateVersion' => $h->certificate->version,
            'policyNumber'       => $h->policy_number,
            'prefix'             => $h->prefix,
            'issueNumber'        => $h->issue_number,
            'amountRequested'    => $h->amount_requested,
            'currency'           => $h->currency,
            'term'               => $h->term,
            'typeTerm'           => $h->type_term,
            'createdAt'          => $h->created_at->toDateTimeString(),
            'issued'             => $h->issued,
            'dateIssue'          => is_null($h->date_issue) ? null : $h->date_issue->toDateTimeString(),
            'canceled'           => $h->canceled,
            'facultative'        => $h->facultative,
            'userName'           => $h->user->username,
            'userFullName'       => $h->user->full_name,
            'userCity'           => $h->user->city->slug,
            'userCityName'       => $h->user->city->name,
            'userAgency'         => $h->user->agency->slug,
            'userAgencyName'     => $h->user->agency->name,
            'details'            => $h->details->pluck('idc'),
        ];

        $details = [];

        $h->details->each(function ($d) use (&$details) {
            $details[$d->idc] = [
                'idx'                       => $d->idc,
                'amount'                    => $d->amount,
                'balance'                   => $d->balance,
                'cumulus'                   => $d->cumulus,
                'refinancing'               => $d->refinancing,
                'parallelCredit'            => $d->parallel_credit,
                'beneficiaryFirstName'      => $d->beneficiary->first_name,
                'beneficiaryLastName'       => $d->beneficiary->last_name,
                'beneficiaryMotherLastName' => $d->beneficiary->mother_last_name,
                'beneficiaryRelationship'   => $d->beneficiary->relationship,
                'beneficiaryDni'            => $d->beneficiary->dni,
                'beneficiaryExtension'      => $d->beneficiary->extension,
                'client' => [
                    'fullName'              => $d->client->full_name,
                    'firstName'             => $d->client->first_name,
                    'lastName'              => $d->client->last_name,
                    'motherLastName'        => $d->client->mother_last_name,
                    'marriedName'           => $d->client->married_name,
                    'dni'                   => $d->client->dni,
                    'complement'            => $d->client->complement,
                    'extension'             => $d->client->extension,
                    'birthdate'             => $d->client->birthdate->toDateString(),
                    'birthPlace'            => $d->client->birth_place,
                    'locality'              => $d->client->locality,
                    'homeAddress'           => $d->client->home_address,
                    'businessAddress'       => $d->client->business_address,
                    'workplace'             => $d->client->workplace,
                    'activity'              => $d->client->activity->id,
                    'occupationDescription' => $d->client->occupation_description,
                    'phoneNumberHome'       => $d->client->phone_number_home,
                    'phoneNumberMobile'     => $d->client->phone_number_mobile,
                    'phoneNumberOffice'     => $d->client->phone_number_office,
                    'email'                 => $d->client->email,
                    'weight'                => $d->client->weight,
                    'height'                => $d->client->height,
                ],
            ];
        });

        return compact('header', 'details');
    }


    /**
     * @param string $code
     * @param int    $id
     *
     * @return bool
     */
    public function getHeader($code, $id)
    {
        $this->setModel($code);
        $this->getRelations($code);

        $this->model = $this->model->with($this->relations)
                                   ->where('id', $id)
                                   ->where('type', 'I')
                                   ->first();

        if (! is_null($this->model)) {
            return true;
        }

        return false;
    }


    /**
     * @param $code
     *
     * @return array
     */
    public function getDataEdit($code)
    {
        $rp = RetailerProduct::with([ 'coverages', 'creditProducts', 'policies', 'activities' ])
                             ->where('active', true)
                             ->whereHas('companyProduct.product', function ($q) use ($code) {
                                 $q->where('code', $code);
                             })
                             ->first();
        $cities = City::get();

        $coverages       = $rp->coverages->pluck('name', 'id');
        $currencies      = config('base.currencies');
        $type_terms      = config('base.term_types');
        $credit_products = $rp->creditProducts->pluck('name', 'id');
        $policies        = $rp->policies->pluck('number', 'number');
        $extensions      = $cities->where('type_ci', true)->pluck('name', 'abbreviation');
        $activities      = $rp->activities->pluck('occupation', 'id');

        return [
            'coverages'       => $coverages,
            'currencies'      => $currencies,
            'type_terms'      => $type_terms,
            'credit_products' => $credit_products,
            'policies'        => $policies,
            'extensions'      => $extensions,
            'activities'      => $activities,
        ];
    }


    /**
     * @param Request $request
     * @param string  $code
     *
     * @return bool
     */
    public function editData($request, $code)
    {
        $rp = RetailerProduct::with([ 'retailer.exchangeRate' ])
                             ->where('active', true)
                             ->whereHas('companyProduct.product', function ($q) use ($code) {
                                 $q->where('code', $code);
                             })
                             ->first();

        if ($code === 'de') {
            return $this->updateDeData($request, $rp);
        }

        return false;
    }


    /**
     * @param Request               $request
     * @param Model|RetailerProduct $rp
     *
     * @return bool
     */
    protected function updateDeData($request, $rp)
    {
        try {
            $type = $request->get('type');

            if (! is_null($type)) {
                $data = $request->get('data');

                DB::enableQueryLog();

                switch ($type) {
                    case 'Header':
                        $bsValue       = $rp->retailer->exchangeRate->bs_value;
                        $newAmount     = $this->getAmountInBs($data['currency'], $data['amountRequested'], $bsValue);
                        $currentAmount = $this->getAmountInBs($this->model->currency, $this->model->amount_requested, $bsValue);

                        if ($newAmount > $currentAmount) {
                            return -1;
                        }

                        $attributes = [
                            'ad_coverage_id'       => $data['coverage'],
                            'amount_requested'     => $data['amountRequested'],
                            'currency'             => $data['currency'],
                            'term'                 => $data['term'],
                            'type_term'            => $data['typeTerm'],
                            'ad_credit_product_id' => $data['creditProduct'],
                            'policy_number'        => $data['policyNumber'],
                        ];

                        $this->model->update($attributes);

                        foreach ($this->model->details as $detail) {
                            $detail->update([
                                'amount' => $data['amountRequested'],
                            ]);
                        }

                        break;
                    case 'Client':
                        $idd    = decode($data['idd']);
                        $detail = $this->model->details->where('id', "$idd")->first();

                        if ($data['amount'] > $detail->amount) {
                            return -2;
                        }

                        if ($data['cumulus'] > $detail->cumulus) {
                            return -3;
                        }

                        $detail->client()->update([
                            'first_name'             => $data['firstName'],
                            'last_name'              => $data['lastName'],
                            'mother_last_name'       => $data['motherLastName'],
                            'married_name'           => $data['marriedName'],
                            'dni'                    => $data['dni'],
                            'complement'             => $data['complement'],
                            'extension'              => $data['extension'],
                            'birthdate'              => $data['birthdate'],
                            'birth_place'            => $data['birthPlace'],
                            'locality'               => $data['locality'],
                            'home_address'           => $data['homeAddress'],
                            'business_address'       => $data['businessAddress'],
                            'workplace'              => $data['workplace'],
                            'ad_activity_id'         => $data['activity'],
                            'occupation_description' => $data['occupationDescription'],
                            'phone_number_home'      => $data['phoneNumberHome'],
                            'phone_number_mobile'    => $data['phoneNumberMobile'],
                            'phone_number_office'    => $data['phoneNumberOffice'],
                            'email'                  => $data['email'],
                            'weight'                 => $data['weight'],
                            'height'                 => $data['height'],
                        ]);

                        $detail->beneficiary()->update([
                            'first_name'       => $data['beneficiaryFirstName'],
                            'last_name'        => $data['beneficiaryLastName'],
                            'mother_last_name' => $data['beneficiaryMotherLastName'],
                            'relationship'     => $data['beneficiaryRelationship'],
                            'dni'              => $data['beneficiaryDni'],
                            'extension'        => (key_exists('beneficiaryExtension', $data) ? $data['beneficiaryExtension'] : ''),
                        ]);

                        $detail->update([
                            'amount'          => $data['amount'],
                            'balance'         => $data['balance'],
                            'cumulus'         => $data['cumulus'],
                            'refinancing'     => $data['refinancing'],
                            'parallel_credit' => $data['parallelCredit'],
                        ]);

                        break;
                }

                $this->queries = DB::getQueryLog();
                $this->model   = $this->model->fresh($this->relations);

                DB::disableQueryLog();

                return true;
            }
        } catch (QueryException $e) {
            $this->errors  = $e->getMessage();
            $this->queries = [
                'query'   => $e->getSql(),
                'message' => $e->getMessage(),
            ];
        }

        return false;
    }


    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
}