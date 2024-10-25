<?php

namespace Sibas\Http\Controllers\Elp;

use Illuminate\Http\Request;

use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Elp\ClientEditFormRequest;
use Sibas\Repositories\Elp\DetailRepository;
use Sibas\Repositories\Elp\HeaderRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\UserRepository;

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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var HeaderRepository
     */
    private $headerRepository;


    public function __construct(
        DetailRepository $repository,
        HeaderRepository $headerRepository,
        RetailerProductRepository $retailerProductRepository,
        UserRepository $userRepository
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->userRepository            = $userRepository;
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
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerRepository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $retailer        = $retailerProduct->retailer;
            $header          = $this->headerRepository->getModel();
            $client          = $header->detail->client;
            $data            = $this->getData($rp_id);
            $data['users']   = $this->userRepository->getUsersByPosition($retailer->id, 'CSP');
            $acc             = [
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

            return view('elp.client-edit', [
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

            if ($this->headerRepository->checkAge($request, $retailerProduct)) {
                if ($this->headerRepository->getHeaderById(decode($header_id))) {
                    $header = $this->headerRepository->getModel();
                    $detail = $header->detail;

                    if ($this->repository->clientUpdate($request, $detail)) {
                        return redirect()->route('elp.edit.issuance', compact('rp_id',
                            'header_id'))->with([ 'success_header' => 'Los datos del Cliente se actualizaron con éxito.' ]);
                    }
                }
            } else {
                return redirect()->back()->with([
                    'error_client' => $this->headerRepository->error_message,
                ])->withInput()->withErrors($this->repository->getErrors());
            }
        }

        return redirect()->back()->with([
            'error_header' => 'El información no pudo ser actualizada',
        ])->withInput();
    }
}
