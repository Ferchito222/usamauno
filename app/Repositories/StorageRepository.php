<?php

namespace Sibas\Repositories;

use Illuminate\Support\Facades\Storage;

class StorageRepository
{

    /**
     * @var int
     */
    protected $id;
    /**
     * @var array
     */
    protected $data;


    /**
     * @param string $type
     *
     * @return bool
     */
    public function getDataStorage($type)
    {
        $file = $this->getFileName($type);

        if (Storage::has($file)) {
            $data       = Storage::get($file);
            $this->data = json_decode($data, true);

            if (count($this->data) > 0) {
                return true;
            }
        }

        return false;
    }


    /**
     * @param string|array $data
     * @param string       $type
     */
    public function storeDataStorage($data, $type)
    {
        $file = $this->getFileName($type);

        Storage::put($file, json_encode($data));
    }


    /**
     * @param string $type
     *
     * @return string
     */
    protected function getFileName($type)
    {
        return $type . '/' . $type . '_' . $this->id . '_' . date('Ymd');
    }


    /**
     * @param string $type
     */
    public function deleteDataStorage($type)
    {
        $file = $this->getFileName($type);

        Storage::delete($file);
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}