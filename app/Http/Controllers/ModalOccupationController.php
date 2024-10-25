<?php

namespace Sibas\Http\Controllers;

use Illuminate\Http\Request;

use Sibas\Entities\RetailerProduct;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;

class ModalOccupationController extends Controller
{
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajax_modal($id_retailer_product, $text)
    {
        $query_product = RetailerProduct::with('retailer','companyProduct.product')->where('id',$id_retailer_product)->first();
        $query_occupation = \DB::table('ad_retailer_product_activities as arpa')
                            ->join('ad_activities as ac', 'ac.id', '=', 'arpa.ad_activity_id')
                            ->select('ac.occupation')
                            ->where('arpa.ad_retailer_product_id',$id_retailer_product)
                            ->get();
        //dd($query_product);

        $response = view('partials.modal_content_occupation',
            compact('query_occupation', 'text', 'query_product'));

        return response()->json([
            'payload' => $response->render()
        ]);

    }
}
