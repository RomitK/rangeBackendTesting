<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class BannerRequest extends FormRequest
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
                        'title' => ['max:225'],
                        'button_text' => ['max:225'],
                        'button_link' => ['max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image', 'max:2048'],
                        'video' => ['mimes:mp4,mov,ogg,qt', 'max:20000'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title' => ['max:225'],
                        'button_text' => ['max:225'],
                        'button_link' => ['max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image', 'max:2048'],
                        'video' => ['mimes:mp4,mov,ogg,qt', 'max:20000'],
                    ];
                }
            default: break;
        }
    }
}
