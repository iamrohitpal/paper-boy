<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $customerId = $this->route('customer') ? $this->route('customer') : null;

        return [
            'customer_id' => 'nullable|string|max:255|unique:customers,customer_id,'.$customerId,
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'area' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'delivery_address' => 'nullable|string',
            'start_date' => 'required|date',
            'status' => 'required|in:Active,Inactive',
            'payment_frequency' => 'required|in:Daily,Weekly,Monthly',
            'notes' => 'nullable|string',
            'customer_photo' => 'nullable|image|max:2048', // optional file upload
        ];
    }
}
