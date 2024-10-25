<?php

namespace Sibas\Repositories\De;

use Illuminate\Http\Request;
use Sibas\Entities\Mc\Answer;
use Sibas\Entities\Mc\Certificate;
use Sibas\Repositories\BaseRepository;

class MedicalCertificateRepository extends BaseRepository
{

    /**
     * Get Medical Certificate by Product
     *
     * @param $rp_id
     *
     * @return bool
     */
    public function getMedicalCertificateByProduct($rp_id)
    {
        $this->model = Certificate::with([
            'certificateQuestionnaires.questionnaire',
            'certificateQuestionnaires.questions'
        ])->whereHas('certificateQuestionnaires', function ($query) {
            $query->where('active', true);
        })->where('ad_retailer_product_id', $rp_id)->where('active', true)->first();

        if ($this->model instanceof Certificate) {
            return true;
        }

        return false;
    }


    /**
     * Get Medical Certificate by Id
     *
     * @param $id
     *
     * @return bool
     */
    public function getMedicalCertificateById($id)
    {
        $this->model = Certificate::with([
            'certificateQuestionnaires.questionnaire',
            'certificateQuestionnaires.questions',
        ])->where('id', $id)->first();

        if ($this->model instanceof Certificate) {
            return true;
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function storeMedicalCertificate(Request $request)
    {
        $user       = $request->user();
        $this->data = $request->all();

        $number = $this->getMedicalCertificateNumber();
        $date = $this->carbon->createFromTimestamp(strtotime(str_replace('/', '-', $this->data['date_attention'])));
        $this->model = new Answer([
            'id'                         => date('U'),
            'medical_certificate_number' => $number,
            'ad_user_id'                 => $user->id,
            'mc_certificate_id'          => decode($this->data['mcid']),
            'ad_care_center_id'          => $this->data['care_center']['id'],
            'contact_person'             => $this->data['contact_person'],
            'date_attention'             => $date->format('Y-m-d h:i:s'),
            'response'                   => json_encode($this->data['answers']),
        ]);

        return $this->saveModel();
    }


    /**
     * Get Number of Medical Certificate
     * @return int
     */
    protected function getMedicalCertificateNumber()
    {
        $number = Answer::max('medical_certificate_number');

        return is_null($number) ? 1 : $number + 1;
    }
}