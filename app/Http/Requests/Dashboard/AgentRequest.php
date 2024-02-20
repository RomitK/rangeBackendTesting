<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgentRequest extends FormRequest
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
                        // 'email' => ['required','email',Rule::unique('agents')->whereNull('deleted_at'), 'min:3','max:225'],
                        'employeeId' => ['required',Rule::unique('agents')->whereNull('deleted_at'), 'min:3','max:225'],
                        'email' => ['required','email', 'min:3','max:225'],
                        'contact_number' => ['required','max:225'],
                        'whatsapp_number' => ['required','max:225'],
                        'orderBy'=>['integer','min:0'],
                        'designation'=>['max:225'],
                        'specialization'=>['max:225'],
                        'nationality'=>['max:225'],
                        'experience'=>['max:225'],
                        'start_working'=>['max:225'],
                        'linkedin_profile'=>['max:225'],
                        'license_number'=>['max:225'],
                        'languageIds'=>['array'],
                        'languageIds.*' => [Rule::exists('languages', 'id')],
                        'serviceIds'=>['array'],
                        'serviceIds.*' => [Rule::exists('services', 'id')],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image', 'max:2048'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required', 'min:3','max:225'],
                        // 'email' => ['required','email',Rule::unique('agents')->ignore($this->agent)->whereNull('deleted_at'), 'min:3','max:225'],
                        'employeeId' => ['required',Rule::unique('agents')->ignore($this->agent)->whereNull('deleted_at'), 'min:3','max:225'],
                        'email' => ['required','email', 'min:3','max:225'],
                        'contact_number' => ['required','max:225'],
                        'orderBy'=>['integer','min:0'],
                        'whatsapp_number' => ['required','max:225'],
                        'designation'=>['max:225'],
                        'specialization'=>['max:225'],
                        'nationality'=>['max:225'],
                        'experience'=>['max:225'],
                        'start_working'=>['max:225'],
                        'linkedin_profile'=>['max:225'],
                        'license_number'=>['max:225'],
                        'languageIds'=>['array'],
                        'languageIds.*' => [Rule::exists('languages', 'id')],
                        'serviceIds'=>['array'],
                        'serviceIds.*' => [Rule::exists('services', 'id')],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'image' => ['image', 'max:2048'],
                    ];
                }
            default: break;
        }
    }
}
