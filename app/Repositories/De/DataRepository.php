<?php

namespace Sibas\Repositories\De;

use Illuminate\Support\Collection;
use Sibas\Repositories\BaseRepository;

class DataRepository extends BaseRepository
{

    /** Returns list of Currencies
     *
     * @return Collection
     */
    public function getCurrency()
    {
        $currencies = config('base.currencies');

        return $this->getData($currencies);
    }


    /** Returns list of Term Types
     *
     * @return Collection
     */
    public function getTermType()
    {
        $termType = config('base.term_types');

        return $this->getData($termType);
    }

    /** Returns list of Term Types
     *
     * @return Collection
     */
    public function getCountry()
    {
        $countries = config('base.countries');

        return $this->getData($countries);
    }

    /** Returns list of Term
     *
     * @return Collection
     */
    public function getTerm()
    {
        $term = [];
        for ($i = 12; $i <= 120; $i=$i+6) {
            $term[$i] = $i;
        }
        
        return $this->getData($term);
    }

    /** Returns list of Term
     *
     * @return Collection
     */
    public function getTermYear()
    {
        $term = [];
        for ($i = 12; $i <= 240; $i=$i+12) {
            $term[$i] = $i;
        }

        return $this->getData($term);
    }

    /** Returns list of Payment Types
     *
     * @return Collection
     */
    public function getPaymentType()
    {
        $payment_type = config('base.payment_type');

        return $this->getData($payment_type);
    }


    /** Returns list of Civil Status
     *
     * @return Collection
     */
    public function getCivilStatus()
    {
        $civil_status = config('base.client_civil_status');

        return $this->getData($civil_status);
    }


    /** Returns list of Document Types
     *
     * @return Collection
     */
    public function getDocumentType()
    {
        $document_types = config('base.client_document_types');

        return $this->getData($document_types);
    }


    /** Returns list of Genders
     *
     * @return Collection
     */
    public function getGender()
    {
        $genders = config('base.client_genders');

        return $this->getData($genders);
    }


    /** Returns list of Hands
     *
     * @return Collection
     */
    public function getHand()
    {
        $hands = config('base.client_hands');

        return $this->getData($hands);
    }


    /** Returns list of Avenue Street
     *
     * @return Collection
     */
    public function getAvenueStreet()
    {
        $avenue_street = config('base.avenue_street');

        return $this->getData($avenue_street);
    }


    /** Returns list of Payment Methods
     *
     * @return Collection
     */
    public function getPaymentMethod()
    {
        $payment_methods = config('base.payment_methods');
        unset($payment_methods['elp']);

        return $this->getData($payment_methods);
    }


    /** Returns list of Periods
     *
     * @return Collection
     */
    public function getPeriod()
    {
        $periods = config('base.periods');

        return $this->getData($periods);
    }


    /** Returns list of Vehicle Categories
     *
     * @return Collection
     */
    public function getVehicleCategory()
    {
        $vehicle_categories = config('base.vehicle_category');

        return $this->getData($vehicle_categories);
    }


    /** Returns list of Vehicle Uses
     *
     * @return Collection
     */
    public function getVehicleUse()
    {
        $vehicle_uses = config('base.vehicle_use');

        return $this->getData($vehicle_uses);
    }


    public function getMovementType()
    {
        $movement_types = config('base.movement_types');

        return $this->getData($movement_types);
    }


    public function getPropertyTypes()
    {
        $property_types = config('base.property_types');

        return $this->getData($property_types);
    }


    public function getPropertyUses()
    {
        $property_uses = config('base.property_uses');

        return $this->getData($property_uses);
    }

    public function getPropertyStates()
    {
        $property_states = config('base.property_states');

        return $this->getData($property_states);
    }

    public function getFideicomisos()
    {
        $fideicomisos = config('base.fideicomisos');

        return $this->getData($fideicomisos);
    }
    
    /** Returns list of capital
     *
     * @return Collection
     */
    public function getCapital()
    {
        $capitals = config('base.capital');

        return $this->getData($capitals);
    }
    
    public function getAccountNumberTypes()
    {
        $account_number_types = config('base.account_number_types');

        return $this->getData($account_number_types);
    }
    
    /** Returns list of relationship
     *
     * @return Collection
     */
    public function getRelationship()
    {
        $array = config('base.relationship');

        return $this->getData($array);
    }

    public function getTypesOfRequests()
    {
        $array = config('base.types_of_requests');

        return $this->getData($array);
    }
    
    /** Returns list of type contracts
     *
     * @return Collection
     */
    public function getTypeOfContract()
    {
        $array = config('base.type_of_contract');

        return $this->getData($array);
    }

    public function getYesNo()
    {
        $array = config('base.yes_no');

        return $this->getData($array);
    }

    public function getPaymentMethodVi()
    {
        $payment_methods = config('base.payment_methods_vi');
        
        return $this->getData($payment_methods);
    }

    /** Returns list of months
     *
     * @return Collection
     */
    public function getMonthText()
    {
        $month_text = monthTranslated();

        return $this->getData($month_text);
    }

    /** Returns list Years
     *
     * @return Collection
     */
    public function getYearsText()
    {
        for ($i=2020 ; $i<= 2030 ; $i++){
            $year_text[$i] = $i;
        }

        return $this->getData($year_text);
    }

    /** Returns list of pre printed
     *
     * @return Collection
     */
    public function getTrueFalse()
    {
        $array = config('base.true_false');
        
        return $this->getData($array);
    }
}
