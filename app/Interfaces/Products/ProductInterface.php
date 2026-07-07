<?php

namespace App\Interfaces\Products;

use App\Http\Requests\Products\AdjustStockRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;

interface ProductInterface
{
    public function index();
    public function store(StoreProductRequest $request);
    public function show(Product $product);
    public function update(UpdateProductRequest $request, Product $product);
    public function destroy(Product $product);
    public function adjustStock(AdjustStockRequest $request, Product $product);
    public function lowStock();
}
