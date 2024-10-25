<?php

use Sibas\Entities\De\Coverage;

class CoverageTableSeeder extends BaseSeeder
{
    /**
     * @return \Illuminate\Support\Facades\DB
     */
    protected function getModel()
    {
        return new Coverage();
    }

    protected function getData()
    {
        $coverages = config('base.coverages');

        $data = [];

        foreach ($coverages as $coverage) {
            $data[] = [
                'name' => $coverage['name'],
                'slug' => $coverage['slug']
            ];
        }

        return $data;
    }
}
