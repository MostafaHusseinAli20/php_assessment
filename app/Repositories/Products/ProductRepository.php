<?php

namespace App\Repositories\Products;

use App\Http\Requests\Products\AdjustStockRequest;
use App\Interfaces\Products\ProductInterface;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductInterface
{
    use ApiResponseTrait;
    public function index()
    {
        try {
            $page = request()->get('page', 1);
            $products = Cache::remember(
                "products_page_{$page}",
                now()->addMinutes(10),
                function () {
                    return Product::latest()->paginate(10);
                }
            );

            return $this->success(
                ProductResource::collection($products),
                'Products fetched successfully',
                200,
                [
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                    ]
                ]
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::create($request->validated());
            Cache::flush();
            DB::commit();
            return $this->success(
                new ProductResource($product),
                'Product created successfully',
                201
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    public function show(Product $product)
    {
        try {
            return $this->success(
                new ProductResource($product),
                'Product fetched successfully',
                200
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->update($request->validated());

            Cache::flush();

            DB::commit();

            return $this->success(
                new ProductResource($product),
                'Product updated successfully',
                200
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();

            Cache::flush();

            DB::commit();

            return $this->success(
                null,
                'Product deleted successfully',
                200
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    public function adjustStock(AdjustStockRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            if ($request->action === 'increment') {

                $product->increment('stock_quantity', $request->quantity);

            } else {

                if ($product->stock_quantity < $request->quantity) {

                    DB::rollBack();
                    return $this->error(
                        'Insufficient stock.',
                        422
                    );
                }

                $product->decrement('stock_quantity', $request->quantity);

            }
            $product->refresh();

            Cache::flush();

            DB::commit();

            return $this->success(
                new ProductResource($product),
                'Stock updated successfully.'
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    public function lowStock()
    {
        try {
            $products = Product::whereColumn(
                'stock_quantity',
                '<=',
                'low_stock_threshold'
            )->paginate(10);

            return $this->success(
                ProductResource::collection($products),
                'Low stock products fetched successfully',
                200,
                [
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                    ]
                ]
            );
        } catch (\Throwable $e) {
            return $this->errorHandle($e);
        }
    }

    private function errorHandle(\Throwable $e)
    {
        DB::rollBack();
        return $this->error(
            'Something went wrong.',
            500,
            [
                'exception' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ],
        );
    }
}