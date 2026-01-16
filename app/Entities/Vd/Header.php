<?php

namespace Sibas\Entities\Vd;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Certificate;
use Sibas\Entities\Plan;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\RetailerCityAgency;
use Sibas\Entities\User;

class Header extends Model
{

    protected $table = "op_vd_headers";

    public $incrementing = false;

    protected $casts = [
        "issued"   => "boolean",
        "canceled" => "boolean",
    ];

    protected $fillable = [
        "ad_retailer_product_id",
        "ad_certificate_id",
        "quote_number",
        "warranty",
        "validity_start",
        "validity_end",
        "type",
        "issue_number",
        "prefix",
        "policy_number",
        "ad_certificate_id",
        "issued",
        "date_issue",
        "bill_name",
        "bill_dni",
        "pre_printed",
        "pre_printed_number",
        "term",
        "type_term",
        "pledged_number",
        "pledged",
        "payment_method",
        "period",
        "case_number",
        "premium",
        "annual_premium",
        "total_premium",
        "ad_retailer_city_agency_id",
        "currency",
    ];
    
    protected $appends = [
        "questions",
        "complet_signature",
        "created_date",
        "days_from_creation",
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "ad_user_id", "id");
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class, "ad_plan_id", "id");
    }


    public function details()
    {
        return $this->hasMany(Detail::class, "op_vd_header_id", "id");
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'op_vd_header_id', 'id');
    }
    
    public function cancellation()
    {
        return $this->hasOne(Cancellation::class, "op_vd_header_id", "id");
    }


    public function retailerProduct()
    {
        return $this->belongsTo(RetailerProduct::class, "ad_retailer_product_id", "id");
    }
    
    public function retailerCityAgency()
    {
        return $this->belongsTo(RetailerCityAgency::class, "ad_retailer_city_agency_id", "id");
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, "ad_certificate_id", "id");
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
        return Carbon::createFromTimestamp(strtotime($this->created_at))->format("d/m/Y H:i a");
    }
    
    public function getDaysFromCreationAttribute()
    {
        $date_now    = Carbon::now();
        $date_create = Carbon::createFromTimestamp(strtotime($this->created_at));

        return $date_now->diffInDays($date_create);
    }
    
    public function getCompletSignatureAttribute()
    {
        foreach ($this->details()->get() as $key => $detail) {
            
            if($detail->signature == "")
                return false;
        }
        return true;
    }

    public function getPolicyEndAttribute()
    {
        $date = date('Y-m-d');
        /*if($this->type_term == 'D')
            $date = carbon::parse($this->date_issue)->addDays($this->term);

        if($this->type_term == 'M')*/
            $date = carbon::parse($this->date_issue)->addMonths($this->term);

        /*if($this->type_term == 'W')
            $date = carbon::parse($this->date_issue)->addWeeks($this->term);

        if($this->type_term == 'Y')
            $date = carbon::parse($this->date_issue)->addYears($this->term);*/

        return $date;
    }
}