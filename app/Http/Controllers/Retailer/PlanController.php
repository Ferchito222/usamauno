<?php

namespace Sibas\Http\Controllers\Retailer;

use Illuminate\Http\Request;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Retailer\PlanRepository;

class PlanController extends Controller
{
    /**
     * @var PlanRepository
     */
    private $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function planByProduct($rp_id = null)
    {
        return $this->repository->getPlanByProduct($rp_id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($rp_id)
    {
        $response = [
            'plans' => null
        ];

        if (request()->ajax()) {
            $plans = $this->repository->getPlansByProduct(decode($rp_id));

            if ($plans->count() > 0) {
                $response['plans'] = $plans;
            }
        }

        return response()->json($response);
    }
}
