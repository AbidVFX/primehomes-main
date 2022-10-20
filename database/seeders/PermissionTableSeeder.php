<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           'owner-list',
           'owner-create',
           'owner-edit',
           'owner-delete',
           'owner-export',
           'owner-import',
           'owner-download',
           'lease-list',
           'lease-create',
           'lease-edit',
           'lease-delete',
           'unit-list',
           'unit-create',
           'unit-edit',
           'unit-delete',
           'unit-import',
           'unit-export',
           'unit-download',
           'tenant-list',
           'tenant-create',
           'tenant-edit',
           'tenant-delete',
           'tenant-import',
           'tenant-export',
           'tenant-download',
           'project-list',
           'project-create',
           'project-edit',
           'project-delete',
           'billing-list',
           'billing-create',
           'billing-edit',
           'billing-delete',
           'billing-invoice',
           'amenitie-list',
           'amenitie-create',
           'amenitie-edit',
           'amenitie-delete',
           'complain-list',
           'complain-create',
           'complain-edit',
           'complain-delete',

        ];
     
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
