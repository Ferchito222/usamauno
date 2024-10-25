<?php

namespace Sibas\Repositories\API;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Sibas\Repositories\BaseRepository;

class GeneralRepository extends BaseRepository
{
    protected $subject;

    protected $message;

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}