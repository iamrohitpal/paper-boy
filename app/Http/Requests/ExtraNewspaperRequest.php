<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExtraNewspaperRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'newspaper_id' => 'required|exists:newspapers,id',
            'date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_billed' => 'boolean',
        ];
    }
}
