<?php

namespace Sibas\Http\Controllers\Eb;

use Illuminate\Http\Request;
use Sibas\Entities\Client;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Controllers\PdfController;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Eb\HeaderCreateFormRequest;
use Sibas\Http\Requests\Eb\HeaderEditFormRequest;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\Eb\HeaderRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class HeaderController extends Controller
{

    /**
     * @var HeaderRepository
     */
    private $repository;
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;
    /**
     * @var PolicyRepository
     */
    private $policyRepository;
    /**
     * @var PdfController
     */
    private $pdf;

    public function __construct(
        HeaderRepository $repository,
        ClientRepository $clientRepository,
        RetailerProductRepository $retailerProductRepository,
        PolicyRepository $policyRepository,
        PdfController $pdf
    )
    {
        $this->repository                = $repository;
        $this->clientRepository          = $clientRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->policyRepository          = $policyRepository;
        $this->pdf                       = $pdf;
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

            return view('eb.list', [
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
            $client          = new Client();

            if (session('client')) {
                $client = session('client');
            }

            $data = $this->getData($rp_id);

            return view('eb.create', [
                'rp_id'   => $rp_id,
                'data'    => $data,
                'client'  => $client,
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
            $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();
            $coverage        = $retailerProduct->coverages()->where('slug', 'IC')->first();

            if (! $this->repository->checkMembership($request->get('dni'), $request->get('complement'), $request->get('extension'))) {
                if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                    if ($this->clientRepository->createClient($request)) {
                        $client = $this->clientRepository->getModel();

                        if ($this->repository->storeHeader($request, $client, $coverage)) {
                            $header = $this->repository->getModel();

                            return redirect()->route('eb.lists', [
                                'rp_id'     => $rp_id,
                                'header_id' => encode($header->id),
                            ])->with([ 'success_header' => 'Los datos del Cliente se registraron con éxito.' ]);
                        }
                    }
                } else {
                    return redirect()->back()->with([
                        'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                          . $parameter->age_min . ' - '
                                          . $parameter->age_max . ' ]',
                    ])->withInput()->withErrors($this->repository->getErrors());
                }
            } else {
                return redirect()->back()->with([
                    'error_client' => 'El Cliente ya cuenta con el Seguro ECOAGUINALDO.',
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
            $retailerProduct = $this->retailerProductRepository->getModel();
            $retailer        = $retailerProduct->retailer;
            $header          = $this->repository->getModel();
            $client          = $header->detail->client;
            $data            = $this->getData($rp_id);

            return view('eb.edit', [
                'rp_id'           => $rp_id,
                'header_id'       => $header_id,
                'header'          => $header,
                'retailerProduct' => $retailerProduct,
                'data'            => $data,
                'client'          => $client,
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
            $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();

            if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)
                && $this->repository->getHeaderById(decode($header_id))
            ) {
                $header = $this->repository->getModel();

                if ($this->clientRepository->createClient($request)) {
                    $client = $this->clientRepository->getModel();

                    if ($this->repository->updateHeader($request, $header, $client)) {
                        return redirect()->route('eb.lists', [
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
            $rp              = $retailerProduct->retailer->retailerProducts()
                ->with('companyProduct.product')
                ->whereHas('companyProduct.product', function ($q) {
                    $q->where('code', 'eb');
                })->where([ 'type' => 'MP' ]);

            $retailerProduct = $rp->first();

            if ($this->repository->setHeaderResult($retailerProduct, $header)) {
                return view('eb.result', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
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
            $retailerProduct  = $this->retailerProductRepository->getModel();
            $header           = $this->repository->getModel();
            $data             = $this->getData($rp_id);
            $data['policies'] = $this->policyRepository->getPolicyForIssuance(decode($rp_id));
            $data['policies'] = $data['policies']->toArray();

            return view('eb.edit-issuance', [
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

        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))
        ) {
            $retailerProduct = $this->repository->getModel();
            $header          = $this->repository->getModel();
            $voucher         = $header->detail->client_eco ? null : $request->get('voucher');
            $client          = $header->detail->client;

            if ( ! $this->repository->checkMembership($client->dni, $client->complement, $client->extension)) {
                if ( ! $this->repository->checkVoucher($voucher)) {
                    if ($this->repository->updateIssuanceHeader($request, $header)) {
                        return redirect()->route('eb.edit.issuance', [
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function getOrder($rp_id, $header_id)
    {
        $error_header = 'La orden no puede ser generada.';

        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))
        ) {
            $retailerProduct = $this->repository->getModel();
            $header          = $this->repository->getModel();

            $view = view('eb.order', [ 'header' => $header ])->render();

            return $this->pdf->create($view, 'Orden-de-Cobro-Ecoaguinaldo', 'C5', 'landscape');
        }

        return redirect()->back()->with([ 'error_header' => $error_header ]);
    }

}
