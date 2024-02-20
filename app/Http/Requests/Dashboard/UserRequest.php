<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
                        'email' => ['required',Rule::unique('users')->whereNull('deleted_at'), 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'password' => ['required',Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                        'image' => ['image', 'max:2048'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required', 'min:3','max:225'],
                        'email' => ['required',Rule::unique('users')->ignore($this->user)->whereNull('deleted_at'), 'min:3','max:225'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        // 'password' => [Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                        'image' => ['image', 'max:2048'],
                    ];
                }
            default: break;
        }
    }
}
