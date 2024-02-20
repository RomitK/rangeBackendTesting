<?php

namespace App\Http\Requests\Dashboard\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectBedroomRequest extends FormRequest
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
                        'bedroom_number' => ['required','max:225'],
                        'bathroom_number'=>['required', 'max:225'],
                        'price'=>['required', 'max:225'],
                        'area'=>['required', 'max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'floorplan_image'=>['image',  'max:2048'],
                        'floorplan_file'=>['mimes:pdf'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'bedroom_number' => ['required','max:225'],
                        'bathroom_number'=>['required', 'max:225'],
                        'price'=>['required', 'max:225'],
                        'area'=>['required', 'max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'floorplan_image'=>['image','max:2048'],
                        'floorplan_file'=>['mimes:pdf'],
                    ];
                }
            default: break;
        }
    }
}
