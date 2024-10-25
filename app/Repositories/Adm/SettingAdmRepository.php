<?php

namespace Sibas\Repositories\Adm;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sibas\Entities\Adm\Setting;
use Sibas\Repositories\BaseRepository;


class SettingAdmRepository extends BaseRepository
{

    public function getSettings()
    {
        $user = auth()->user();

        $settings = Setting::with([ 'logs.user' ])
            ->orderBy('model', 'asc')
            ->orderBy('codename', 'asc');

        if ($user->type->code === 'OPT') {
            $settings->whereIn('model', [ 'password' ]);
        }

        $settings = $settings->get();

        return $settings;
    }


    /**
     * @param string $model
     * @param string $codename
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSettingsByModel($model, $codename = '')
    {
        $settings = Setting::with([
            'logs' => function ($q) {
                $q->latest();
            },
        ])
            ->has('logs')
            ->where('model', $model);

        if (! empty($codename)) {
            $settings->where('codename', $codename);
        }

        $settings = $settings->get();

        return $settings;
    }


    /**
     * @param string $id
     *
     * @return bool
     */
    public function getSettingsById($id)
    {
        $this->model = Setting::with([
            'logs' => function ($q) {
                $q->latest();
            },
        ])
            ->has('logs')
            ->where('id', $id)
            ->first();

        if ($this->model instanceof Setting) {
            return true;
        }

        return false;
    }


    /**
     * @param Request $request
     * @param boolean $edit
     *
     * @return bool
     */
    public function storeSettingLog(Request $request, $edit)
    {
        try {
            if (! $edit) {
                $id = Setting::max('id');

                $this->model = Setting::create([
                    'id'       => ( $id + 1 ),
                    'name'     => $request->get('name'),
                    'codename' => $request->get('codename'),
                    'model'    => $request->get('model'),
                ]);
            }

            $this->model->logs()->create([
                'id'             => null,
                'value'          => $request->get('value'),
                'action_time'    => ( $this->model->model !== 'purge' ? $this->carbon->now() : '' ),
                'change_message' => $request->get('change_message'),
                'ad_user_id'     => $request->user()->id,
            ]);

            $this->model = $this->model->fresh([ 'logs' ]);

            return true;
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }
}