<?php

namespace Sibas\Http\Controllers\Client;

use Illuminate\Http\Request;
use Sibas\Entities\Client;
use Sibas\Entities\De\Detail;
use Sibas\Entities\De\Header;
use Sibas\Entities\De\Response;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\Client\QuestionFormRequest;
use Sibas\Repositories\Client\QuestionRepository;
use Sibas\Repositories\De\DetailRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;
use Sibas\Repositories\StorageRepository;

class QuestionController extends Controller
{

    /**
     * @var QuestionRepository
     */
    protected $repository;

    /**
     * @var DetailRepository
     */
    protected $detailRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;
    /**
     * @var StorageRepository
     */
    protected $storageRepository;


    public function __construct(
        QuestionRepository $repository,
        DetailRepository $detailRepository,
        RetailerProductRepository $retailerProductRepository,
        StorageRepository $storageRepository
    ) {
        $this->repository                = $repository;
        $this->detailRepository          = $detailRepository;
        $this->retailerProductRepository = $retailerProductRepository;
        $this->storageRepository = $storageRepository;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param String $rp_id
     * @param String $header_id
     * @param String $detail_id
     *
     * @return \Illuminate\Http\Response
     */
    public function create($rp_id, $header_id, $detail_id)
    {
        if ($this->detailRepository->getDetailById(decode($detail_id))) {
            $detail = $this->detailRepository->getModel();

            QuestionCreate:
            $data = [
                'detail'      => $detail,
                'questions'   => $this->retailerProductRepository->getQuestionByProduct(decode($rp_id),
                    $detail->header),
                'observation' => '',
            ];

            return view('client.de.question-create', compact('rp_id', 'header_id', 'detail_id', 'data'));
        } elseif (request()->session()->has('guest')) {
            /*
             * Local Storage
             */
            $this->storageRepository->setId(decode($header_id));

            if ($this->storageRepository->getDataStorage('guest')) {
                $data = $this->storageRepository->getData();

                foreach ($data['header']['details'] as $dt) {
                    if ($dt['id'] === decode($detail_id)) {
                        $detail         = new Detail($dt);
                        $detail->client = new Client($dt['client']);
                        $detail->header = new Header($data['header']);

                        goto QuestionCreate;
                    }
                }
            }
            /*
             * Local Storage
             */
        }

        return redirect()->route('de.client.list', [
            'rp_id'     => $rp_id,
            'header_id' => $header_id
        ])->with([ 'error_detail' => 'Ha ocurrido un error en el Cuestionario de Salud' ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionFormRequest $request
     * @param                     $rp_id
     * @param                     $header_id
     * @param                     $detail_id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function storeDe(QuestionFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($this->detailRepository->getDetailById(decode($detail_id))) {
            $request['detail'] = $this->detailRepository->getModel();

            if ($this->repository->storeQuestionDe($request)) {
                QuestionStore:
                return redirect()->route('de.client.list', [
                    'rp_id'     => $rp_id,
                    'header_id' => $header_id,
                ])->with([
                    'success_question' => 'La información y el cuestionario de salud del Cliente fueron registrados',
                ]);
            }
        } elseif (request()->session()->has('guest')) {
            /*
             * Local Storage
             */
            $this->storageRepository->setId(decode($header_id));

            if ($this->storageRepository->getDataStorage('guest')) {
                $data          = $this->storageRepository->getData();
                $flag_response = false;

                foreach ($data['header']['details'] as &$d) {
                    if ($d['id'] == decode($detail_id)) {
                        $detail         = new Detail($d);
                        $detail->client = new Client($d['client']);
                        $detail->header = new Header($data['header']);

                        $qs_data = $request->all();

                        if ((int) $qs_data['qs_number'] === count($qs_data['qs'])) {
                            foreach ($qs_data['qs'] as &$qs) {
                                $qs['expected']      = (boolean) $qs['expected'];
                                $qs['response']      = (boolean) $qs['response'];
                                $qs['response_text'] = (boolean) $qs['response_text'];
                            }

                            $observation = '';

                            if (! in_array($detail->header->creditProduct->slug, [ 'PMO', 'PLT' ]) && ! $request->has('credit_product')) {
                                $observation = $qs_data['qs_observation'];
                            }

                            $d['response'] = [
                                'id'          => filter_var(date('U'), FILTER_VALIDATE_INT),
                                'response'    => json_encode($qs_data['qs']),
                                'observation' => $observation,
                            ];

                            $flag_response = true;

                            break;
                        }
                    }
                }

                if ($flag_response) {
                    $this->storageRepository->storeDataStorage($data, 'guest');

                    goto QuestionStore;
                }
            }
            /*
             * Local Storage
             */
        }

        return redirect()->back()->with([ 'error_question' => 'El Cuestionario de Salud no pudo ser registrado' ])
            ->withInput()->withErrors($this->repository->getErrors());
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param $rp_id
     * @param $header_id
     * @param $detail_id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($rp_id, $header_id, $detail_id)
    {
        if ($this->detailRepository->getDetailById(decode($detail_id))) {
            $detail = $this->detailRepository->getModel();

            QuestionEdit:
            $data = [
                'detail'      => $detail,
                'questions'   => $this->repository->getQuestionsByResponse($detail->response->response),
                'observation' => $detail->response->observation
            ];

            return view('client.de.question-edit', compact('rp_id', 'header_id', 'detail_id', 'data'));
        } elseif (request()->session()->has('guest')) {
            /*
             * Local Storage
             */
            $this->storageRepository->setId(decode($header_id));

            if ($this->storageRepository->getDataStorage('guest')) {
                $data = $this->storageRepository->getData();

                foreach ($data['header']['details'] as $dt) {
                    if ($dt['id'] === decode($detail_id)) {
                        $detail         = new Detail($dt);
                        $detail->client = new Client($dt['client']);
                        $detail->header = new Header($data['header']);
                        $detail->response = new Response($dt['response']);

                        goto QuestionEdit;
                    }
                }
            }
            /*
             * Local Storage
             */
        }

        return redirect()->back()->with([ 'error_question' => 'El Cuestionario de Salud no puede ser editado' ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  QuestionFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function updateDe(QuestionFormRequest $request, $rp_id, $header_id, $detail_id)
    {
        if ($this->detailRepository->getDetailById(decode($detail_id))) {
            $request['detail'] = $this->detailRepository->getModel();

            if ($this->repository->updateQuestionDe($request)) {
                QuestionUpdate:
                return redirect()->route('de.client.list', [
                    'rp_id'     => $rp_id,
                    'header_id' => $header_id,
                ])->with([ 'success_question' => 'El Cuestionario de Salud se actualizó correctamente' ]);
            }
        } elseif (request()->session()->has('guest')) {
            /*
             * Local Storage
             */
            $this->storageRepository->setId(decode($header_id));

            if ($this->storageRepository->getDataStorage('guest')) {
                $data          = $this->storageRepository->getData();
                $flag_response = false;

                foreach ($data['header']['details'] as &$d) {
                    if ($d['id'] == decode($detail_id)) {
                        $detail         = new Detail($d);
                        $detail->client = new Client($d['client']);
                        $detail->header = new Header($data['header']);

                        $qs_data = $request->all();

                        if ((int) $qs_data['qs_number'] === count($qs_data['qs'])) {
                            foreach ($qs_data['qs'] as &$qs) {
                                $qs['expected']      = (boolean) $qs['expected'];
                                $qs['response']      = (boolean) $qs['response'];
                                $qs['response_text'] = (boolean) $qs['response_text'];
                            }

                            $observation = '';

                            if (! in_array($detail->header->creditProduct->slug, [ 'PMO', 'PLT' ]) && ! $request->has('credit_product')) {
                                $observation = $qs_data['qs_observation'];
                            }


                            $d['response']['response']    = json_encode($qs_data['qs']);
                            $d['response']['observation'] = $observation;

                            $this->storageRepository->storeDataStorage($data, 'guest');

                            goto QuestionUpdate;

                            break;
                        }
                    }
                }
            }
            /*
             * Local Storage
             */
        }

        return redirect()->back()->with([ 'error_question' => 'El Cuestionario de Salud no pudo ser actualizado' ])
            ->withInput()->withErrors($this->repository->getErrors());
    }

}
