<?php

namespace Sibas\Http\Controllers\Elp;

use Illuminate\Http\Request;

use Sibas\Entities\Client;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Elp\HeaderCreateFormRequest;
use Sibas\Http\Requests\Elp\HeaderEditFormRequest;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\Elp\CollectionRepository;
use Sibas\Repositories\Elp\HeaderRepository;
use Sibas\Repositories\Retailer\CashierRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\UserRepository;

class HeaderController extends Controller
{

    /**
     * @var HeaderRepository
     */
    private $repository;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PolicyRepository
     */
    private $policyRepository;

    /**
     * @var CollectionRepository
     */
    private $collectionRepository;
    /**
     * @var CashierRepository
     */
    private $cashierRepository;


    public function __construct(
        HeaderRepository $repository,
        ClientRepository $clientRepository,
        UserRepository $userRepository,
        CashierRepository $cashierRepository,
        CollectionRepository $collectionRepository,
        RetailerProductRepository $retailerProductRepository,
        PolicyRepository $policyRepository
    ) {
        $this->repository                = $repository;
        $this->clientRepository          = $clientRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->userRepository            = $userRepository;
        $this->policyRepository          = $policyRepository;
        $this->collectionRepository      = $collectionRepository;
        $this->cashierRepository         = $cashierRepository;
    }


    use DataTrait;


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function lists($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();

            return view('elp.list', [
                'rp_id'           => $rp_id,
                'header_id'       => $header_id,
                'retailerProduct' => $retailerProduct,
                'header'          => $header,
            ]);
        }

        return redirect()->back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param string $rp_id
     *
     * @return \Illuminate\Http\Response
     */
    public function create($rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $retailer        = $retailerProduct->retailer;
            $client          = new Client();
            $account         = [
                'type'     => '',
                'currency' => '',
                'number'   => '',
                'total'    => '',
            ];

            if (session('client')) {
                $client = session('client');
            }

            if (session('account')) {
                $account = session('account');
            }

            $data             = $this->getData($rp_id);
            $data['cashiers'] = $this->cashierRepository->getCashiers();

            return view('elp.create', [
                'rp_id'   => $rp_id,
                'data'    => $data,
                'client'  => $client,
                'account' => $account,
            ]);
        }

        return redirect()->back();
    }


    /**
     *  Store a newly created resource in storage.
     *
     * @param HeaderCreateFormRequest $request
     * @param string                  $rp_id
     *
     * @return mixed
     */
    public function store(HeaderCreateFormRequest $request, $rp_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($this->repository->checkAge($request, $retailerProduct)) {
                if ($this->clientRepository->createClient($request)) {
                    $client = $this->clientRepository->getModel();

                    if ($this->repository->storeHeader($request, $client, $this->repository->coverage)) {
                        $header = $this->repository->getModel();

                        return redirect()->route('elp.lists', [
                            'rp_id'     => $rp_id,
                            'header_id' => encode($header->id),
                        ])->with([ 'success_header' => 'Los datos del Cliente se registraron con éxito.' ]);
                    }
                }
            } else {
                return redirect()->back()->with([
                    'error_client' => $this->repository->error_message,
                ])->withInput()->withErrors($this->repository->getErrors());
            }
        }

        return redirect()->back()->with([
            'error_header' => 'El Cliente no pudo ser registrado',
        ])->withInput()->withErrors($this->repository->getErrors());
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct    = $this->retailerProductRepository->getModel();
            $retailer           = $retailerProduct->retailer;
            $header             = $this->repository->getModel();
            $client             = $header->detail->client;
            $data               = $this->getData($rp_id);
            $data[ 'cashiers' ] = $this->cashierRepository->getCashiers();
            $acc                = [
                'type'     => $header->detail->account_type,
                'currency' => $header->detail->currency,
                'number'   => '',
                'total'    => '',
            ];

            foreach ($client->accounts as $account) {
                if ($account->main_account) {
                    $acc['number'] = $account->number;
                }

                $acc['total'] .= $account->number . ',';
            }

            return view('elp.edit', [
                'rp_id'           => $rp_id,
                'header_id'       => $header_id,
                'header'          => $header,
                'retailerProduct' => $retailerProduct,
                'data'            => $data,
                'client'          => $client,
                'account'         => $acc,
            ]);
        }

        return redirect()->back();
    }


    /**
     * @param HeaderCreateFormRequest $request
     * @param string                  $rp_id
     * @param string                  $header_id
     *
     * @return \Illuminate\Http\RedirectResponse|HeaderController
     */
    public function update(HeaderCreateFormRequest $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($this->repository->checkAge($request,
                    $retailerProduct) && $this->repository->getHeaderById(decode($header_id))
            ) {
                $header = $this->repository->getModel();

                if ($this->clientRepository->createClient($request)) {
                    $client = $this->clientRepository->getModel();

                    if ($this->repository->updateHeader($request, $header, $client)) {
                        return redirect()->route('elp.lists', [
                            'rp_id'     => $rp_id,
                            'header_id' => encode($header->id),
                        ])->with([ 'success_header' => 'Los datos del Cliente se actualizaron con éxito.' ]);
                    }
                }
            } else {
                return redirect()->back()->with([
                    'error_client' => $this->error_message,
                ])->withInput()->withErrors($this->repository->getErrors());
            }
        }

        return redirect()->back()->with([
            'error_header' => 'El información no pudo ser actualizada',
        ])->withInput()->withErrors($this->repository->getErrors());
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function result($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();
            $rp              = $retailerProduct->retailer->retailerProducts()->whereHas('companyProduct.product',
                function ($q) {
                    $q->where('code', 'elp');
                })->where([ 'type' => 'MP' ]);

            $retailerProduct = $rp->first();

            if ($this->repository->setHeaderResult($retailerProduct, $header)) {
                $header = $header->fresh([ 'detail' ]);

                return view('elp.result', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
            }
        }

        return redirect()->back()->with([ 'error_header' => 'La prima no pudo ser registrada' ]);
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editIssuance($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct         = $this->retailerProductRepository->getModel();
            $header                  = $this->repository->getModel();
            $data                    = $this->getData($rp_id);
            $data['policies']        = $this->policyRepository->getPolicyForIssuance(decode($rp_id));
            $data['payment_methods'] = $this->retailerProductRepository->getPaymentMethodsByProductById(decode($rp_id));
            $data['payment_methods']->shift();

            $data['policies'] = $data['policies']->filter(function ($item) use ($header) {
                if ($header->detail->client_eco) {
                    return $item->type === 'EP';
                } else {
                    return $item->type === 'EL';
                }
            })->toArray();

            return view('elp.edit-issuance', [
                'rp_id'     => $rp_id,
                'header_id' => $header_id,
                'header'    => $header,
                'data'      => $data,
            ]);
        }

        return redirect()->back()->with([ 'error_header' => 'La cotización no existe.' ]);
    }


    /**
     * @param HeaderEditFormRequest $request
     * @param string                $rp_id
     * @param string                $header_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIssuance(HeaderEditFormRequest $request, $rp_id, $header_id)
    {
        $error_header = 'La póliza no puede ser actualizada';

        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->repository->getModel();
            $header          = $this->repository->getModel();
            $voucher         = $header->detail->client_eco ? null : $request->get('voucher');

            if ( ! $this->repository->checkMembership($header)) {
                if ( ! $this->repository->checkVoucher($voucher)) {
                    if ($this->repository->updateIssuanceHeader($request, $header)) {
                        return redirect()->route('elp.edit.issuance', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id
                        ])->with([ 'success_header' => 'La Póliza fue actualizada con éxito.' ]);
                    }
                } else {
                    $error_header = 'El Nº de Boleta / Voucher ya esta registrado';
                }
            } else {
                $error_header = 'El Cliente ya se encuentra afiliado';
            }
        }

        return redirect()->back()->with([ 'error_header' => $error_header ]);
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function issuance($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();

            if ( ! $header->issued) {
                if ($this->repository->issuanceHeader($retailerProduct, $header)) {
                    $header = $header->fresh([ 'detail', ]);

                    if ($this->collectionRepository->createCollections($header)) {
                        request()->session()->flash('success_header', 'La póliza fue emitida con éxito.');

                        goto Show;
                    } else {
                        request()->session()->flash('error_header', 'No se pudieron generar las quotas.');

                        goto Show;
                    }
                }
            } else {
                Show:
                return view('elp.issuance', [
                    'rp_id'     => $rp_id,
                    'header_id' => $header_id,
                    'header'    => $header,
                ]);
            }
        }

        return redirect()->back()->with([ 'error_header' => 'La póliza no puede ser emitida.' ]);
    }
}
