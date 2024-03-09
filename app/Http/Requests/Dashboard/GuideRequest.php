<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuideRequest extends FormRequest
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
                        'title' => ['required', Rule::unique('guides')->whereNull('deleted_at'), 'min:3', 'max:225'],
                        'sliderImage' => ['required', 'image'],
                        'featureImage' => ['required', 'image'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))]
                    ];
                }
            case 'PATCH':
            case 'PUT': {
                    return [
                        'title' => ['required', Rule::unique('guides')->ignore($this->guide)->whereNull('deleted_at'), 'min:3', 'max:225'],
                        'sliderImage' => ['image'],
                        'featureImage' => ['image'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))]
                    ];
                }
            default:
                break;
        }
    }
}
