<?php

namespace Tests\Feature;

use App\Server;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeEndpointAsUserTest extends HomeEndPointTest
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(factory(User::class)->create());
    }

    public function testHome()
    {
        $this->callHome();
        $this->response->assertStatus(200);
    }

    public function testProfile(){
        $this->callProfile();
        $this->response->assertStatus(200);
    }

    public function testAddAServer(){
        $this->callAddAServer();
        $this->response->assertStatus(200);
    }

    public function testPostAddAServer(){
        $data = $this->postAddAServer();
        $this->response->assertStatus(302);
        unset($data['banner']);
        unset($data['logo']);
        $this->assertDatabaseHas('servers', $data);
        $server = Server::whereName($data['name'])->first();
        $this->assertNotNull($server->banner);
        $this->assertNotNull($server->logo);
    }
}
