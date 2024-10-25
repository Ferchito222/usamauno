<?php

namespace Sibas\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\SettingAdmRepository;
use Sibas\Repositories\Report\ReportCollectionRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class ReportCollectionController extends Controller
{

    protected static $types = [
        'COLL'  => 'Cobranzas',
    ];

    protected $title = '';

    /**
     * @var ReportCollectionRepository
     */
    private $repository;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;
    
    /**
     * @var SettingAdmRepository
     */
    protected $settingAdmRepository;


    public function __construct(
        ReportCollectionRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        SettingAdmRepository $settingAdmRepository
    ) {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);

        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->settingAdmRepository      = $settingAdmRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Guard       $auth
     * @param Request     $request
     * @param string      $code
     * @param string      $rp_id
     * @param string      $type
     * @param string|null $format
     *
     * @return \Illuminate\Http\Response
     */
    public function master(Guard $auth, Request $request, $code, $rp_id, $type, $format = null)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $title           = $retailerProduct->companyProduct->product->name;
            $view            = 'report.master-collection';
            
            if (view()->exists($view) && key_exists($type, self::$types)) {

                $collections = $this->repository->getDataReport($request, $code, $type, $format);
                
                $user    = $auth->user();
                $data    = $this->repository->data($user, $code);

                if ( ! is_null($collections)) {
                    if ($format === 'xls') {
                        $view_xls = 'report.rows.rows-coll-' . $code;

                        if (view()->exists($view_xls)) {
                            $this->title = self::$types[ $type ];
                            return $this->repository->exportExcelCollection($collections, $view_xls, $this->title, $type, $format);
                        }
                    }

                    if ($request->has('_token')) {
                        $request->flash();
                    }

                    $data_report = [
                        'code'     => $code,
                        'rp_id'    => $rp_id,
                        'data'     => $data,
                        'collections'  => $collections,
                        'title'    => $title,
                        'type'     => $type,
                        'format'   => $format,
                        'user'     => $user,
                        'retailerProduct'     => $retailerProduct,
                    ];

                    return view($view, $data_report);
                }
            }
        }
        
        return redirect()->route('home');
    }


    /**
     * @param Guard $auth
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function graphic(Guard $auth)
    {
        $user  = $auth->user();
        $data  = $this->repository->data($user);
        $types = $this->repository->getTypes();

        return view('report.graphic', [
            'data'  => $data,
            'types' => $types,
        ]);
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dataGraphic(Request $request)
    {
        if (request()->ajax()) {
            $res  = $this->repository->getDataGraphic($request);

            return response()->json([
                'res' => $res,
            ]);
        }

        return redirect()->back();
    }
}
