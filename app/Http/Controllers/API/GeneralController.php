<?php

namespace Sibas\Http\Controllers\API;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sibas\Http\Controllers\Controller;
use Sibas\Repositories\API\GeneralRepository;

class GeneralController extends Controller
{
    /**
     * @var GeneralRepository
     */
    protected $repository;

    public function __construct(GeneralRepository $repository)
    {
        $this->repository = $repository;
    }

    public function post(Request $request)
    {
        $info = $request->all();
        $model = $this->repository->$info['method']($request);
        
        return response()->json($model, 200); 
    }

    public function get(Request $request)
    {
        $info = $request->all();
        $model = $this->repository->$info['method']($request);
        
        if(isset($info['attached']) && $info['attached'])
            return $this->repository->$info['attached']($info['method'], $model);

        return response()->json($model, 200);  
    }

}