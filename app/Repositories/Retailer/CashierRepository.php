<?php

namespace Sibas\Repositories\Retailer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sibas\Entities\Retailer\Cashier;
use Sibas\Repositories\BaseRepository;

class CashierRepository extends BaseRepository
{

    /**
     * @return Model|Cashier mixed
     */
    public function getCashier()
    {
        return request()->session()->get('cashier');
    }

    public function getCashiers()
    {
        return Cashier::where('active', true)->get();
    }


    /**
     * @param Model|Cashier $cashier
     *
     * @return mixed
     */
    public function getRetailerProducts($cashier)
    {
        $rp = $cashier->retailer->retailerProducts()
            ->with('companyProduct.product')
            ->whereHas('companyProduct.product', function ($q) {
                $q->whereIn('code', [ 'elp', 'eb' ]);
            })
            ->get();

        return $rp;
    }

    /**
     * @param Request       $request
     * @param Model|Cashier $cashier
     * @param string        $code
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDataReport(Request $request, $cashier, $code, $format)
    {
        $this->setModel($code);
        $this->getRelations($code);

        $headers = collect([]);

        if (! is_null($this->model)) {
            $headers = $this->model->with($this->relations)
                ->whereHas('cashier', function ($q) use ($cashier) {
                    $q->where('id', $cashier->id);
                });

            if ($request->has('issue_number')) {
                $headers->where('issue_number', $request->get('issue_number'));
            }

            if ($request->has('dni') || $request->has('complement') || $request->has('extension')) {
                $headers->whereHas('detail.client', function ($q) use ($request) {
                    if ($request->has('dni')) {
                        $q->where('dni', $request->get('dni'));
                    }

                    if ($request->has('complement')) {
                        $q->where('complement', $request->get('complement'));
                    }

                    if ($request->has('extension')) {
                        $q->where('extension', $request->get('extension'));
                    }
                });
            }

            if ($request->has('date_begin') && $request->has('date_end')) {
                $date_begin = $this->carbon->createFromFormat('m/d/Y', $request->get('date_begin'));
                $date_end   = $this->carbon->createFromFormat('m/d/Y', $request->get('date_end'));

                $headers->whereRaw('date(date_issue) between "'
                                   . $date_begin->toDateString()
                                   . '" and "'
                                   . $date_end->toDateString()
                                   . '"');
            }

            $headers = ( $format === 'xls' ) ? $headers->get() : $headers->paginate(20);
        }

        return $headers;
    }
}