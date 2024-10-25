<?php

namespace Sibas\Http\Controllers;

use Sibas\Repositories\Client\ActivityRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\Retailer\CityRepository;

trait DataTrait
{

    /**
     * @var DataRepository
     */
    protected $dataRepository;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @var ActivityRepository
     */
    protected $activityRepository;


    protected function getInstance()
    {
        $this->dataRepository     = new DataRepository();
        $this->cityRepository     = new CityRepository();
        $this->activityRepository = new ActivityRepository();
    }


    /**
     * Returns Data for Client register
     *
     * @param string $rp_id
     *
     * @return array
     */
    public function getData($rp_id)
    {
        $this->getInstance();

        return [
            'civil_status'  => $this->dataRepository->getCivilStatus(),
            'document_type' => $this->dataRepository->getDocumentType(),
            'gender'        => $this->dataRepository->getGender(),
            'capital'       => $this->dataRepository->getCapital(),
            'cities'        => $this->cityRepository->getCitiesByType(),
            'activities'    => $this->activityRepository->getActivitiesByProduct(decode($rp_id)),
            'hands'         => $this->dataRepository->getHand(),
            'avenue_street' => $this->dataRepository->getAvenueStreet(),
            'term_types'    => $this->dataRepository->getTermType(),
            'currencies'    => $this->dataRepository->getCurrency(),
            'account_number_types' => $this->dataRepository->getAccountNumberTypes(),
            'type_of_contracts' => $this->dataRepository->getTypeOfContract(),
        ];
    }
}
