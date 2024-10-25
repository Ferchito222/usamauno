<?php

namespace Sibas\Repositories\Vi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Client;
use Sibas\Entities\De\Header as HeaderDe;
use Sibas\Entities\Modality;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;
use Sibas\Entities\Vi\Header;
use Sibas\Repositories\BaseRepository;

class HeaderRepository extends BaseRepository
{

    /**
     * @var double
     */
    public $insured_value = 0;

    /**
     * @var double
     */
    public $amount_max = 0;

    /**
     * @var boolean
     */
    public $error_value = false;


    /**
     * @param Request               $request
     * @param Model|RetailerProduct $retailerProduct
     *
     * @return bool
     */
    public function storeSubProduct(Request $request, $retailerProduct)
    {
        $user       = $request->user();
        $this->data = $request->all();
        $policies   = $this->data['policies'];
        $plans      = $this->data['plans'];
        $detail     = $this->data['detail'];

        $plan = $plans->filter(function ($item) {
            return $item['id'] == $this->data['plan'];
        });

        $plan = $plan->first();

        if ($policies->count() === 1 && $this->getCertificate($retailerProduct)) {
            $policy = $policies->first();

            $this->model = new Header();

            $this->model->id                     = date('U');
            $this->model->ad_user_id             = $user->id;
            $this->model->ad_retailer_product_id = $retailerProduct->id;
            $this->model->type                   = 'I';
            $this->model->policy_number          = $this->getNumber('P', $policy->number);
            $this->model->issue_number           = $this->getNumber('I');
            $this->model->prefix                 = 'VI';
            $this->model->pre_printed            = false;
            $this->model->pre_printed_number     = 0;
            $this->model->ad_plan_id             = $plan['id'];
            $this->model->premium                = $plan['annual_premium'];
            $this->model->payment_method         = $this->data['payment_method'];
            $this->model->period                 = $this->data['period'];
            $this->model->issued                 = true;
            $this->model->date_issue             = $this->carbon->format('Y-m-d H:i:s');
            $this->model->pledged                = false;
            $this->model->case_number            = '';
            $this->model->amount_pledged         = 0;
            $this->model->file                   = '';
            $this->model->ad_certificate_id      = $this->certificate->id;

            if ($this->saveModel()) {
                try {
                    $detailVi = $this->model->details()->create([
                        'id'            => date('U'),
                        'op_client_id'  => $detail->client->id,
                        'insured_value' => $this->data['insured_value'],
                        'currency'      => 'BS',
                        'client_code'   => $detail->client->code,
                        'taker_name'    => $this->data['taker_name'],
                        'taker_dni'     => $this->data['taker_dni'],
                    ]);

                    $detailVi->response()->create([
                        'id'          => date('U'),
                        'response'    => json_encode($this->data['qs']),
                        'observation' => '',
                    ]);

                    $beneficiaries  = [];
                    $beneficiary_id = date('U');

                    foreach ($this->data['beneficiaries'] as $key => $beneficiary) {
                        array_push($beneficiaries, [
                            'id'               => $beneficiary_id + $key,
                            'coverage'         => 'VI',
                            'first_name'       => $beneficiary['first_name'],
                            'last_name'        => $beneficiary['last_name'],
                            'mother_last_name' => $beneficiary['mother_last_name'],
                            'dni'              => $beneficiary['dni'],
                            'relationship'     => $beneficiary['relationship'],
                            'participation'    => $beneficiary['participation'],
                        ]);
                    }

                    $detailVi->beneficiaries()->createMany($beneficiaries);

                    return true;
                } catch (QueryException $e) {
                    $this->errors = $e->getMessage();
                }
            }
        }

        return false;
    }


    /**
     * @param Model|User     $user
     * @param string         $sp_id
     * @param Model|HeaderDe $headerDe
     * @param Model|Client   $client
     *
     * @return bool
     */
    public function getInsuredValue($user, $sp_id, $headerDe, $client)
    {
        $modality         = null;
        $retailer         = $user->retailerUser->retailer;
        $retailerProduct  = $retailer->retailerProducts()->where('id', decode($sp_id))->first();
        $modalities       = $retailerProduct->modalities()->where('active', true)->get();
        $amount_requested = $this->getAmountInBs($headerDe->currency, $headerDe->amount_requested,
            $retailer->exchangeRate->bs_value);

        foreach ($modalities as $modality) {
            if ($modality->modality === 'MV' && ( $amount_requested >= $modality->rank_min && $amount_requested <= $modality->rank_max )) {
                $this->insured_value = $modality->amount;

                break;
            } elseif ($modality->modality === 'MS') {
                $this->insured_value = $modality->amount;

                break;
            }
        }

        if ($this->insured_value > 0 && ( $modality instanceof Modality )) {
            if ($amount_requested < $modality->amount) {
                $this->insured_value = $amount_requested;
            }

            if ($this->insured_value < $modality->amount_min) {
                $this->insured_value = $modality->amount_min;
            }

            $amount_total = 0;

            foreach ($client->detailsVi as $detail) {
                $amount_total += $detail->insured_value;
            }

            $amount_total += $this->insured_value;

            if ($amount_total > $modality->amount_max) {
                $this->insured_value = '';
                $this->error_value   = true;
                $this->amount_max    = $modality->amount_max;
            }

            return true;
        }

        return false;
    }
}