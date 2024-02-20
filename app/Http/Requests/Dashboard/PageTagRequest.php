<?php

namespace App\Http\Requests\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageTagRequest extends FormRequest
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
    public function rules(Request $request)
    {
        switch ($this->method()) {
            case 'POST':
                {
                    return [
                        'page_name' => ['required',Rule::unique('page_tags')->whereNull('deleted_at'),'max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'banner_image' => ['image', 'mimes:webp', 'max:2048'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'page_name' => ['required',Rule::unique('page_tags')->ignore($this->page_tag)->whereNull('deleted_at'),'max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'banner_image'=>['image', 'mimes:webp', 'max:2048'],
                    ];
                }
            default: break;
        }
    }
}
