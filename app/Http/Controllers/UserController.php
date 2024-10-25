<?php

namespace Sibas\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Entities\RetailerCity;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\UserRepository;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    private $repository;


    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function options(Guard $auth)
    {
        $user         = $auth->user();
        $agencies     = [];
        $retailerCity = $user->retailerUser->retailer->retailerCities()->with('agencies')->where('ad_city_id',
            $user->ad_city_id)->first();

        if ($retailerCity instanceof RetailerCity) {
            $agencies = $retailerCity->agencies;
        }

        // dd($agencies->toJson());

        return view('user.options', [
            'user'     => $user,
            'agencies' => $agencies,
        ]);
    }


    /**
     * @param Request $request
     * @param string  $option
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function optionsUpdate(Request $request, $option)
    {
        if ($request->ajax()) {
            $user = $request->user();

            $rules   = [];
            $message = '';

            switch ($option) {
                case 'pwd':
                    $rules = [
                        'old_password'              => 'required|old_password:' . $user->password,
                        'password_new'              => 'required',
                        'password_new_confirmation' => 'required|same:password_new',
                    ];

                    $message = 'La contraseña se actualizó con éxito.';
                    break;
                case 'agy':
                    $rules = [
                        'agency' => 'required|exists:ad_agencies,id',
                    ];

                    $message = 'La agencia se actualizó correctamente.';
                    break;
                case 'tel':
                    $rules = [
                        'phone_number' => 'required|numeric|digits_between:7,10',
                    ];

                    $message = 'El teléfono de agencia se actualizó correctamente.';
                    break;
            }

            if (count($rules) > 0) {
                $this->validate($request, $rules);

                if ($this->repository->updateOption($user, $request, $option)) {
                    return response()->json([
                        'message' => $message,
                        'option'  => $option,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Unauthorized action.',
                'option'  => $option,
            ], 401);
        }

        return redirect()->back();
    }
}
