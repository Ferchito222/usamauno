<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;
use Sibas\Entities\Vi\Detail as DetailVi;
use Sibas\Entities\Vm\Detail as DetailVm;
use Sibas\Entities\Vp\Detail as DetailVp;
use Sibas\Entities\Vs\Detail as DetailVs;
use Sibas\Entities\Vc\Detail as DetailVc;
use Sibas\Entities\Pc\Detail as DetailPc;
use Sibas\Entities\Sc\Detail as DetailSc;
use Sibas\Entities\Scp\Detail as DetailScp;
use Sibas\Entities\Ap\Detail as DetailAp;
use Sibas\Entities\Vd\Detail as DetailVd;
use Sibas\Entities\In\Detail as DetailIn;
use Sibas\Entities\Sep\Detail as DetailSep;
use Sibas\Entities\Pr\Detail as DetailPr;

class Referred extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ad_referred';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'type',
        'first_name',
        'last_name',
        'mother_last_name',
        'phone_number_mobile',
        'regional',
        'origin',
        'email',
        'insurance',
        'dni',
        'assigned',
        'paid_points',
    ];

    protected $appends = [
        'full_name',
    ];

    public function detailVi()
    {
        return $this->hasMany(DetailVi::class, 'reference', 'id');
    }

    // public function detailVm()
    // {
    //     return $this->hasMany(DetailVm::class, 'reference', 'id');
    // }

    // public function detailVp()
    // {
    //     return $this->hasMany(DetailVp::class, 'reference', 'id');
    // }

    // public function detailVs()
    // {
    //     return $this->hasMany(DetailVs::class, 'reference', 'id');
    // }

    // public function detailVc()
    // {
    //     return $this->hasMany(DetailVc::class, 'reference', 'id');
    // }

    // public function detailPc()
    // {
    //     return $this->hasMany(DetailPc::class, 'reference', 'id');
    // }

    // public function detailSc()
    // {
    //     return $this->hasMany(DetailSc::class, 'reference', 'id');
    // }

    // public function detailScp()
    // {
    //     return $this->hasMany(DetailScp::class, 'reference', 'id');
    // }

    // public function detailAp()
    // {
    //     return $this->hasMany(DetailAp::class, 'reference', 'id');
    // }

    // public function detailVd()
    // {
    //     return $this->hasMany(DetailVd::class, 'reference', 'id');
    // }

    // public function detailIn()
    // {
    //     return $this->hasMany(DetailIn::class, 'reference', 'id');
    // }

    // public function detailSep()
    // {
    //     return $this->hasMany(DetailSep::class, 'reference', 'id');
    // }

    // public function detailPr()
    // {
    //     return $this->hasMany(DetailPr::class, 'reference', 'id');
    // }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->mother_last_name;
    }
}
