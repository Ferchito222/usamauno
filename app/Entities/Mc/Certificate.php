<?php

namespace Sibas\Entities\Mc;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    protected $table = 'mc_certificates';

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $hidden = [
        'ad_retailer_product_id',
        'created_at',
        'updated_at',
        'pivot'
    ];


    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class, 'mc_certificate_questionnaires', 'mc_certificate_id', 'mc_questionnaire_id');
    }


    public function certificateQuestionnaires()
    {
        return $this->hasMany(CertificateQuestionnaire::class, 'mc_certificate_id', 'id');
    }
}
