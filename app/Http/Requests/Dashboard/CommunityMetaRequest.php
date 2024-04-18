<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommunityMetaRequest extends FormRequest
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
        return [
            'slug' => $this->community->slug,
            'slug' => ['required', 'string', Rule::unique('communities', 'slug')->ignore($this->community->id), 'slug']
        ];
    }
    public function messages()
    {
        return [
            'slug.slug' => 'The :attribute is invalid.',
        ];
    }
}
