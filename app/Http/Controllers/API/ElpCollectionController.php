<?php

namespace Sibas\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\API\ElpCollectionRepository;

class ElpCollectionController extends Controller
{

    /**
     * @var ElpCollectionRepository
     */
    private $repository;


    public function __construct(ElpCollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function export()
    {
        $success = $this->repository->collectionExport();

        if ($success) {
            $this->sendMail();
        }

        dd('ERROR - EXPORT');
    }

    public function importConfirm()
    {
        $success = $this->repository->collectionImportConfirm();

        if ($success) {
            $this->sendMail();
        }

        dd('ERROR - CONFIRM');
    }

    public function importProduction()
    {
        $success = $this->repository->collectionImportProduction();

        if ($success) {
            $this->sendMail();
        }

        dd('ERROR - PRODUCTION');
    }

    private function sendMail()
    {
        $subject = $this->repository->getSubject();
        $message = $this->repository->getMessage();

        var_dump($message);

        Mail::raw($message, function ($m) use ($subject) {
            $m->from('admin@abrenet.com', 'Abrenet');
            $m->subject($subject);

            $m->to('mmamani@coboser.com', 'Miguel Angel Mamani');
            $m->to('jvera@coboser.com', 'Juan Pablo Vera');

            $m->cc('juanpaverah@gmail.com');
            $m->cc('djmikeangelo@icloud.com');
        });

        dd('OK -->> ' . $message . ' - ' . date('Y-m-d H:i:s'));
    }

}