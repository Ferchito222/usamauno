<?php

namespace Sibas\Http\Controllers\Eb;

use Illuminate\Http\Request;

use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Eb\ClientEditFormRequest;
use Sibas\Repositories\Eb\DetailRepository;
use Sibas\Repositories\Eb\HeaderRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class DetailController extends Controller
{

    /**
     * @var DetailRepository
     */
    private $repository;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;

    /**
     * @var HeaderRepository
     */
    private $headerRepository;


    public function __construct(
        DetailRepository $repository,
        HeaderRepository $headerRepository,
        RetailerProductRepository $retailerProductRepository
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->retailerProductRepository = $retailerProductRepository;
    }

    use DataTrait;


    /**
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function clientEdit($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->headerRepository->getHeaderById(decode($header_id))
        ) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $retailer        = $retailerProduct->retailer;
            $header          = $this->headerRepository->getModel();
            $client          = $header->detail->client;
            $data            = $this->getData($rp_id);

            return view('eb.client-edit', [
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
     * @param ClientEditFormRequest $request
     * @param string                $rp_id
     * @param string                $header_id
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function clientUpdate(ClientEditFormRequest $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters()->where('slug', 'GE')->first();

            if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter)) {
                if ($this->headerRepository->getHeaderById(decode($header_id))) {
                    $header = $this->headerRepository->getModel();
                    $detail = $header->detail;

                    if ($this->repository->clientUpdate($request, $detail)) {
                        return redirect()->route('eb.edit.issuance', compact('rp_id',
                            'header_id'))->with([ 'success_header' => 'Los datos del Cliente se actualizaron con éxito.' ]);
                    }
                }
            } else {
                return redirect()->back()->with([
                    'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                      . $parameter->age_min . ' - '
                                      . $parameter->age_max . ' ]',
                ])->withInput()->withErrors($this->repository->getErrors());
            }
        }

        return redirect()->back()->with([
            'error_header' => 'El información no pudo ser actualizada',
        ])->withInput();
    }
}
