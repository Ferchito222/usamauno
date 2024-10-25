<?php

namespace Sibas\Repositories\De;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sibas\Entities\CreditProduct;
use Sibas\Entities\De\CreditLine;
use Sibas\Entities\Rate;
use Sibas\Repositories\BaseRepository;

class CreditLineRepository extends BaseRepository
{

    /**
     * @param int $id
     *
     * @return bool
     */
    public function getCreditLineById($id)
    {
        try {
            $this->model = CreditLine::with([
                'clients' => function ($q) {
                    $q->orderBy('headline', 'asc');
                }
            ])
                ->where('id', $id)
                ->first();

            if (! is_null($this->model)) {
                return true;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function storeCreditLine($request)
    {
        $data    = $request->all();
        $id      = date('U');
        $user    = $request->user();
        $clients = [];
        $p_types = $this->getPolicyTypes();

        try {
            $this->model = CreditLine::create([
                'id'           => $id,
                'ad_user_id'   => $user->id,
                'number_line'  => $data[ 'numberLine' ],
                'coverage'     => $data[ 'coverage' ],
                'policy_type'  => $data[ 'policyType' ],
                'free_cover'   => false,
                'deleted'      => false,
                'data_records' => [],
            ]);
            
            if ($request->has('clients')) {
                $data['clients'] = collect($data['clients']);
                $details         = $data['clients']->count();

                if ($data['policyType'] === 'TE') {
                    $details = $data['clients']->where('insured', true)->count();
                }


                foreach ($data['clients'] as $index => $c) {
                    $rate = $this->getRateFinal($p_types[$data['policyType']]['rate'], $data['policyType'], $details);

                    array_push($clients, [
                        'id'                => ($id - $index),
                        'dni'               => $c[ 'dni' ],
                        'extension'         => '',
                        'complement'        => $c[ 'complement' ],
                        'number'            => $c[ 'number' ],
                        'numbers'           => $c[ 'numbers' ],
                        'percentage_credit' => $c[ 'percentageCredit' ],
                        'rate'              => $rate,
                        'percentage'        => $c[ 'percentage' ],
                        'terms'             => '',
                        'insured'           => $c[ 'insured' ],
                        'headline'          => ($index + 1),
                    ]);
                }
            }

            $this->model->clients()->createMany($clients);

            if ($this->getCreditLineById($id)) {
                return true;
            }
        } catch (QueryException $e) {
            $this->errors = $e->getMessage();
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return bool
     */
    public function updateCreditLine($request)
    {
        $data = $request->all();

        if ($this->getCreditLineById(decode($data['idx']))) {
            try {
                $p_types = $this->getPolicyTypes();
                $deletes = [];
                $id_new  = date('U');

                $this->model->update([
                    'number_line' => $data[ 'numberLine' ],
                    'coverage'    => $data[ 'coverage' ],
                    'policy_type' => $data[ 'policyType' ],
                ]);

                $details = count($data['clients']);

                foreach ($data[ 'clients' ] as $index => $c) {
                    $id   = ($id_new - $index);
                    $rate = $this->getRateFinal($p_types[$data['policyType']]['rate'], $data['policyType'], $details);

                    $d = [
                        'dni'               => $c[ 'dni' ],
                        'complement'        => mb_strtoupper($c[ 'complement' ]),
                        'number'            => $c[ 'number' ],
                        'numbers'           => json_encode($c[ 'numbers' ]),
                        'percentage_credit' => $c[ 'percentageCredit' ],
                        'rate'              => $rate,
                        'percentage'        => $c[ 'percentage' ],
                        'insured'           => $c[ 'insured' ],
                        'headline'          => ($index + 1),
                    ];

                    if (key_exists('idx', $c)) {
                        $id = decode($c['idx']);

                        $this->model->clients()->where('id', $id)->update($d);
                    } else {
                        $d[ 'id' ]      = $id;
                        $d[ 'numbers' ] = $c[ 'numbers' ];

                        $this->model->clients()->create($d);
                    }

                    $deletes[] = $id;
                }

                $this->model->clients()->whereNotIn('id', $deletes)->delete();

                $this->model = $this->model->fresh(['clients']);

                return true;
            } catch (QueryException $e) {
                $this->errors = $e->getMessage();
            }
        }

        return false;
    }


    /**
     * @param double $rate
     * @param string $policy_type
     * @param int    $details
     *
     * @return float
     */
    protected function getRateFinal($rate, $policy_type, $details)
    {
        if ($policy_type === 'TE') {
            $rate = $this->getRateTendered($rate, $details) / $details;
        }

        if ($policy_type !== 'CA') {
            $rate = $rate * 12;
        }

        return $rate;
    }


    public function getApiModel()
    {
        $cl = $this->model;

        $model = [
            'idx'        => $cl->idc,
            'numberLine' => $cl->number_line,
            'coverage'   => $cl->coverage,
            'policyType' => $cl->policy_type,
            'confirmed'  => $cl->confirmed,
            'clients'    => collect([]),
        ];

        $cl->clients->each(function ($c) use ($model) {
            $model['clients']->push([
                'idx'              => $c->idc,
                'dni'              => $c->dni,
                'complement'       => $c->complement,
                'number'           => $c->number,
                'numbers'          => $c->numbers,
                'percentageCredit' => $c->percentage_credit,
                'rate'             => $c->rate,
                'percentage'       => $c->percentage,
                'insured'          => $c->insured,
            ]);
        });

        return $model;
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function confirmCreditLine($id)
    {
        if ($this->getCreditLineById($id)) {
            try {
                $this->model->update([
                    'confirmed' => true
                ]);

                return true;
            } catch (QueryException $e) {
                $this->errors = $e;
            }
        }

        return false;
    }


    /**
     * Check Number Line if exists
     * 
     * @int $number_line
     * @return bool
     */
    public function checkNumberLine($number_line)
    {
        if (CreditLine::where('number_line', $number_line)->count() === 0) {
            return true;
        }

        return false;
    }


    /**
     * @param int         $number_line
     * @param int         $cp_id
     * @param null|string $cp_slug
     *
     * @return int
     */
    public function checkCreditLineNumber($number_line, $cp_id, $cp_slug = null)
    {
        $cp = CreditProduct::selectRaw('CASE slug WHEN "PLT" THEN "TE" WHEN "PLNT" THEN "TN" WHEN "PCA" THEN "TN" END as tendered')
                           ->where('active', true);

        if ($cp_id > 0) {
            $cp->where('id', $cp_id);
        } elseif (! is_null($cp_slug)) {
            $cp->where('slug', $cp_slug);
        } else {
            $cp->whereNull('id');
        }

        $cp = $cp->first();

        if (! is_null($cp)) {
            $creditLine = CreditLine::with(['clients'])
                                     ->where('number_line', trim($number_line))
                                     ->where('confirmed', true)
                                     ->first();

            if ($creditLine instanceof CreditLine) {
                if ($creditLine->coverage !== 'UC') {
                    if ($cp->tendered === $creditLine->policy_type) {
                        $this->model = $creditLine;

                        return 1;
                    }
                } else {
                    return 3;
                }

                return 2;
            }
        }

        return 0;
    }


    /**
     * @param integer $number_line
     * @param integer $dni
     * @param string  $complement
     *
     * @return bool
     */
    public function checkClientInsured($number_line, $dni, $complement)
    {
        $i = CreditLine::where('number_line', $number_line)
                       ->whereHas('clients', function ($q) use ($dni, $complement) {
                           $q->where('dni', $dni)
                             ->where('complement', $complement)
                             ->where('insured', true);
                       })->count();

        return ($i > 0 ? true : false);
    }


    /**
     * @return mixed
     */
    public function getPolicyTypes()
    {
        $policy_types = [
            '' => [
                'rate' => 0,
                'name' => 'Seleccione...'
            ]
        ];

        $rs = Rate::select([
            'rate_final as rate',
            DB::raw('CASE ad_credit_products.slug
                        WHEN "PLT" THEN "TE"
                        WHEN "PMO" THEN "TE"
                        ELSE "TN"
                    END as policy_type'),
            DB::raw('CASE 
                        WHEN ad_credit_products.slug = "PLT" OR ad_credit_products.slug = "PMO" THEN "Licitada"
                        WHEN ad_credit_products.slug = "PCA" THEN "Tarjetas"
                        ELSE "No Licitada"
                    END as name')
        ])
            ->leftJoin('ad_credit_products', 'ad_rates.ad_credit_product_id', '=', 'ad_credit_products.id')
            ->join('ad_retailer_products', 'ad_rates.ad_retailer_product_id', '=', 'ad_retailer_products.id')
            ->join('ad_company_products', 'ad_retailer_products.ad_company_product_id', '=', 'ad_company_products.id')
            ->join('ad_products', 'ad_company_products.ad_product_id', '=', 'ad_products.id')
            ->where('ad_products.code', 'de')
            ->whereIn('ad_retailer_products.type', ['MP', 'RP'])
            ->groupBy('policy_type')
            ->orderBy('name')
            ->get();

        foreach ($rs as $r) {
            $policy_types[$r->policy_type] = [
                'rate' => $r->rate,
                'name' => $r->name
            ];
        }

        return $policy_types;
    }
}