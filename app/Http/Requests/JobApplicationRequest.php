<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
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
            'career_id' => 'required|exists:careers,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required',
            'cv' => 'required|file',
            'message' => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'career_id.exists' => 'Career does not exist',
        ];
    }
}