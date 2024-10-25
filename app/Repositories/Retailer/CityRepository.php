<?php

namespace Sibas\Repositories\Retailer;

use Illuminate\Support\Collection;
use Sibas\Entities\City;
use Sibas\Repositories\BaseRepository;

class CityRepository extends BaseRepository
{
    private $cities;

    private function getCities()
    {
        return City::select('id', 'id as city_id', 'name', 'abbreviation', 'slug', 'type_ci', 'type_re', 'type_de')
            ->get();
    }

    public function getCitiesByType()
    {
        $this->cities = $this->getCities();
        $selectOption = $this->getSelectOption();

        $cities = [];

        $cities['CI'] = $this->cities->filter(function($item) use ($cities) {
            $item = $this->getDataOptions($item, 'CI');

            if ($item->type_ci == 1) {
                return $item;
            }
        })->toArray();

        $cities['RE'] = $this->cities->filter(function($item) {
            $item = $this->getDataOptions($item, 'RE');

            if ($item->type_re == 1) {
                return $item;
            }
        })->toArray();

        $cities['DE'] = $this->cities->filter(function($item) {
            $item = $this->getDataOptions($item, 'DE');

            if ($item->type_de == 1) {
                return $item;
            }
        })->toArray();

        foreach ($cities as &$city) {
            $city = $selectOption->merge($city);
        }

        return $cities;
    }

    /**
     * @param Collection $item
     * @return mixed
     */
    private function getDataOptions($item, $type)
    {
        switch ($type) {
            case 'CI':
                $item->id = $item->abbreviation;
                break;
            case 'RE':
                $item->id = $item->abbreviation;
                break;
            case 'DE':
                $item->id = $item->slug;
                break;
        }

        $item->data_city = $item->abbreviation;

        return $item;
    }

    /**
     * @param int $retailer_id
     * @return mixed
     */
    public function getCitiesByRetailer($retailer_id)
    {
        return City::select(['slug as id', 'name'])
            ->whereHas('retailerCities', function($q) use ($retailer_id) {
                $q->where('ad_retailer_id', $retailer_id);
            })
            ->get();
    }


    /**
     * Get City by slug
     * @param $slug
     *
     * @return mixed
     */
    public function getCityBySlug($retailer_id, $slug)
    {
        return City::select(['slug as id', 'name'])
            ->whereHas('retailerCities', function ($q) use ($retailer_id) {
                $q->where('ad_retailer_id', $retailer_id);
            })
            ->where('slug', $slug)
            ->get();
    }
}