<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class CreateTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'Tenant'])
        ->givePermissionTo(['project-list','billing-list','billing-invoice','complain-list','complain-create' ,'complain-edit'  ]);
    }
}
