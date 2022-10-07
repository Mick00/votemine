<?php

namespace Tests\Feature;

use App\Server;
use App\User;
use App\VoteSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\SeededTest;
use Tests\TestCase;

class ServerEndpointTest extends SeededTest
{
    use RefreshDatabase;

    protected $server;
    protected $site;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(factory(User::class)->create());
    }

    protected function callServerPage(){
        $this->response = $this->get($this->getServerUrl());
    }

    public function testServerRoute(){
        $this->callServerPage();
        $this->response->assertStatus(200);
    }

   protected function callServerEdit(){
       $this->response = $this->get($this->getServerUrl().'/edit');
    }

    public function testServerEdit(){
        $this->callServerEdit();
        $this->response->assertStatus(302);
    }

    public function testServerRoute404()
    {
        $response = $this->get('server');
        $response->assertStatus(404);
        $response = $this->get('server/unkown-very_long_serverr-name,with(error');
        $response->assertStatus(404);
    }

    public function testAddSiteToServerRoute(){
        $data = $this->callAddVotingSiteToServer();
        $this->response->assertStatus(302);
        $this->assertDatabaseMissing('server_vote_site',$this->getServerVotingSiteEntry($data));
    }

    protected function callAddVotingSiteToServer(){
        $idOnSite = "10";
        $data = [
            'site'  => $this->site->id,
            'id'    => $idOnSite,
        ];
        $this->response = $this->json('POST',$this->getServerUrl().'/site',$data);
        return $data;
    }

    protected function getServerVotingSiteEntry($data){
        return [
            'vote_site_id' => $data['site'],
            'server_id'     => $this->server->id,
            'server_id_on_site' => $data['id'],
        ];
    }

    protected function getServerUrl(){
        return '/fr/server/'.$this->server->name;
    }

    protected function getEncodedUrl(){
        return '/fr/server/'.rawurlencode($this->server->name);
    }

    public function callValidate(){
        $data = [
            'playername' => 'Allo',
        ];
        $this->response = $this->json('POST',$this->getServerUrl().'/validate', $data);
        return $data;
    }

    public function testValidateVote(){
        $this->callValidate();
        $this->response->assertStatus(302);
    }

    public function testGenerateToken(){
        $this->callCreateServerToken();
        $this->server->fresh();
        $this->assertTrue($this->server->tokens->isEmpty());
    }

    protected function callCreateServerToken(){
        $this->response = $this->json('POST',$this->getServerUrl().'/token',['key_name'=>'test']);
    }

    public function testDeleteToken(){
        $this->callDeleteToken();
        $this->assertFalse($this->server->tokens->isEmpty());
    }

    public function callDeleteToken(){
        $tokenName = 'api';
        $this->server->createToken($tokenName);
        $this->response = $this->json('DELETE',$this->getServerUrl().'/token', ['key_name'=>$tokenName]);
    }

    public function callUpdateSite(){
        $oldId = 11;
        $newId = 222;
        $this->site->addRegisteredServer($this->server,$oldId);
        $this->response = $this->json('PATCH',$this->getServerUrl().'/site', [
            'site'=>$this->site->id,
            'id'=>$newId
        ]);
        return [
            'old' => $oldId,
            'new' => $newId,
        ];
    }

    public function testUpdateSite(){
        $ids = $this->callUpdateSite();
        $this->assertDatabaseHas('server_vote_site',[
            'vote_site_id' => $this->site->id,
            'server_id' => $this->server->id,
            'server_id_on_site' => $ids['old'],
        ]);
        $this->assertDatabaseMissing('server_vote_site',[
            'vote_site_id' => $this->site->id,
            'server_id' => $this->server->id,
            'server_id_on_site' => $ids['new'],
        ]);
    }

}
