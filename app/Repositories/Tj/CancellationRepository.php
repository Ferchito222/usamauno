<?php

namespace Sibas\Repositories\Tj;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\Tj\Header;
use Sibas\Entities\RetailerProduct;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class CancellationRepository extends BaseRepository
{
    use ReportTrait;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getHeaderList(Request $request)
    {
        $headers = Header::with('details.client', 'user.city', 'user.agency')
            ->where('type', 'I')
            ->where('canceled', false)
            ->orderBy('issue_number', 'desc');
        
        $this->filtersByHeader($request, $headers);

        return $headers->paginate(50);
    }


    public function data()
    {
        $select = [
            'id'   => '',
            'name' => 'Seleccione...',
        ];

        $users = DB::table('ad_users')
            ->select([
                'ad_users.username as id',
                'ad_users.full_name as name',
            ])
            ->join('ad_user_types', 'ad_users.ad_user_type_id', '=', 'ad_user_types.id')
            ->whereNotIn('username', ['admin', 'guest'])
            ->where('ad_user_types.code', 'UST')
            ->get();

        $cities = DB::table('ad_cities')
            ->select([
                'ad_cities.slug as id',
                'ad_cities.name',
            ])
            ->where('type_de', true)
            ->get();

        $agencies = DB::table('ad_agencies')
            ->select([
                'ad_agencies.slug as id',
                'ad_agencies.name',
                'ad_retailer_cities.ad_city_id as city',
            ])
            ->join('ad_retailer_city_agencies', 'ad_agencies.id', '=', 'ad_retailer_city_agencies.ad_agency_id')
            ->join('ad_retailer_cities', 'ad_retailer_city_agencies.ad_retailer_city_id', '=', 'ad_retailer_cities.id')
            ->get();

        $products = $this->getProducts();

        array_unshift($users, $select);
        array_unshift($cities, $select);
        array_unshift($agencies, $select);

        $type_terms = config('base.term_types');

        return compact('users', 'agencies', 'cities', 'type_terms', 'products');
    }


    public function getProducts()
    {
        return RetailerProduct::with('companyProduct.product')
            ->where('type', 'MP')
            ->where('active', true)
            ->get()
            ->pluck('companyProduct.product.name', 'idc')
            ->toArray();
    }

    public function getProductsById($id)
    {
        return RetailerProduct::with('companyProduct.product')
            ->where('id', $id)
            ->where('active', true)
            ->get()
            ->pluck('companyProduct.product.name', 'idc')
            ->toArray();
    }


    /**
     * @param Request $request
     * @param Model   $header
     *
     * @return bool
     */
    public function storeCancellation(Request $request, $header)
    {
        $user        = $request->user();
        $this->data  = $request->all();
        $this->model = $header;

        $this->model->cancellation()->create([
            'id'                => date('U'),
            'op_tj_header_id'   => $header->id,
            'ad_user_id'        => $user->id,
            'reason'            => $this->data[ 'reason' ],
        ]);

        $this->model->canceled = true;

        return $this->saveModel();
    }

    public function storeCancellationAutomatic($header, $user_id, $reason)
    {
        $this->model = $header;
        $id = DB::table('op_tj_cancellations')->max('id');
        $this->model->cancellation()->create([
            'id'                => $id+1,
            'op_tj_header_id'   => $header->id,
            'ad_user_id'        => $user_id,
            'reason'            => $reason,
            'code'              => 76,
        ]);

        $this->model->canceled = true;

        return $this->saveModel();
    }
}