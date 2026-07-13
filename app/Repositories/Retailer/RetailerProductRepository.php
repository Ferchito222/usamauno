<?php

namespace Sibas\Repositories\Retailer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Sibas\Entities\CreditProduct;
use Sibas\Entities\De\Header;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\RetailerProductCoverage;
use Sibas\Entities\RetailerProductPaymentMethod;
use Sibas\Entities\RetailerProductQuestion;
use Sibas\Entities\RetailerProductState;
use Sibas\Entities\State;
use Sibas\Entities\User;
use Sibas\Repositories\BaseRepository;

class RetailerProductRepository extends BaseRepository
{

    /**
     * Find Questions for Product Retailer
     *
     * @param string            $rp_id
     * @param Model|Header|null $header
     *
     * @return array
     */
    public function getQuestionByProduct($rp_id, $header = null)
    {
        $this->model = RetailerProduct::with([
            'productQuestions' => function ($q) {
                $q->where('active', true);
                $q->orderBy('order', 'asc');
            },
        ])->where('id', $rp_id)->get();

        $questions = [];

        if ($this->model->count() === 1) {
            $creditProduct = is_null($header) ? new CreditProduct() : $header->creditProduct;
            $retailer      = $this->model->first();
            $order         = 1;

            foreach ($retailer->productQuestions as $productQuestion) {
                $pq = null;
                
                if (in_array(is_null($creditProduct) ? [] : $creditProduct->slug, [ 'PMO', 'PLT' ]) && in_array($productQuestion->type, [ 'PMO', 'PLT' ])) {
                    $pq = $productQuestion;
                } elseif (!in_array(is_null($creditProduct) ? [] : $creditProduct->slug, [ 'PMO', 'PLT' ]) && is_null($productQuestion->type)) {
                    $pq = $productQuestion;
                } elseif (!in_array($productQuestion->type, [ 'PMO', 'PLT' ])) {
                    $pq = $productQuestion;
                }

                if ($pq instanceof RetailerProductQuestion) {
                    $check_yes = $pq->response ? true : false;
                    $check_no  = ! $pq->response ? true : false;

                    $questions[] = [
                        'id'                     => $pq->question->id,
                        'order'                  => $order,
                        'question'               => $pq->question->question,
                        'response'               => $pq->response,
                        'expected'               => (int)$pq->response,
                        'response_text'          => $pq->response_text,
                        'type'                   => $pq->type,
                        'check_yes'              => $check_yes,
                        'check_no'               => $check_no,
                        'response_specification' => '',
                    ];

                    $order += 1;
                }
            }
        }

        return $questions;
    }


    public function getRetailerProductById($rp_id)
    {
        $this->model = RetailerProduct::with([
            'retailer.exchangeRate',
            'retailer.retailerProducts.companyProduct.product',
            'companyProduct.product',
            'rates.increments.category',
            'parameters',
            'subProducts.productCompany',
            'subProducts.companyProduct.product',
            'coverages',
            'certificates',
        ])->where('id', $rp_id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    public function getRetailerProductByCodeProduct($code)
    {
        
        $this->model = RetailerProduct::with([
            'retailer.exchangeRate',
            'retailer.retailerProducts.companyProduct.product',
            'companyProduct.product',
            'rates.increments.category',
            'parameters',
            'subProducts.productCompany',
            'subProducts.companyProduct.product',
            'coverages',
            'certificates',
        ])->whereHas('companyProduct.product', function ($q) use ($code) {
            $q->where('code', $code);
        })->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function getRetailerProductByCode($code)
    {
        $this->model = RetailerProduct::with(
            [
                'companyProduct.product',
                'emails',
            ])
            ->whereHas('companyProduct.product', function ($q) use ($code) {
                $q->where('code', $code);
            })
            ->where('type', 'MP')
            ->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * @param Model|User $user
     *
     * @return Collection
     */
    public function getRetailerProductByUser($user)
    {
        return RetailerProduct::with([
            'companyProduct.product',
        ])->whereHas('retailer.users', function ($q) use ($user) {
            $q->where('ad_users.id', $user->id);
        })->whereIn('type', [ 'MP', 'SP' ])->get();
    }


    public function getCoverageByProduct($rp_id)
    {
        $selectOption     = $this->getSelectOption();
        $productCoverages = RetailerProductCoverage::with('coverage')->where('ad_retailer_product_id', $rp_id)->get();
        $coverages        = [];

        foreach ($productCoverages as $productCoverage) {
            $coverages[] = [
                'id'            => $productCoverage->coverage->id,
                'name'          => $productCoverage->coverage->name,
                'data_coverage' => $productCoverage->coverage->slug,
            ];
        }

        $coverages = $selectOption->merge($coverages);

        return $coverages;
    }


    public function getPaymentMethodsByProductById($rp_id)
    {
        $selectOption          = $this->getSelectOption();
        $productPaymentMethods = RetailerProductPaymentMethod::where('ad_retailer_product_id', $rp_id)->get();
        $paymentMethods        = [];

        foreach ($productPaymentMethods as $productPaymentMethod) {
            $paymentMethods[] = [
                'id'                  => $productPaymentMethod->payment_method,
                'name'                => config('base.payment_methods.' . $productPaymentMethod->payment_method),
                'data_payment_method' => $productPaymentMethod->payment_method,
            ];
        }

        $paymentMethods = $selectOption->merge($paymentMethods);

        return $paymentMethods;
    }


    public function getStatusByProduct($rp_id)
    {
        $status = State::select('id', 'state', 'state as name', 'slug as data_slug')
            ->whereHas('retailerProduct', function ($q) use ($rp_id) {
                $q->where('ad_retailer_products.id', $rp_id);
            })->get();

        return $status;
    }


    /**
     * @param int     $rp_id
     * @param boolean $credit_line
     *
     * @return array|static
     */
    public function getCreditProductByProduct($rp_id)
    {
        $selectOption   = $this->getSelectOption();
        $creditProducts = CreditProduct::where('ad_retailer_product_id', $rp_id)->where('active', true)->get();
        $data           = [];

        foreach ($creditProducts as $creditProduct) {
            $data[] = [
                'id'                  => $creditProduct->id,
                'name'                => $creditProduct->name,
                'data_credit_product' => $creditProduct->slug,
            ];
        }

        $data = $selectOption->merge($data);

        return $data;
    }
}