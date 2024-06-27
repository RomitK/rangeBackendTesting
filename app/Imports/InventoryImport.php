<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InventoryException;
use App\Models\{
    Property,
    Accommodation
};


class InventoryImport implements ToCollection
{
    protected $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        try {
            DB::beginTransaction();
            foreach ($collection as $index => $data) {
                if ($index > 0) {
                    $srNo = $data[0];
                    $accommodationName = $data[1];
                    $bedrooms = $data[2];
                    $area = $data[3];
                    $buildArea = $data[4];
                    $price = $data[5];
                    $unitType = $data[6];
                    if (Accommodation::where('name', $accommodationName)->exist()) {
                    } else {

                        throw new InventoryException('Property Type is not match with our data');
                    }
                    Property::where('project_id', $this->project->id)->where()->first();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
