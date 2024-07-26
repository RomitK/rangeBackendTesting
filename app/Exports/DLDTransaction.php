<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\{
    Accommodation,
    Property,
    Project
};



class DLDTransaction implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
	$collection = collect();
	$propertyCount = 1;
       	foreach($this->data->subProjects as $key=>$unitType){
		$properties = Property::where('project_id', $this->data->id)->where('sub_project_id',$unitType->id)->where('property_source', 'crm')->get();
		foreach($properties as $propertyIndex=>$value){
			if($value->website_status == config('constants.NA') && $value->out_of_inventory){
				$inventoryStatus = 0;
			}else{
				$inventoryStatus = 1;
			}
			$collection->push([
				$propertyCount,
                		$value->accommodations->name,
				$value->bedrooms,
				$value->area,
				$value->builtup_area,
				$value->price,
				$unitType->title,
				$inventoryStatus
            		]);
			$propertyCount = $propertyCount+1;
		}
	}
	return $collection;
       // return collect($this->data);
    }
    public function headings(): array
    {
        // Add your headers here
         /** return [
            'transaction_id',
            'procedure_id',
            'trans_group_id',
            'trans_group_ar',
            'trans_group_en',
            'procedure_name_ar',
            'procedure_name_en',
            'instance_date',
            'property_type_id',
            'property_type_ar',
            'property_type_en',
            'property_sub_type_id',
            'property_sub_type_ar',
            'property_sub_type_en',
            'property_usage_ar',
            'property_usage_en',
            'property_usage_en',
            'reg_type_ar',
            'reg_type_en',
            'area_id',
            'area_name_ar',
            'area_name_en',
            'building_name_ar',
            'building_name_en',
            'project_number',
            'project_name_ar',
            'project_name_en',
            'master_project_en',
            'master_project_ar',
            'nearest_landmark_ar',
            'nearest_landmark_en',
            'nearest_metro_ar',
            'nearest_metro_en',
            'nearest_mall_ar',
            'nearest_mall_en',
            'rooms_ar',
            'rooms_en',
            'has_parking',
            'procedure_area',
            'actual_worth',
            'meter_sale_price',
            'rent_value',
            'meter_rent_price',
            'no_of_parties_role_1',
            'no_of_parties_role_2',
            'no_of_parties_role_3',
 
        ];
*/

return [
            'SR',
            'Property Type',
            'Bedrooms',
            'Area',
	'Build-up Area',
            'Price',
            'Unit Type',
            'Inventory Status'
        ];
    }

    public function styles()
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true]],
        ];
    }
}
