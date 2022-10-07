<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstByEmailOrCreate([
            'name' => "Administrator",
            'email' => "admin@localhost.com",
            'password' => Hash::make("testtest"),
        ])->assignRole(['name'=>'admin']);
    }
}
