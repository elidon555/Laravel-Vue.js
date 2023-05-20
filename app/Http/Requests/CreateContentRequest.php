<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateContentRequest extends FormRequest
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
            'properties' => 'required|array',
            'properties.title' => 'required',
            'properties.description' => 'required',
            'properties.isPublic' => 'required|boolean',
            'file' => 'required|mimes:jpeg,jpg,png,gif,mp4,mov,avi'
        ];
    }
}
