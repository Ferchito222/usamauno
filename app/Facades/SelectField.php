<?php

namespace Sibas\Facades;


use Illuminate\Support\Facades\Facade;

class SelectField extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'selectField';
    }
}