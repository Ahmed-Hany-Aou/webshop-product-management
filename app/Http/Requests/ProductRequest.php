<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Return true if you want to allow all users to access this request
        return true; // i will handel it when i  get to auth section further-------------
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255', // Product name must be a string and not exceed 255 characters
            'description' => 'nullable|string|max:500', // Product description is optional and should not exceed 500 characters
            'price' => 'required|numeric|min:0', // Product price is required and must be a positive number
            'stock_quantity' => 'required|integer|min:0', // Product stock quantity is required and must be an integer
        ];
    }
}
