<?php

namespace App\Http\Requests\Dashboard\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStatValueRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                {
                    return [
                        'key' => ['required', 'max:225'],
                        'value' => ['required', 'max:225'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'key' => ['required', 'max:225'],
                        'value' => ['required', 'max:225'],
                    ];
                }
            default: break;
        }
    }
}
