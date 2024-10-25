<?php

namespace Sibas\Http\Controllers\Eb;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Sibas\Http\Controllers\MailController;
use Sibas\Http\Controllers\ReportTrait;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Eb\CancellationRepository;
use Sibas\Repositories\Eb\HeaderRepository;

class CancellationController extends Controller
{

    use ReportTrait;

    /**
     * @var CancellationRepository
     */
    protected $repository;

    /**
     * @var HeaderRepository
     */
    protected $headerRepository;


    /**
     * CancellationController constructor.
     *
     * @param CancellationRepository $repository
     * @param HeaderRepository       $headerRepository
     */
    public function __construct(CancellationRepository $repository, HeaderRepository $headerRepository)
    {
        $this->repository       = $repository;
        $this->headerRepository = $headerRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Guard   $auth
     * @param Request $request
     * @param string  $rp_id
     *
     * @return \Illuminate\Http\Response
     */
    public function lists(Guard $auth, Request $request, $rp_id)
    {
        $data    = $this->data($auth->user());
        $headers = [];

        if ($request->has('_token')) {
            $request->flash();
        }

        $headers = $this->repository->getHeaderList($request);

        return view('eb.cancellation.list', compact('rp_id', 'headers', 'data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param string $rp_id
     * @param string $header_id
     *
     * @return \Illuminate\Http\Response
     */
    public function create($rp_id, $header_id)
    {
        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();

                $payload = view('eb.cancellation.create', compact('rp_id', 'header'));

                return response()->json([
                    'payload' => $payload->render(),
                ]);
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string                    $rp_id
     * @param string                    $header_id
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $rp_id, $header_id)
    {
        $this->validate($request, [
            'reason' => 'required|ands_full',
        ]);

        if (request()->ajax()) {
            if ($this->headerRepository->getHeaderById(decode($header_id))) {
                $header = $this->headerRepository->getModel();

                if ($this->repository->storeCancellation($request, $header)) {
                    $mail           = new MailController($request->user());
                    $mail->subject  = 'Anulacion de Poliza No. ' . $header->prefix . '-' . $header->issue_number;
                    $mail->template = 'de.cancellation';

                    array_push($mail->receivers, [
                        'email' => $header->user->email,
                        'name'  => $header->user->full_name,
                    ]);

                    if ($mail->send(decode($rp_id), ['header' => $header])) {

                    }

                    return response()->json([
                        'location' => route('eb.cancel.lists', ['rp_id' => $rp_id]),
                    ]);
                }
            }

            return response()->json(['err' => 'Unauthorized action.'], 401);
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
