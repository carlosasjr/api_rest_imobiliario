<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{

    /**
     * @var Category
     */
    private $category;

    public function __construct(Category $category)
    {

        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = $this->category->paginate(10);

        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        try {

            $user = $this->category->create($data);

            return response()->json([
                'data' => [
                    'mensagem' => 'Categoria cadastrada com sucesso!'
                ]
            ]);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try {

            $category = $this->category->findOrFail($id);

            $category->update($data);

            return response()->json([
                    'mensagem' => 'Categoria atualizada com sucesso!'
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {

            $category = $this->category->findOrFail($id);

            $category->delete();

            return response()->json([
                    'msg' => 'Categoria removida com sucesso!'
                ]
                , 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    public function realState($id)
    {
        try {

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category->realStates
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
