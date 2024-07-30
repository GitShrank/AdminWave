<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class VoyagerDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath;

    public function run()
    {
        $this->seedersPath = database_path('seeders') . '/';
        $this->seed(\Database\Seeders\DataTypesTableSeeder::class);
        $this->seed(\Database\Seeders\DataRowsTableSeeder::class);
        $this->seed(\Database\Seeders\MenusTableSeeder::class);
        $this->seed(\Database\Seeders\MenuItemsTableSeeder::class);
        $this->seed(\Database\Seeders\RolesTableSeeder::class);
        $this->seed(\Database\Seeders\PermissionsTableSeeder::class);
        $this->seed(\Database\Seeders\PermissionRoleTableSeeder::class);
        $this->seed(\Database\Seeders\SettingsTableSeeder::class);
        $this->seed(\Database\Seeders\UsersTableSeeder::class);
        $this->seed(\Database\Seeders\HostnamesTableSeeder::class);
        $this->seed(\Database\Seeders\HostnamesBreadSeeder::class);
    }
}
