<?php

namespace Sibas\Repositories\De;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\De\Header;
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
        $data = Header::with([
            'details.client',
            'creditProduct',
            'user.city',
            'user.agency',
        ])
            ->where('type', 'I')
            ->where('canceled', false)
            ->orderBy('issue_number', 'desc');

        $this->filtersByHeader($request, $data);

        return $data->paginate(50);
        /**
        $api = collect([
            'headers' => collect([]),
            'details' => collect([]),
            'request' => $request->all(),
        ]);

        $data->each(function ($h) use ($api) {
            $api['headers']->push([
                'idx'             => $h->idc,
                'prefix'          => $h->prefix,
                'issueNumber'     => $h->issue_number,
                'details'         => $h->details->pluck('id'),
                'amountRequested' => $h->amount_requested,
                'currency'        => $h->currency,
                'term'            => $h->term,
                'typeTerm'        => $h->type_term,
                'createdAt'       => $h->created_at,
                'userName'        => $h->user->username,
                'userFullName'    => $h->user->full_name,
                'userCity'        => $h->user->city->slug,
                'userCityName'    => $h->user->city->name,
                'userAgency'      => $h->user->agency->slug,
                'userAgencyName'  => $h->user->agency->name,
            ]);

            $h->details->each(function ($d) use ($api) {
                $api['details'][$d->id] = [
                    'clientFullName'  => $d->client->full_name,
                    'clientDni'       => $d->client->dni,
                    'clientExtension' => $d->client->extension,
                ];
            });
        });

        $api = $api->merge($data->toArray());
        $api->forget('data');

        return $api;
        /**/
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
            'id'         => date('U'),
            'ad_user_id' => $user->id,
            'reason'     => $this->data[ 'reason' ],
        ]);

        $this->model->canceled = true;

        return $this->saveModel();
    }
}