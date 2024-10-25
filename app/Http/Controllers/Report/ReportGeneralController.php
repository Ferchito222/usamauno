<?php

namespace Sibas\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\SettingAdmRepository;
use Sibas\Repositories\LogRepository;
use Sibas\Repositories\Report\ReportGeneralRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class ReportGeneralController extends Controller
{

    protected static $types = [
        'GEN' => 'General',
        'ISS' => 'Pólizas Emitidas',
        'QTE' => 'Solicitudes',
        'BW'  => 'Quincenal',
        'OP'  => 'Operaciones bajo Línea',
    ];

    protected $title = '';

    /**
     * @var ReportGeneralRepository
     */
    private $repository;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;
    /**
     * @var LogRepository
     */
    protected $logRepository;
    /**
     * @var SettingAdmRepository
     */
    protected $settingAdmRepository;


    public function __construct(
        ReportGeneralRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        LogRepository $logRepository,
        SettingAdmRepository $settingAdmRepository
    ) {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);

        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->logRepository             = $logRepository;
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
            $view            = 'report.master';

            if (view()->exists($view) && key_exists($type, self::$types)) {
                DB::enableQueryLog();

                $headers = $this->repository->getDataReport($request, $code, $type, $format);
                $user    = $auth->user();
                $data    = $this->repository->data($user, $code);

                if ( ! is_null($headers)) {
                    if ($format === 'xls') {
                        $view_xls = $type === 'OP' ? 'report.rows.rows-cl' : 'report.rows.rows-' . $code;

                        if (view()->exists($view_xls)) {
                            $this->title = self::$types[ $type ];
                            return $this->repository->exportExcel($headers, $view_xls, $this->title, $type, $format);
                        }
                    }

                    if ($request->has('_token')) {
                        $request->flash();
                    }

                    /*
                     * Log - report
                     */
                    if ($headers->count() > 0) {
                        $queries = DB::getQueryLog();
                        $extra   = [
                            'user'        => $user->username,
                            'type_report' => self::$types[$type],
                            'date_report' => Carbon::now(),
                        ];

                        $this->logRepository->setLog(strtoupper($code) . ' report', $queries, $user, Logger::INFO,
                            $extra);
                    }

                    DB::disableQueryLog();
                    /*
                     * Log - report
                     */

                    $settings = $this->settingAdmRepository->getSettingsByModel('host_files');
                    $settings = $settings->pluck('logs.0.value', 'codename');
                    $settings['host_secure'] = filter_var($settings['host_secure'], FILTER_VALIDATE_BOOLEAN);

                    $data_report = [
                        'code'     => $code,
                        'rp_id'    => $rp_id,
                        'data'     => $data,
                        'headers'  => $headers,
                        'title'    => $title,
                        'type'     => $type,
                        'format'   => $format,
                        'settings' => $settings,
                        'user'     => $user,
                    ];

                    if ($type === 'OP') {
                        $data_report['credit_lines'] = $headers;
                        unset($data_report['headers']);
                    }

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
