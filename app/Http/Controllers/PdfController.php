<?php

namespace Sibas\Http\Controllers;

use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;

class PdfController extends Controller
{
    /**
     * Show the form for creating a new resource PDF.
     *
     * @param string $view
     * @param string $title
     * @param string $size
     * @param string $orientation
     *
     * @return \Illuminate\Http\Response
     */
    public function create($view, $title, $size = 'Letter', $orientation = 'portrait')
    {
        $pdf = \PDF::loadHTML($view);

        return $pdf->setPaper($size, $orientation)->stream($title . '.pdf');
    }

}
