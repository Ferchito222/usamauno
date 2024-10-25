<?php

namespace Sibas\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Monolog\Logger;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Au\VehicleMakeRepository;
use Sibas\Repositories\Au\VehicleTypeRepository;
use Sibas\Repositories\DataPolicyRepository;
use Sibas\Repositories\LogRepository;
use Sibas\Repositories\Retailer\PlanRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\UserRepository;

class DataPolicyController extends Controller
{

    /**
     * @var DataPolicyRepository
     */
    private $repository;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;

    /**
     * @var Guard
     */
    private $auth;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PolicyRepository
     */
    private $policyRepository;

    /**
     * @var PlanRepository
     */
    private $planRepository;

    /**
     * @var VehicleTypeRepository
     */
    private $vehicleTypeRepository;

    /**
     * @var VehicleMakeRepository
     */
    private $vehicleMakeRepository;

    /**
     * @var LogRepository
     */
    private $log;


    public function __construct(
        Guard $auth,
        LogRepository $log,
        DataPolicyRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        UserRepository $userRepository,
        PolicyRepository $policyRepository,
        PlanRepository $planRepository,
        VehicleTypeRepository $vehicleTypeRepository,
        VehicleMakeRepository $vehicleMakeRepository
    ) {
        $this->auth                      = $auth;
        $this->log                       = $log;
        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->userRepository            = $userRepository;
        $this->policyRepository          = $policyRepository;
        $this->planRepository            = $planRepository;
        $this->vehicleTypeRepository     = $vehicleTypeRepository;
        $this->vehicleMakeRepository     = $vehicleMakeRepository;
    }


    use DataTrait;


    private function getDataPolicy()
    {
        $retailer = $this->auth->user()->retailerUser->retailer;

        return [
            'retailerProducts' => $this->retailerProductRepository->getRetailerProductByUser($this->auth->user()),
            'users'            => $this->userRepository->getUsersByRetailer($retailer->id),
        ];
    }


    /**
     * Search
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function search(Request $request)
    {
        $data    = $this->getDataPolicy();
        $headers = collect();
        $code    = '';

        if ($request->has('_token')) {
            $this->validate($request, [
                'product' => 'required',
            ]);

            if ($this->retailerProductRepository->getRetailerProductById(decode($request->get('product')))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $headers         = $this->repository->getHeaderList($request, $retailerProduct, $code);

                $request->flash();
            }
        }

        return view('data-policy.lists', [
            'data'    => $data,
            'headers' => $headers,
            'rp_id'   => $request->get('product'),
        ])->with([ 'code' => $code ]);
    }


    /**
     * Edit Policy
     *
     * @param string $code
     * @param string $rp
     * @param string $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editPolicy($code, $rp, $id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp))) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($this->repository->getPolicyResource($retailerProduct, $id)) {
                $header        = $this->repository->getModel();
                $details       = collect();
                $vehicles      = collect();
                $risks         = collect();
                $beneficiaries = collect();

                $data                       = $this->getData($rp);
                $data['coverages']          = $this->retailerProductRepository->getCoverageByProduct(decode($rp));
                $data['currencies']         = $this->dataRepository->getCurrency();
                
                $data['credit_products']    = $this->retailerProductRepository->getCreditProductByProduct(decode($rp));
                $data['movement_types']     = $this->dataRepository->getMovementType();
                $data['policies']           = $this->policyRepository->getPolicyForIssuance(decode($rp));
                $data['payment_methods']    = $this->dataRepository->getPaymentMethod();
                $data['periods']            = $this->dataRepository->getPeriod();
                $data['plans']              = $this->planRepository->getPlanByProduct();
                $data['vehicle_types']      = $this->vehicleTypeRepository->vehicleTypes();
                $data['vehicle_categories'] = $this->dataRepository->getVehicleCategory();
                $data['vehicle_uses']       = $this->dataRepository->getVehicleUse();
                $data['vehicle_makes']      = $this->vehicleMakeRepository->vehicleMakes();
                $data['vehicle_models']     = $this->vehicleMakeRepository->vehicleModels();
                $data['capital']            = $this->dataRepository->getCapital();
                $data['property_types']     = $this->dataRepository->getPropertyTypes();
                $data['property_uses']      = $this->dataRepository->getPropertyUses();
                $data['cities']             = $this->cityRepository->getCitiesByType();
                $data['property_states']    = $this->dataRepository->getPropertyStates();
                
                switch ($code) {
                    case 'de':
                        $details = $header->details;

                        foreach ($header->details as $detail) {
                            if($detail->beneficiary != null)
                                $beneficiaries->push($detail->beneficiary);
                        }
                        break;
                    case 'au':
                    case 'td':
                    case 'ema':
                    case 'rma':
                        $detail         = collect();
                        $detail->client = $header->client;
                        
                        $details->push($detail);
                        $risks = $header->details;
                        break;
                }

                return view('data-policy.edit', [
                    'code'            => $code,
                    'rp'              => $rp,
                    'header'          => $header,
                    'details'         => $details,
                    'risks'           => $risks,
                    'beneficiaries'   => $beneficiaries,
                    'data'            => $data,
                    'retailerProduct' => $retailerProduct,
                ]);
            }
        }

        return redirect()->back();
    }


    /**
     * Update Policy
     *
     * @param Request $request
     * @param string  $code
     * @param string  $rp
     * @param string  $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function updatePolicy(Request $request, $code, $rp, $id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $token           = $this->repository->updatePolicy($request, $retailerProduct, $id);
            $queries         = $this->repository->getQueries();
            $logId           = 0;

            if (count($queries) > 0) {
                $level = $token ? Logger::INFO : Logger::ERROR;
                $user  = $request->user();

                $this->log->setLog('Manual editing policy', $queries, $user, $level);
                $logId = $this->log->getId();
            }

            return redirect()->route('dp.policy.result', [
                'code'  => $code,
                'rp'    => $rp,
                'id'    => $id,
                'logId' => encode($logId),
            ]);
        }

        return redirect()->back()->withInput();
    }


    /**
     * @param string $code
     * @param string $rp
     * @param string $id
     * @param string $logId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|DataPolicyController
     */
    public function result($code, $rp, $id, $logId)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp))) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($this->repository->getPolicyResource($retailerProduct, $id) && $this->log->getLog(decode($logId))) {
                $header = $this->repository->getModel();
                $log    = $this->log->getDocument();

                ini_set('xdebug.var_display_max_depth', 1024);
                ini_set('xdebug.var_display_max_children', 1024);
                ini_set('xdebug.var_display_max_data', 1024);

                return view('data-policy.result', [
                    'code'            => $code,
                    'header'          => $header,
                    'retailerProduct' => $retailerProduct,
                    'log'             => $log,
                ]);
            }
        }

        return redirect()->route('dp.search');
    }
}
