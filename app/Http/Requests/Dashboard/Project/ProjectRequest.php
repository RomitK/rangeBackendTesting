<?php

namespace App\Http\Requests\Dashboard\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
                        'sub_title' => ['max:225'],
                        // 'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'website_status' => ['required', Rule::in(array_keys(config('constants.newStatusesWithoutAll')))],
                        'is_new_launch'=> ['boolean'],
                        'is_featured'=> ['boolean'],
                        'is_display_home'=> ['required','boolean'],
                        'starting_price'=>['max:225'],
                        'starting_price_highlight'=>['boolean'],
                        'completion_date'=>['max:225'],
                        'completion_date_highlight'=>['boolean'],
                        'bedrooms'=>['max:225'],
                        'bathrooms'=>['max:225'],
                        'area'=>['max:225'],
                        'area_highlight'=>['boolean'],
                        'is_parent_project'=>['boolean'],
                        'accommodation_id_highlight'=>['boolean'],
                        'community_id_highlight'=>['boolean'],
                        'agent_id'=>[Rule::exists('agents', 'id')],
                        'developer_id'=>['required',Rule::exists('developers', 'id')],
                        'main_community_id'=>['required', Rule::exists('communities', 'id')],
                        'sub_community_id'=>[Rule::exists('communities', 'id')],
                        'mainImage'=>['image', 'max:2048'],
                        'video'=>['mimes:mp4,mov,ogx,oga,ogv,ogg,webm'],
                        // 'exteriorGallery'=>['array'],
                        // 'exteriorGallery.*' => ['image',  'max:2048'],
                        
                        'exteriorGallery'=>['array'],
                        'exteriorGallery.*.file' => ['image','max:1048'],
                        
                        'interiorGallery'=>['array'],
                        'interiorGallery.*.file' => ['image','max:1048'],
                        
                        // 'interiorGallery'=>['array'],
                        // 'interiorGallery.*' => ['image',  'max:2048'],
                        
                        'brochure'=>['mimes:pdf','max:24576'],
                        'factsheet'=>['mimes:pdf','max:24576'],
                        'paymentPlan'=>['mimes:pdf','max:24576'],
                        'accommodationIds'=>['array'],
                        'accommodationIds.*' => [Rule::exists('accommodations', 'id')],
                        'highlight_amenities'=>['array'],
                        'highlight_amenities.*' => [Rule::exists('amenities', 'id')],
                        'tagIds'=>['array'],
                        'tagIds.*' => [Rule::exists('tag_categories', 'id')],
                        // 'emirate'=>['required', Rule::in(config('constants.emirates'))],
                        'address_longitude' => ['nullable','numeric','between:-180,180'],
                        'address_latitude' => ['nullable','numeric','between:-90,90'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title' => ['required', 'min:3','max:225'],
                        'sub_title' => ['max:225'],
                        // 'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        'website_status' => ['required', Rule::in(array_keys(config('constants.newStatusesWithoutAll')))],
                        'is_new_launch'=> ['boolean'],
                        'is_featured'=> ['boolean'],
                        'is_display_home'=> ['required','boolean'],
                        'starting_price'=>['max:225'],
                        'starting_price_highlight'=>['boolean'],
                        'completion_date'=>['max:225'],
                        'completion_date_highlight'=>['boolean'],
                        'bedrooms'=>['max:225'],
                        'bathrooms'=>['max:225'],
                        'area'=>['max:225'],
                        'area_highlight'=>['boolean'],
                        'is_parent_project'=>['boolean'],
                        'accommodation_id_highlight'=>['boolean'],
                        'community_id_highlight'=>['boolean'],
                        'agent_id'=>[Rule::exists('agents', 'id')],
                        'developer_id'=>['required',Rule::exists('developers', 'id')],
                        'main_community_id'=>['required',Rule::exists('communities', 'id')],
                        'sub_community_id'=>['nullable',Rule::exists('communities', 'id')],
                        'mainImage'=>['image', 'max:2048'],
                        'video'=>['mimes:mp4,mov,ogx,oga,ogv,ogg,webm'],
                        // 'exteriorGallery'=>['array'],
                        // 'exteriorGallery.*' => ['image', 'max:2048'],
                        // 'interiorGallery'=>['array'],
                        // 'interiorGallery.*' => ['image','max:2048'],
                        
                        'exteriorGallery'=>['array'],
                        'exteriorGallery.*.file' => ['image','max:1048'],
                        
                        'interiorGallery'=>['array'],
                        'interiorGallery.*.file' => ['image','max:1048'],
                        
                        'brochure'=>['mimes:pdf','max:24576'],
                        'factsheet'=>['mimes:pdf','max:24576'],
                        'paymentPlan'=>['mimes:pdf','max:24576'],
                        'accommodationIds'=>['array'],
                        'accommodationIds.*' => [Rule::exists('accommodations', 'id')],
                        'highlight_amenities'=>['array'],
                        'highlight_amenities.*' => [Rule::exists('amenities', 'id')],
                        'tagIds'=>['array'],
                        'tagIds.*' => [Rule::exists('tag_categories', 'id')],
                        // 'emirate'=>['required', Rule::in(config('constants.emirates'))],
                        // 'address_longitude' => ['nullable','numeric','between:-180,180'],
                        // 'address_latitude' => ['nullable','numeric','between:-90,90'],
                    ];
                }
            default: break;
        }
    }
}
//max:10240 = max 10 MB. max:1 = max 1024 bytes.
