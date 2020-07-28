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
        $realState = auth('api')->user()->real_state()->paginate(10);


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
        $images = $request->file('images');

        try {

            $data['user_id'] = auth('api')->user()->id;

            $realState = auth('api')->user()->real_state()->create($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            if ($images) {
                $imagesUploaded = [];

                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'mensagem' => 'Imóvel cadastrado com sucesso!'
                ]
            ],200);

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

            $realState = auth('api')->user()->real_state()->with(['photos', 'address'])->findOrFail($id)->makeHidden('thumb');

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
        $images = $request->file('images');

        try {

            $realState = auth('api')->user()->real_state()->findOrFail($id);

            $realState->update($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            if ($images) {
                $imagesUploaded = [];

                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'mensagem' => 'Imóvel atualizado com sucesso!'
                ]
            ],200);

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

            $realState = auth('api')->user()->real_state()->findOrFail($id);

            $realState->delete();

            return response()->json([
                'data' => [
                    'mensagem' => 'Imóvel removido com sucesso!'
                ]
            ],200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }
}
