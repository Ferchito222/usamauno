<?php

namespace Sibas\Http\Controllers\Adm;

use Illuminate\Http\Request;
use Monolog\Logger;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\PolicyEditRepository;
use Sibas\Repositories\LogRepository;

class PolicyEditController extends Controller
{

    /**
     * @var PolicyEditRepository
     */
    protected $repository;
    /**
     * @var LogRepository
     */
    protected $log;


    public function __construct(PolicyEditRepository $repository, LogRepository $log)
    {
        $this->repository = $repository;
        $this->log        = $log;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adm.operation.policy-edit');
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        \Debugbar::disable();
        ini_set('memory_limit', -1);

        if (request()->ajax()) {
            $data   = [];
            $action = filter_var($request->get('action'), FILTER_VALIDATE_BOOLEAN);

            if (! $action) {
                $data    = $this->repository->dataSearch();
                $product = $data['products']->keys()->first();

                $request->request->add(['product' => $product]);
            }

            $api = $this->repository->getHeaders($request);

            return response()->json(compact('api', 'data'));
        }

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param string $code
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $data   = $this->repository->getDataEdit($code);

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code, $id)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            if ($this->repository->getHeader($code, decode($id))) {
                $level = $this->repository->editData($request, $code);

                if ($level < 0) {
                    $message = '';

                    switch ($level) {
                        case -1:
                            $message .= 'El nuevo valor del Monto Solicitado no debe ser mayor al actual.';
                            break;
                        case -2:
                            $message .= 'El nuevo valor del Monto Actual Solicitado no debe ser mayor al actual.';
                            break;
                        case -3:
                            $message .= 'El nuevo valor del Monto Actual Acumulado no debe ser mayor al actual.';
                            break;
                    }

                    return response()->json([
                        'warning' => true,
                        'message' => $message,
                        'log'     => [],
                    ], 402);
                }

                $queries = $this->repository->getQueries();
                $user    = $request->user();

                $this->log->setLog('Policy Edit', $queries, $user, ($level ? Logger::INFO : Logger::ERROR));

                if ($level) {
                    $api = $this->repository->getApiModel();

                    return response()->json([
                        'success' => true,
                        'api'     => $api,
                        'message' => 'Los datos se actualizaron correctamente.',
                        'log'     => $queries,
                    ]);
                } else {
                    return response()->json([
                        'warning' => true,
                        'message' => 'La información no pudo se actualizada.',
                        'log'     => $queries,
                    ], 402);
                }
            }

            return response()->json([
                'warning' => true,
                'message' => 'La información no pudo se actualizada.',
            ], 402);
        }

        return redirect()->back();
    }

}
