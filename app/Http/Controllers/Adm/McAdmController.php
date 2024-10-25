<?php

namespace Sibas\Http\Controllers\Adm;

use Illuminate\Http\Request;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\Adm\McAdmRepository;

class McAdmController extends Controller
{
    /**
     * @var McAdmRepository
     */
    protected $repository;


    public function __construct(McAdmRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adm.setting.mc');
    }


    public function getMcs()
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            $mcs      = $this->repository->getMcs();
            $mc_types = config('base.medical_certificate_types');

            return response()->json([
                'mcs'      => $mcs,
                'mc_types' => $mc_types
            ]);
        }

        return redirect()->back();
    }


    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function editQuestionnaires($id)
    {
        \Debugbar::disable();

        if (request()->ajax()) {
            if ($this->repository->getMcs($id)) {
                $mc             = $this->repository->getModel();
                $questionnaires = $this->repository->getQuestionnaires();
                $questions      = $this->repository->getQuestions();

                return response()->json([
                    'mc'             => $mc,
                    'questionnaires' => $questionnaires,
                    'questions'      => $questions,
                ]);
            } else {
                return response()->json(['err' => 'Unauthorized action.'], 401);
            }
        }

        return redirect()->back();
    }


    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateQuestionnaires(Request $request, $id)
    {
        \Debugbar::disable();

        $this->validate($request, [
            'questionnaires' => 'required|array'
        ]);

        if (request()->ajax()) {
            if ($this->repository->getMcs($id)) {
                if ($this->repository->editQuestionnaires($request)) {
                    $mc = $this->repository->getModel();

                    return response()->json([
                        'success' => true,
                        'mc'      => $mc,
                        'message' => 'Los datos se actualizaron correctamente.',
                    ]);
                }
            } else {
                return response()->json([
                    'warning' => true,
                    'message' => 'La información no pudo se actualizada.'
                ], 402);
            }
        }

        return redirect()->back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
