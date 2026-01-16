<?php

namespace Sibas\Http\Controllers;

use Sibas\Repositories\Client\ActivityRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\Retailer\PlanRepository;

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

    /**
     * @var PlanRepository
     */
    protected $planRepository;


    protected function getInstance()
    {
        $this->dataRepository     = new DataRepository();
        $this->cityRepository     = new CityRepository();
        $this->activityRepository = new ActivityRepository();
        $this->planRepository     = new PlanRepository();
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
            'countries'    => $this->dataRepository->getCountry(),
            'terms'         => $this->dataRepository->getTerm(),
            'terms_year'    => $this->dataRepository->getTermYear(),
            'currencies'    => $this->dataRepository->getCurrency(),
            'account_number_types' => $this->dataRepository->getAccountNumberTypes(),
            'type_of_contracts' => $this->dataRepository->getTypeOfContract(),
            // 'annulleds'     => $this->annulledRepository->getAnnulledByActive(),
            'plans'         => $this->planRepository->getPlanByProduct(decode($rp_id)),
            'payment_methods' => $this->dataRepository->getPaymentMethodVi(),
            'periods'       => $this->dataRepository->getPeriod(),
            'months'        => $this->dataRepository->getMonthText(),
            'years'         => $this->dataRepository->getYearsText(),
            'relationships' => $this->dataRepository->getRelationship(),
            'true_false'    => $this->dataRepository->getTrueFalse(),
            // 'referred'      => $this->clientRepo->getRef(),
        ];
    }
}
