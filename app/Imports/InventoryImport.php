<?php

namespace App\Imports;

use App\Exceptions\InventoryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Accommodation
};

class InventoryImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        DB::beginTransaction();

        try {
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
                        throw new InventoryException("Inventory item not found", 0, 422);
                    }
                }
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
