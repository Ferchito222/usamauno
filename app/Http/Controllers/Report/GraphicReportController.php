<?php

namespace Sibas\Http\Controllers\Report;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Report\ReportGeneralRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class GraphicReportController extends Controller
{
    protected $title = '';

    /**
     * @var ReportGeneralRepository
     */
    private $repository;

    /**
     * @var RetailerProductRepository
     */
    private $retailerProductRepository;


    public function __construct(
        ReportGeneralRepository $repository,
        RetailerProductRepository $retailerProductRepository
    ) {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);

        $this->repository                = $repository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->carbon = new Carbon;
    }

    public function graphic(Guard $auth, Request $request, $format)
    {
        if ($auth) {
            $title           = 'Reporte Gráfíco';
            $view            = 'report.graphic.master';
            
            if (view()->exists($view)) {
                $headers = [];
                $codes = [];
                foreach ($this->getRetailerProducts() as $key => $retailerProduct) {
                    if($retailerProduct->goals){
                        $headers[] = $this->getHeader($request, $retailerProduct->companyProduct->product->code, $retailerProduct);
                        $codes[] = $retailerProduct->companyProduct->product->code;
                    }
                }
                $maxHeaders = $this->getMaxHeaders($headers, $codes);
                $headers = $this->groupByProducts($headers, $codes);
                
                $user    = $auth->user();
                $data    = $this->repository->data($user);
                
                if ( ! is_null($headers)) {
                    if ($format === 'pdf') {
                        $view_xls = 'report.graphic.partials.graphic';
                        if (view()->exists($view_xls)) {
                            $this->title = $title;
                            return $this->repository->exportPdf($headers, $maxHeaders, $view_xls, $title, $format, $this->getRetailerProducts()[0]);
                        }
                    }
                    
                    if ($request->has('_token')) {
                        $request->flash();
                    }

                    return view($view, [
                        
                        'headers' => $headers,
                        'maxHeaders' => $maxHeaders,
                        'data' => $data,
                        'title'   => $title,
                        'format'  => $format,
                        'user'  => $user,
                        
                        'retailerProduct' => $this->getRetailerProducts()[0],
                    ]);
                }
            }
        }

        return redirect()->route('home');
    }
    
    public function groupByProducts($array, $codes)
    {
        $block['sucursal'] = [];
        $block['nacional'] = [];
        foreach ($array as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2['nacional'] as $key3 => $value3) {
                    foreach ($codes as $key4 => $value4) {
                        $block['nacional'][$value3['sucursal']][$value4] = [];
                    }
                }
                foreach ($value2['sucursal'] as $key5 => $value5) {
                    foreach ($value5['agencias'] as $key6 => $value6) {
                        foreach ($codes as $key7 => $value7) {
                            $block['sucursal'][$value5['sucursal']][$value6['agency']][$value7]=[];
                        }
                    }
                }   
            }
        }
        
        # Nacional / Sucursal
        foreach ($array as $key => $value) {
            foreach ($value as $key2 => $value2) {
                
                foreach ($value2['nacional'] as $key3 => $value3) {
                    $block['nacional'][$value3['sucursal']][$key2]=$value3;
                }
                foreach ($value2['sucursal'] as $key4 => $value4) {
                    foreach ($value4['agencias'] as $key5 => $value5) {
                        $block['sucursal'][$value4['sucursal']][$value5['agency']][$key2]=$value5;
                    }
                }
                $block['diffMonth'] = $value2['diffMonth'];
            }
        }
        
        # Usuarios
        foreach ($block['sucursal'] as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    if($value3){
                        foreach ($value3['users'] as $key4 => $value4) {
                            if(!isset($block['users'][$key][$key2][$key4.'-'.$value4[0]->user->full_name]))
                                foreach ($codes as $keyCode => $valueCode) {
                                    $block['users'][$key][$key2][$key4.'-'.$value4[0]->user->full_name][$valueCode] = [];
                                }
                            foreach ($value4 as $key5 => $value5) {
                                $block['users'][$key][$key2][$key4.'-'.$value4[0]->user->full_name][strtolower($value5->prefix)][]=$value5;
                            }
                        }
                    }
                }    
            }
        }
        
        return $block;
    }
    
    public function getRetailerProducts()
    {
        $retailerProducts = [];
        foreach (auth()->user()->retailerUser->retailer->retailerProducts as $key => $value) {
            if ($value->type == 'MP' && $value->active) {
                if (auth()->user()->profile->first()->slug === 'SEP' || auth()->user()->profile->first()->slug === 'REP') {
                    if (auth()->user()->retailerUser->products()->where('ad_products.id', $value->companyProduct->product->id)->count() === 1) {
                        $retailerProducts[] = $value;
                    }
                }
            }
        }
        return $retailerProducts;
    }
    
    public function getHeader($request, $code, $retailerProduct)
    {
        $date = Carbon::now();

        if (!$request->get('date_begin'))
            $request->request->add(['date_begin' => Carbon::now()->firstOfMonth()->format('d/m/Y')]);
        if (!$request->get('date_end'))
            $request->request->add(['date_end' => Carbon::now()->endOfMonth()->format('d/m/Y')]);

        $headers = $this->repository->getDataReportGraphic($request, $code);
        $meta = $retailerProduct->goals->goal * $this->getDifMonth($request);
        $array[$code]['nacional'] = $this->groupByNacional($headers, $meta);
        $array[$code]['sucursal'] = $this->groupBySucursal($headers, $meta);
        $array[$code]['diffMonth'] = $this->getDifMonth($request);
        $array[$code]['headers'] = $headers;
        
        return $array;
    }

    public function getDifMonth($request)
    {         
        $date_begin =   $this->carbon->createFromFormat('d/m/Y', $request->get('date_begin'));
        $date_end   =   $this->carbon->createFromFormat('d/m/Y', $request->get('date_end'));
        
        $diff = $date_begin->firstOfMonth()->diffInMonths($date_end->endOfMonth());
        
        return $diff+1;
    }
    
    public function groupBySucursal($model, $meta)
    {
        $data = [];
        foreach ($model as $key => $value) {
            $data[$value->user->city->name][$value->user->agency->name][$value->user->id][]=$value;
        }
        $arr = [];
        $cont = 0;
        foreach ($data as $key2 => $value2) {
            $arr[$cont]['sucursal'] = $key2;
            
            $cont2 = 0;
            foreach ($value2 as $key3 => $value3) {
                $cont3 = 0;
                foreach ($value3 as $key4 => $value4) {
                    $cont3+=count($value4);
                }
                $arr[$cont]['agencias'][$cont2]['agency'] = $key3;
                $arr[$cont]['agencias'][$cont2]['meta_total'] = count($value3)*$meta;
                $arr[$cont]['agencias'][$cont2]['total_ejecutado'] = $cont3;
                $arr[$cont]['agencias'][$cont2]['users'] = $data[$key2][$key3];
                
                $cont2++;
            }
        $cont++;
        }
        return $arr;
    }
    
    
    public function groupByNacional($model, $meta)
    {
        $arrA = [];
        foreach ($model as $key => $value) {
            $arrA[$value->user->city->name][$value->user->id][]=$value;
        }
        $arrB = [];
        $y = 0;
        foreach ($arrA as $key2 => $value2) {
            $cont = 0;
            foreach ($value2 as $key3 => $value3) {
                $cont+=count($value3); 
            }
            $arrB[$y]['sucursal']        = $key2;
            $arrB[$y]['meta_total']      = count($value2) * $meta;
            $arrB[$y]['total_ejecutado'] = $cont;
            $y++;
        }
        
        return $arrB;
    }
    
    public function getMaxHeaders($array)
    {
        $data['users'] = [];
        $data['agencies'] = [];
        foreach ($array as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2['headers'] as $key3 => $value3) {
                    $data['users'][$value3->user->id.'-'.$value3->user->full_name.'-'.$value3->user->agency->name.'-'.$value3->user->city->name][]=$value3;
                    $data['agencies'][$value3->user->agency->id.'-'.$value3->user->agency->name.'-'.$value3->user->city->name][]=$value3;
                }
            }
        }
        
        foreach ($data as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $data[$key][$key2]=count($value2);
            }
        }
        arsort($data['users']);
        arsort($data['agencies']);
        
        return $data;
    }
}
