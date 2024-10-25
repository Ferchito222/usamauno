<?php

namespace Sibas\Collections;

use Illuminate\Database\Eloquent\Collection;

class BaseCollection extends Collection
{
    protected $selectName = 'Seleccione';

    public function selectOption()
    {
        $selectOption = [
            [
                'id'   => '',
                'name' => $this->selectName
            ]
        ];

        return collect($selectOption);
    }
}