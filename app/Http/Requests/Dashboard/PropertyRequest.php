<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyRequest extends FormRequest
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
                        'name' => ['required', 'min:3', 'max:225'],
                        'sub_title' => ['max:225'],
                        'reference_number' => ['max:225'],
                        'permit_number' => ['max:225'],
                        'property_source' => ['required', Rule::in(config('constants.propertySources'))],
                        'website_status' => ['required', Rule::in(array_keys(config('constants.newStatusesWithoutAll')))],
                        //'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        // 'emirate'=>['required', Rule::in(config('constants.emirates'))],
                        'bedrooms' => ['nullable', 'max:225'],
                        'bathrooms' => ['nullable', 'numeric', 'max:225'],
                        'area' => ['nullable', 'max:225'],
                        'parking' => ['nullable', 'numeric'],
                        'parking_space' => ['max:225'],
                        'unit_refNo' => ['max:225'],
                        'furnished' => ['max:225'],
                        'currency' => ['max:225'],
                        'primary_view' => ['max:225'],
                        'cheque_frequency' => ['max:225'],
                        'exclusive' => ['boolean'],
                        'built_area' => ['nullable', 'numeric'],
                        'price' => ['nullable', 'max:225'],
                        'is_featured' => ['boolean'],
                        'offer_type_id' => [Rule::exists('offer_types', 'id')],
                        'developer_id' => [Rule::exists('developers', 'id')],
                        'agent_id' => [Rule::exists('agents', 'id')],
                        'completion_status_id' => [Rule::exists('completion_statuses', 'id')],
                        'is_display_home' => ['boolean'],
                        'community_id' => [Rule::exists('communities', 'id')],
                        'subcommunity_id' => [Rule::exists('subcommunities', 'id')],
                        'category_id' => [Rule::exists('categories', 'id')],
                        'accommodation_id' => [Rule::exists('accommodations', 'id')],
                        'rating' => ['nullable', 'numeric'],
                        'address_longitude' => ['nullable', 'numeric', 'between:-180,180'],
                        'address_latitude' => ['nullable', 'numeric', 'between:-90,90'],
                        'mainImage' => ['image', 'max:2048'],
                        'qr' => ['image', 'max:2048'],
                        // 'subImages'=>['array'],
                        // 'subImages.*' => ['image', 'max:2048'],

                        'subImages' => ['array'],
                        'subImages.*.file' => ['image', 'max:1048'],


                        'floorplans' => ['array'],
                        'floorplans.*' => ['image', 'max:2048'],

                        'amenityIds' => ['array'],
                        'amenityIds.*' => [Rule::exists('amenities', 'id')],
                        'saleOffer' => ['size:2097152'],
                        'video' => ['size:2097152'],

                    ];
                }
            case 'PATCH':
            case 'PUT': {
                    return [
                        'name' => ['required', 'min:3', 'max:225'],
                        'sub_title' => ['max:225'],
                        'reference_number' => ['max:225'],
                        'permit_number' => ['max:225'],
                        'property_source' => ['required', Rule::in(config('constants.propertySources'))],
                        'website_status' => ['required', Rule::in(array_keys(config('constants.newStatusesWithoutAll')))],
                        //'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                        // 'emirate'=>['required', Rule::in(config('constants.emirates'))],
                        'bedrooms' => ['nullable', 'max:225'],
                        'bathrooms' => ['nullable', 'numeric', 'max:225'],
                        'area' => ['nullable', 'max:225'],
                        'parking' => ['nullable', 'numeric'],
                        'parking_space' => ['max:225'],
                        'unit_refNo' => ['max:225'],
                        'furnished' => ['max:225'],
                        'currency' => ['max:225'],
                        'primary_view' => ['max:225'],
                        'cheque_frequency' => ['max:225'],
                        'exclusive' => ['boolean'],
                        'built_area' => ['nullable', 'numeric'],
                        'price' => ['nullable', 'max:225'],
                        'is_featured' => ['boolean'],
                        'offer_type_id' => [Rule::exists('offer_types', 'id')],
                        'developer_id' => [Rule::exists('developers', 'id')],
                        'agent_id' => [Rule::exists('agents', 'id')],
                        'completion_status_id' => [Rule::exists('completion_statuses', 'id')],
                        'is_display_home' => ['boolean'],
                        'community_id' => [Rule::exists('communities', 'id')],
                        'subcommunity_id' => [Rule::exists('subcommunities', 'id')],
                        'category_id' => [Rule::exists('categories', 'id')],
                        'accommodation_id' => [Rule::exists('accommodations', 'id')],
                        'rating' => ['nullable', 'numeric'],
                        'address_longitude' => ['nullable', 'numeric', 'between:-180,180'],
                        'address_latitude' => ['nullable', 'numeric', 'between:-90,90'],
                        'mainImage' => ['image', 'max:2048'],
                        'qr' => ['image',  'max:2048'],
                        // 'subImages'=>['array'],
                        // 'subImages.*' => ['image', 'max:2048'],

                        'subImages' => ['array'],
                        'subImages.*.file' => ['image', 'max:1048'],

                        'amenityIds' => ['array'],
                        'amenityIds.*' => [Rule::exists('amenities', 'id')],

                        'floorplans' => ['array'],
                        'floorplans.*' => ['image', 'max:2048'],

                        'saleOffer' => ['size:2097152'],
                        'video' => ['size:2097152'], //2 MB

                    ];
                }
            default:
                break;
        }
    }
}
