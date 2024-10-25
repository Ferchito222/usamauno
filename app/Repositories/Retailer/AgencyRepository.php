<?php

namespace Sibas\Repositories\Retailer;

use Sibas\Entities\Agency;
use Sibas\Repositories\BaseRepository;

class AgencyRepository extends BaseRepository
{
    /**
     * @param int $retailer_id
     *
     * @return mixed
     */
    public function getAgenciesByRetailer($retailer_id)
    {
        return Agency::select(['slug as id', 'name'])
            ->with('retailerCityAgencies.retailerCity')
            ->whereHas('retailerCityAgencies.retailerCity', function ($q) use ($retailer_id) {
                $q->where('ad_retailer_id', $retailer_id);
            })->get();
    }


    /**
     * Get Agency By Slug
     *
     * @param $retailer_id
     * @param $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAgencyBySlug($retailer_id, $slug)
    {
        return Agency::select(['slug as id', 'name'])
            ->with('retailerCityAgencies.retailerCity')
            ->whereHas('retailerCityAgencies.retailerCity', function ($q) use ($retailer_id) {
                $q->where('ad_retailer_id', $retailer_id);
            })
            ->where('slug', $slug)
            ->get();
    }


    /**
     * Get Agencies by city
     *
     * @param $retailer_id
     * @param $city_id
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAgenciesByCity($retailer_id, $city_id)
    {
        return Agency::select(['slug as id', 'name'])
            ->with('retailerCityAgencies.retailerCity')
            ->whereHas('retailerCityAgencies.retailerCity', function ($q) use ($retailer_id, $city_id) {
                $q->where('ad_retailer_id', $retailer_id)
                    ->where('ad_city_id', $city_id);
            })
            ->get();
    }
}