<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

class TenantImport implements ToCollection, WithValidation, WithHeadingRow
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
                User::create([
                    'name'     => $row['firstname'].''.$row['lastname'],
                    'email'    => strtolower($row['email1']),
                    'type'     => 'Tenant',
                    'password' => Hash::make('123456'),
            ])->assignRole('Tenant');
            }
            Owner::updateOrCreate(['primary_email'=>$row['email1']], [
                'type'     => 'tenant',
                'firstname'     => $row['firstname'],
                'lastname'    => $row['lastname'],
                'middlename'    => $row['middlename'],
                'landline'    => $row['landline'],
                'primary_mobile'    => $row['mobile1'],
                'secondary_mobile'    => $row['mobile2'],
                'primary_email'    => $row['email1'],
                'secondary_email'    => $row['email2'],
                'atlernate_email'    => $row['email3'],
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
