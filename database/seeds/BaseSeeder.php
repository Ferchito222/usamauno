<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    /**
     * @var Collection
     */
    protected static $entities;

    /**
     * @return \Illuminate\Support\Facades\DB
     */
    abstract protected function getModel();

    abstract protected function getData();

    public function run()
    {
        $this->create();
    }

    protected function create()
    {
        $data = $this->getData();

        foreach ($data as $value) {
            $this->addToEntity($this->getModel()->create($value));
        }
    }

    /**
     * @param $model
     * @return Collection
     * @throws Exception
     */
    protected function getModelData($model)
    {
        if (! isset(static::$entities[$model])) {
            throw new Exception('The collection ' . $model . ' does not exist!');
        }

        return static::$entities[$model];
    }

    protected function addToEntity($entity)
    {
        $reflection = new ReflectionClass($entity);

        $class = $reflection->getShortName();

        if (! isset(static::$entities[$class])) {
            static::$entities[$class] = new Collection();
        }

        static::$entities[$class]->add($entity);

        return $entity;
    }
}