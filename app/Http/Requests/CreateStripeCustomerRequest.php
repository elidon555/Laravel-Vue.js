<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateStripeCustomerRequest extends FormRequest
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
            'email' => 'nullable',
            'name' => 'required',
            'shipping' => [
                'address' => [
                    'city' => 'nullable',
                    'country' => 'US',
                    'line1' => 'nullable',
                    'postal_code' => 'nullable',
                    'state' => 'nullable'
                ],
                'name' => 'nullable'
            ],
            'address' => [
                'city' => 'nullable',
                'country' => 'nullable',
                'line1' => 'nullable',
                'postal_code' => 'nullable',
                'state' => 'nullable'
            ]
        ];
    }
}
