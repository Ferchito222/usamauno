<?php

namespace Sibas\Http\Controllers\Adm;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\SettingAdmRepository;
use Sibas\Repositories\Adm\UserAdmRepository;

class UserAdmController extends Controller
{
    /**
     * @var UserAdmRepository
     */
    protected $repository;
    /**
     * @var SettingAdmRepository
     */
    protected $settingAdmRepository;
    /**
     * @var array
     */
    protected $settings;


    public function __construct(UserAdmRepository $repository, SettingAdmRepository $settingAdmRepository)
    {
        $this->repository           = $repository;
        $this->settingAdmRepository = $settingAdmRepository;

        $this->settings = [
            'min_char' => 0,
            'max_char' => 0,
        ];

        $this->getSettings();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adm.user.list');
    }


    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getUsers()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $auth      = auth()->user();
            $api       = $this->repository->getUsers();
            $user_type = auth()->user()->type->code;

            return response()->json([
                'api'       => $api,
                'settings'  => $this->settings,
                'data'      => $this->repository->getDataUser($auth),
                'user_type' => $user_type,
            ]);
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            $user_type = auth()->user()->type->code;
            $rules     = $this->getRules($request, $user_type);

            $this->validate($request, $rules);

            if ($user_type === 'OPT' && strpos($request->get('email'), 'bnb.com.bo') === false) {
                return response()->json([
                    'warning' => true,
                    'message' => 'El dominio del correo electrónico debe ser "@bnb.com.bo"',
                ], 402);
            }

            if ($this->repository->userCreate($request, $user_type)) {
                $user = $this->repository->getUser();

                return response()->json([
                    'user'    => $user,
                    'success' => true,
                    'message' => 'El usuario fue creado con éxito.',
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
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
            $user_type = auth()->user()->type->code;
            $rules     = $this->getRules($request, $user_type);

            $this->validate($request, $rules);

            if ($user_type === 'OPT' && strpos($request->get('email'), 'bnb.com.bo') === false) {
                return response()->json([
                    'warning' => true,
                    'message' => 'El dominio del correo electrónico debe ser "@bnb.com.bo"',
                ], 402);
            }

            if ($this->repository->getUserById(decode($request->get('id'))) && $this->repository->userEdit($request)) {
                $user = $this->repository->getUser();

                return response()->json([
                    'user'    => $user,
                    'success' => true,
                    'message' => 'El usuario fue actualizado con éxito.',
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param string  $user_type
     *
     * @return array
     */
    protected function getRules(Request $request, $user_type)
    {
        $min_char = $this->settings['min_char'];
        $max_char = $this->settings['max_char'];
        $edit     = $request->has('id') ? true : false;

        $rules = [
            'retailer'    => 'required|exists:ad_retailers,id',
            'products'    => 'required|array|min:1',
            'userType'    => 'required|exists:ad_user_types,id',
            'profile'     => 'required|exists:ad_profiles,id',
            'permissions' => 'array|min:1',
            'username'    => 'required|alpha|unique:ad_users,username',
            'email'       => 'required|email|unique:ad_users,email',
            'fullName'    => 'required|alpha_space',
            'city'        => 'required|exists:ad_cities,id',
            'agency'      => 'exists:ad_agencies,id',
            'phoneNumber' => 'numeric|digits_between:7,10',
            'position'    => 'ands_full',
        ];

        if ($request->get('profile') == 4) {
            $rules['companies'] = 'required|exists:ad_companies,id';
        }

        if ($edit) {
            $id = decode($request->get('id'));

            $rules['username'] = 'required|alpha|unique:ad_users,username,' . $id;
            $rules['email']    = 'required|email|unique:ad_users,email,' . $id;
        }

        if (! $edit) {
            $rules['password']              = 'required|min:' . $min_char . '|max:' . $max_char . '|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        return $rules;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordEdit()
    {
        return view('adm.user.password');
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function passwordUpdate(Request $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            if ($this->repository->getUserById(decode($request->get('id')))) {
                $user = $this->repository->getModel();
                $min_char = $this->settings['min_char'];
                $max_char = $this->settings['max_char'];

                $rules = [
                    'password'              => 'required|min:' . $min_char . '|max:' . $max_char . '|confirmed',
                    'password_confirmation' => 'required',
                ];

                if (! $request->has('reset')) {
                    $rules['oldPassword'] = 'required|oldPassword:' . $user->password;
                    $rules['password']    = 'required|min:' . $min_char . '|max:' . $max_char . '|different:oldPassword|confirmed';
                }

                $this->validate($request, $rules);

                if ($this->repository->passwordEdit($request)) {
                    return response()->json([
                        'success' => true,
                        'message' => 'La contraseña fue actualizada correctamente.',
                    ]);
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * Get Settings by password model
     */
    protected function getSettings()
    {
        $settings = $this->settingAdmRepository->getSettingsByModel('password');

        foreach ($settings as $setting) {
            if ($setting->codename === 'min_char') {
                $this->settings['min_char'] = (int) $setting->logs->first()->value;
            }

            if ($setting->codename === 'max_char') {
                $this->settings['max_char'] = (int) $setting->logs->first()->value;
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $active = ! filter_var(request()->get('active'), FILTER_VALIDATE_BOOLEAN);

            if ($this->repository->getUserById(decode($request->get('id'))) && $this->repository->userActivate($active)) {
                return response()->json([
                    'active' => $active,
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function importStart()
    {
        return view('adm.user.import');
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importComplete(Request $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            $this->validate($request, [
                'file' => 'required',
            ]);

            $message    = 'No se pudieron importar los datos.';
            $extensions = [ 'xls', 'xlsx', ];

            if ($request->hasFile('file')) {
                $file   = $request->file('file');
                $fields = [
                    'full_name' => 'nombre_completo',
                    'agency'    => 'nombre_agencia',
                    'email'     => 'correo_electrónico',
                    'username'  => 'nombre_usuario',
                ];

                $data = collect([]);

                if (in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                    Excel::load($file, function($reader) use (&$data) {
                        $results = $reader->toArray();

                        foreach ($results as $result) {
                            if (count($result) > 0) {
                                $data = collect($result);
                            }
                        }
                    });

                    $data = $data->groupBy(function ($item, $key) {
                        return (int) $item['codigo_sucursal'];
                    });

                    if ($this->repository->importUsers($data, $fields)) {
                        return response()->json([
                            'success' => true,
                            'message' => 'La importación se realizó con éxito.'
                        ]);
                    }
                } else {
                    $message = 'El archivo debe ser de tipo (' . implode(', ', $extensions) . ').';
                }
            }

            return response()->json([
                'warning' => true,
                'message' => $message,
            ], 402);
        }

        return redirect()->back();
    }


    /**
     *
     */
    public function export()
    {
        \Debugbar::disable();

        $users = $this->repository->getUsers(true);
        $view  = 'adm.user.export';

        Excel::create('Usuarios-' . date('Ymd'), function ($excel) use ($users, $view) {
            $excel->setTitle('Usuarios');
            $excel->setCreator('Abrenet')->setCompany('Abrenet');
            $excel->setDescription('Usuarios');

            $excel->sheet('Usuarios', function ($sheet) use ($users, $view) {
                $sheet->setPageMargin([
                    0.25,
                    0.25,
                    0.25,
                    0.30,
                ]);

                $sheet->loadView($view, [
                    'users' => $users,
                ]);

                $sheet->freezeFirstRow();
                $sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
        })->export('xls');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignmentWalletCreate()
    {
        return view('adm.user.assignment-wallet');
    }


    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getAssignmentWalletUsers()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $api = $this->repository->getUsersByAssignmentWallet();

            return response()->json(compact('api'));
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function assignmentWalletStore(Request $request)
    {
        \Debugbar::disable();

        $this->validate($request, [
            'userFrom'     => 'required',
            'userTo'       => 'required',
            'certificates' => 'required',
            'disabled'     => 'boolean',
        ]);

        if (request()->ajax()) {
            if ($this->repository->storeAssignmentWallet($request) ) {
                return response()->json([
                    'success' => true,
                    'message' => 'La cartera se traspasó correctamente.',
                ]);
            }

            return response()->json([
                'warning' => true,
                'message' => 'La cartera seleccionada no pudo ser traspasada',
            ], 402);
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function passwordTurnOff(Request $request)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $turn_off = filter_var(request()->get('turn_off'), FILTER_VALIDATE_BOOLEAN);

            if ($this->repository->getUserById(decode($request->get('id'))) && $this->repository->passwordTurnOff($turn_off)) {
                return response()->json([
                    'turn_off' => $turn_off,
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }
}
