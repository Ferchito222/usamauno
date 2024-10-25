<?php

namespace Sibas\Http\Controllers\API;

use Validator;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Sibas\Http\Controllers\CertificateController;
use Sibas\Http\Controllers\PdfController;
use Sibas\Repositories\Tj\HeaderRepository;
use Sibas\Repositories\UserRepository;
use Sibas\Repositories\CertificateRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class DocumentPrintController extends Controller
{
    /**
     * @var HeaderRepository
     */
    private $headerRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CertificateRepository
     */
    private $certificateRepository;

    /**
     * @var PdfController
     */
    private $pdf;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;

    public function __construct(
        HeaderRepository $headerRepository,
        UserRepository $userRepository,
        CertificateRepository $certificateRepository, 
        PdfController $pdf,
        RetailerProductRepository $retailerProductRepository
        )
    {
        $this->headerRepository             = $headerRepository;
        $this->userRepository               = $userRepository;
        $this->certificateRepository        = $certificateRepository;
        $this->retailerProductRepository    = $retailerProductRepository;
        $this->pdf                          = $pdf;
        $this->key                          = env('JWT_SECRET');
    }

    public function printing($token)
    {
        $status_code    = 400;  
        try {
            if($decoded = JWT::decode($token, new Key($this->key, 'HS256'))){
                
                if($this->userRepository->getUserPublicById($decoded->data->uid)->count() > 0){
                    
                    if($this->retailerProduct = validRp($decoded->data->rid)){
                       
                        if($this->issetHeader($decoded->data->hid)){
                            if($this->policyValidity()){
                                $certificate = new CertificateController($this->certificateRepository, $this->pdf);
                                $document = $certificate->show($this->retailerProduct->product_code, $decoded->data->rid, $decoded->data->hid, 'certificate', 'pdf', false);
                                $file_name = str_replace(' ','-',strtolower($this->retailerProduct->retailer->name)).'-'.str_replace(' ','-',strtolower($this->retailerProduct->companyProduct->product->name));
                                
                                return $document->header('Content-Disposition','attachment; filename="'.$file_name.'.pdf"')->header('Pragma','no-cache')->header('Expires','0');
                            }else
                                $arr['errors'][] = 'Error vigencia 2 de poliza.';
                        }else
                            $arr['errors'][] = 'Error vigencia 1 de poliza';
                    }else
                        $arr['errors'][] = 'Error módulo.';
                }else
                    $arr['errors'][] = 'Error usuario publico';
            }else{
                $status_code = 401;
                $arr['errors'][] = 'Token has expired';
            }
        } catch (\Throwable $th) {
            $arr['errors'][] = 'Error';
        }
        
        return response()->json($arr, $status_code);
    }

    public function issetHeader($header_id)
    {
        $model = app('Sibas\Entities\\' . ucfirst($this->retailerProduct->product_code) . '\Header');
        
        if($this->header = $model->where('id',decode($header_id))
            ->where('canceled',false)
            ->get()){
            
            if($this->header->count() > 0){
                $this->header = $this->header->first();
                return true;
            }
        }
        return false;
    }

    public function policyValidity()
    {
        switch ($this->retailerProduct->product_code) {
            case 'de':
            case 'au':
            case 'td':
            case 'ema':
            case 'rma':
            case 'tj':
                    if(carbon::now() <= $this->header->policy_end)
                        return true;
                    else
                    return false;
                break;
            default:
                # code...
                break;
        }
    }
}