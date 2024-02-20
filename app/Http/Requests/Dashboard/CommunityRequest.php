<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommunityRequest extends FormRequest
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
                        'name' =>['required','min:3','max:225'],
                        'emirates'=>['required', Rule::in(config('constants.emirates'))],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'mainImage' => ['image', 'max:2048'],
                        // 'imageGallery' => ['required','array'],
                        // 'imageGallery.*' => ['image', 'max:2048'],
                        'video'=>['mimes:mp4,mov,ogx,oga,ogv,ogg,webm'],
                        'categoryIds'=>['array'],
                        'categoryIds.*' => [Rule::exists('categories', 'id')],
                        'tagIds'=>['array'],
                        'tagIds.*' => [Rule::exists('tag_categories', 'id')],
                        'developerIds'=>['array'],
                        'developerIds.*' => [Rule::exists('developers', 'id')],
                        
                        'gallery'=>['array'],
                        'gallery.*.file' => ['image','max:2048']
                        
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' =>['required','min:3','max:225'],
                        'emirates'=>['required', Rule::in(config('constants.emirates'))],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'mainImage' => ['image','max:2048'],
                        // 'imageGallery' => ['array'],
                        // 'imageGallery.*' => ['image', 'max:2048'],
                        'video'=>['mimes:mp4,mov,ogx,oga,ogv,ogg,webm'],
                        'categoryIds'=>['array'],
                        'categoryIds.*' => [Rule::exists('categories', 'id')],
                        'tagIds'=>['array'],
                        'tagIds.*' => [Rule::exists('tag_categories', 'id')],
                        'developerIds'=>['array'],
                        'developerIds.*' => [Rule::exists('developers', 'id')],
                        'gallery'=>['array'],
                        'gallery.*.file' => ['image','max:2048']
                    ];
                }
            default: break;
        }
    }
}
