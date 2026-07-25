<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'delivery_days' => 'required|in:Daily,Sunday Only,Custom',
            'custom_days' => 'nullable|array',
            'custom_days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'price' => 'required|numeric|min:0',
            'price_sunday' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Active,Paused,Cancelled',
        ];
    }
}
