<?php

namespace Sibas\Repositories\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Entities\Activity;
use Sibas\Entities\Client;
use Sibas\Repositories\BaseRepository;

class ClientRepository extends BaseRepository
{
    protected $addresses = [];


    /**
     * Create a newly created Client.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function createClient($request)
    {
        $this->data = $request->all();

        if ($this->getClientByDni($this->data['dni'], $this->data['extension'])) {
            return $this->updateClient($this->model);
        }

        return $this->storeClient($request);
    }


    /**
     * Client store.
     *
     * @param Request $request
     *
     * @return bool
     */
    private function storeClient($request)
    {
        $user     = is_null($request->user()) ? request()->user() : $request->user();
        $retailer = $user->retailerUser->retailer;

        $this->model = new Client();

        $max = $this->model->max('id');
        $id  = is_null($max) ? date('U') : $max + 1;

        $this->model->id             = $id;
        $this->model->ad_retailer_id = $retailer->id;
        $this->model->dni            = $this->data['dni'];
        $this->model->extension      = $this->data['extension'];

        if (! $request->has('ad_activity_id')) {
            $activity = Activity::where('occupation', 'SIN OCUPACIÓN')->orWhere('code', 9)->first();

            $this->data['ad_activity_id'] = $activity->id;
        }

        $this->setData();

        $exists = $this->model->where('id', $id)->exists();

        if (! $exists) {
            return $this->saveModel();
        }

        return false;
    }


    /**
     * Client edit
     *
     * @param Request $request
     * @param         $client
     *
     * @return bool
     */
    public function editClient($request, $client)
    {
        $this->data = $request->all();

        return $this->updateClient($client);
    }


    /** Update Client
     *
     * @param null $client
     *
     * @return bool
     */
    private function updateClient($client = null)
    {
        if ($client instanceof Client) {
            $this->model = $client;

            $this->setData();

            return $this->saveModel();
        }

        return false;
    }


    /** Set complementary data on Issue
     *
     * @param Request      $request
     * @param Model|Client $client
     *
     * @return bool
     */
    public function updateIssueClient($request, $client)
    {
        $this->data  = $request->all();
        $this->model = $client;

        $this->data['document_type'] = $this->model->document_type;
        $this->data['dni']           = $this->model->dni;
        $this->data['extension']     = $this->model->extension;
        $this->data['complement']    = $this->model->complement;
        //$this->data['gender']        = $this->model->gender;
        $this->data['weight']        = $this->model->weight;
        $this->data['height']        = $this->model->height;

        if(isset($this->data['birthdate'])){
            $this->setData();
        }

        return $this->saveModel();
    }


    /** Set data to Client
     *
     */
    private function setData()
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
        if (key_exists('cesantia', $this->data)) {
            if (key_exists('company_work', $this->data)) 
                $this->model->company_work = $this->data['cesantia']==true?$this->data['company_work']:'';

            if (key_exists('year_of_entry_to_work', $this->data)) 
                $this->model->year_of_entry_to_work = $this->data['cesantia']==true?$this->data['year_of_entry_to_work']:0;

            if (key_exists('type_of_contract', $this->data)) 
                $this->model->type_of_contract = $this->data['cesantia']==true?$this->data['type_of_contract']:'';

            if (key_exists('public_work', $this->data))
                $this->model->public_work = $this->data['public_work'];

            if (key_exists('position', $this->data))
                $this->model->position = $this->data['public_work']==true?$this->data['position']:'';

            if (key_exists('period', $this->data))
                $this->model->period = $this->data['public_work']==true?$this->data['period']:'';

            if (key_exists('institution', $this->data))
                $this->model->institution = $this->data['public_work']==true?$this->data['institution']:'';

            if (key_exists('economic_activity_type_code', $this->data))
                $this->model->economic_activity_type_code = $this->data['economic_activity_type_code'];

            if (key_exists('economic_activity_type', $this->data))
                $this->model->economic_activity_type = $this->data['economic_activity_type'];
        }
    }


    /** Find Client by dni and extension
     *
     * @param string $dni
     * @param null   $ec
     * @param bool   $complement
     *
     * @return bool
     */
    public function getClientByDni($dni, $ec = null, $complement = false)
    {
        $query = Client::where('dni', '=', $dni);

        if ( ! empty($ec)) {
            if ($complement) {
                $query->where('complement', '=', $ec);
            } else {
                $query->where('extension', '=', $ec);
            }
        }

        $this->model = $query->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }

    public function getClientByCode($code = '')
    {
        
        if($code)
            $query = Client::select('id','dni', 'dni_history', 'code')->where('code', '=', $code);
        else
            $query = Client::select('id','dni', 'dni_history', 'code')->where('code', '!=', '');

        $this->model = $query->get();

        if ($this->model->count() > 0) 
            return true;

        return false;
    }

    public function getClientByLikeDni($dni)
    {
        
        $query = Client::select('id','dni', 'dni_history', 'code', 'extension')->where('dni', 'like', '%' . $dni . '%');

        $this->model = $query->get();

        if ($this->model->count() > 0) 
            return true;

        return false;
    }


    /** Find Client by Id
     *
     * @param $client_id
     *
     * @return bool
     */
    public function getClientById($client_id)
    {
        $this->model = Client::where('id', '=', $client_id)->get();

        if ($this->model->count() === 1) {
            $this->model = $this->model->first();

            return true;
        }

        return false;
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function getAddresses($data)
    {
        $this->addresses = collect($data['Direcciones']['Direccion']);
        $this->addresses = $this->addresses->pluck('Descripcion');

        return $this->addresses;
    }
    
    public function setFormData($data)
    {            
        $arr['last_name']              = ($data)?$data['response']['firstSurname']:'';
        $arr['mother_last_name']       = ($data)?$data['response']['secondSurname']:'';
        $arr['first_name']             = ($data)?$data['response']['firstName']:'';
        $arr['married_name']           = ($data)?$data['response']['marriedSurname']:'';
        $arr['birthdate']              = ($data)?$data['response']['birthDate']:'';
        $arr['dni']                    = ($data)?getDni($data['response']['documentId']):'';
        $arr['extension']              = ($data)?getExtension($data['response']['documentId']):'';
        $arr['ad_activity_id']         = ($data)?$data['response']['occupationCode']:'';
        $arr['occupation_description'] = ($data)?$data['response']['occupation']:'';
        $arr['home_address']           = ($data)?$data['response']['homeAddress']:'';
        $arr['business_address']       = ($data)?$data['response']['businessName']:'';
        $arr['civil_status'] = ($data && $data['response']['civilStateCode'])?config('base.civil_status_code_fortaleza.'.$data['response']['civilStateCode']):'';

        return $arr;

    }
}