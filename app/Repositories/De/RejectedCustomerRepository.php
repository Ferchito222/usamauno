<?php

namespace Sibas\Repositories\De;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\De\RejectedCustomer;
use Sibas\Repositories\BaseRepository;

class RejectedCustomerRepository extends BaseRepository
{

    /** Store a newly created RejectedCustomer in DB.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function createRejectedCustomer($request)
    {
        $user       = $request->user();
        $this->data = $request->all();
        
        $this->model  = new RejectedCustomer();

        $this->model->id                    = date('U');
        $this->model->op_de_header_id       = $this->data['header']->id;
        $this->model->ad_user_id            = $user->id;
        $this->model->city                  = $user->city->name;
        $this->setData();
        return $this->saveModel();
    }
    public function updateRejectedCustomer($request)
    {
        $user       = $request->user();
        $this->data = $request->all();
        
        $this->setData();
        return $this->saveModel();
    }

    public function getRejectedCustomerByDni($header_id, $dni)
    {
        $this->model = RejectedCustomer::with([ 
            'user', 
            'header.details.client.detailsVi',
            'header.details.beneficiary',
            'header.details.facultative',
            'header.details.coverages',
            'header.details.response',
            'header.user.city',
            'header.coverageWarranty',
            'header.creditProduct',
            'header.coverage',
            'header.placeIssuance' ])
            ->where('op_de_header_id', $header_id)
            ->where('dni', $dni)
            ->get();

        if ($this->model->count() > 0) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    public function getRejectedCustomerById($id)
    {
        $this->model = RejectedCustomer::with([ 
            'user', 
            'header.details.client.detailsVi',
            'header.details.beneficiary',
            'header.details.facultative',
            'header.details.coverages',
            'header.details.response',
            'header.user.city',
            'header.coverageWarranty',
            'header.creditProduct',
            'header.coverage',
            'header.retailerProduct.retailer',
            'header.placeIssuance' ])
            ->where('id', $id)
            ->get();

        if ($this->model->count() == 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    public function setData()
    {
        $date = $this->carbon->createFromFormat('d/m/Y', $this->data['birthdate']);

        if (key_exists('code', $this->data)) {
            $this->model->code = $this->data['code'];
        }

        $this->model->phone_number_home = $this->data['phone_number_home'];
        $this->model->dni               = $this->data['dni'];
        $this->model->extension         = $this->data['extension'];
        $this->model->birthdate         = $date->format('Y-m-d');

        $this->model->type = (key_exists('type', $this->data))?$this->data['type']:'N';
        $this->model->age  = $date->age;

        if (key_exists('first_name', $this->data)) {
            $this->model->first_name = $this->data['first_name'];
        }

        if (key_exists('last_name', $this->data)) {
            $this->model->last_name = $this->data['last_name'];
        }

        if (key_exists('mother_last_name', $this->data)) {
            $this->model->mother_last_name = $this->data['mother_last_name'];
        }

        if (key_exists('married_name', $this->data)) {
            $this->model->married_name = $this->data['married_name'];
        }

        if (key_exists('complement', $this->data)) {
            $this->model->complement = $this->data['complement'];
        }

        if (key_exists('avenue_street', $this->data)) {
            $this->model->avenue_street = $this->data['avenue_street'];
        }
        
        if (key_exists('zone', $this->data)) {
            $this->model->zone = $this->data['zone'];
        }

        if (key_exists('home_number', $this->data)) {
            $this->model->home_number = $this->data['home_number'];
        }

        if (key_exists('business_address', $this->data)) {
            $this->model->business_address = $this->data['business_address'];
        }

        if (key_exists('workplace', $this->data)) {
            $this->model->workplace = $this->data['workplace'];
        }

        if (key_exists('birth_place', $this->data)) {
            $this->model->birth_place = $this->data['birth_place'];
        }

        if (key_exists('document_type', $this->data)) {
            $this->model->document_type = $this->data['document_type'];
        }

        if (key_exists('document_type_bank', $this->data)) {
            $this->model->document_type_bank = $this->data['document_type_bank'];
        }

        if (key_exists('civil_status', $this->data)) {
            $this->model->civil_status = $this->data['civil_status'];
        } elseif (! empty($this->data['married_name'])) {
            $this->model->civil_status = 'C';
        }

        if (key_exists('place_residence', $this->data)) {
            $this->model->place_residence = $this->data['place_residence'];
        }

        if (key_exists('locality', $this->data)) {
            $this->model->locality = $this->data['locality'];
        }

        if (key_exists('home_address', $this->data)) {
            $this->model->home_address = $this->data['home_address'];
        }

        if (key_exists('country', $this->data)) {
            $this->model->country = $this->data['country'];
        }

        if (key_exists('ad_activity_id', $this->data)) {
            $this->model->ad_activity_id = $this->data['ad_activity_id'];
        }

        if (key_exists('occupation_description', $this->data)) {
            $this->model->occupation_description = $this->data['occupation_description'];
        }

        if (key_exists('phone_number_office', $this->data)) {
            $this->model->phone_number_office = $this->data['phone_number_office'];
        }

        if (key_exists('phone_number_mobile', $this->data)) {
            $this->model->phone_number_mobile = $this->data['phone_number_mobile'];
        }

        if (key_exists('email', $this->data)) {
            $this->model->email = $this->data['email'];
        }

        if (key_exists('weight', $this->data)) {
            $this->model->weight = $this->data['weight'];
        }

        if (key_exists('height', $this->data)) {
            $this->model->height = $this->data['height'];
        }

        if (key_exists('gender', $this->data)) {
            $this->model->gender = $this->data['gender'];
        } elseif (! empty($this->data['married_name'])) {
            $this->model->gender = 'F';
        }

        if (key_exists('hand', $this->data)) {
            $this->model->hand = $this->data['hand'];
        }

        if (key_exists('debit_balance', $this->data)) {
            $this->model->debit_balance = $this->data['debit_balance'];
        }

        if (key_exists('number', $this->data)) {
            $this->model->number = $this->data['number'];
        }

        if (key_exists('addresses', $this->data)) {
            $this->model->addresses = $this->data['addresses'];
        }

        if (key_exists('operations', $this->data)) {
            $this->model->operations = $this->data['operations'];
        }
    }

}
