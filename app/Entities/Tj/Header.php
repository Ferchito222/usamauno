<?php

namespace Sibas\Entities\Tj;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Certificate;
use Sibas\Entities\VersionCert;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\RetailerCityAgency;
use Sibas\Entities\User;

class Header extends Model
{

    protected $table = 'op_tj_headers';

    public $incrementing = false;

    protected $casts = [
        'issued'   => 'boolean',
        'canceled' => 'boolean',
    ];

    protected $fillable = [
        'ad_retailer_product_id',
        'ad_certificate_id',
        'quote_number',
        'warranty',
        'validity_start',
        'validity_end',
        'type',
        'issue_number',
        'prefix',
        'policy_number',
        'ad_certificate_id',
        'issued',
        'date_issue',
        'bill_name',
        'bill_dni',
        'pre_printed',
        'pre_printed_number',
        'term',
        'payment_method',
        'period',
        'premium',
        'total_premium',
        'ad_retailer_city_agency_id',
        'currency',
        'file',
        'file_date',
        'read',
        'cpn_send_company',
        'cpn_lot_number',
        'cpn_result_ws',
    ];
    
    protected $appends = [
        'questions',
        'created_date',
        'days_from_creation',
        'version_cert',
        'policy_end',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'ad_user_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'op_tj_header_id', 'id');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'op_tj_header_id', 'id');
    }
    
    public function cancellation()
    {
        return $this->hasOne(Cancellation::class, 'op_tj_header_id', 'id');
    }


    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, 'ad_retailer_product_id', 'id');
    }
    
    public function retailerCityAgency()
    {
        return $this->belongsTo(RetailerCityAgency::class, 'ad_retailer_city_agency_id', 'id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'ad_certificate_id', 'id');
    }
    
    public function versionCertificates()
    {
        return $this->hasMany(VersionCert::class, 'ad_certificate_id', 'ad_certificate_id');
    }
    
    public function getQuestionsAttribute()
    {
        $i = 0;
        foreach ($this->details()->get() as $key => $value) {
            if($value->response)
                $i++;
        }
        
        if($this->details()->get()->count() == $i)
            return true;
        else
            return false;
    }
    
    public function getCreatedDateAttribute()
    {
        return Carbon::createFromTimestamp(strtotime($this->created_at))->format('d/m/Y H:i a');
    }
    
    public function getDaysFromCreationAttribute()
    {
        $date_now    = Carbon::now();
        $date_create = Carbon::createFromTimestamp(strtotime($this->created_at));

        return $date_now->diffInDays($date_create);
    }
    
    public function getVersionCertAttribute()
    {
        $model = $this->versionCertificates()->where('ad_city_id',$this->user()->first()->city->id)->where('active', true);
        if($model->count()== 1)
            return $model->first();
        else
            return false; 
            
    }

    public function getPolicyEndAttribute()
    {
        if($this->details->first()->card == 'DC')
            $date = carbon::parse($this->collections->last()->fee_date)->addMonths(3);
        else
            $date = Carbon::parse($this->validity_end);

        return $date;
    }
}
