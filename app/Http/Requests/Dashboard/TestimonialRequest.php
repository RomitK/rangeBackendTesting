<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestimonialRequest extends FormRequest
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
                        'client_name' => ['required','min:3','max:225'],
                        'feedback_title' => ['required','min:3','max:225'],
                        'image' => ['required','image',  'max:2048'],
                        'feedback' => ['required','min:3','max:500'],
                        'rating'=>['required', Rule::in(config('constants.rating'))],
                        'agent_id' => ['required', Rule::exists('agents','id')],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))]
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'client_name' => ['required','min:3','max:225'],
                        'feedback_title' => ['required','min:3','max:225'],
                        'image' => ['image', 'image',  'max:2048'],
                        'feedback' => ['required','min:3','max:500'],
                        'rating'=>['required', Rule::in(config('constants.rating'))],
                        'agent_id' => ['required', Rule::exists('agents','id')],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))]
                    ];
                }
            default: break;
        }
    }
}
