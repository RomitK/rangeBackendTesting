<?php

namespace App\Http\Requests\Enquiry;

use App\Enums\PropertyStatus\PropertyStatusEnum;
use App\Enums\PropertyType\PropertyTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnquiryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'campaign_id' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile_country_code' => ['required', 'string', 'max:5'],
            'mobile' => ['required', 'string', 'max:15'],
            'property_status' => ['required', Rule::in(array_values(PropertyStatusEnum::toValues()))],
            'property_type' => ['required', Rule::in(array_values(PropertyTypeEnum::toValues()))],
            'number_of_rooms' => ['required', 'integer', 'min:0'],
            'min_price' => ['required', 'numeric', 'min:0'],
            'max_price' => ['required', 'numeric', 'min:0', 'gte:min_price'],
        ];
    }
}
