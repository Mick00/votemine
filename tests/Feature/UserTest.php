<?php

namespace Tests\Feature;

use App\Server;
use App\User;
use App\VoteSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\SeededTest;
use Tests\TestCase;

class UserTest extends SeededTest
{
    use RefreshDatabase;
    public function testServersRelation()
    {
        $serverCount = 10;
        $user = factory(User::class)->create();
        $user->servers()->saveMany(
            factory(Server::class,$serverCount)->make()
        );
        $this->assertEquals($serverCount, $user->servers->count());
    }
}
