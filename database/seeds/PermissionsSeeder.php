<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'vote-sites.create']);
        Permission::firstOrCreate(['name' => 'vote-sites.read']);
        Permission::firstOrCreate(['name' => 'vote-sites.update']);
        Permission::firstOrCreate(['name' => 'vote-sites.delete']);
        Permission::firstOrCreate(['name' => 'vote-sites.*']);
        Permission::firstOrCreate(['name' => 'servers.create']);
        Permission::firstOrCreate(['name' => 'servers.read']);
        Permission::firstOrCreate(['name' => 'servers.update']);
        Permission::firstOrCreate(['name' => 'servers.delete']);
        Permission::firstOrCreate(['name' => 'servers.*']);
    }
}
