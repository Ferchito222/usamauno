<?php

namespace Sibas\Repositories\Retailer;

use Sibas\Entities\VersionCert;
use Sibas\Repositories\BaseRepository;

class VersionCertRepository extends BaseRepository
{   
    public function getPolicyByVersionCert($rp_id, $card, $user_city)
    {
        $policy = ($card=='DC')?'cert_number_b':'cert_number';
        return VersionCert::select($policy.' as id', $policy.' as name')
                ->where('ad_retailer_product_id', $rp_id)
                ->where('ad_city_id', $user_city)
                ->where('active', true)
                ->get();
    }
}
