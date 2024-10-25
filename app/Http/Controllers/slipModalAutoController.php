<?php

namespace Sibas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\PdfController;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Illuminate\Support\Facades\Auth;
use Sibas\Entities\User;
use Sibas\Entities\Au\Header;
use Sibas\Entities\Au\VehicleType;
use Sibas\Entities\Retailer;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\CompanyProduct;

class slipModalAutoController extends Controller
{

    /**
     * @var PdfController
     */
    protected $pdf;

    var $retailer;

    var $retailerProduct;


    public function __construct(PdfController $pdf)
    {
        $this->pdf = $pdf;

        $auth           = Auth::user();
        $user           = User::where('id', $auth->id)->first();
        $this->retailer = $user->retailerUser->retailer;

    }


    /**
     * funcion retorna ajax
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function ajaxBuscar(Request $request)
    {
        $var = $this->returnHtmlModal($request->get('type'), $request->get('id_header'), 0, $request->get('aux'));

        if (count($var['cli']) > 0) {
            return response()->json($var['html']);
        }

        return 0;
    }


    /**
     *
     * @param type $type
     * @param type $idHeader
     *
     * @return Response
     */
    public function generaPdf($type, $idHeader, $aux)
    {

        $var = $this->returnHtmlModal($type, decode($idHeader), 1, $aux);
        set_time_limit(0);

        return $this->pdf->create($var['html']['template_cert'], $type);
    }


    public function returnHtmlModal($type, $idHeader, $flagPdf, $aux)
    {
        $cli             = 1;
        $header          = Header::where('id', $idHeader)->first();
        $retailer        = Retailer::where('id', $header->client->ad_retailer_id)->first();
        $retailerProduct = RetailerProduct::where('ad_company_product_id', $aux)->get();
        $companyProduct  = CompanyProduct::where('id', $aux)->first();
        $vehicleType     = VehicleType::where('active', 1)->get();
        $groupVehicle    = [ ];

        $time = $header->getFullYearAttribute();
        $i    = 1;
        $e    = 1;
        foreach ($vehicleType as $key => $value) {

            $groupVehicle[$i][$value->id]['id_vehicle']   = $value->id;
            $groupVehicle[$i][$value->id]['name_vehicle'] = $value->vehicle;

            if ($e == 5) {
                $i++;
                $e = 1;
            }
            $e++;
        }
        $data  = [
            'fecha_validacion' => date('Y-m-d',
                strtotime('+' . $retailerProduct[0]->parameters[0]->expiration . ' days',
                    strtotime($header->created_at))),
        ];
        $tools = 1;
        switch ($type) {
            case 'cotizacion':
                $var = [ 'template_cert' => view('au.cert.cotizacion',
                    compact('time', 'header', 'retailer', 'retailerProduct', 'companyProduct', 'data', 'type', 'aux',
                        'tools'))->render()
                ];
                break;
            case 'emision':
                $var = [ 'template_cert' => view('au.cert.emision',
                    compact('header', 'retailer', 'retailerProduct', 'companyProduct', 'data', 'type', 'aux',
                        'vehicleType', 'groupVehicle', 'tools'))->render()
                ];
                break;
            case 'print_all':
                $cot   = view('au.cert.cotizacion',
                    compact('time', 'header', 'retailer', 'retailerProduct', 'companyProduct', 'data', 'type', 'aux',
                        'tools'))->render();
                $tools = 0;
                $emi   = view('au.cert.emision',
                    compact('header', 'retailer', 'retailerProduct', 'companyProduct', 'data', 'type', 'aux',
                        'vehicleType', 'groupVehicle', 'tools'))->render();

                $var = [ 'template_cert' => view('au.cert.printAll', compact('cot', 'emi'))->render() ];
                break;
            default:
                break;
        }
        $arr = [ 'html' => $var, 'cli' => $cli ];

        return $arr;
    }


    public function getEvaluationResponse($response)
    {
        $questions = json_decode($response->response, true);

        foreach ($questions as $question) {
            if ($question['expected'] != $question['response']) {
                return true;
            }
        }

        return false;
    }

}
