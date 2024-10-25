<?php

namespace Sibas\Repositories\Eb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Sibas\Entities\Eb\Detail;
use Sibas\Http\Requests\Request;
use Sibas\Repositories\BaseRepository;

class DetailRepository extends BaseRepository
{

    /**
     * @param Request      $request
     * @param Model|Detail $detail
     *
     * @return bool
     */
    public function clientUpdate($request, $detail)
    {
        $this->data = $request->all();

        try {
            $date = $this->carbon->createFromFormat('d/m/Y', $this->data['birthdate']);

            $detail->client()->update([
                'first_name'             => $this->data['first_name'],
                'last_name'              => $this->data['last_name'],
                'mother_last_name'       => $this->data['mother_last_name'],
                'married_name'           => $this->data['married_name'],
                'birthdate'              => $date->format('Y-m-d'),
                'age'                    => $date->age,
                'birth_place'            => $this->data['birth_place'],
                'document_type'          => $this->data['document_type'],
                'gender'                 => $this->data['gender'],
                'home_address'           => $this->data['home_address'],
                'ad_activity_id'         => $this->data['ad_activity_id'],
                'occupation_description' => $this->data['occupation_description'],
                'phone_number_home'      => $this->data['phone_number_home'],
                'phone_number_office'    => $this->data['phone_number_office'],
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}