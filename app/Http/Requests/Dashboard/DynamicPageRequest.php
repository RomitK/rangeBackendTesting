<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Validator;
class DynamicPageRequest extends FormRequest
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
                        'page_name' => ['required',Rule::unique('dynamic_pages')->whereNull('deleted_at'), 'min:3','max:225'],
                        'slug' => ['required','slug',Rule::unique('dynamic_pages')->whereNull('deleted_at'), 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'page_name' => ['required',Rule::unique('dynamic_pages')->ignore($this->dynamic_page)->whereNull('deleted_at'), 'min:3','max:225'],
                        'slug' => ['required','slug',Rule::unique('dynamic_pages')->ignore($this->dynamic_page)->whereNull('deleted_at'), 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        
                    ];
                }
            default: break;
        }
    }
    public function messages()
    {
        return [
            'slug.slug' => 'The Slug is Invalid'
        ];
    }
}
