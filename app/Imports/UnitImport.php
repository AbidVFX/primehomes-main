<?php

namespace App\Imports;

use App\Models\Owner;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Unit;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

class UnitImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $projectid = Project::where('building_id', $row['building'])->pluck('id')->first();
        // if(isset($row['owner'])){
        //     $ownerid = Owner::where('primary_email',$row['owner'])->pluck('id')->first();
        // }
        // else{
        //     $ownerid = null;
        // }
        $watermeter = (isset($row['water_meter_number'])) ? $row['water_meter_number'] : null;
        if ($projectid) {
            return Unit::create([
            'project_id'     => $projectid,
            'owner_id' => $row['owner'],
            'unit_no'    => $row['unitno'],
            'unit_type'    => $row['unittype'],
            'floor_area'    => $row['floorarea'],
            'parking'    => $row['parking'],
            'slot_no'    => $row['slotno'],
            'parking_area'    => $row['area'],
            'unit_paid'    => $row['fullypaid'],
            'parking_location'    => $row['parkinglocation'],
            'water_meter_number' =>  $watermeter,
        ]);
        }
    }
    public function rules(): array
    {
        return [
            'building' => [
                'required'
            ],
            'unitno' => [
                'required',
            ],
            'unittype' => [
                'required',
            ],
            'floorarea' => [
                'required',
            ],
            'parking' => [
                'required',
            ],
            'fullypaid' => [
                'required',
            ],

        ];
    }
}
