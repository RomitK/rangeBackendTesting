<?php

namespace App\Http\Requests\Dashboard\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubProjectRequest extends FormRequest
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
            case 'POST': {
                    return [
                        'title' => ['required', 'min:3', 'max:225'],
                        //'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'website_status' => ['required', Rule::in(array_keys(config('constants.newStatusesWithoutAll')))],
                        'is_parent_project' => ['boolean'],
                        'parent_project_id' => [Rule::exists('projects', 'id')],
                        // 'starting_price'=>['required','max:225'],
                        'amenities' => ['array'],
                        'amenities.*' => [Rule::exists('amenities', 'id')],
                    ];
                }
            case 'PATCH':
            case 'PUT': {
                    return [
                        'title' => ['required', 'min:3', 'max:225'],
                        //'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'website_status' => ['required', Rule::in(array_keys(config('constants.newStatusesWithoutAll')))],
                        'is_parent_project' => ['boolean'],
                        'parent_project_id' => [Rule::exists('projects', 'id')],
                        // 'starting_price'=>['required','max:225'],
                        'amenities' => ['array'],
                        'amenities.*' => [Rule::exists('amenities', 'id')],
                    ];
                }
            default:
                break;
        }
    }
}
