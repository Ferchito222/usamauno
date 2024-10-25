<?php

namespace Sibas\Http\Controllers;

use Illuminate\Http\Request;

use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use \Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\Td\HeaderRepository as TdHeaderRepository;
use Sibas\Repositories\Au\HeaderRepository as AuHeaderRepository;
use Sibas\Repositories\Ema\HeaderRepository as emaHeaderRepository;

class ModalCSController extends Controller
{
    
    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;
    
    /**
     * @var TdHeaderRepository
     */
    protected $tdHeaderRepository;
    
    /**
     * @var AuHeaderRepository
     */
    protected $auHeaderRepository;
    
    /**
     * @var EmaHeaderRepository
     */
    protected $emaHeaderRepository;
    
    public function __construct(
        RetailerProductRepository $retailerProductRepository,
        AuHeaderRepository $auHeaderRepository,
        EmaHeaderRepository $emaHeaderRepository,
        TdHeaderRepository $tdHeaderRepository
    ) {
        $this->retailerProductRepository = $retailerProductRepository;
        $this->tdHeaderRepository = $tdHeaderRepository;
        $this->auHeaderRepository = $auHeaderRepository;
        $this->emaHeaderRepository = $emaHeaderRepository;
    }
    
    /**
     * ajax modal
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function ajax_modal($id_retailer_product, $id_header, $text, $type) {

        $this->retailerProductRepository->getRetailerProductById(decode($id_retailer_product));
        $retailerProduct = $this->retailerProductRepository->getModel();
        $code_product = $retailerProduct->companyProduct->product->code;
        $header = [];
        switch ($retailerProduct->companyProduct->product->code) {
            case 'td':
                $this->tdHeaderRepository->getHeaderById(decode($id_header));
                $header = $this->tdHeaderRepository->getModel();
                break;
            case 'au':
                $this->auHeaderRepository->getHeaderById(decode($id_header));
                $header = $this->auHeaderRepository->getModel();
                break;
            case 'ema':
                $this->emaHeaderRepository->getHeaderById(decode($id_header));
                $header = $this->emaHeaderRepository->getModel();
                break;

            default:
                break;
        }
        
        $compact = compact('header', 'retailerProduct', 'id_retailer_product', 'id_header', 'text', 'code_product','type');
        if ($type == 'PDF') {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML(\View::make('cert.base', $compact)->render())->setPaper('Letter');
            return $pdf->stream('certificado_slip.pdf');
        } elseif ($type == 'IMPR') {
            return response()->json([
                        'payload' => view('cert.base', $compact)->render()
            ]);
        }
    }

    public function getFullYearAttribute($term,$type_term)
    {
        switch ($type_term) {
            case 'Y':
                return $term;
                break;
            case 'M':
                return round($term / 12, 0, PHP_ROUND_HALF_UP);
                break;
            case 'D':
                return round($term / 365, 0, PHP_ROUND_HALF_UP);
                break;
            default:
                return 0;
                break;
        }
    }

    public function getImcStatusAttribute($weight, $height)
    {
        $imc = '';

        $result = ceil(( $weight + 100 ) - $height);

        if ($result >= -15 && $result <= 15) {
            $imc = false . '|Saludable';
        } elseif ($result < 15) {
            $imc = true . '|Desnutricion';
        } elseif ($result > 15) {
            $imc = true . '|Obeso';
        }

        return $imc;
    }
}
