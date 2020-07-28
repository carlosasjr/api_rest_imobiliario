<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;
use Illuminate\Http\Request;


class RealStateSearchController extends Controller
{
    /**
     * @var RealState
     */
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }


    public function index(Request $request)
    {
         $repository =  (new RealStateRepository($this->realState))
                       ->setLocation($request->all(['state', 'city']));

        if ($request->has('conditions')) {
            $repository->setConditions($request->get('conditions'));
        }

        if ($request->has('fields')) {
            $repository->setFields($request->get('fields'));
        }

        return response()->json([
            'data' => $repository->getResult()->paginate(10)
        ], 200);
    }

    public function show($id)
    {
        try {
            $realState = $this->realState->with(['photos', 'address'])->findOrFail($id);

            return response()->json([
                'data' => $realState
            ],200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


}
