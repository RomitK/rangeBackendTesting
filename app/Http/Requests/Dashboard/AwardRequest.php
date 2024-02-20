<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class AwardRequest extends FormRequest
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
                        'title' => ['required', 'min:3','max:225'],
                        // 'position' => ['required', 'min:3','max:225'],
                        // 'year' => ['required', 'min:3','max:225'],
                        'trophy' => ['image',  'max:2048'],
                        // 'badge' => ['image', 'max:2048'],
                        // 'gallery' => ['required','array'],
                        // 'gallery.*' => ['image', 'max:2048'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        // 'developer_id' => ['required', Rule::exists('developers', 'id')]
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title' => ['required', 'min:3','max:225'],
                        // 'position' => ['required', 'min:3','max:225'],
                        // 'year' => ['required', 'min:3','max:225'],
                        'trophy' => ['image', 'max:2048'],
                        // 'badge' => ['image',  'max:2048'],
                        // 'gallery' => ['array'],
                        // 'gallery.*' => ['image', 'max:2048'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        // 'developer_id' => ['required', Rule::exists('developers', 'id')]
                    ];
                }
            default: break;
        }
    }
}
