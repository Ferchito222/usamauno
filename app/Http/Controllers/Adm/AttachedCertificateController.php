<?php

namespace Sibas\Http\Controllers\Adm;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\AttachedCertificateRepository;

class AttachedCertificateController extends Controller
{
    /**
     * @var AttachedCertificateRepository
     */
    protected $repository;

    public function __construct(AttachedCertificateRepository $repository)
    {
        ini_set('memory_limit', -1);
        
        $this->repository = $repository;
    }

    public function index()
    {
        return view('adm.operation.attached-certificate');
    }


    public function lists()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $api = $this->repository->getApi();

            return response()->json(compact('api'));
        }

        return redirect()->back();
    }


    public function delete($id)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            if ($this->repository->getHeaderById(decode($id)) && $this->repository->deleteCertificate()) {
                return response()->json([
                    'delete' => true,
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }
}
