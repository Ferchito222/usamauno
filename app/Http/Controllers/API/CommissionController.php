<?php

namespace Sibas\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\API\CommissionRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class CommissionController extends Controller
{
    /**
     * @var CommissionRepository
     */
    private $repository;
    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;


    public function __construct(
        CommissionRepository $repository, RetailerProductRepository $retailerProductRepository
    )
    {
        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;

        $this->repository->setCampaigns();
    }

    /**
     * Commissions
     *
     * @param string $code
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function panel($code)
    {
        if ($this->retailerProductRepository->getRetailerProductByCode($code)) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $date            = new Carbon();

            return view('extra.commissions.panel', [
                'retailerProduct' => $retailerProduct,
                'date'            => $date,
                'campaigns'       => $this->repository->getCampaigns(),
            ]);
        }

        return view('errors.503', [ 'message' => 'El Producto no existe.' ]);
    }

    /**
     * Report
     *
     * @param Request $request
     * @param string  $code
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report(Request $request, $code)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $title   = 'Comisiones_:type_:query';
        $headers = collect();
        $type    = $request->get('type');
        $rules   = [
            'type' => 'required|in:' . join(',', array_keys(config('base.commission_types'))),
        ];

        if (! empty($type)) {
            switch ($type) {
                case 'daily':
                    $rules[ 'day' ] = 'required|date';
                    $title          = str_replace([ ':type', ':query' ], [ 'Diarias', $request->get('day') ], $title);
                    break;
                case 'monthly':
                    $rules[ 'month' ]        = 'required|between:1,12';
                    $rules[ 'monthly_year' ] = 'required|digits:4|integer|min:2015|max:' . date('Y');
                    $title                   = str_replace([ ':type', ':query' ],
                        [ 'Mensuales', $request->get('month') . '_de_' . $request->get('monthly_year') ], $title);
                    break;
                case 'campaign':
                    $rules[ 'campaign' ]      = 'required|digits:1|integer|min:1|max:4';
                    $rules[ 'campaign_year' ] = 'required|digits:4|integer|min:2015|max:' . date('Y');
                    $title                    = str_replace([ ':type', ':query' ],
                        [ 'Campaña', $request->get('campaign') . '_de_' . $request->get('campaign_year') ], $title);
                    break;
            }
        }

        $this->validate($request, $rules);

        if ($this->retailerProductRepository->getRetailerProductByCode($code)) {
            $headers = $this->repository->getDataCommissions($request, $code, $type);

            if ($headers->count()) {
                if ($request->has('export')) {
                    \Debugbar::disable();

                    $result = view('extra.commissions.' . $code . '.' . $type, [
                        'headers'  => $headers,
                        'export'   => true,
                        'info'     => $this->repository->getInfo(),
                    ])->render();

                    header("Content-type: application/vnd.ms-excel charset=utf-8");
                    header("Content-Disposition: attachment; filename=" . $title . ".xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");

                    return $result;
                }

                return view('extra.commissions.' . $code . '.' . $type, [
                    'headers'  => $headers,
                    'info'     => $this->repository->getInfo(),
                ]);
            }
        }

        return view('errors.503', [ 'message' => 'El reporte no puede ser generado.' ]);
    }
}
