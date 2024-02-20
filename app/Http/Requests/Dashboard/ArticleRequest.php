<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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
                        'author'=>[ 'min:3','max:225'],
                        'publish_at'=>['required','date'],
                        'mainImage' => ['image', 'max:2048'],
                        'article_type'=>['required', Rule::in(config('constants.mediaTypes'))],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))]
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title' => ['required', 'min:3','max:225'],
                        'author'=>['min:3','max:225'],
                        'publish_at'=>['required','date'],
                        'mainImage' => ['image', 'max:2048'],
                        'article_type'=>['required', Rule::in(config('constants.mediaTypes'))],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))]
                    ];
                }
            default: break;
        }
    }
}
