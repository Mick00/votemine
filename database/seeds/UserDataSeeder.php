<?php

use Illuminate\Database\Seeder;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ServerSeeder::class,
            VotingSiteSeeder::class,
            PlayerSeeder::class
        ]);
    }
}
