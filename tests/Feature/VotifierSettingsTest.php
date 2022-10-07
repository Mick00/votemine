<?php

namespace Tests\Feature;

use App\VotifierSettings;
use Tests\TestCase;

class VotifierSettingsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPortRule()
    {
        $settings = factory(VotifierSettings::class)->make();
        $this->assertTrue($settings->isValid());
        $settings->port = -1;
        $this->assertFalse($settings->isValid());
        $settings->port = 0;
        $this->assertTrue($settings->isValid());
        $settings->port = 65536;
        $this->assertFalse($settings->isValid());
        $settings->port = 65535;
        $this->assertTrue($settings->isValid());
    }

    public function testDefaultPort(){
        $settings = new VotifierSettings(['ip'=>'localhost','token'=>'asdsad']);
        $this->assertEquals(8192, $settings->port);
    }

    public function testRequiredIp(){
        $settings = new VotifierSettings(['token'=>'asdsad','port'=>90]);
        $this->assertFalse($settings->isValid());
        $settings->ip = 'localhost';
        $this->assertTrue($settings->isValid());
    }

    public function testRequiredToken(){
        $settings = new VotifierSettings(['port'=>90,'ip'=>'localhost']);
        $this->assertFalse($settings->isValid());
        $settings->token = "nadsndasd";
        $this->assertTrue($settings->isValid());
    }
}
