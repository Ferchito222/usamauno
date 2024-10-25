<?php

namespace Sibas\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Entities\Profile;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\User;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\De\FacultativeRepository as FacultativeDeRepository;
use Sibas\Repositories\Au\FacultativeRepository as FacultativeAuRepository;
use Sibas\Repositories\Td\FacultativeRepository as FacultativeTdRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class HomeController extends Controller
{

    /**
     * @var string
     */
    protected $inbox;

    /**
     * @var int
     */
    protected $header_id;

    /**
     * @var FacultativeDeRepository
     */
    protected $facultativeDeRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;

    /**
     * @var FacultativeAuRepository
     */
    protected $facultativeAuRepository;

    /**
     * @var FacultativeTdRepository
     */
    protected $facultativeTdRepository;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Profile
     */
    private $profile;


    public function __construct(
        FacultativeDeRepository $facultativeDeRepository,
        FacultativeAuRepository $facultativeAuRepository,
        FacultativeTdRepository $facultativeTdRepository,
        RetailerProductRepository $retailerProductRepository
    ) {
        $this->facultativeDeRepository   = $facultativeDeRepository;
        $this->retailerProductRepository = $retailerProductRepository;

        $this->inbox                   = 'all';
        $this->header_id               = null;
        $this->facultativeAuRepository = $facultativeAuRepository;
        $this->facultativeTdRepository = $facultativeTdRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Guard $auth
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Guard $auth)
    {
        if (request()->session()->has('guest')) {
            $retailerProduct = $auth->user()->retailerUser->retailer->with([
                'retailerProducts' => function ($q) {
                    $q->where('type', 'MP')
                        ->whereHas('companyProduct.product', function ($q) {
                            $q->where('code', 'de');
                        });
                },
            ])->first()->retailerProducts->first();

            return redirect()->route('de.create', [ 'rp_id' => encode($retailerProduct->id) ]);
        }

        $arp_id = null;

        if (request()->has('inbox') && in_array(request()->get('inbox'), config('base.inbox'))) {
            $this->inbox = request()->get('inbox');
        }

        if (request()->has('arp')) {
            $arp_id = decode(request()->get('arp'));
        }

        if (request()->has('odh')) {
            $this->header_id = decode(request()->get('odh'));
        }

        $this->user    = $auth->user();
        $this->profile = $this->user->profile->first();

        $data = [
            'products' => [ ],
        ];

        if ($this->profile->slug === 'SEP' || $this->profile->slug === 'COP') {
            foreach ($this->user->retailerUser->retailer->retailerProducts as $retailerProduct) {
                $this->setData($retailerProduct, $data);
            }
        }

        $current_date = $this->retailerProductRepository->carbon->now();

        return view('home', [
            'user'         => $this->user,
            'data'         => $data,
            'current_date' => $current_date,
        ]);
    }


    /**
     * @param Model|RetailerProduct $retailerProduct
     * @param array                 $data
     */
    private function setData($retailerProduct, &$data)
    {
        if ($retailerProduct->type === 'MP' && $retailerProduct->facultative && $retailerProduct->active) {
            $flag_product = false;
            $companies = $this->user->retailerUser->companies;

            switch ($this->profile->slug) {
                case 'COP':
                    if ($this->profile->slug === 'COP'
                        && in_array($retailerProduct->companyProduct->ad_company_id, $companies)) {
                        $flag_product = true;
                    }
                    break;
                case 'SEP':
                    if ($this->user->retailerUser->products()
                            ->where('ad_products.id', $retailerProduct->companyProduct->product->id)->count() === 1) {
                        $flag_product = true;
                    }
                    break;
            }

            if ($flag_product) {
                $product     = $retailerProduct->companyProduct->product;
                $product->rp = $retailerProduct;

                switch ($product->code) {
                    case 'de':
                        $product->records = $this->facultativeDeRepository->getRecords($this->user, $this->inbox,
                            $this->header_id);
                        break;
                    case 'au':
                        $product->records = $this->facultativeAuRepository->getRecords($this->user, $this->inbox,
                            $this->header_id);
                        break;
                    case 'td':
                        $product->records = $this->facultativeTdRepository->getRecords($this->user, $this->inbox,
                            $this->header_id);
                        break;
                }

                array_push($data['products'], $product);
            }
        }
    }

}
