<?php

namespace Tests\Feature;

use App\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\SeededTest;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    public function testMaxLength(){
        $player = factory(Player::class)->make(['name'=>'123456789012345']);
        $this->assertTrue($player->isValid());
        $player = factory(Player::class)->make(['name'=>'1234567890123456']);
        $this->assertTrue($player->isValid());
        $player = factory(Player::class)->make(['name'=>'12345678901234567']);
        $this->assertFalse($player->isValid());
    }

    public function testMinLength(){
        $player = factory(Player::class)->make(['name'=>'1234']);
        $this->assertTrue($player->isValid());
        $player = factory(Player::class)->make(['name'=>'123']);
        $this->assertTrue($player->isValid());
        $player = factory(Player::class)->make(['name'=>'12']);
        $this->assertFalse($player->isValid());
    }
}
