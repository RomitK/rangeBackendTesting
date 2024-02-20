<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeveloperRequest extends FormRequest
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
                        'name' => ['required',Rule::unique('developers')->whereNull('deleted_at'), 'min:3','max:225'],
                        'logo' => ['required','image','max:2048'],
                        'image'=>['image'],
                        'video'=>['mimes:mp4,mov,ogx,oga,ogv,ogg,webm', 'max:2048'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'orderBy'=>['integer','min:0'],
                        'tagIds'=>['array'],
                        'tagIds.*' => [Rule::exists('tag_categories', 'id')],
                        
                        'gallery'=>['array'],
                        'gallery.*.file' => ['image','max:1048'] 
                        
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required',Rule::unique('developers')->ignore($this->developer)->whereNull('deleted_at'), 'min:3','max:225'],
                        'logo' => ['image', 'max:2048'],
                        'image'=>['image'],
                        'video'=>['mimes:mp4,mov,ogx,oga,ogv,ogg,webm', 'max:2048'],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'orderBy'=>['integer','min:0'],
                        'tagIds'=>['array'],
                        'tagIds.*' => [Rule::exists('tag_categories', 'id')],
                        'gallery'=>['array'],
                        'gallery.*.file' => ['image','max:1048']
                    ];
                }
            default: break;
        }
    }
}
