<?php

use Sibas\Entities\State;

class StateTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new State();
    }

    protected function getData()
    {
        $data = [];

        $states = config('base.states');

        foreach ($states as $key => $state) {
            array_push($data, [
                'state' => $state,
                'slug'  => $key
            ]);
        }

        return $data;
    }
}
