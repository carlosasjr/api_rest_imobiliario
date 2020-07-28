<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ProductCollection
     */
    public function index(Request $request)
    {
        $productRepository = new ProductRepository($this->product);

        if ($request->has('conditions')) {
            $productRepository->setConditions($request->get('conditions'));
        }

        if ($request->has('fields')) {
            $productRepository->setFields($request->get('fields'));
        }

        return new ProductCollection($productRepository->getResult()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();

        $product = $this->product->create($data);

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ProductResource
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        //return response()->json($product);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();

        $product = $this->product->find($id);

        $product->update($data);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);

        $product->delete();

        return response()->json(['data' => ['msg' => 'Produto excluído com sucesso!']]);
    }
}
