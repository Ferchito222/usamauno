<?php

namespace Sibas\Http\Controllers\Retailer;

use Illuminate\Http\Request;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Retailer\PlanRepository;
use Sibas\Repositories\Retailer\PolicyRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class RetailerProductController extends Controller
{
    /**
     * @var RetailerProductRepository
     */
    private $repository;
    /**
     * @var PlanController
     */
    private $plan;
    /**
     * @var PolicyController
     */
    private $policy;

    public function __construct(RetailerProductRepository $repository)
    {
        $this->repository = $repository;
        $this->plan       = new PlanController(new PlanRepository);
        $this->policy     = new PolicyController(new PolicyRepository);
    }

    /**
     * Find Questions for Product Retailer
     *
     * @param $rp_id
     * @return array
     */
    public function questionByProduct($rp_id)
    {
        return $this->repository->getQuestionByProduct(decode($rp_id));
    }

    public function plans($rp_id)
    {
        return $this->plan->planByProduct(decode($rp_id));
    }

    public function policies($rp_id)
    {
        return $this->policy->policyByProduct(decode($rp_id));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
