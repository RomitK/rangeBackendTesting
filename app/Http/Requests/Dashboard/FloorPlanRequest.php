<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FloorPlanRequest extends FormRequest
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
                        'project_name'=>['required',Rule::unique('floor_plans')->whereNull('deleted_at'), 'min:3','max:225'],
                        'community_id'=>[Rule::exists('communities', 'id')],
                        'mainImage'=>['image', 'max:2048'],
                        'floorPlanFile'=>['required','mimes:pdf'],
                        'sub_community_id'=>[Rule::exists('subcommunities', 'id')],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title' => ['required','min:3','max:225'],
                        'mainImage'=>['image',  'max:2048'],
                        'floorPlanFile'=>['mimes:pdf'],
                        'project_name'=>['required', Rule::unique('floor_plans')->ignore($this->floorPlan)->whereNull('deleted_at'), 'min:3','max:225'],
                        'community_id'=>[Rule::exists('communities', 'id')],
                        'sub_community_id'=>[Rule::exists('subcommunities', 'id')],
                        'status' => ['required', Rule::in(array_keys(config('constants.statuses')))],
                    ];
                }
            default: break;
        }
    }
}
