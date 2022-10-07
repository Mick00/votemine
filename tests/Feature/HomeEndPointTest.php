<?php

namespace Tests\Feature;

use App\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\SeededTest;
use Tests\TestCase;

class HomeEndPointTest extends SeededTest
{
    use RefreshDatabase;
    protected function callHome(){
        $this->response = $this->get('home');
    }

    public function testHome()
    {
       $this->callHome();
       $this->response->assertStatus(302);
    }

    protected function callProfile(){
        $this->response = $this->get('home/myprofile');
    }

    public function testProfile(){
        $this->callProfile();
        $this->response->assertStatus(302);
    }

    protected function callAddAServer(){
        $this->response = $this->get('home/addaserver');
    }

    public function testAddAServer(){
        $this->callAddAServer();
        $this->response->assertStatus(302);
    }

    protected function postAddAServer(){
        $data = [
            'name' => 'Whats up',
            'ip'    => 'play.station47.net',
            'port'  => '99',
            'description' => 'A great server to come play',
            'version_id' => Version::first()->id,
            'banner' => UploadedFile::fake()->image('salut.jpg'),
            'logo'  => UploadedFile::fake()->image('salut.jpg'),
        ];
        $this->response = $this->json('POST', 'home/addaserver', $data);
        return $data;
    }

    public function testPostAddAServer(){
        $data = $this->postAddAServer();
        $this->response->assertStatus(401);
        $this->assertDatabaseMissing('servers',$data);
    }

}
