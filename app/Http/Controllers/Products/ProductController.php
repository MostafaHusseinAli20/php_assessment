<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\AdjustStockRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Interfaces\Products\ProductInterface;
use App\Models\Product;

class ProductController extends Controller
{
    private $productInterface;

    public function __construct(ProductInterface $productInterface)
    {
        $this->productInterface = $productInterface;
    }

    public function index()
    {
        return $this->productInterface->index();
    }

    public function store(StoreProductRequest $request)
    {
        return $this->productInterface->store($request);
    }

    public function show(Product $product)
    {
        return $this->productInterface->show($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return $this->productInterface->update($request, $product);
    }

    public function destroy(Product $product)
    {
        return $this->productInterface->destroy($product);
    }

    public function adjustStock(AdjustStockRequest $request, Product $product)
    {
        return $this->productInterface->adjustStock($request, $product);
    }

    public function lowStock()
    {
        return $this->productInterface->lowStock();
    }
}
