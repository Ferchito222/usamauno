<?php

namespace Sibas\Http\Controllers\Adm;

use Illuminate\Http\Request;

use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\SettingAdmRepository;

class SettingAdmController extends Controller
{

    /**
     * @var SettingAdmRepository
     */
    protected $repository;


    public function __construct(SettingAdmRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = auth()->user()->type->code;

        return view('adm.setting.list', compact('user_type'));
    }


    public function getSettings()
    {
        if (request()->ajax()) {
            $settings  = $this->repository->getSettings();
            $user_type = auth()->user()->type->code;

            return response()->json([
                'settings'  => $settings,
                'user_type' => $user_type,
            ]);
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param $model
     *
     * @return \Illuminate\Http\Response
     */
    public function getSetting($model)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $settings = $this->repository->getSettingsByModel($model);

            if ($settings->count() > 0) {
                return response()->json([
                    'settings' => $settings,
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            $edit  = $request->get('edit') ? true : false;
            $rules = [ 'value' => 'required' ];

            if (! $edit) {
                $rules['name']     = 'required';
                $rules['model']    = 'required|alpha_dash';
                $rules['codename'] = 'required|alpha_dash';
            }

            $this->validate($request, $rules);

            if ($edit) {
                if (! $this->repository->getSettingsById($request->get('id'))) {
                    goto Error;
                }
            }

            if ($this->repository->storeSettingLog($request, $edit)) {
                $setting = $this->repository->getModel();

                return response()->json([
                    'setting' => $setting,
                    'success' => true,
                    'message' => 'La configuración fue actualizada correctamente.',
                ]);
            }

            Error:
            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }
}
