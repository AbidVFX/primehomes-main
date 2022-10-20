<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
class OwnerImport implements ToCollection, WithValidation, WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
       
         Validator::make($rows->toArray(), [
             '*.firstname' => 'required',
             '*.lastname' => 'required',
             '*.mobile1' => 'required',
             '*.email1' => 'required','unique:users,email',
         ])->validate();

        foreach ($rows as $row) {
            if (User::where('email', '=', $row['email1'])->count() == 0) {
            User::create ([
                'name'     => $row['firstname'].''.$row['lastname'],
                'email'    => strtolower($row['email1']),
                'type'     => 'Owner',
                'password' => Hash::make('123456'),
            ])->assignRole('Owner');
            }
            
            Owner::updateOrCreate (['primary_email'=>$row['email1']],[
                'firstname'     => $row['firstname'],
                'lastname'    => $row['lastname'], 
                'middlename'    => $row['middlename'], 
                'landline'    => $row['landline'], 
                'primary_mobile'    => $row['mobile1'], 
                'secondary_mobile'    => $row['mobile2'], 
                'primary_email'    => $row['email1'], 
                'secondary_email'    => $row['email2'], 
                'alternate_email'    => $row['email3'], 
                'contact_person'    => $row['emergencycontactperson'], 
                'contact_number'    => $row['emergencynumber'], 
            ]);
        }
    }
    public function rules(): array
{
    return [
        'email1' => 'distinct',
    ];
}
    
}
