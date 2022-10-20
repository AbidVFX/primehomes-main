<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\Lease;
use App\Models\Project;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\ValidationException;
class BillingImport implements ToModel, WithHeadingRow,SkipsEmptyRows
{

    use Importable;
    /**
    * @param Collection $collection
    */
    public function  __construct($year,$month)
    {
        $this->year = $year;
        $this->month= $month;
        
    }
    public function model(array $row)
    {
         
        //check if this month bill is created
        if(isset($row['building'])){
            $building = Project::where('building_id',$row['building'])->first();
        }
        else{
            throw ValidationException::withMessages(['Building Does not Exists or Wrong file']);
        }
       
        $unitinfo = Unit::where('project_id',$building->id)->where('unit_no',$row['unit'])->first();

         //check if there is lease created again this unit
        // if (Lease::where('project_id',$building->id)->where('unit_id',$unitinfo->id)->count()==0) {
        //     throw ValidationException::withMessages(['field_name' => 'No Lease exists against Unit '.$row['unit'].'']);
        //  }
        if($building){
            
            $billings = Billing::where('project_id',$building->id)->where('unit_no',$row['unit'])->where('month',$this->month)->where('year',$this->year)->first();
           
            if(!$unitinfo){
                throw ValidationException::withMessages(['field_name' => 'Unit '.$row['unit'].' Does not Exists']);
            }
            if(!$unitinfo){
                throw ValidationException::withMessages(['field_name' => 'Unit '.$row['unit'].' Does not Exists']);
            }
            if (!$billings) {

                    $rent = $building->association_dues* ($unitinfo->floor_area+$unitinfo->parking_area);
                
                    $billings = Billing::create([
                        'project_id' => $building->id,
                        'unit_no'    => $unitinfo->id,
                        'month'     => $this->month,
                        'year'     => $this->year,
                    ]);

                    //creating default rent for unit
                    BillingDetail::create([
                        'billing_id'       => $billings->id,
                        'type'             => 'default', 
                        'price'             => $rent, 
                    ]);
            }
          
             BillingDetail::create([
                'billing_id'       => $billings->id,
                'type'             => $row['type'],
                'current_reading'  => $row['reading'], 
                'consumption'       => $row['consumption'], 
                'price'             => $row['rate'], 
                'remarks'           => $row['remarks'],
            ]);
            
        }
        else{
            throw ValidationException::withMessages(['field_name' => 'This buidling id '.$row['building'].' does not exists.']);
        }
      
        
    }
    
}
