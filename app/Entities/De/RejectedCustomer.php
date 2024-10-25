<?php

namespace Sibas\Entities\De;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\CreditProduct;

use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;

class RejectedCustomer extends Model
{

    protected $table = 'op_de_rejected_customers';

    public $incrementing = false;

    protected $fillable = [
            'id',
            'op_de_header_id',
            'ad_user_id',
            'city',
           

            'code',
            'type',
            'business_name',
            'first_name',
            'last_name',
            'mother_last_name',
            'married_name',
            'birthdate',
            'age',
            'birth_place',
            'dni',
            'extension',
            'complement',
            'document_type',
            'dni_file',
            'civil_status',
            'gender',
            'place_residence',
            'locality',
            'home_address',
            'home_number',
            'avenue_street',
            'business_address',
            'country',
            'ad_activity_id',
            'occupation_description',
            'phone_number_home',
            'phone_number_office',
            'phone_number_mobile',
            'email',
            'weight',
            'height',
            'hand',
            'debit_balance',
    ];

    protected $appends = [
        'full_name',
        'full_dni',
        'operation_numbers',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }

    public function header()
    {
        return $this->belongsTo(Header::class, 'op_de_header_id', 'id');
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

    public function getOperationNumbersAttribute()
    {
        return json_decode($this->operations, true);
    }
}
