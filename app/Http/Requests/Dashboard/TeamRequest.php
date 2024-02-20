<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
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
                        'email' => ['required','email',Rule::unique('teams')->whereNull('deleted_at'), 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
                        'contact_number' => ['max:225'],
                        'designation' => ['max:225'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required', 'min:3','max:225'],
                        'email' => ['required',Rule::unique('teams')->ignore($this->team)->whereNull('deleted_at'), 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
                        'designation' => ['max:225'],
                        'contact_number' => ['max:225'],
                    ];
                }
            default: break;
        }
    }
}
