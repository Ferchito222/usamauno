<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Elp\Detail as DetailElp;
use Sibas\Entities\Vi\Detail as DetailVi;
use Sibas\Entities\De\Detail as DetailDe;

class Client extends Model
{

    protected $table = 'op_clients';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'mother_last_name',
        'married_name',
        'birthdate',
        'age',
        'birth_place',
        'dni',
        'dni_history',
        'extension',
        'complement',
        'document_type',
        'civil_status',
        'gender',
        'place_residence',
        'locality',
        'home_address',
        'home_number',
        'avenue_street',
        'business_address',
        'workplace',
        'country',
        'ad_activity_id',
        'occupation_description',
        'phone_number_home',
        'phone_number_office',
        'phone_number_mobile',
        'email',
        'weight',
        'height',
        'debit_balance',
        'number',
        'addresses',
        'operations',
        'zone',
        'type',
        'city',
        'nationality',
        'company_work',
        'year_of_entry_to_work',
        'type_of_contract',
        'public_work',
        'position',
        'period',
        'institution',
        'economic_activity_type_code',
        'economic_activity_type',
    ];

    protected $appends = [
        'full_name',
        'full_dni',
        'operation_numbers',
    ];

    protected $dates = [
        'birthdate',
    ];

    protected $hidden = [
        'ad_retailer_id',
        'client_id_old',
        'age',
        'ad_activity_id',
        'operations',
        'created_at',
        'updated_at',
    ];


    public function detailsDe()
    {
        return $this->hasMany(DetailDe::class, 'op_client_id', 'id');
    }


    public function detailsVi()
    {
        return $this->hasMany(DetailVi::class, 'op_client_id', 'id');
    }


    public function detailsElp()
    {
        return $this->hasMany(DetailElp::class, 'op_client_id', 'id');
    }


    public function activity()
    {
        return $this->belongsTo(Activity::class, 'ad_activity_id', 'id');
    }

    public function cityClient()
    {
        return $this->belongsTo(City::class, 'city', 'abbreviation');
    }


    public function accounts()
    {
        return $this->hasMany(Account::class, 'op_client_id', 'id');
    }


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->mother_last_name;
    }

    public function getFullDniAttribute()
    {
        $extension = ($this->extension=='SE')?'':$this->extension;
        return $this->dni . ' ' . $this->complement . ' ' . $extension;
    }


    public function getImcAttribute()
    {
        $imc = false;

        $result = ceil($this->weight / ( ( $this->height / 100 ) * ( $this->height / 100 ) ));

        if ($result < 18) {
            $imc = true;
        } elseif (( $result >= 18 ) and ( $result <= 24 )) {
            $imc = false;
        } elseif (( $result >= 25 ) and ( $result <= 29 )) {
            $imc = false;
        } elseif (( $result >= 30 ) and ( $result <= 39 )) {
            $imc = true;
        } elseif ($result >= 40) {
            $imc = true;
        }

        return $imc;
    }


    public function getImcStatusAttribute()
    {
        $imc = '';

        $result = ceil($this->weight / ( ( $this->height / 100 ) * ( $this->height / 100 ) ));

        if ($result < 18) {
            $imc = 'Desnutricion';
        } elseif (( $result >= 18 ) and ( $result <= 24 )) {
            $imc = 'Saludable';
        } elseif (( $result >= 25 ) and ( $result <= 29 )) {
            $imc = 'Sobrepeso aceptable';
        } elseif (( $result >= 30 ) and ( $result <= 39 )) {
            $imc = 'Obeso';
        } elseif ($result >= 40) {
            $imc = 'Obesidad morbida';
        }

        return $imc;

    }


    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = mb_strtoupper($value);
    }


    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = mb_strtoupper($value);
    }


    public function setMotherLastNameAttribute($value)
    {
        $this->attributes['mother_last_name'] = mb_strtoupper($value);
    }


    public function setMarriedNameAttribute($value)
    {
        $this->attributes['married_name'] = mb_strtoupper($value);
    }


    public function setBirthPlaceAttribute($value)
    {
        $this->attributes['birth_place'] = mb_strtoupper($value);
    }


    public function setComplementAttribute($value)
    {
        $this->attributes['complement'] = strtoupper($value);
    }


    public function setLocalityAttribute($value)
    {
        $this->attributes['locality'] = mb_strtoupper($value);
    }


    public function setHomeAddressAttribute($value)
    {
        $this->attributes['home_address'] = mb_strtoupper($value);
    }


    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = mb_strtoupper($value);
    }


    public function setOccupationDescriptionAttribute($value)
    {
        $this->attributes['occupation_description'] = mb_strtoupper($value);
    }


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }


    public function setBusinessAddressAttribute($value)
    {
        $this->attributes['business_address'] = strtoupper($value);
    }


    public function setWorkplaceAttribute($value)
    {
        $this->attributes['workplace'] = strtoupper($value);
    }


    public function getOperationNumbersAttribute()
    {
        return json_decode($this->operations, true);
    }

}
