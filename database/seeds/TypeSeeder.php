<?php

use App\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['freebuild','Freebuild'],
            ['creative','Creative'],
            ['pvp','PvP'],
            ['mini-games','Mini-games'],
            ['skyblock','Skyblock'],
            ['prison','Prison'],
            ['roleplay','Roleplay']
        ];
        foreach ($types as $type){
            $this->addType($type[0], $type[1]);
        }
    }

    public function addType($slug, $name){
        Type::firstOrCreate(['slug'=> $slug, 'name' =>$name]);
    }
}
