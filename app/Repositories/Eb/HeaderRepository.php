<?php

namespace Sibas\Repositories\Eb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Client;
use Sibas\Entities\De\Coverage;
use Sibas\Entities\Eb\Header;
use Sibas\Entities\Retailer\Cashier;
use Sibas\Entities\RetailerProduct;
use Sibas\Repositories\BaseRepository;

class HeaderRepository extends BaseRepository
{

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
            'detail.client',
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


    /** Get Order
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function getOrder($request)
    {
        $header = Header::with([
            'detail.client',
            'detail.beneficiary',
            'user.city',
            'coverage',
        ])
            ->whereHas('detail.client', function ($q) use ($request) {
                $q->where('dni', $request->get('dni'))
                    ->where('complement', 'like', '%' . $request->get('complement') . '%')
                    ->where('extension', $request->get('extension'));
            })
            ->where('issue_number', $request->get('issue_number'))
            ->where('canceled', false)
            ->first();

        return $header;
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
                    $is_client = empty($request->get('code')) ? false : true;

                    $this->model->detail()->create([
                        'id'           => date('U'),
                        'op_client_id' => $client->id,
                        'client_eco'   => $is_client,
                        'age'          => $client->age,
                    ]);

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
            $is_client = empty($request->get('code')) ? false : true;

            $header->detail()->update([
                'client_eco'   => $is_client,
                'op_client_id' => $client->id,
                'age'          => $client->age,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
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
                try {
                    $header->update([
                        'ad_retailer_product_id' => $retailerProduct->id,
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
     * @param string $dni
     * @param string $complement
     * @param string $extension
     *
     * @return bool
     */
    public function checkMembership($dni, $complement, $extension)
    {
        $m = Header::whereHas('detail.client', function ($q) use ($dni, $complement, $extension) {
            $q->where('dni', $dni)
                ->where('complement', $complement)
                ->where('extension', $extension);
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
                        $user       = $request->user();

                        $attributes = [
                            'type'              => 'I',
                            'issue_number'      => $issue_number,
                            'prefix'            => 'ECA',
                            'policy_number'     => $this->data['policy_number'],
                            'approved'          => true,
                            'ad_agency_id'      => is_null($user->agency) ? null : $user->agency->id,
                            'ad_certificate_id' => $this->certificate->id,
                        ];

                        $client_eco = $header->detail->client_eco;

                        $header->update($attributes);

                        /*if (! $client_eco) {
                            $header->detail()->update([
                                'voucher' => $this->data['voucher'],
                            ]);
                        }*/

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
     * Header issuance
     *
     * @param Model|Header  $header
     * @param Model|Cashier $cashier
     * @param string        $voucher
     * @param string        $reason
     *
     * @return bool
     */
    public function issuanceHeader($header, $cashier, $voucher, $reason)
    {
        try {
            $amount = 30;

            $header->update([
                'issued'        => true,
                'date_issue'    => $this->carbon->format('Y-m-d H:i:s'),
                'ad_cashier_id' => $cashier->id,
            ]);

            $header->detail()->update([
                'premium' => $amount,
                'voucher' => $voucher,
            ]);

            $header->collection()->create([
                'id'                 => date('U'),
                'op_eb_header_id'    => $header->id,
                'fee_number'         => 1,
                'fee_date'           => $this->carbon->format('Y-m-d H:i:s'),
                'fee_amount'         => $amount,
                'charged'            => true,
                'transaction_number' => $voucher,
                'transaction_date'   => $this->carbon->format('Y-m-d H:i:s'),
                'transaction_amount' => $amount,
                'reason'             => $reason,
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}