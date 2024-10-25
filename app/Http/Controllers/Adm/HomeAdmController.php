<?php

namespace Sibas\Http\Controllers\Adm;

use Sibas\Http\Controllers\Controller;

class HomeAdmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adm.home');
    }

}
