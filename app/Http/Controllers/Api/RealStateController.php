<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;
use Illuminate\Http\JsonResponse;

class RealStateController extends Controller
{

    /**
     * @var RealState
     */
    private $realState;

    public function __construct(RealState $realState)
    {

        $this->realState = $realState;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $realState = $this->realState->paginate('10');

        return response()->json($realState, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RealStateRequest $request
     * @return JsonResponse
     */
    public function store(RealStateRequest $request)
    {
        //não esquecer de colocar no header "Accept = Application/Json"

        $data = $request->all();

        try {

            $realState = $this->realState->create($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                    'mensagem' => 'Imóvel cadastrado com sucesso!'
                ]
                , 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {

            $realState = $this->realState->findOrFail($id);

            return response()->json([
                'data' => $realState
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param RealStateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(RealStateRequest $request, $id)
    {
        $data = $request->all();

        try {

            $realState = $this->realState->findOrFail($id);

            $realState->update($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                    'mensagem' => 'Imóvel atualizado com sucesso!'
                ]
                , 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {

            $realState = $this->realState->findOrFail($id);

            $realState->delete();

            return response()->json([
                    'msg' => 'Imóvel removido com sucesso!'
                ]
                , 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }
}
