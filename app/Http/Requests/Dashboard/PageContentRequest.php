<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class PageContentRequest extends FormRequest
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
                        'image' => ['image', 'max:2048'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title' => ['required', 'min:3','max:225'],
                        'image' => ['image', 'max:2048'],
                    ];
                }
            default: break;
        }
    }
}
