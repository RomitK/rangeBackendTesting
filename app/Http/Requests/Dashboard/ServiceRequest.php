<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
                        'name' => ['required', 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image',  'max:2048'],
                        'is_parent'=>['boolean'],
                        'parent_id'=>['required_if:is_parent,0'],
                        'icon' => ['image', 'max:2048'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required', 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image',  'max:2048'],
                        'icon' => ['image',  'max:2048'],
                        'is_parent'=>['boolean'],
                        'parent_id'=>['required_if:is_parent,0'],
                    ];
                }
            default: break;
        }
    }
}
