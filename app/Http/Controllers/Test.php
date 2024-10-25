<?php

namespace Sibas\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class Test extends Controller
{

    public function __construct()
    {

    }

    public function mail()
    {
        $data = [];

        Mail::send('emails.test', $data, function ($m) {
            $m->from(env('MAIL_USERNAME'), env('MAIL_NAME'));
            $m->subject('BNB - Mail test');

            $m->to('sguzman@sudseguros.com', 'Sergio Guzman');
            $m->to('mmamani@coboser.com', 'Miguel Angel');
        });
    }
}