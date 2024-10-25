<?php

namespace Sibas\Http\Controllers\Tj;

use Illuminate\Auth\Guard;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Tj\Header;
use Sibas\Entities\Client;
use Sibas\Entities\ProductParameter;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Controllers\MailController;
use Sibas\Http\Requests;
use Sibas\Http\Requests\Tj\HeaderCreateFormRequest;
use Sibas\Http\Requests\Tj\HeaderEditFormRequest;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\Tj\HeaderRepository;
use Sibas\Repositories\Tj\DetailRepository;
use Sibas\Repositories\Tj\CollectionRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\VersionCertRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\WsRepository;

class HeaderController extends Controller
{

    /**
     * @var HeaderRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;

    /**
     * @var PolicyRepository
     */
    protected $policyRepository;

    /**
     * @var VersionCertRepository
     */
    protected $versionCertRepository;

    /**
     * @var WsRepository
     */
    protected $wsRepository;

    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    public function __construct(
        HeaderRepository $repository,
        RetailerProductRepository $retailerProductRepository,
        ClientRepository $clientRepository,
        DetailRepository $detailRepository,
        PolicyRepository $policyRepository,
        VersionCertRepository $versionCertRepository,
        WsRepository $wsRepository,
        CollectionRepository $collectionRepository
    ) {
        $this->repository                   = $repository;
        $this->retailerProductRepository    = $retailerProductRepository;
        $this->clientRepository             = $clientRepository;
        $this->detailRepository             = $detailRepository;
        $this->policyRepository             = $policyRepository;
        $this->versionCertRepository        = $versionCertRepository;
        $this->wsRepository                 = $wsRepository;
        $this->collectionRepository         = $collectionRepository;
    }
    
    use DataTrait;
    
    public function lists($rp_id, $header_id)
    {
        $header = null;

        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {

            if ($this->repository->getHeaderById(decode($header_id))) {
                $header = $this->repository->getModel();
                
                $user            = auth()->user();
                $retailerProduct = $this->retailerProductRepository->getModel();
                
                $parameter       = $retailerProduct->parameters->filter(function ($item) {
                    return $item->slug === 'GE';
                })->first();
                
                $coverage_detail = ($parameter->detail)?$parameter->detail:0;
                
            }

            return view($retailerProduct->companyProduct->product->code.'.list',
                compact('rp_id', 'header_id', 'header', 'coverage_detail', 'user', 'retailerProduct'));
        }

        return redirect()->back();
    }
    
    public function result($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
           
            if ($this->repository->getHeaderById(decode($header_id)) && $this->repository->setRetailerProduct($retailerProduct) && 
                    $this->repository->storePremium()) {
                $header    = $this->repository->getModel();
                $user      = request()->user();
                
                return view($retailerProduct->companyProduct->product->code.'.result', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
            }
        }

        return redirect()->back()->with([ 'error_header' => 'La tasa no pudo ser registrada' ]);
    }
    
    public function edit($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
            && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();
            $user            = auth()->user();
            $data = $this->getData($rp_id);
            $data['accounts'] = $this->returnAccountsWs($header->details->first()->accounts_ws);
            if($header->details->first()->card == "CC"){
                $data['policies'] = $this->policyRepository->getPolicyForIssuanceCC(decode($rp_id),$user->ad_city_id)->filter(function ($value) use ($header) {
                    if($header->details->first()->card == $value->modality)
                        return $value;     
                });    
            }else{
                $data['policies'] = $this->policyRepository->getPolicyForIssuance(decode($rp_id))->filter(function ($value) use ($header) {
                        if($header->details->first()->card == $value->modality)
                            return $value;     
                    });
            }
            
            return view($retailerProduct->companyProduct->product->code.'.edit', compact('rp_id', 'header_id', 'header', 'data', 'retailerProduct'));
        }

        return redirect()->back();
    }
    
    public function update(HeaderEditFormRequest $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            
            if($this->repository->getHeaderByCardExists($header->details->first()->card, $header->details->first()->client->code, $header->details->first()->card_number))
                return redirect()->back()->with(['error_client' => 'Ya existe un Seguro vigente para este numero de tarjeta.'])->withInput()->withErrors($this->repository->getErrors());
            
            if ($this->repository->updateHeader($request, $retailerProduct)) {
                $header = $this->repository->getModel();
                if($this->detailRepository->updateDetail($request,$header->details->first()))
                    return redirect()->route($retailerProduct->companyProduct->product->code.'.edit', [
                        'rp_id'     => $rp_id,
                        'header_id' => $header_id,
                    ])->with([ 'success_header' => 'La Póliza fue actualizada con éxito.' ]);
            }
        }

        return redirect()->back()->withInput();
    }
    
    public function issuance($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            
            if($this->repository->getHeaderByCardExists($header->details->first()->card, $header->details->first()->client->code, $header->details->first()->card_number))
                return redirect()->back()->with(['error_client' => 'Ya existe un Seguro vigente para este numero de tarjeta.'])->withInput()->withErrors($this->repository->getErrors());
            
            if ($this->repository->issuanceHeader()) {
                
                $this->collectionRepository->storeCollections($header);
                return redirect()->route($retailerProduct->companyProduct->product->code . '.show.issuance', [
                            'rp_id' => $rp_id,
                            'header_id' => $header_id,
                        ])->with(['success_header' => 'La Póliza fue emitida con éxito. ']);
            }
        }

        return redirect()->back();
    }
    
    public function showIssuance($rp_id, $header_id)
    {
        if($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))){
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            
            if ($header->issued) 
                return view($retailerProduct->companyProduct->product->code.'.issuance', compact('rp_id', 'header_id', 'header','retailerProduct'))
                    ->with([ 'success_header' => 'La Póliza fue emitida con éxito.' ]);
        }
        return redirect()->back();
    }
    
    public function getAttachFile($file)
    {
        $path = base_path('public/assets/certificates/' . $file);

        return response()->download($path, null, [], null);
    }
    
    /**
     * @param Request $request
     * @param string  $rp_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCertificate(Request $request, $rp_id)
    {
        $extensions = [ 'pdf', 'jpg', 'jpeg', 'png', 'tiff', 'tif', ];
        $size       = 3;       // 3M size
        $message    = 'Adjunte un archivo válido (' . implode(', ', $extensions) . ')';
        
        if ($request->hasFile('file')) {
            $id = $request->get('id');

            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id))
                && $this->repository->getHeaderById(decode($id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header          = $this->repository->getModel();
                $file            = $request->file('file');

                if ($file->getClientSize() <= ( $size * 1024 * 1024 )) {
                    if (in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                        $filename = 'crt_'.strtolower($header->prefix).'_' . $header->id . '_' . date('Ymd') . '.' . $file->getClientOriginalExtension();
                        $upload   = $file->move('assets/certificates', $filename);

                        if ($upload->isFile() && $this->repository->saveCertificate($filename)) {
                           
                            $message = 'El archivo se adjuntó correctamente, pero no se pudo completar la emisión. Por favor recargue la página.';
                            
                            $location = route('tj.show.issuance', ['rp_id' => $rp_id, 'header_id' => $id]);
                            
                            if ($request->has('report')) {
                                $location = route('report.master', [ 'code' => 'tj', 'rp_id' => $rp_id, 'type' => 'ISS' ]);
                            }

                            return response()->json([
                                'location' => $location,
                                'message'  => 'El archivo se adjuntó correctamente.',
                            ]);
                        } else {
                            $message = 'El archivo no pudo ser registrado.';
                        }
                    } else {
                        $message = 'El archivo debe ser de tipo (' . implode(', ', $extensions) . ').';
                    }
                } else {
                    $message = 'El tamaño del archivo no debe ser mayor a los ' . $size . 'M.';
                }
            }
        } else {
            $message .= '.';
        }

        Error:
        return response()->json([ 'message' => $message ], 401);
    }
    
    public function returnAccountsWs($accounts)
    {
        $selectOption = [['id'   => '','name' => 'Seleccione']];
        
        $arr = [];
        if($accounts != '')
            foreach (json_decode($accounts) as $key => $value) {
                $arr[$key]['id'] = trim($value->accountNumber); 
                $arr[$key]['name'] = trim($value->type).' - '.trim($value->accountNumber); 
            }
        
        return array_merge($selectOption, $arr);
    }
}
