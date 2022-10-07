<?php

namespace Tests\Feature;

use App\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\SeededTest;
use Tests\TestCase;

class VersionTest extends SeededTest
{

    public function testOrderByReleaseDateScope(){
        $versions = Version::all();
        $orderedCorrectly = true;
        for($i = 0; $i < $versions->count()-1; $i++){
            if($versions[$i]->release_date->timestamp < $versions[$i+1]->release_date->timestamp){
                $orderedCorrectly = false;
            }
        }
        $this->assertTrue($orderedCorrectly);
    }

    public function testToSelectOptions()
    {
        $options = Version::toSelectOptions();
        $this->assertIsArray($options);
        $versions = Version::all();
        $valuesAreOk = true;
        foreach ($options as $option){
            if(Version::whereNumber($option['label'])->first()->id != $option['value']){
                $valuesAreOk = false;
            }
        }
        $this->assertTrue($valuesAreOk);
    }
}
