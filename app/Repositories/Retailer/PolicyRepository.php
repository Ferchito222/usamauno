<?php

namespace Sibas\Repositories\Retailer;

use Sibas\Entities\Policy;
use Sibas\Repositories\BaseRepository;

class PolicyRepository extends BaseRepository
{

    /**
     * Policy by product
     *
     * @param string $rp_id
     *
     * @return mixed
     */
    public function getPolicyByProduct($rp_id)
    {
        return Policy::where('ad_retailer_product_id', $rp_id)->get();
    }


    /**
     * Policy for issuance
     *
     * @param string $rp_id
     *
     * @return mixed
     */
    public function getPolicyForIssuance($rp_id)
    {
        return Policy::select('number as id', 'number as name', 'type', 'ad_city_id','modality')->where('ad_retailer_product_id', $rp_id)->where('active',true)->get();
    }

    public function getPolicyForIssuanceCC($rp_id, $city_id)
    {
        return Policy::select('number as id', 'number as name', 'type', 'ad_city_id','modality')->where('ad_retailer_product_id', $rp_id)->where('ad_city_id',$city_id)->where('active',true)->get();
    }


    /**
     * Policy by currency
     *
     * @param string $rp_id
     * @param string $currency
     */
    public function getPolicyByCurrency($rp_id, $currency)
    {
        return Policy::select('number as id', 'number as name')
                ->where('ad_retailer_product_id', $rp_id)
                ->where('currency', $currency)->get();
    }
    
    public function getPolicyByTypeVehicle($rp_id, $category_vehicle)
    {
        return Policy::select('number as id', 'number as name')
                ->where('ad_retailer_product_id', $rp_id)
                ->where('modality', $category_vehicle)
                ->where('active', true)
                ->get();
}
    
    public function getPolicyByModality($rp_id, $modalities)
    {
        return Policy::where('ad_retailer_product_id', $rp_id)
                ->whereIn('modality', $modalities)
                ->where('active', true)
                ->get();
    }
}
