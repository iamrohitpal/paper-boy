<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NewspaperRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We will use middleware for authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'language' => 'required|string|max:50',
            'mrp' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'selling_price_sunday' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
            'description' => 'nullable|string',
        ];
    }
}
