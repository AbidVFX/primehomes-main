<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(PermissionTableSeeder::class);
        $this->call(CreateAdminUserSeeder::class);
        $this->call(CreateOwnerSeeder::class);
        $this->call(CreateTenantSeeder::class);
        $this->call(CreateUserSeeder::class);
        $this->call(CreateElectricianSeeder::class);
        $this->call(CreateJanitorSeeder::class);
        $this->call(CreatePlumberSeeder::class);


    }
}
