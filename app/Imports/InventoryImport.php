<?php

namespace App\Imports;

use App\Exceptions\InventoryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\{
    DB,
    Log,
    Auth
};
use App\Models\{
    Accommodation,
    Property,
    Project
};


class InventoryImport implements ToCollection
{
    protected $project, $oldProperties, $updatedProperties;

    function __construct($project)
    {
        $this->project = $project;
        $this->oldProperties = Property::where('project_id', $this->project->id)->pluck('id')->toArray();
        $this->updatedProperties = [];
    }
    public function collection(Collection $collection)
    {
        DB::beginTransaction();

        try {
            $allowedBedrooms = ['Studio','studio', 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 10, 11, 12];
            $groupedAccommodationData = [];
            foreach ($collection as $index => $data) {
                if ($index > 0) {
                    $srNo = $data[0];
                    $accommodationName = $data[1];
                    $bedrooms = $data[2];
                    $area = $data[3];
                    $buildArea = $data[4];
                    $price = $data[5];
                    $unitType = $data[6];
		            $inventoryStatus = $data[7];

                    if ($accommodationName && $bedrooms && $unitType) {

                        if (!Accommodation::where('name', $accommodationName)->exists()) {
                            throw new InventoryException("Property Type is not found", 0, 420);
                        }
                        // Define allowed bedroom values


                        // Check if $bedrooms is not set or is not in the allowed values
                        $normalizedBedrooms = $bedrooms;
                       
                        if ( !in_array($normalizedBedrooms, $allowedBedrooms, true)) {
                           
                            throw new InventoryException("Bedroom is not found or not an allowed value", 0, 420);
                        }
                        if (!isset($price) || !filter_var($price, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                            throw new InventoryException("Price is not found or maybe not a positive number", 0, 420);
                        }
			
                        if (!isset($inventoryStatus) || !in_array($inventoryStatus, [0, 1], true)) {
                                throw new InventoryException("Inventory status is not found or is not a valid boolean value (0 or 1)", 0, 420);
                        }

                        // Check if the accommodation and bedroom already exist in the array
                        
                        if (!isset($groupedUnitnData[$unitType])) {
                            $groupedUnitData[$unitType] = [
                                'srNo' => $srNo,
                                'accommodationName' => $accommodationName,
                                'bedrooms' => $bedrooms,
                                'area' => $area,
                                'buildArea' => $buildArea,
                                'price' => $price,
                                'unitType' => $unitType,
				                'inventoryStatus' => $inventoryStatus
                            ];
                        } else {
                            // If the accommodation and bedroom exist, update only if the new price is lower
                            if ($price < $groupedUnitData[$unitType]['price']) {
                                $groupedUnitData[$unitType]= [
                                    'srNo' => $srNo,
                                    'accommodationName' => $accommodationName,
                                    'bedrooms' => $bedrooms,
                                    'area' => $area,
                                    'buildArea' => $buildArea,
                                    'price' => $price,
                                    'unitType' => $unitType,
				    'inventoryStatus' => $inventoryStatus
                                ];
                            }
                        }
                    }
                }
            }
		
            Log::info($groupedUnitData);

            foreach ($groupedUnitData as $unitIndex => $unitData) {

                Log::info($unitData);

			            $propertyBedrooms = $unitData['bedrooms'];
                        $propertyArea = $unitData['bedrooms'];
                        $propertyBuildArea = $unitData['buildArea'];
                        $propertyPrice = $unitData['price'];
                        $unitType = $unitIndex;
                        $inventoryStatus = $unitData['inventoryStatus'];
                        $accommodationId = Accommodation::where('name', $unitData['accommodationName'])->first()->id;
                       Log::info('accommodationId-' . $accommodationId);
                   
                        $unityTypeExist = Project::where('parent_project_id', $this->project->id)
                            ->where('is_parent_project', 0)
                            ->Where('title', $unitType)
                            ->exists();
                        Log::info('unityTypeExist' . $unityTypeExist);
                        if ($unityTypeExist) {


                            $unityType = Project::where('parent_project_id', $this->project->id)
                                ->where('is_parent_project', 0)
                                ->Where('title', $unitType)
                                ->first();

                            $unityTypeId = $unityType->id;

                            $propertyExist = Property::where('project_id', $this->project->id)->where('sub_project_id', $unityTypeId)->exists();

                            if ($propertyExist) {
                                $property = Property::where('project_id', $this->project->id)->where('sub_project_id', $unityTypeId)->first();

                                $originalAttributes = $property->getOriginal();
                                $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                                $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

                                if ($property->amenities) {
                                    $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                                } else {
                                    $originalAttributes['amenityIds'] = [];
                                }

                                $property->price = $propertyPrice;

                                if ($propertyArea) {
                                    $property->area = $propertyArea;
                                }
                                if ($propertyBuildArea) {
                                    $property->builtup_area = $propertyArea;
                                }
                                $property->updated_by = Auth::user()->id;
                                if($accommodationId){

                                    $property->accommodation_id =  $accommodationId;
                                }
                                if($inventoryStatus == 0){
                                    $property->website_status = config('constants.NA');
                                    $property->is_approved = config('constants.approved');
                                    $property->status = config('constants.Inactive');
                                }else{
                                    // $property->is_approved = config('constants.requested');
                                    // $property->status = config('constants.active');
                                    // $property->approval_id = Auth::user()->id;
                                    // $property->website_status = config('constants.requested');
                                }
                                $property->save();

                                $project = Project::find($this->project->id);
                                $project->timestamps = false;
                                $project->inventory_update = Carbon::now();
                                $project->save();

                                array_push($this->updatedProperties, $property->id);


                                $newPropertyOriginalAttributes = $property->getOriginal();

                                if ($property->amenities) {
                                    $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                                } else {
                                    $newPropertyOriginalAttributes['amenityIds'] = [];
                                }

                                if (isset($property->description)) {
                                    $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
                                }
                                if (isset($property->short_description)) {
                                    $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                                }

                                $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);

                                logActivity('Property has been updated through inventory file', $property->id, Property::class, $properties);

                                Log::info('Property Exist true');
                            } else {

                                $property = new Property;
                                $property->name = $unityType->title;
				                if($inventoryStatus == 0){
                                        $property->website_status = config('constants.NA');
                                        $property->is_approved = config('constants.approved');
                                        $property->status = config('constants.Inactive');
					                    $property->approval_id = Auth::user()->id;
                                }else{

                                	$property->is_approved = config('constants.requested');
                                	$property->status = config('constants.active');
                                	$property->approval_id = Auth::user()->id;
                                	$property->website_status = config('constants.requested');
				                }
                                $property->property_source = 'crm';
                                $property->bathrooms = 0;
                                $property->bedrooms = $propertyBedrooms;
                                $property->area = $propertyArea;
                                $property->price = $propertyPrice;
                                $property->builtup_area = $propertyBuildArea;
                                $property->project_id = $this->project->id;
                                $property->sub_project_id = $unityType->id;
                                $property->accommodation_id = $accommodationId;
                                $property->category_id = 8;
                                $property->reference_number = generatePropertyUniqueCode('S');
                                if ($this->project->completion_status_id == 289) { // if the project is under-construction then its offplan else ready
                                    $property->completion_status_id = 287;
                                } else {
                                    $property->completion_status_id = 286;
                                }


                                $property->address_longitude = $this->project->address_longitude;
                                $property->address_latitude = $this->project->address_latitude;
                                $property->address = $this->project->address;
                                $property->user_id = Auth::user()->id;
                                $property->save();

                                $project = Project::find($this->project->id);
                                $project->timestamps = false;
                                $project->inventory_update = Carbon::now();
                                $project->save();


                                array_push($this->updatedProperties, $property->id);

                                $property->amenities()->attach($this->project->amenities->pluck('id')->toArray());


                                $originalAttributes = $property->getOriginal();

                                $originalAttributes['short_description'] = null;
                                $originalAttributes['description'] = null;

                                $originalAttributes['amenityIds'] = $this->project->amenities->pluck('id')->toArray();


                                // Log activity for developer creation
                                $properties = json_encode([
                                    'old' => [],
                                    'new' => $originalAttributes,
                                    'updateAttribute' => [],
                                    'attribute' => []
                                ]);
                                logActivity('New Property has been created through inventory file', $property->id, Property::class, $properties);

                                Log::info("newPropertyId-" . $property);
                            }
                        } else {

                            $subProject = new Project;
                            $subProject->title = $unitType;
                            $subProject->is_parent_project = 0;
                            $subProject->parent_project_id = $this->project->id;
                            $subProject->bedrooms = $propertyBedrooms;
                            $subProject->list_type = 'primary';
                            $subProject->area = $propertyArea;
                            $subProject->builtup_area = $propertyBuildArea;
                            $subProject->user_id = Auth::user()->id;
                            $subProject->accommodation_id = $accommodationId;
                            $subProject->starting_price = $propertyPrice;
                            $subProject->is_approved = config('constants.requested');
                            $subProject->status = config('constants.active');
                            $subProject->website_status = config('constants.requested');
                            $subProject->save();


                            Log::info('subProject-');
                            Log::info($subProject);
                            $property = new Property;
                            $property->name = $subProject->title;
			                if($inventoryStatus == 0){
                                $property->website_status = config('constants.NA');
                                $property->is_approved = config('constants.approved');
                                $property->status = config('constants.Inactive');
                                $property->approval_id = Auth::user()->id;
                            }else{

                                $property->is_approved = config('constants.requested');
                                $property->status = config('constants.active');
                                $property->approval_id = Auth::user()->id;
                                $property->website_status = config('constants.requested');
				            }

                            $project = Project::find($this->project->id);
                            $project->timestamps = false;
                            $project->inventory_update = Carbon::now();
                            $project->save();

                            $property->property_source = 'crm';
                            $property->bathrooms = 0;
                            $property->bedrooms = $propertyBedrooms;
                            $property->area = $propertyArea;
                            $property->price = $propertyPrice;
                            $property->builtup_area = $propertyBuildArea;
                            $property->project_id = $this->project->id;
                            $property->sub_project_id = $subProject->id;
                            $property->accommodation_id = $accommodationId;
                            $property->category_id = 8;
                            $property->reference_number = generatePropertyUniqueCode('S');
                            if ($this->project->completion_status_id == 289) { // if the project is under-construction then its offplan else ready
                                $property->completion_status_id = 287;
                            } else {
                                $property->completion_status_id = 286;
                            }


                            $property->address_longitude = $this->project->address_longitude;
                            $property->address_latitude = $this->project->address_latitude;
                            $property->address = $this->project->address;
                            $property->user_id = Auth::user()->id;
                            $property->save();

                            array_push($this->updatedProperties, $property->id);

                            $property->amenities()->attach($this->project->amenities->pluck('id')->toArray());


                            $originalAttributes = $property->getOriginal();

                            $originalAttributes['short_description'] = null;
                            $originalAttributes['description'] = null;

                            $originalAttributes['amenityIds'] = $this->project->amenities->pluck('id')->toArray();


                            // Log activity for developer creation
                            $properties = json_encode([
                                'old' => [],
                                'new' => $originalAttributes,
                                'updateAttribute' => [],
                                'attribute' => []
                            ]);
                            logActivity('New Property has been created through inventory file', $property->id, Property::class, $properties);

                            Log::info("newPropertyId-" . $property);
                        
                }
            }

            Log::info('group-DATA');
            Log::info($groupedAccommodationData);

            Log::info('$this->updatedProperties');
            Log::info($this->updatedProperties);


            Log::info('oldProperties');
            Log::info($this->oldProperties);


            $outOfInventoryProperties = array_diff($this->oldProperties, $this->updatedProperties);

            Log::info('outOfInventoryProperties');
            Log::info($outOfInventoryProperties);
            foreach ($outOfInventoryProperties as $propertyId) {


                $originalAttributes = $property->getOriginal();
                $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

                if ($property->amenities) {
                    $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $originalAttributes['amenityIds'] = [];
                }


                $property = Property::find($propertyId);
                $property->status = config('constants.Inactive');
                $property->approval_id = Auth::user()->id;
                $property->website_status = config('constants.NA');
                $property->out_of_inventory = 1;
                $property->save();


                // Retrieve the updated attributes
                $newPropertyOriginalAttributes = $property->getOriginal();

                if ($property->amenities) {
                    $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $newPropertyOriginalAttributes['amenityIds'] = [];
                }


                if (isset($property->description)) {
                    $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
                }
                if (isset($property->short_description)) {
                    $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                }


                $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);
                logActivity('Property marked as NA due to out of inventory', $property->id, Property::class, $properties);
            }


            DB::commit();
        } catch (InventoryException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
