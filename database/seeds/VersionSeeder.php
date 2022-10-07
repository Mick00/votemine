<?php

use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versions = [
            ['1.7', new DateTime('25-10-2013')],
            ['1.8', new DateTime('02-09-2014')],
            ['1.9', new DateTime('29-02-2016')],
            ['1.10', new DateTime('08-06-2016')],
            ['1.11', new DateTime('14-11-2016')],
            ['1.12', new DateTime('07-06-2017')],
            ['1.13', new DateTime('18-07-2018')],
            ['1.14', new DateTime('23-04-2019')],
            ['1.15', new DateTime('10-12-2019')],
            ['1.16', new DateTime('23-06-2020')],
            ['1.17', new DateTime('08-06-2021')],
            ['1.18', new DateTime('30-11-2021')],
            ['1.19', new DateTime('07-06-2021')]
        ];
        foreach ($versions as $version){
            $this->addType($version[0], $version[1]);
        }
    }

    public function addType($number, $releaseDate){
        \App\Version::firstOrCreate(['number'=> $number, 'release_date' =>$releaseDate]);
    }
}
