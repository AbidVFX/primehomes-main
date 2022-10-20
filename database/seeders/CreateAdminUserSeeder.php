<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'Primehomes',
            'email' => 'admin@gmail.com',
            'type' => 'Superadmin',
            'password' => bcrypt('123456')
        ]);
        $role = Role::firstOrCreate(['name' => 'Superadmin']);
        $permissions = Permission::pluck('id', 'id')->except(49)->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
