<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Str;
use App\Repository\ProductRepositoryInterface;


class ProductController extends BaseController
{

    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        try {
            return $this->sendResponse($this->productRepository->getAll(), 'Product retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {

        try {
            $product =  $this->productRepository->create($request->all());
            return $this->sendResponse($product, 'Product created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->sendResponse($this->productRepository->getById($id), 'Product retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
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
        // return $request->all();
        try {
            $product =  $this->productRepository->update($id, $request->all());
            return $this->sendResponse($product, 'Product updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = $this->productRepository->delete($id);
            return $this->sendResponse($product, 'Product deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $product = $this->productRepository->restore($id);
            return $this->sendResponse($product, 'Product restored permanently.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $product = $this->productRepository->forceDelete($id);
            return $this->sendResponse($product, 'Product deleted permanently.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
