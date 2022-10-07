<?php


namespace Tests;


use App\Server;
use App\VoteSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class SeededTest extends TestCase
{
    use RefreshDatabase;
    /** @var Server $server */
    protected $server;
    /** @var VoteSite $site */
    protected $site;
    /** @var \Illuminate\Testing\TestResponse $server */
    protected $response;

    protected function setUp() : void{
        parent::setUp();
        Storage::fake('upload');
        Bus::fake();
        $this->seed();
        $this->site = factory(VoteSite::class)->create();
        $this->server = factory(Server::class)->create();
        $this->response = null;

    }
}
