<?php

namespace Sibas\Http\Controllers\De;

use Illuminate\Http\Request;

use Sibas\Entities\City;
use Sibas\Entities\Mc\Answer;
use Sibas\Http\Controllers\PdfController;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Sibas\Http\Requests\De\MedicalCertificateFormRequest;
use Sibas\Repositories\De\FacultativeRepository;
use Sibas\Repositories\De\MedicalCertificateRepository;

class MedicalCertificateController extends Controller
{

    /**
     * @var FacultativeRepository
     */
    protected $facultativeRepository;

    /**
     * @var MedicalCertificateRepository
     */
    protected $repository;

    /**
     * @var PdfController
     */
    protected $pdf;


    public function __construct(
        PdfController $pdf,
        MedicalCertificateRepository $repository,
        FacultativeRepository $facultativeRepository
    ) {
        $this->repository            = $repository;
        $this->facultativeRepository = $facultativeRepository;
        $this->pdf                   = $pdf;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    /**
     * Show the form for creating a new resource.
     *
     * @param $rp_id
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function create($rp_id, $id)
    {
        if (request()->ajax()) {
            if ($this->facultativeRepository->getFacultativeById(decode($id))
                && $this->repository->getMedicalCertificateByProduct(decode($rp_id))) {
                $fa           = $this->facultativeRepository->getModel();
                $mc           = $this->repository->getModel();
                $city         = $fa->detail->header->user->city;
                $care_centers = collect([]);
                
                if ($city instanceof City) {
                    //$care_centers = $city->careCenters;
                    $care_centers = \Sibas\Entities\CareCenter::where('ad_city_id', '!=', 8)->get();
                }
                
                return response()->json([
                    'payload'      => view('de.mc.create', compact('rp_id', 'id', 'fa', 'mc'))->render(),
                    'mc_id'        => encode($mc->id),
                    'care_centers' => $care_centers,
                ]);
            }

            return response()->json([ 'err' => 'Unauthorized action.' ], 401);
        }

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param MedicalCertificateFormRequest $request
     * @param string                        $rp_id
     * @param string                        $id
     *
     * @return \Illuminate\Http\Response
     */
    public function store(MedicalCertificateFormRequest $request, $rp_id, $id)
    {
        if ($request->ajax()) {
            if ($this->facultativeRepository->getFacultativeById(decode($id))
                && $this->repository->storeMedicalCertificate($request)) {
                $mc = $this->repository->getModel();

                return response()->json([
                    'mc_id' => $mc->id
                ]);
            }
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param string $rp_id
     * @param  int   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($rp_id, $id)
    {
        if ($this->facultativeRepository->getFacultativeById(decode($id))) {
            $fa = $this->facultativeRepository->getModel();

            if ($fa->observations->last()->state->slug === 'me') {
                $answer = $fa->observations->last()->answer()->with([ 'careCenter' ])->first();

                if (( $answer instanceof Answer ) && $this->repository->getMedicalCertificateById($answer->mc_certificate_id)) {
                    $mc               = $this->repository->getModel();
                    $answer->response = json_decode($answer->response, true);

                    $payload = view('de.mc.certificate', compact('mc', 'fa', 'answer'))->render();

                    return $this->pdf->create($payload, 'Certificado Médico');
                }
            }
        }

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
