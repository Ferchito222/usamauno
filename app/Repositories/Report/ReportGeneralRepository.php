<?php

namespace Sibas\Repositories\Report;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDO;
use Sibas\Entities\City;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\De\Header;
use Sibas\Entities\User;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Repositories\BaseRepository;

class ReportGeneralRepository extends BaseRepository
{

    protected $types = [
        'sale'         => [
            'title' => 'Ventas por Sucursal',
            'yAxis' => 'Seguros vendidos',
        ],
        'unemployment' => [
            'title' => 'Cesantía',
            'yAxis' => 'Seguros vendidos',
        ],
    ];


    use ReportTrait;


    /**
     * @param Request $request
     * @param string  $code
     * @param string  $type
     * @param string  $format
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function getDataReport(Request $request, $code, $type, $format)
    {
        $data = null;
        $this->setModel($code);
        $this->getRelations($code);

        if ($this->model instanceof Model) {
            try {
                $data = $this->model->with($this->relations);

                switch ($type) {
                    case 'GEN':
                        $data->where('type','I')->orderBy('issue_number', 'desc');
                        break;
                    case 'ISS':
                        $data->where('type', 'I')
                            ->where('issued', true)
                            ->orderBy('issue_number', 'desc');
                        break;
                    case 'QTE':
                        $data->where('type', 'Q')->orderBy('quote_number', 'desc');
                        break;
                    case 'BW':
                        $this->dataReportAttachments($data);
                        break;
                    case 'OP':
                        $data = CreditLine::with([
                            'clients',
                            'user.city',
                            'user.agency',
                            'header.details.client',
                            'header.details.coverages',
                            'header.details.facultative',
                            'header.creditProduct',
                            'header.cancellation.user',
                            'header.user.city',
                            'header.user.agency',
                        ])
                            ->orderBy('created_at', 'desc');

                        break;
                }

                $this->filtersByHeader($request, $data, $code, $type);

                if ($format === 'xls') {
                    $data = $data->get();
                } else {
                    $data = $data->paginate(25);
                }
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            } catch (\BadMethodCallException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return $data;
    }


    /**
     * @param Builder $data
     */
    public function dataReportAttachments($data)
    {
        $date_end   = $this->carbon->now();
        $date_begin = $date_end->copy()->subDays(15);

        $data->where('type','I')
            ->where('issued', true)
            ->where('canceled', false)
            ->whereNotNull('file')
            ->where(function ($q) use ($date_begin, $date_end) {
                $q->where('date_issue', '>=', $date_begin->copy()->startOfDay())
                    ->where('date_issue', '<=', $date_end->copy()->endOfDay());
            })
            ->orderBy('issue_number', 'desc');
    }


    /**
     * @param Request $request
     *
     * @return array
     *
     */
    public function getDataGraphic($request)
    {
        ini_set('display_errors', true);
        $data = [
            'categories' => [],
            'series'     => [],
            'type'       => [],
            'results'    => [],
        ];

        $cities = City::where('type_de', true);

        if (! empty($request->get('city'))) {
            $cities->with([ 'retailerCities.agencies' ])
                ->where('slug', $request->get('city'));
        }

        $user         = $request->user();
        $cities       = $cities->orderBy('id')->get();
        $type         = $request->get('type');
        $data['type'] = $this->types[$type];

        switch ($type) {
            case 'sale':
                /*
                 * Ventas por Sucursal
                 */

                $retailerProducts = $user->retailerUser->retailer->retailerProducts()
                    ->with([ 'companyProduct.product' ])
                    ->where('type', 'MP')
                    ->where('active', true)
                    ->get();

                $products = [];

                foreach ($retailerProducts as $retailerProduct) {
                    $code = $retailerProduct->companyProduct->product->code;
                    $name = $retailerProduct->companyProduct->product->name;

                    $builder = DB::table('op_' . $code . '_headers AS oh');

                    $this->setGraphicBuilderForSale($builder, $request, $cities, $name);

                    $products[] = $builder;
                }

                if (count($products) > 0) {
                    $hs = $products[0];

                    DB::setFetchMode(PDO::FETCH_ASSOC);

                    for ($i = 1; $i < count($products); $i ++) {
                        $hs = $hs->union($products[$i]);
                    }

                    $hs = $hs->get();

                    DB::setFetchMode(config('database.fetch'));

                    foreach ($hs as $h) {
                        if (count($data['categories']) === 0) {
                            $categories = array_keys($h);

                            foreach ($categories as $key => $category) {
                                if ($category === 'product_name') {
                                    unset($categories[$key]);
                                }
                            }

                            $data['categories'] = array_values($categories);
                        }

                        $series             = [];
                        $series[ 'name' ]   = $h[ 'product_name' ];

                        unset($h['product_name']);

                        $series[ 'data' ]   = array_values($h);
                        $data[ 'series' ][] = $series;
                    }
                }

                return $data;

                break;
            case 'unemployment':
                /*
                 * Cesantia
                 */

                $headers = Header::with([ 'details', 'creditProduct', 'user.city', 'user.agency' ])
                    ->whereHas('creditProduct', function ($q) {
                        $q->where('slug', '7x5');
                    })
                    ->where('issued', true);

                if (! empty($request->get('date_begin')) && ! empty($request->get('date_end'))) {
                    $date_begin = $this->carbon->createFromFormat('d/m/Y', $request->get('date_begin'));
                    $date_end   = $this->carbon->createFromFormat('d/m/Y', $request->get('date_end'));

                    $headers->whereRaw(DB::raw('date(date_issue) between "'
                                               . $date_begin->toDateString()
                                               . '" and "'
                                               . $date_end->toDateString()
                                               . '"'));
                }

                $headers = $headers->get();
                $headers = $headers->groupBy(function ($item, $key) {
                    return $item->user->city->slug;
                });

                $data['series'] = [
                    0 => [
                        'name' => 'Emisiones',
                        'data' => [],
                    ],
                    1 => [
                        'name' => 'Cesantías',
                        'data' => [],
                    ],
                ];

                foreach ($cities as $city) {
                    $agencies = $city->retailerCities->first()->agencies;

                    if (empty($request->get('city'))) {
                        $data['categories'][] = $city->name;
                    } else {
                        foreach ($agencies as $agency) {
                            $data['categories'][] = $agency->name;
                        }
                    }

                    $issuance     = 0;
                    $unemployment = 0;

                    if ($headers->has($city->slug)) {
                        $hs = $headers[$city->slug];

                        if (empty($request->get('city'))) {
                            $users    = clone $hs;
                            $issuance = $headers[$city->slug]->count();

                            foreach ($hs as $header) {
                                $sum = $header->details->sum(function ($detail) {
                                    return $detail->involuntary_unemployment ? 1 : 0;
                                });

                                $unemployment += $sum;
                            }

                            $this->setUserResults($users, $data, 'S');
                        } else {
                            $hs = $hs->groupBy(function ($item, $key) {
                                return $item->user->agency->slug;
                            });

                            foreach ($agencies as $agency) {
                                $issuance     = 0;
                                $unemployment = 0;

                                if ($hs->has($agency->slug)) {
                                    $users    = clone $hs[$agency->slug];
                                    $issuance = $hs[$agency->slug]->count();

                                    foreach ($hs[$agency->slug] as $header) {
                                        $sum = $header->details->sum(function ($detail) {
                                            return $detail->involuntary_unemployment ? 1 : 0;
                                        });

                                        $unemployment += $sum;
                                    }

                                    $this->setUserResults($users, $data, 'A');
                                }

                                $data['series'][0]['data'][] = $issuance;
                                $data['series'][1]['data'][] = $unemployment;
                            }
                        }
                    }

                    if (empty($request->get('city'))) {
                        $data['series'][0]['data'][] = $issuance;
                        $data['series'][1]['data'][] = $unemployment;
                    }
                }

                return $data;

                break;
        }
    }

    /**
     * @param Builder    $builder
     * @param Request    $request
     * @param Collection $cities
     * @param string     $name
     */
    public function setGraphicBuilderForSale($builder, $request, $cities, $name)
    {
        $builder->select([
            DB::raw('"' . $name . '" product_name'),
        ]);

        foreach ($cities as $city) {
            $builder->addSelect([
                DB::raw('count(CASE WHEN ad_cities.slug = "' . $city->slug . '"
                            THEN oh.id
                                ELSE NULL END) AS "' . $city->name . '"'),
            ]);
        }

        $builder
            ->join('ad_users', 'oh.ad_user_id', '=', 'ad_users.id')
            ->join('ad_cities', 'ad_users.ad_city_id', '=', 'ad_cities.id')
            ->where('oh.issued', true)
            ->groupBy('product_name');

        if (! empty($request->get('date_begin')) && ! empty($request->get('date_end'))) {
            $date_begin = $this->carbon->createFromFormat('d/m/Y', $request->get('date_begin'));
            $date_end   = $this->carbon->createFromFormat('d/m/Y', $request->get('date_end'));

            $builder->whereRaw(DB::raw('date(date_issue) between "'
                                       . $date_begin->toDateString()
                                       . '" and "'
                                       . $date_end->toDateString()
                                       . '"'));
        }
    }


    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }


    /**
     * @param Collection $users
     * @param array      $data
     * @param string     $category
     */
    protected function setUserResults($users, &$data, $category)
    {
        $users = $users->groupBy(function ($item, $key) {
            return $item->user->username;
        });

        foreach ($users as $key => $uhs) {
            $user_unemployment = 0;
            $category_name     = ($category === 'A' ? $uhs->first()->user->agency->name : $uhs->first()->user->city->name);

            foreach ($uhs as $uh) {
                $sum = $uh->details->sum(function ($detail) {
                    return $detail->involuntary_unemployment ? 1 : 0;
                });

                $user_unemployment += $sum;
            }

            array_push($data['results'], [
                'user'         => $uhs->first()->user->full_name . ' (' . $key . ')',
                'issuance'     => $uhs->count(),
                'unemployment' => $user_unemployment,
                'category'     => $category_name,
            ]);
        }
    }

    public function getDataReportGraphic(Request $request, $code)
    {        
        $model = app('Sibas\Entities\\' . ucfirst($code) . '\Header');
        $data = $model::with(['details','cancellation', 'user.city', 'user.agency', 'user.target', 'user.assignment',])
                    ->where([
                            'type'   => 'I',
                            'issued' => true,
                        ])->orderBy('issue_number', 'desc');
        
        $this->filtersByHeader($request, $data, $code);

        return $data->get();
    }
}