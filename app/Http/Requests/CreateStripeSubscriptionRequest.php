<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateStripeSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer' => 'required',
            'items' => [
                'price' => 'required'
            ],
            'payment_behavior' => 'nullable',
            'payment_settings' => [
                'save_default_payment_method' => 'nullable',
            ],
            'expand' => 'nullable',
        ];
    }
}
