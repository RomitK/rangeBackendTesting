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


                    if (!Accommodation::where('name', $accommodationName)->exists()) {
                        throw new InventoryException("Property Type is not found", 0, 420);
                    }
                    if (!isset($bedrooms) || (!filter_var($bedrooms, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) && strtolower($bedrooms) !== 'studio')) {
                        throw new InventoryException("Bedroom is not found or maybe not a positive number or 'Studio'", 0, 420);
                    }
                    if (!isset($price) || !filter_var($price, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                        throw new InventoryException("Price is not found or maybe not a positive number", 0, 420);
                    }

                    // Check if the accommodation and bedroom already exist in the array
                    if (!isset($groupedAccommodationData[$accommodationName])) {
                        $groupedAccommodationData[$accommodationName] = [];
                    }
                    if (!isset($groupedAccommodationData[$accommodationName][$bedrooms])) {
                        $groupedAccommodationData[$accommodationName][$bedrooms] = [
                            'srNo' => $srNo,
                            'accommodationName' => $accommodationName,
                            'bedrooms' => $bedrooms,
                            'area' => $area,
                            'buildArea' => $buildArea,
                            'price' => $price,
                            'unitType' => $unitType,
                        ];
                    } else {
                        // If the accommodation and bedroom exist, update only if the new price is lower
                        if ($price < $groupedAccommodationData[$accommodationName][$bedrooms]['price']) {
                            $groupedAccommodationData[$accommodationName][$bedrooms] = [
                                'srNo' => $srNo,
                                'accommodationName' => $accommodationName,
                                'bedrooms' => $bedrooms,
                                'area' => $area,
                                'buildArea' => $buildArea,
                                'price' => $price,
                                'unitType' => $unitType,
                            ];
                        }
                    }
                }
            }
            foreach ($groupedAccommodationData as $index => $accommodationData) {

                Log::info($accommodationData);

                $accommodationId = Accommodation::where('name', $index)->first()->id;
                Log::info('accommodationId-' . $accommodationId);
                foreach ($accommodationData as $data) {
                    Log::info('data-');
                    Log::info($data);


                    $propertyBedrooms = $data['bedrooms'];
                    $propertyArea = $data['bedrooms'];
                    $propertyBuildArea = $data['buildArea'];
                    $propertyPrice = $data['price'];
                    $unitType = $data['unitType'];






                    $propertyExist = Property::where('project_id', $this->project->id)
                        ->where('bedrooms', $propertyBedrooms)
                        ->where('accommodation_id', $accommodationId)
                        ->where('sub_project_id',)
                        ->exists();


                    Log::info('propertyExist-' . $propertyExist);
                    if ($propertyExist) {


                        $unityTypeExist = Project::where('parent_project_id', $this->project->id)
                            ->where('is_parent_project', 0)
                            ->where('bedrooms', $propertyBedrooms)
                            ->where('accommodation_id', $accommodationId)
                            ->Where('title', $unitType)
                            ->exists();

                        if ($unityTypeExist) {
                            $property = Property::where('project_id', $this->project->id)
                                ->where('bedrooms', $propertyBedrooms)
                                ->where('accommodation_id', $accommodationId)
                                ->first();

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
                            $property->save();


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
                        }
                    } else {

                        $unitType = $data['unitType'];
                        Log::info('unitType-' . $unitType);


                        if ($unitType) {

                            $unityTypeExist = Project::where('parent_project_id', $this->project->id)
                                ->where('is_parent_project', 0)
                                ->where('bedrooms', $propertyBedrooms)
                                ->where('accommodation_id', $accommodationId)
                                ->Where('title', $unitType)
                                ->exists();

                            Log::info('unityTypeExist-' . $unityTypeExist);

                            if ($unityTypeExist) {
                                $subProject = Project::where('parent_project_id', $this->project->id)
                                    ->where('is_parent_project', 0)
                                    ->where('bedrooms', $propertyBedrooms)
                                    ->where('accommodation_id', $accommodationId)
                                    ->Where('title', $unitType)
                                    ->first();
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
                            }
                            Log::info('subProject-');
                            Log::info($subProject);
                            $property = new Property;
                            $property->name = $subProject->title;
                            $property->is_approved = config('constants.requested');
                            $property->status = config('constants.active');
                            $property->approval_id = Auth::user()->id;
                            $property->website_status = config('constants.requested');
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


                        Log::info("propertyBedrooms-" . $propertyBedrooms . "projectId-" . $this->project->id . "accommodation_id-" . $accommodationId);

                        Log::info('Property Exist false');
                    }
                }
            }
            // foreach ($collection as $index => $data) {
            //     if ($index > 0) {
            //         $propertysrNo = $data[0];
            //         $propertyAccommodationName = $data[1];
            //         $propertyBedrooms = $data[2];
            //         $propertyArea = $data[3];
            //         $propertyBuildArea = $data[4];
            //         $propertyPrice = $data[5];
            //         $propertyUnitType = $data[6];

            //         Log::info("propertyAccommodationName:" . $propertyAccommodationName . "propertyBedrooms" . $propertyBedrooms);

            //         $sameProprtyTypeBedroomDataSet = $groupedAccommodationData[$propertyAccommodationName][$propertyBedrooms];

            //         Log::info('sameProprtyTypeBedroomDataSet');
            //         Log::info($sameProprtyTypeBedroomDataSet);

            //         $minPriceProperty = collect($sameProprtyTypeBedroomDataSet)->sortBy('price')->first();
            //         Log::info('minPriceProperty');
            //         Log::info($minPriceProperty);

            //         $propertyExist = Property::where('project_id', $this->project->id)
            //             ->where('bedrooms', $propertyBedrooms)
            //             ->where('accommodation_id', Accommodation::where('name', $propertyAccommodationName)->first()->id)
            //             ->exists();

            //         if ($propertyExist) {

            //             $property = Property::where('project_id', $this->project->id)
            //                 ->where('bedrooms', $propertyBedrooms)
            //                 ->where('accommodation_id', Accommodation::where('name', $propertyAccommodationName)->first()->id)
            //                 ->first();

            //             $originalAttributes = $property->getOriginal();
            //             $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
            //             $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

            //             if ($property->amenities) {
            //                 $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
            //             } else {
            //                 $originalAttributes['amenityIds'] = [];
            //             }

            //             $property->price = $propertyPrice;

            //             if ($propertyArea) {
            //                 $property->area = $propertyArea;
            //             }
            //             if ($propertyBuildArea) {
            //                 $property->builtup_area = $propertyArea;
            //             }
            //             $property->updated_by = Auth::user()->id;
            //             $property->save();


            //             $newPropertyOriginalAttributes = $property->getOriginal();

            //             if ($property->amenities) {
            //                 $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
            //             } else {
            //                 $newPropertyOriginalAttributes['amenityIds'] = [];
            //             }

            //             if (isset($property->description)) {
            //                 $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
            //             }
            //             if (isset($property->short_description)) {
            //                 $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
            //             }

            //             $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);

            //             logActivity('Property has been updated du', $property->id, Property::class, $properties);

            //             Log::info('Property Exist true');
            //         } else {


            //             Log::info("propertyBedrooms-" . $propertyBedrooms . "projectId-" . $this->project->id . "accommodation_id-" . Accommodation::where('name', $propertyAccommodationName)->first()->id);

            //             Log::info('Property Exist false');
            //         }
            //     }
            // }
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
