<?php

namespace Sibas\Entities\Tj;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Client;

class Detail extends Model
{

    protected $table = 'op_tj_details';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'op_client_id',
        'insured_value',
        'currency',
        'account_number',
        'savings_bank',
        'client_code',
        'holder',
        'card_number',
        'card',
        'premium',
        'expiration_card_year',
        'expiration_card_month',
        'debit_cards_ws',
        'accounts_ws',
        'credit_cards_ws',
        'card_customer_id',
        'card_account_number',
        'card_customer_name',
        'card_due_date',
        'card_customer_type',
        'card_product_type',
        'card_card_name',
        'card_card_type_code',
        'card_card_type',
        'debit_cards_ws',
    ];

    protected $appends = [
        'completed',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'op_client_id', 'id');
    }


    public function header()
    {
        return $this->belongsTo(Header::class, 'op_tj_header_id', 'id');
    }


    public function response()
    {
        return $this->hasOne(Response::class, 'op_tj_detail_id', 'id');
    }


    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'op_tj_detail_id', 'id');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'op_tj_detail_id', 'id');
    }

    public function getCompletedAttribute()
    {
        /*if(empty($this->client->workplace)){
            return false;
        }else*/
            return true;
    }
}
