<?php

namespace Sibas\Repositories\Adm;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Mc\Certificate;
use Sibas\Entities\Mc\McQuestion;
use Sibas\Entities\Mc\Questionnaire;
use Sibas\Repositories\BaseRepository;

class McAdmRepository extends BaseRepository
{

    public function getMcs($id = null)
    {
        $mcs = Certificate::with([
            'questionnaires',
            'certificateQuestionnaires.questions'
        ])->orderBy('name', 'asc');

        if (! is_null($id)) {
            $this->model = $mcs->where('id', $id)->first();

            return ($this->model instanceof Certificate ? true : false);
        }

        $mcs = $mcs->get();

        return $mcs;
    }


    public function getQuestionnaires()
    {
        $qt = Questionnaire::orderBy('title', 'asc')->get();

        return $qt;
    }


    public function getQuestions()
    {
        $qs = McQuestion::orderBy('question', 'asc')->get();

        return $qs;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function editQuestionnaires($request)
    {
        $questionnaires = $request->get('questionnaires');
        $data           = [];
        $date           = $this->carbon->now();

        foreach ($questionnaires as $key => $qt) {
            if (is_array($qt)) {
                if ($qt['selected']) {
                    $data[$key] = [
                        'active'     => $qt['enabled'],
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }
        }

        try {
            $this->model->questionnaires()->detach();

            if (count($data) > 0) {
                $this->model->questionnaires()->attach($data);
            }

            $this->model = $this->model->fresh([
                'questionnaires',
                'certificateQuestionnaires.questions'
            ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}