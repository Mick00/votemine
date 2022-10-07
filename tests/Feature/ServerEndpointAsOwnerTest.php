<?php

namespace Tests\Feature;

use App\Server;
use App\Version;
use App\VoteSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServerEndpointAsOwnerTest extends ServerEndpointTest
{

    protected function setUp() : void{
        parent::setUp();
        $this->actingAs($this->server->ownedBy);
    }

    public function testServerEdit(){
        $this->callServerEdit();
        $this->response->assertStatus(200);
    }

    public function testServerEditPost(){
        $data = [
            'name' => 'testest',
            'ip'    => 'play.station47.net',
            'description' => 'Salut ca va',
            'version_id' => Version::first()->id,
            'port'  => '1000',
            ];
        $response = $this->json('Patch',$this->getServerUrl().'/edit', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('servers', array_merge($data,['id'=>$this->server->id]));
    }

    public function testServerEditPostFailValidation(){
        $data = [
            'name' => 'testest',
            'ip'    => 'play.station47.net',
            'description' => 'Salut ca va',
            'version' => "1.15",
            'port'  => '-1',
        ];
        $response = $this->json('Patch',$this->getServerUrl().'/edit', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('servers', array_merge($data,['id'=>$this->server->id]));
    }

    public function testAddSiteToServerRoute(){
        $data = $this->callAddVotingSiteToServer();
        $this->response->assertStatus(302);
        $this->assertDatabaseHas('server_vote_site', $this->getServerVotingSiteEntry($data));
    }

    public function testGenerateToken(){
        $this->callCreateServerToken();
        $this->server->fresh();
        $this->assertFalse($this->server->tokens->isEmpty());
    }

    public function testDeleteToken(){
        $this->callDeleteToken();
        $this->assertTrue($this->server->tokens->isEmpty());
    }

    public function testUpdateSite(){
        $ids = $this->callUpdateSite();
        $this->assertDatabaseHas('server_vote_site',[
            'vote_site_id' => $this->site->id,
            'server_id' => $this->server->id,
            'server_id_on_site' => $ids['new'],
        ]);
        $this->assertDatabaseMissing('server_vote_site',[
            'vote_site_id' => $this->site->id,
            'server_id' => $this->server->id,
            'server_id_on_site' => $ids['old'],
        ]);
    }
}
