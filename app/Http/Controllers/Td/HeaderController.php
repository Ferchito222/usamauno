<?php

namespace Sibas\Http\Controllers\Td;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Sibas\Entities\Td\Header;
use Sibas\Entities\Client;
use Sibas\Entities\City;
use Sibas\Entities\Rate;
use Sibas\Entities\ProductParameter;
use Sibas\Entities\Td\Detail;
use Sibas\Http\Controllers\DataTrait;
use Sibas\Http\Controllers\MailController;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Td\ClientComplementFormRequest;
use Sibas\Http\Requests\Td\CoverageCreateFormRequest;
use Sibas\Http\Requests\Td\CoverageEditFormRequest;
use Sibas\Http\Requests\Td\FacultativeRequestFormRequest;
use Sibas\Http\Requests\Td\HeaderCreateFormRequest;
use Sibas\Http\Requests\Td\HeaderEditFormRequest;
use Sibas\Repositories\Td\FacultativeRepository;
use Sibas\Repositories\Td\HeaderRepository;
use Sibas\Repositories\De\HeaderRepository as HeaderDeRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\Td\DetailRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\WsRepository;

class HeaderController extends Controller
{

    /**
     * @var DetailRepository
     */
    protected $detailRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var HeaderRepository
     */
    protected $repository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var PolicyRepository
     */
    protected $policyRepository;

    /**
     * @var FacultativeRepository
     */
    protected $facultativeRepository;

    /**
     * @var HeaderDeRepository
     */
    protected $headerDeRepository;

    /**
     * @var WsRepository
     */
    protected $wsRepository;

    public function __construct(
        HeaderRepository $repository,
        ClientRepository $clientRepository,
        DetailRepository $detailRepository,
        FacultativeRepository $facultativeRepository,
        RetailerProductRepository $retailerProductRepository,
        PolicyRepository $policyRepository,
        HeaderDeRepository $headerDeRepository,
        WsRepository $wsRepository
    ) {
        $this->repository                = $repository;
        $this->clientRepository          = $clientRepository;
        $this->detailRepository          = $detailRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->policyRepository          = $policyRepository;
        $this->facultativeRepository     = $facultativeRepository;
        $this->headerDeRepository        = $headerDeRepository;
        $this->wsRepository              = $wsRepository;
    }


    use DataTrait;


    /**
     * Show the form for creating a new resource.
     *
     * @param string $rp_id
     *
     * @return mixed
     */
    public function create($rp_id)
    {
        $client = new Client();
        $header = new Header();

        if (session('client')) {
            $client = session('client');
        }
        if (session('header')) {
            $header = session('header');
        }

        $data                    = $this->getData($rp_id);
        $data['payment_methods'] = $this->retailerProductRepository->getPaymentMethodsByProductById(decode($rp_id));
        return view('td.create', compact('rp_id', 'data', 'client', 'header'));
    }
    
    public function store(HeaderCreateFormRequest $request, $rp_id)
    {
        if ($this->clientRepository->createClient($request) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $client = $this->clientRepository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();
            
            if ($parameter instanceof ProductParameter) {
                
                if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter) ||$request->get('type')=='L') {
                    if ($this->repository->storeHeader($request, $client)) {
                        $header = $this->repository->getModel();

                        if ($header->warranty && $request->get('number_de')) {
                            if ($this->headerDeRepository->getHeaderById($request->get('number_de'))) {
                                $de = $this->headerDeRepository->getModel();

                                if ($this->repository->setCoverage($de)) {
                                    goto Store;
                                } else {
                                    return redirect()->back()->with([ 'error_header' => 'La Garantía no pudo ser asociada' ])->withInput()->withErrors($this->repository->getErrors());
                                }
                            }
                        }

                        Store:

                        return redirect()->route('td.mr.lists', [
                            'rp_id'     => $rp_id,
                            'header_id' => encode($header->id),
                        ])->with([ 'success_header' => 'La cotización fue registrada con éxito.' ]);
                    }
            } else {
                        return redirect()->back()->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                              . $parameter->age_min . ' - '
                                              . $parameter->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                    }
            }
        }

        return redirect()->back()->with([ 'error_header' => 'El Cliente no pudo ser registrado' ])->withInput()->withErrors($this->repository->getErrors());
    }


    public function result($rp_id, $header_id)
    {
        if ($this->repository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header          = $this->repository->getModel();
            $rate            = Rate::where('ad_retailer_product_id', decode($rp_id))->where('active', true)->first();
            
            $totalPremium = $this->detailRepository->updateRate($header, $rate, $retailerProduct->retailer->exchangeRate);
            
            if($this->repository->updateHeaderTotalPremium($retailerProduct, decode($header_id), $totalPremium)){
                $header = $this->repository->getModel();
                return view('td.result', compact('rp_id', 'header_id', 'header', 'retailerProduct'));
            }
        }
        return redirect()->back()->with([ 'error_header' => 'No se posible calcular la prima.' ]);
    } 
    
    /**
     * @param type $rp_id
     * @param type $header_id
     * @param type $id
     *
     * @return type
     */
    public function formDetail($rp_id, $header_id, $id_detail, $steep)
    {

        $materia = $this->returnArray(config('base.property_types'));
        $uso     = $this->returnArray(config('base.property_uses'));
        $city    = City::where('abbreviation', '!=', '')->where('abbreviation', '!=', 'PE')->get();
        $header  = 0;
        if ($this->repository->getHeaderById($header_id)) {
            $header = $this->repository->getModel();
        }

        if ($id_detail != 0) {
            $detail = Detail::where('id', $id_detail)->first();
        } else {
            $detail = new Detail();
        }

        $arr = [ 0 => [ 'id' => 0, 'name' => 'Seleccione' ] ];
        $i   = 1;
        foreach ($city->toArray() as $key => $value) {
            $arr[$i]       = $value;
            $arr[$i]['id'] = $value['name'];
            $i++;
        }
        $city = $arr;

        $var = [
            'template' => view('td.form.formInsured',
                compact('rp_id', 'header_id', 'materia', 'uso', 'city', 'detail', 'header', 'steep'))->render()
        ];

        return response()->json($var);
    }


    /**
     * funcion retorna formulario ajax solicitud de aprovacion de la compañia.
     *
     * @param type $rp_id
     * @param type $header_id
     * @param type $id_detail
     *
     * @return type
     */
    public function requestCreate($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->repository->getHeaderById(decode($header_id))) {
                $header = $this->repository->getModel();

                if ($header->facultative) {
                    $payload = view('td.facultative.request', compact('rp_id', 'header'));

                    return response()->json([
                        'payload'                 => $payload->render(),
                        'facultative_observation' => strip_tags($header->facultative_observation),
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     *
     * @param \Sibas\Http\Requests\Td\FacultativeRequestFormRequest $request
     * @param type                                                  $rp_id
     * @param type                                                  $header_id
     *
     * @return type
     */
    public function requestStore(FacultativeRequestFormRequest $request, $rp_id, $header_id)
    {
        if ($request->ajax()) {
            if ($this->repository->getHeaderById(decode($header_id)) && $this->repository->storeFacultative($request)) {
                $header = $this->repository->getModel();

                $mail = new MailController($request->user());

                $mail->subject  = 'Solicitud de aprobación: Caso Facultativo No. ' . $header->prefix . ' - ' . $header->issue_number;
                $mail->template = 'td.request-approval';

                if ($mail->send(decode($rp_id), [ 'header' => $header ], 'COP')) {
                    $this->repository->storeSent();
                }

                return response()->json([
                    'location' => route('td.edit', compact('rp_id', 'header_id'))
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * funcion guarda detalle ajax
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function storeInsured(Request $request, $rp_id, $header_id)
    {
        $prodParam = ProductParameter::where('ad_retailer_product_id', decode($rp_id))->where('slug', 'GE')->first();

        $this->detailRepository->getDetailByHeader(decode($header_id));
        $detail = $this->detailRepository->getModel();

        if (count($detail) < $prodParam->detail || $request->get('id_detail') != '') {
            $this->detailRepository->createDetail($request);
        }

        if ($this->repository->getHeaderById(decode($header_id))) {
            $header      = $this->repository->getModel();
            $facultative = $this->facultativeRepository->roleFacultative(decode($rp_id), decode($header_id), $header);

            if ($facultative['facultative'] === 0) {

                if ($header->facultative === true) {
                    $this->repository->deleteFacultativeHeader(decode($header_id));
                }
            } else {
                $this->facultativeRepository->storeFacultative($facultative, $request->user());

                foreach ($header->details as $key => $value) {
                    if ( ! in_array($value->id, $this->facultativeRepository->idsFactultative)) {
                        $this->detailRepository->delFacultativeById($value->id);
                    }
                }
                $obs = $this->facultativeRepository->returnObservation();
                $this->repository->updateFacultativeHeader(true, $obs);
            }
        }
    }

    /**
     * retorna array
     *
     * @param type $array
     */
    public function returnArray($array)
    {
        $arr            = [];
        $arr[0]['id']   = 0;
        $arr[0]['name'] = 'Seleccione';
        $i              = 1;
        foreach ($array as $key => $value) {
            $arr[$i]['id']   = $key;
            $arr[$i]['name'] = $value;
            $i++;
        }

        return $arr;
    }

    /**
     * @param type $rp_id
     * @param type $header_id
     * @return type
     */
    public function edit($rp_id, $header_id)
    {
        if ($this->repository->getHeaderById(decode($header_id))) {
            $header   = $this->repository->getModel();
            $data   = [
                'policies' => $this->policyRepository->getPolicyByCurrency(decode($rp_id), $header->currency)
            ];
            //--edw--> $policies = $this->policyRepository->getPolicyByModality(decode($rp_id), ['MR','TD']);
            
            return view('td.edit', compact('rp_id', 'header_id', 'header', 'data'));
        }
        return redirect()->back();
    }

    public function update(HeaderEditFormRequest $request, $rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            if ($this->repository->checkOldOperationNumber($request->get('operation_number'))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $header          = $this->repository->getModel();
                $keyFac          = false;
                $obs             = false;
                
                if($this->detailRepository->verifyCatastralCodeHeader($header)){
                    # validacion facultativo
                    $facultative = $this->facultativeRepository->roleFacultative(decode($rp_id), decode($header_id), $header);

                    //if ($facultative['facultative'] > 0) {
                    if (false) {
                        $this->facultativeRepository->storeFacultative($facultative, $request->user());
                        $obs = $this->facultativeRepository->returnObservation();

                        $keyFac = true;
                    } else {
                        if ($header->facultative === true) {
                            $this->repository->deleteFacultativeHeader();
                        }
                    }

                    if ($this->repository->updateHeader($request, $retailerProduct, $keyFac, $obs)) {
                        //$policies   = $this->policyRepository->getPolicyByModality(decode($rp_id), ['MR','TD']);

                        //$this->detailRepository->updateNumberpolicy($header->fresh(), $policies, $retailerProduct->retailer->exchangeRate);
                        return redirect()->route('td.edit', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                        ])->with([ 'success_header' => 'La Póliza fue actualizada con éxito.' ]);
                    }
                }else
                return redirect()->back()->withInput()
                    ->with(['error_ws' => 'Ya existe un seguro contratado para la misma garantía (Código Catastral). No puede tener dos seguros para la misma garantía']);
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(['error_ws' => 'Existe un certificado emitido con el mismo Número de Operación, favor proceda con la respectiva anulación si corresponde']);
            }
        }

        return redirect()->back()->withInput();
    }


    /**
     * @param string $rp_id
     * @param string $header_id
     * @param string $client_id
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function editClient($rp_id, $header_id, $client_id)
    {
        if ($this->repository->getHeaderById(decode($header_id))) {
            $header = $this->repository->getModel();
            $client = $header->client;

            if ($client instanceof Client && $client->id == decode($client_id)) {
                $data = $this->getData($rp_id);

                return view('client.td.edit-issuance', compact('rp_id', 'header_id', 'client_id', 'client', 'data'));
            }
        }

        return redirect()->back();
    }


    /**
     * @param ClientComplementFormRequest $request
     * @param string                      $rp_id
     * @param string                      $header_id
     * @param string                      $client_id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateClient(ClientComplementFormRequest $request, $rp_id, $header_id, $client_id)
    {
        if ($this->repository->getHeaderById(decode($header_id)) && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))) {
            $header = $this->repository->getModel();
            $retailerProduct = $this->retailerProductRepository->getModel();
            $parameter       = $retailerProduct->parameters->filter(function ($item) {
                return $item->slug === 'GE';
            })->first();
            
            if ($parameter instanceof ProductParameter) {
                
                if ($this->repository->checkAgeByParameter($request->get('birthdate'), $parameter) || $header->client->type=='L') {

                    if (( $header->client instanceof Client && $header->client->id == decode($client_id) ) && $this->clientRepository->updateIssueClient($request,
                            $header->client)
                    ) {
                        if ($request->has('coverage')) {
                            return redirect()->route('td.coverage.edit', [
                                'rp_id'     => $rp_id,
                                'de_id'     => $request->get('coverage'),
                                'header_id' => $header_id
                            ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]);
                        }

                        return redirect()->route('td.edit', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                            $request->get('idf') ? 'idf=' . $request->get('idf') : null
                        ])->with([ 'success_client' => 'La información del Cliente se actualizó correctamente' ]);
                    }
            } else {
                        return redirect()->back()->with([
                            'error_client' => 'El Cliente no cumple con el rango de edades [ '
                                              . $parameter->age_min . ' - '
                                              . $parameter->age_max . ' ]',
                        ])->withInput()->withErrors($this->repository->getErrors());
                    }
            }
        }

        return redirect()->back()->with([ 'error_client' => 'La información del Cliente no pudo ser actualizada' ])->withInput()->withErrors($this->repository->getErrors());
    }


    /**
     * Header show issuance
     */
    public function showIssuance($rp_id, $header_id)
    {
        $this->retailerProductRepository->getRetailerProductById(decode($rp_id));
        $rp = $this->retailerProductRepository->getModel();
        if ($this->repository->getHeaderById(decode($header_id))) {
            $header = $this->repository->getModel();

            if ($header->issued) {
                return view('td.issuance', compact('rp_id', 'header_id', 'header',
                    'rp'))->with([ 'success_header' => 'La Póliza fue emitida con éxito.' ]);
            }
        }

        return redirect()->back();
    }


    /**
     * Header update issuance
     *
     * @param string $rp_id
     * @param string $header_id
     *
     * @return
     */
    public function updateIssuance($rp_id, $header_id)
    {
        if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $retailerProduct = $this->retailerProductRepository->getModel();
            $header = $this->repository->getModel();
            
            if($this->detailRepository->validUseAndInsuredValue($header, $header->details->first(), $retailerProduct))
                return redirect()->back()->with([ 'error_client' => $this->detailRepository->getMessageOld()])->withInput()->withErrors($this->repository->getErrors());
            
            $data = $this->wsRepository->storeRates2($header->prefix, $this->repository->getParamGarantias($header,$retailerProduct));
            
            if($data['err'] == ''){
                if ($this->repository->issuanceHeader()) {
                    return redirect()->route('td.show.issuance', [
                        'rp_id'     => $rp_id,
                        'header_id' => $header_id,
                    ])->with([ 'success_header' => 'La Póliza fue emitida con éxito. ']);
                }
            }else
                return redirect()->back()->with('error_header', $data['err']);
            
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $de_id
     *
     * @return mixed
     */
    public function coverageCreate($rp_id, $de_id)
    {
        if (request()->ajax()) {
            if (request()->has('rp_de') && $this->headerDeRepository->getHeaderById(decode($de_id))) {
                $de   = $this->headerDeRepository->getModel();
                $data = [];

                $data            = $this->getData($rp_id);
                $payment_methods = $this->retailerProductRepository->getPaymentMethodsByProductById(decode($rp_id));
                $currencies      = $data['currencies'];
                $term_types      = $data['term_types'];
                $payment_methods->shift();
                $currencies->shift();
                $term_types->shift();

                $payload = view('td.coverage.create', compact('rp_id', 'de_id', 'de'));

                return response()->json([
                    'payload'         => $payload->render(),
                    'payment_methods' => $payment_methods,
                    'currencies'      => $currencies,
                    'term_types'      => $term_types,
                    'term'            => $de->term,
                    'type_term'       => $de->type_term,
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param CoverageCreateFormRequest $request
     * @param string                    $rp_id
     * @param string                    $de_id
     *
     * @return mixed
     */
    public function coverageStore(CoverageCreateFormRequest $request, $rp_id, $de_id)
    {
        if (request()->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->headerDeRepository->getHeaderById(decode($de_id)) && $this->clientRepository->getClientById(decode($request->get('client')))) {
                $retailerProduct = $this->retailerProductRepository->getModel();
                $de              = $this->headerDeRepository->getModel();
                $client          = $this->clientRepository->getModel();

                if ($this->repository->storeCoverage($request, $retailerProduct)) {
                    $header = $this->repository->getModel();

                    Cache::put($header->id, $request->get('rp_de'), 180);

                    return response()->json([
                        'location' => route('td.coverage.edit',
                            [ 'rp_id' => $rp_id, 'de_id' => $de_id, 'header_id' => encode($header->id) ])
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $de_id
     * @param string $header_id
     *
     * @return mixed
     */
    public function coverageEdit($rp_id, $de_id, $header_id)
    {
        if (Cache::has(decode($header_id)) && $this->repository->getHeaderById(decode($header_id))) {
            $header                  = $this->repository->getModel();
            $data                    = $this->getData($rp_id);
            $data['policies']        = $this->policyRepository->getPolicyByCurrency(decode($rp_id), $header->currency);
            $data['payment_methods'] = $this->retailerProductRepository->getPaymentMethodsByProductById(decode($rp_id));

            return view('td.coverage.edit', compact('rp_id', 'de_id', 'header_id', 'header', 'data'));
        }

        return redirect()->back()->with([ 'error_header' => 'La cobertura no puede ser inicializada . ' ]);
    }


    /**
     * @param CoverageEditFormRequest $request
     * @param string                  $rp_id
     * @param string                  $de_id
     * @param string                  $header_id
     *
     * @return mixed
     */
    public function coverageUpdate(CoverageEditFormRequest $request, $rp_id, $de_id, $header_id)
    {
        if (Cache::has(decode($header_id))
            && $this->retailerProductRepository->getRetailerProductById(decode($rp_id))
        ) {
            $retailerProduct = $this->retailerProductRepository->getModel();

            if ($this->repository->getHeaderById(decode($header_id))
                && $this->headerDeRepository->getHeaderById(decode($de_id))
            ) {
                $header = $this->repository->getModel();
                $de     = $this->headerDeRepository->getModel();

                if ($this->repository->setPropertyResult($retailerProduct, $header)
                    && $this->repository->updateCoverage($request, $retailerProduct, $de)
                ) {
                    $rp_de = Cache::get(decode($header_id));

                    return redirect()->route('de.issuance', [
                        'rp_id'     => $rp_de,
                        'header_id' => $de_id
                    ])->with([ 'success_header' => 'La garantía fue asociada correctamente.' ]);
                }
            }
        }

        return redirect()->back()->with([ 'error_header' => 'La cobertura no puede ser emitida . ' ]);
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
                            $location = route(strtolower($header->prefix).'.show.issuance', ['rp_id' => $rp_id, 'header_id' => $id]);
                            
                            if ($request->has('report')) {
                                $location = route('report.master', [ 'code' => strtolower($header->prefix), 'rp_id' => $rp_id, 'type' => 'ISS' ]);
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

}
