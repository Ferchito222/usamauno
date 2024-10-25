<?php

namespace Sibas\Http\Controllers\Au;

use Illuminate\Http\Request;

use Sibas\Http\Controllers\MailController;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\De\FacultativeFormRequest;
use Sibas\Repositories\Au\FacultativeRepository;
use Sibas\Repositories\Au\HeaderRepository;
use Sibas\Repositories\Retailer\RetailerProductRepository;

class FacultativeController extends Controller
{

    /**
     * @var FacultativeRepository
     */
    protected $repository;

    /**
     * @var HeaderRepository
     */
    protected $headerRepository;

    /**
     * @var RetailerProductRepository
     */
    protected $retailerProductRepository;


    public function __construct(
        FacultativeRepository $repository,
        HeaderRepository $headerRepository,
        RetailerProductRepository $retailerProductRepository
    ) {
        $this->repository                = $repository;
        $this->headerRepository          = $headerRepository;
        $this->retailerProductRepository = $retailerProductRepository;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param string $rp_id
     * @param string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($rp_id, $id)
    {
        if (request()->ajax()) {
            if ($this->repository->getFacultativeById(decode($id))) {
                $fa = $this->repository->getModel();

                return response()->json([
                    'payload'      => view('au.facultative.edit', compact('fa', 'rp_id'))->render(),
                    'states'       => $this->retailerProductRepository->getStatusByProduct(decode($rp_id)),
                    'current_rate' => $fa->detail->rate,
                    'user_email'   => $fa->detail->header->user->email,
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param FacultativeFormRequest $request
     * @param string                 $rp_id
     * @param string                 $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FacultativeFormRequest $request, $rp_id, $id)
    {
        if ($request->ajax()) {
            if ($this->retailerProductRepository->getRetailerProductById(decode($rp_id)) && $this->repository->getFacultativeById(decode($id))) {
                $retailerProduct = $this->retailerProductRepository->getModel();

                if ($this->repository->updateFacultative($request)) {
                    $fa     = $this->repository->getModel();
                    $header = $fa->detail->header;

                    $this->repository->approved = (int) $request->get('approved');
                    $surcharge                  = (boolean) $request->get('surcharge');

                    if ($this->repository->approved === 1 || $this->repository->approved === 0) {
                        $this->headerRepository->setApproved($header);

                        if ($surcharge) {
                            $this->headerRepository->setVehicleResult($retailerProduct, $header);
                        }
                    }

                    $mail = new MailController($request->user(), $request->get('emails'));

                    $this->repository->sendProcessMail($mail, $rp_id, $id);

                    return response()->json([
                        'location' => route('home')
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $id
     *
     * @return mixed
     */
    public function observation($rp_id, $id)
    {
        if (request()->ajax()) {
            if ($this->repository->getFacultativeById(decode($id))) {
                $fa = $this->repository->getModel();

                if ($fa->observations->last()->state->slug !== 'me') {
                    $payload = view('au.facultative.observation', compact('fa'));

                    return response()->json([
                        'payload' => $payload->render()
                    ]);
                }

            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $id
     * @param string $id_observation
     *
     * @return mixed
     */
    public function createAnswer($rp_id, $id, $id_observation)
    {
        if (request()->ajax()) {
            if ($this->repository->getFacultativeById(decode($id))) {
                $fa = $this->repository->getModel();

                return response()->json([
                    'payload' => view('au.facultative.answer', compact('fa', 'id_observation', 'rp_id'))->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param string  $rp_id
     * @param string  $id
     * @param string  $id_observation
     *
     * @return mixed
     */
    public function storeAnswer(Request $request, $rp_id, $id, $id_observation)
    {
        $this->validate($request, [
            'observation_response' => 'required|ands_full'
        ]);

        if (request()->ajax()) {
            if ($this->repository->getFacultativeById(decode($id))) {
                if ($this->repository->storeAnswer($request, decode($id_observation))) {
                    $mail = new MailController($request->user());

                    $this->repository->approved = 2;
                    $this->repository->sendProcessMail($mail, $rp_id, $id, true);

                    return response()->json([
                        'location' => route('home')
                    ]);
                }
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $id
     * @param string $id_observation
     *
     * @return mixed
     */
    public function response($rp_id, $id, $id_observation)
    {
        if (request()->ajax()) {
            if ($this->repository->getFacultativeById(decode($id))) {
                $fa          = $this->repository->getModel();
                $observation = $fa->observations()->where('id', decode($id_observation))->first();

                return response()->json([
                    'payload' => view('au.facultative.response', compact('fa', 'observation'))->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param string $rp_id
     * @param string $id
     *
     * @return mixed
     */
    public function observationProcess($rp_id, $id)
    {
        if (request()->ajax()) {
            if ($this->repository->getFacultativeById(decode($id))) {
                $fa = $this->repository->getModel();

                return response()->json([
                    'payload' => view('au.facultative.observation-process', compact('fa'))->render()
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param string  $rp_id
     * @param string  $id
     *
     * @return mixed
     */
    public function readUpdate(Request $request, $rp_id, $id)
    {
        if (request()->ajax()) {
            if ($this->repository->readUpdate($request, decode($id))) {
                return response()->json([
                    'read' => filter_var($request->get('read'), FILTER_VALIDATE_BOOLEAN)
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }

}
