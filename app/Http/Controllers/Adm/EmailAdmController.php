<?php

namespace Sibas\Http\Controllers\Adm;

use Illuminate\Http\Request;
use Sibas\Entities\RetailerProduct;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\EmailAdmRepository;

class EmailAdmController extends Controller
{

    /**
     * @var EmailAdmRepository
     */
    protected $repository;


    public function __construct(EmailAdmRepository $repository)
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
        return view('adm.user.email-list');
    }


    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getEmails()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $api             = $this->repository->getEmails();
            $rps             = RetailerProduct::with('companyProduct.product')->where('active', true)->get();
            $products        = $rps->pluck('companyProduct.product.name', 'id');
            $api['products'] = $products;

            return response()->json(compact('api'));
        }

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emailUpdate(Request $request)
    {
        \Debugbar::disable();

        $id      = $request->get('id');
        $email   = null;
        $success = false;
        $rules   = [
            'email'    => 'required|email|unique:ad_emails,email',
            'name'     => 'required',
            'products' => 'array|min:1',
        ];

        if ($id) {
            $rules['email'] .= ',' . $id;
        }

        $this->validate($request, $rules);

        if ($request->ajax()) {
            if (! $id && $this->repository->emailStore($request)) {
                $email   = $this->repository->getApiModel();
                $success = true;
            } elseif ($this->repository->getEmails($id)) {
                $email = $this->repository->getModel();

                if ($this->repository->emailUpdate($email, $request) && $this->repository->getEmails($id)) {
                    $email   = $this->repository->getApiModel();
                    $success = true;
                }
            }

            if ($success) {
                return response()->json([
                    'email'   => $email,
                    'success' => true,
                    'message' => 'La información fue registrada correctamente.',
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * TurnOff the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function emailTurnOff(Request $request)
    {
        \Debugbar::disable();

        if ($request->ajax()) {
            $id       = $request->get('id');
            $turn_off = ! filter_var($request->get('turn_off'), FILTER_VALIDATE_BOOLEAN);

            if ($this->repository->emailTurnOff($id, $turn_off)) {
                return response()->json([
                    'turnOff' => (int) $turn_off,
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     *
     */
    public function emailDelete()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $id = request()->get('id');

            if ($this->repository->emailDelete($id)) {
                return response()->json([]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }
}
