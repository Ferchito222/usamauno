<?php

use Sibas\Entities\Plan;

class PlanTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Plan();
    }

    protected function getData()
    {
        $data = [];

        $plans = [
            [
                'name'            => 'Plan A',
                'description'     => '<p>Plan A - <span lang="ES-BO">Muerte por cualquier causa&nbsp;</span>&nbsp;Bs. 14,000<br /><span lang="ES-BO">Invalidez Total y Permanente por enfermedad o accidente</span>&nbsp;Bs. 14,000<br />Sepelio Bs 2,100<br />Prima Anual Bs. 252,00<br />Prima Mensual Bs. 21,00</p>',
                'monthly_premium' => 21,
                'annual_premium'  => 252,
                'plan'            => '[{"cov":"Muerte por cualquier causa","rank":"14000"},{"cov":"Pago Anticipado del capital asegurado en caso de invalidez Total y Permanente","rank":"14000"},{"cov":"Sepelio","rank":"2100"}]',
                'minimum_age'     => 0,
                'maximum_age'     => 0,
            ],
            [
                'name'            => 'Plan B',
                'description'     => '<p>Plan B - Muerte por cualquier causa&nbsp;Bs. 21,000<br /><span lang="ES-BO">Invalidez Total y Permanente por enfermedad o accidente</span><strong><span lang="ES-BO">&nbsp; &nbsp; </span></strong>&nbsp;Bs. 21,000<br />Sepelio Bs 2,100<br />Prima Anual Bs. 348,00<br />Prima Mensual Bs. 29,00</p>',
                'monthly_premium' => 29,
                'annual_premium'  => 348,
                'plan'            => '[{"cov":"Muerte por cualquier causa","rank":"21000"},{"cov":"Pago Anticipado del capital asegurado en caso de invalidez Total y Permanente","rank":"21000"},{"cov":"Sepelio","rank":"2100"}]',
                'minimum_age'     => 0,
                'maximum_age'     => 0,
            ],
            [
                'name'            => 'Plan C',
                'description'     => '<p>Plan C - Muerte por cualquier causa&nbsp;Bs. 35,000<br /><span lang="ES-BO">Invalidez Total y Permanente por enfermedad o accidente</span><strong><span lang="ES-BO">&nbsp; &nbsp; &nbsp; &nbsp;</span></strong>&nbsp;Bs. 35,000<br />Sepelio Bs 3,500<br />Prima Anual Bs. 552,00<br />Prima Mensual Bs. 46,00</p>',
                'monthly_premium' => 46,
                'annual_premium'  => 552,
                'plan'            => '[{"cov":"Muerte por cualquier causa","rank":"35000"},{"cov":"Pago Anticipado del capital asegurado en caso de invalidez Total y Permanente","rank":"35000"},{"cov":"Sepelio","rank":"3500"}]',
                'minimum_age'     => 0,
                'maximum_age'     => 0,
            ],
            [
                'name'            => 'Plan D',
                'description'     => '<p>Plan D - Muerte por cualquier causa&nbsp;Bs. 69,000<br /><span lang="ES-BO">Invalidez Total y Permanente por enfermedad o accidente</span>&nbsp;Bs. 69,000<br />Sepelio Bs 3,500<br />Prima Anual Bs. 1.032,00<br />Prima Mensual Bs. 86,00</p>',
                'monthly_premium' => 86,
                'annual_premium'  => 1032,
                'plan'            => '[{"cov":"Muerte por cualquier causa","rank":"69000"},{"cov":"Pago Anticipado del capital asegurado en caso de invalidez Total y Permanente","rank":"69000"},{"cov":"Sepelio","rank":"3500"}]',
                'minimum_age'     => 0,
                'maximum_age'     => 0,
            ],
        ];

        $rp_id = null;
        $retailerProducts = $this->getModelData('RetailerProduct');

        foreach ($retailerProducts as $retailerProduct) {
            if ($retailerProduct->type === 'SP') {
                $rp_id = $retailerProduct->id;

                break;
            }
        }

        foreach ($plans as $plan) {
            $plan['ad_retailer_product_id'] = $rp_id;

            $data[] = $plan;
        }

        return $data;
    }
}
