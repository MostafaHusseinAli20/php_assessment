<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        return [
            'sku' => ['sometimes', 'required','string', 'max:255', Rule::unique('products', 'sku')->ignore($product)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'required', 'min:1', 'numeric'],
            'stock_quantity' => ['sometimes', 'required', 'integer', 'min:0'],
            'low_stock_threshold' => ['sometimes', 'required', 'integer', 'min:0'],
            'status' => ['sometimes', 'required', Rule::in(Product::STATUSES),]
        ];
    }
}
