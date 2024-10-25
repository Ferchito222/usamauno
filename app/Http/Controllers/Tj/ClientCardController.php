<?php

namespace Sibas\Http\Controllers\Tj;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Tj\DetailRepository;
use Sibas\Repositories\Client\ClientRepository;
use Sibas\Repositories\Retailer\CityRepository;


class ClientCardController extends Controller
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;
   /**
     * @var CityRepository
     */
    protected $cityRepository;

    public function __construct(
        ClientRepository $clientRepository,
        DetailRepository $detailRepository,
        CityRepository $cityRepository
    ) {
        $this->clientRepository             = $clientRepository;
        $this->detailRepository             = $detailRepository;
        $this->cityRepository               = $cityRepository;
    }
    
    
    public function clientCard()
    {
        $data['cities'] = $this->cityRepository->getCitiesByType()['CI']->toArray();
        $data['cities'][0]['name'] = 'Extension';
        
            return view('tj.client-card.client-card',compact('data'));
        

        return redirect()->back();
    }
    
    public function clientCardRecord(Request $request)
    {
        $messages = [
            'captcha' => 'Código ingresado incorrecto!!',
        ];
        
        $rules = [
            'dni' => 'required',
            'extension' => 'required',
            'captcha' => 'required|captcha'
            ];
        
        $validator = $this->validate($request, $rules, $messages);
        
        if($this->clientRepository->getClientByDni($request->get('dni'), $request->get('extension'))){
            $client = $this->clientRepository->getModel();
            
            if($this->detailRepository->getDetailByClient($client->id, 'CC')){
                $detail = $this->detailRepository->getModel();
                
                if($detail->header->issued && $detail->header->canceled == 0){
                    
                    return redirect()->route('certificate.show.client', [
                                    'code'     => 'tj',
                                    'rp_id'     => encode($detail->header->ad_retailer_product_id),
                                    'type' => 'certificate',
                                    'header_id' => encode($detail->header->id),
                                    'format' => 'pdf',
                                ])->with([
                                    'success_client' => 'La información del Cliente fueron registrados',
                                ]);
                }else
                    $error_message = 'La poliza no se encuentra emitida';
            }else
                $error_message = 'El Cliente no cuenta con un seguro de tarjetas';
            
        }else
            $error_message = 'El CI del cliente no se encuentra en sistema.';
        
        return redirect()->back()->with([ 'error_client' => $error_message ])->withInput()
            ->withErrors($this->clientRepository->getErrors());
    }
}
