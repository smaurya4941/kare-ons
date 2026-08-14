<?php

namespace App\Http\Requests\Api;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validPaymentMethods = PaymentMethod::where('status', true)->pluck('code')->toArray();

        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:'.implode(',', $validPaymentMethods),
            'coupon_code' => 'nullable|string|max:50',
        ];
    }
}
