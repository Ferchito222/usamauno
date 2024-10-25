<?php

namespace Sibas\Http\Controllers\Vi;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Vi\HeaderSpCreateFormRequest;
use Sibas\Repositories\Client\AccountRepository;
use Sibas\Repositories\Client\ActivityRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\De\DataRepository;
use Sibas\Repositories\De\DetailRepository as DetailDeRepository;
use Sibas\Repositories\De\HeaderRepository as HeaderDeRepository;
use Sibas\Repositories\Retailer\CityRepository;
use Sibas\Repositories\Retailer\PlanRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\Vi\DetailRepository;
use Sibas\Repositories\Vi\HeaderRepository;

class HeaderController extends Controller
{

    /**
     * @var HeaderRepository
     */
    protected $repository;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;

    /**
     * @var HeaderDeRepository
     */
    protected $headerDeRepository;

    /**
     * @var DetailDeRepository
     */
    protected $detailDeRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var PlanRepository
     */
    private $planRepository;

    /**
     * @var PolicyRepository
     */
    protected $policyRepository;

    /**
     * @var DataRepository
     */
    protected $dataRepository;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @var ActivityRepository
     */
    protected $activityRepository;


    public function __construct(
        HeaderRepository $repository,
        DetailRepository $detailRepository,
        HeaderDeRepository $headerDeRepository,
        DetailDeRepository $detailDeRepository,
        ClientRepository $clientRepository,
        RetailerProductRepository $retailerProductRepository,
        AccountRepository $accountRepository,
        PlanRepository $planRepository,
        PolicyRepository $policyRepository,
        DataRepository $dataRepository,
        CityRepository $cityRepository,
        ActivityRepository $activityRepository
    ) {
        $this->repository                = $repository;
        $this->detailRepository          = $detailRepository;
        $this->headerDeRepository        = $headerDeRepository;
        $this->detailDeRepository        = $detailDeRepository;
        $this->clientRepository          = $clientRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->accountRepository         = $accountRepository;
        $this->planRepository            = $planRepository;
        $this->policyRepository          = $policyRepository;
        $this->dataRepository            = $dataRepository;
        $this->cityRepository            = $cityRepository;
        $this->activityRepository        = $activityRepository;
    }


    public function getData()
    {
        return [
            'payment_methods' => $this->dataRepository->getPaymentMethod(),
            'periods'         => $this->dataRepository->getPeriod(),
            'civil_status'    => $this->dataRepository->getCivilStatus(),
            'document_type'   => $this->dataRepository->getDocumentType(),
            'gender'          => $this->dataRepository->getGender(),
            'cities'          => $this->cityRepository->getCitiesByType(),
            'activities'      => $this->activityRepository->getActivities(),
            'hands'           => $this->dataRepository->getHand(),
            'avenue_street'   => $this->dataRepository->getAvenueStreet(),
        ];
    }


    /**
     * Show the form for creating a new Sub Product.
     *
     * @param Guard $auth
     * @param       $rp_id
     * @param       $header_id
     * @param       $sp_id
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function createSubProduct(Guard $auth, $rp_id, $header_id, $sp_id)
    {
        $key = 'clients_' . $header_id;

        if (Cache::has($key)) {
            $clients = Cache::get($key);

            if ( ! is_null($clients)) {
                $clients   = json_decode($clients, true);
                $detail_id = array_shift($clients);

                if ( ! is_null($detail_id)) {
                    if ($this->headerDeRepository->getHeaderById(decode($header_id))) {
                        $header = $this->headerDeRepository->getModel();
                        $detail = $header->details()->where('id', decode($detail_id))->first();

                        if ($this->repository->getInsuredValue($auth->user(), $sp_id, $header, $detail->client)) {
                            $detail->insured_value = $this->repository->insured_value;
                        }

                        $data              = $this->getData();
                        $data['questions'] = $this->retailerProductRepository->getQuestionByProduct(decode($sp_id));
                        $data['plans']     = $this->planRepository->getPlanByProduct(decode($sp_id));

                        return view('vi.sp.create',
                            compact('rp_id', 'header_id', 'sp_id', 'data', 'header', 'detail'))->with([
                            'error_value' => $this->repository->error_value,
                            'amount_max'  => $this->repository->amount_max,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('de.issuance', [
            'rp_id'     => $rp_id,
            'header_id' => $header_id,
        ])->with(['error_header' => 'La Poliza de Vida no puede ser creada']);
    }


    public function storeSubProduct(HeaderSpCreateFormRequest $request, $rp_id, $header_id, $sp_id)
    {
        $beneficiaries = $request->get('beneficiaries');
        $participation = 0;

        foreach ($beneficiaries as $beneficiary) {
            $participation += $beneficiary['participation'];
        }

        if ($participation == 100) {
            $success_header = ['success_header' => 'El Sub-Producto fue asociado correctamente'];

            if ($this->headerDeRepository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($sp_id))) {
                $headerDe        = $this->headerDeRepository->getModel();
                $detailDe        = $headerDe->details()->where('id', decode($request->get('detail_id')))->first();
                $retailerProduct = $this->retailerProductRepository->getModel();

                $request['detail']   = $detailDe;
                $request['policies'] = $this->policyRepository->getPolicyByProduct(decode($sp_id));
                $request['plans']    = $this->planRepository->getPlansByProduct(decode($sp_id));

                if ($this->repository->storeSubProduct($request,
                        $retailerProduct) && $this->accountRepository->storeAccount($request)
                ) {
                    if ($this->repository->destroyClientCacheSP(decode($header_id),
                        decode($request->get('detail_id')))
                    ) {
                        return redirect()->route('de.vi.sp.create', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                            'sp_id'     => $sp_id,
                        ])->with($success_header);
                    } else {
                        return redirect()->route('de.issuance', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                        ])->with($success_header);
                    }
                }
            }
        } else {
            return redirect()->back()->with(['error_participation' => 'La suma de porcentajes de Beneficiarios del Titular debe ser del 100%'])->withInput();
        }

        return redirect()->back()->with(['error_header' => 'El Sub-Producto no puede ser asociado al Titular'])->withInput()->withErrors($this->repository->getErrors());
    }

}
