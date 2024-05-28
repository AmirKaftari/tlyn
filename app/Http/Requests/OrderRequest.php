<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        return [
            'user_id' => 'required|integer', // Todo: If we have real authentication this rule must be remove
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:buy,sell',
            'price' => 'required|numeric|min:0.01'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User_id is required.',
            'user_id.integer' => 'User_id must be an integer.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 1.',
            'type.required' => 'Order type is required.',
            'type.in' => 'Order type must be either buy or sell.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a numeric value.',
            'price.min' => 'Price must be at least 0.01.'
        ];
    }
}
