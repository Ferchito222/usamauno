<?php

namespace Sibas\Repositories\Elp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Client;
use Sibas\Entities\De\Coverage;
use Sibas\Entities\Elp\Header;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\State;
use Sibas\Repositories\BaseRepository;

class HeaderRepository extends BaseRepository
{

    /**
     * @var Coverage
     */
    public $coverage;

    /**
     * @var string
     */
    public $error_message;


    /** Find Header by Id
     *
     * @param int $header_id
     *
     * @return bool
     */
    public function getHeaderById($header_id)
    {
        $this->model = Header::with([
            'detail.client.accounts',
            'detail.reference',
            'detail.beneficiary',
            'user.city',
            'retailerProduct',
            'coverage',
        ])->where('id', '=', $header_id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * @param Request        $request
     * @param Model|Client   $client
     * @param Model|Coverage $coverage
     *
     * @return bool
     */
    public function storeHeader($request, $client, $coverage)
    {
        try {
            $id          = date('U');
            $this->model = new Header([
                'id'             => $id,
                'ad_user_id'     => $request->user()->id,
                'type'           => 'Q',
                'ad_coverage_id' => $coverage->id,
            ]);

            $quote_number = $this->getNumber('Q');

            if ( ! $this->checkNumber('Q', $quote_number)) {
                $this->model->quote_number = $quote_number;

                if ($this->model->save()) {
                    $reference = $this->getCashierBySlug($request->get('reference_id'));
                    $is_client = (decode($request->get('client_eco')) == $client->id) ? true : false;
                    $account   = $request->get('account');

                    $this->model->detail()->create([
                        'id'              => date('U'),
                        'client_eco'      => $is_client,
                        'op_client_id'    => $client->id,
                        'age'             => $client->age,
                        'reference_id'    => is_null($reference) ? null : $reference->id,
                        'reference_other' => $request->get('reference_other'),
                        'account_type'    => $is_client ? $account['type'] : null,
                        'currency'        => $is_client ? $account['currency'] : null,
                    ]);

                    if ($is_client) {
                        $accounts = $this->getAccounts($client, $account);

                        if (count($accounts) > 0) {
                            $client->accounts()->createMany($accounts);
                        }
                    }

                    return true;
                }
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Request      $request
     * @param Model|Header $header
     * @param Model|Client $client
     *
     * @return bool
     */
    public function updateHeader($request, $header, $client)
    {
        try {
            $is_client = (decode($request->get('client_eco')) == $client->id) ? true : false;
            $reference = $this->getCashierBySlug($request->get('reference_id'));
            $account   = $request->get('account');

            $header->detail()->update([
                'client_eco'      => $is_client,
                'op_client_id'    => $client->id,
                'age'             => $client->age,
                'reference_id'    => is_null($reference) ? null : $reference->id,
                'reference_other' => $request->get('reference_other'),
                'account_type'    => $is_client ? $account['type'] : null,
                'currency'        => $is_client ? $account['currency'] : null,
            ]);

            if ($is_client) {
                $accounts = $this->getAccounts($client, $account);

                if (count($accounts) > 0) {
                    $client->accounts()->createMany($accounts);
                }
            }

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Model|Client $client
     * @param array        $account
     *
     * @return array
     */
    protected function getAccounts($client, $account)
    {
        $client_accounts = $client->accounts->toArray();
        $numbers         = explode(',', trim($account['total'], ','));
        $accounts        = [];
        $id              = date('U');

        foreach ($numbers as $key => $number) {
            $account_exists = false;

            foreach ($client_accounts as $client_account) {
                if ($number == $client_account['number']) {
                    $account_exists = true;

                    break;
                }
            }

            if ( ! $account_exists) {
                array_push($accounts, [
                    'id'           => ($id + $key),
                    'number'       => $number,
                    'type'         => 'AC',
                    'main_account' => $number == $account['number'] ? true : false,
                    'active'       => true,
                ]);
            }
        }

        return $accounts;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|Header          $header
     *
     * @return bool
     */
    public function setHeaderResult($retailerProduct, $header)
    {
        if ($retailerProduct instanceof RetailerProduct) {
            if ($retailerProduct->type === 'MP') {
                $premium = $header->premium_el;

                if ($header->detail->client_eco) {
                    $premium = $header->premium_ep;
                }

                try {
                    $header->update([
                        'ad_retailer_product_id' => $retailerProduct->id,
                    ]);

                    $header->detail()->update([
                        'premium' => $premium,
                    ]);

                    return true;
                } catch (QueryException $e) {
                    $this->errors = $e->getMessage();
                }
            }
        }

        return false;
    }


    /**
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     *
     * @return bool
     *
     */
    public function checkAge($request, $retailerProduct)
    {
        if ($retailerProduct instanceof RetailerProduct) {
            $parameter      = $retailerProduct->parameters()->where('slug', 'GE')->first();
            $this->coverage = $retailerProduct->coverages()->where('slug', 'IC')->first();

            if ( ! is_null($parameter) && ! is_null($this->coverage)) {
                if ($this->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                    return true;
                } else {
                    $this->error_message = 'El Cliente no cumple con el rango de edades [ ' . $parameter->age_min . ' - ' . $parameter->age_max . ' ]';
                }
            }
        }

        return false;
    }


    /**
     * @param Model|Header $header
     *
     * @return bool
     */
    public function checkMembership($header)
    {
        $m = Header::whereHas('detail.client', function ($q) use ($header) {
            $q->where('id', $header->detail->client->id);
        })->whereHas('state', function ($q) {
            $q->where('slug', 'va');
        })->where([
            'type'     => 'I',
            'issued'   => true,
            'canceled' => false,
        ])->count();

        return $m > 0 ? true : false;
    }


    /**
     * @param string $voucher
     *
     * @return bool
     */
    public function checkVoucher($voucher)
    {
        $v = 0;

        if ( ! is_null($voucher)) {
            $v = Header::whereHas('detail', function ($q) use ($voucher) {
                $q->where('voucher', $voucher);
            })->where('type', 'I')->count();
        }

        return $v > 0 ? true : false;
    }


    /**
     * @param Request      $request
     * @param Model|Header $header
     *
     * @return bool
     */
    public function updateIssuanceHeader($request, $header)
    {
        $retailerProduct = $this->model->retailerProduct;

        if ($retailerProduct instanceof RetailerProduct) {
            if ($this->getCertificate($retailerProduct)) {
                $issue_number = $this->getNumber('I');

                if ( ! $this->checkNumber('I', $issue_number)) {
                    try {
                        $this->data = $request->all();

                        $attributes = [
                            'header' => [
                                'type'              => 'I',
                                'issue_number'      => $issue_number,
                                'prefix'            => 'ECS',
                                'policy_number'     => $this->data['policy_number'],
                                'approved'          => true,
                                'ad_certificate_id' => $this->certificate->id,
                            ],
                            'detail' => [
                                'charge'          => ($this->data['charge'] == 1 ? true : false),
                                'charge_response' => $this->data['charge_response'],
                                'charge_period'   => $this->data['charge_period'],
                                'payment_method'  => $this->data['payment_method'],
                            ],
                        ];

                        $client_eco = $header->detail->client_eco;

                        if ($client_eco) {
                            $attributes['detail']['account_type']       = $this->data['account_type'];
                            $attributes['detail']['currency']           = $this->data['currency'];
                            $attributes['detail']['operation_number']   = $this->data['number'];
                            $attributes['detail']['operation_currency'] = $this->data['currency'];
                        } else {
                            $attributes['detail']['voucher'] = $this->data['voucher'];
                        }

                        $header->update($attributes['header']);

                        $header->detail()->update($attributes['detail']);

                        return true;
                    } catch (QueryException $e) {
                        $this->errors = $e->getMessage();
                    }
                }
            }
        }

        return false;
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param Model|Header          $header
     *
     * @return bool
     */
    public function issuanceHeader($retailerProduct, $header)
    {
        try {
            $subsidiary = null;

            if (empty($header->detail->operation_number)) {
                $subsidiary = request()->user()->city->code;
            } else {
                $subsidiary = substr($header->detail->operation_number, 0, 2);
            }

            $state = $retailerProduct->states()->where('slug', 'va')->first();

            if (($state instanceof State) && ! is_null($subsidiary)) {
                $user = request()->user();

                $header->update([
                    'issued'       => true,
                    'date_issue'   => $this->carbon->format('Y-m-d H:i:s'),
                    'ad_state_id'  => $state->id,
                    'ad_city_id'   => is_null($user->city) ? null : $user->city->id,
                    'ad_agency_id' => is_null($user->agency) ? null : $user->agency->id,
                ]);

                $header->detail()->update([
                    'subsidiary_code' => $subsidiary,
                ]);

                return true;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}