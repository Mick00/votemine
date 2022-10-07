<?php

namespace Tests\Feature;

use App\Server;
use App\VoteSite;
use App\VoteValidation\org\serveursminecraft\PlayerVoteValidation as ServeursMinecraftValidation;
use App\VoteValidation\org\serveurs_minecraft\PlayerVoteValidation as Serveurs_minecraftValidation;
use App\VoteValidation\org\liste_serveurs_minecraft\PlayerVoteValidation as ListeServeursMinecraftValidation;
use App\VoteValidation\com\serveurs_minecraft\PlayerVoteValidation as ServeursMinecraftComValidation;
use App\VoteValidation\PlayerVoteValidator;
use App\VoteValidation\Test\FakeVoteValidation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use ReflectionMethod;
use Tests\SeededTest;
use Tests\TestCase;

class ValidatorTest extends SeededTest
{
    use RefreshDatabase;

    protected $mockedSite;
    protected function setUp() : void
    {
        parent::setUp();
        $this->mockedSite = $this->createStub(VoteSite::class);
        $this->mockedSite->method('getServerId')->willReturn("8127Sta");
    }

    public function testGetUrl(){
        $method = new ReflectionMethod(FakeVoteValidation::class, 'getApiUrl');
        $method->setAccessible(true);

        $fakeValidator = new FakeVoteValidation($this->mockedSite);
        $serverIdOnSite = $this->mockedSite->getServerId($this->server);
        $playername = "joueur";
        $url = $method->invoke($fakeValidator, $serverIdOnSite, $playername);

        $this->assertStringContainsString($fakeValidator->url, $url);
        $this->assertStringNotContainsString($fakeValidator->path, $url);
        $this->assertStringContainsString($serverIdOnSite, $url);
        $this->assertStringContainsString($playername, $url);
    }

    public function testFetch(){
        $method = new ReflectionMethod(FakeVoteValidation::class, 'parseResponse');
        $method->setAccessible(true);
        $fakeValidator = new FakeVoteValidation($this->mockedSite);

        $this->assertEquals(1,$method->invoke($fakeValidator, "1"));
        $this->assertEquals(false,$method->invoke($fakeValidator, ""));
        $this->assertIsArray($method->invoke($fakeValidator, json_encode(['hello'=>'world'])));
        $this->assertIsNotObject($method->invoke($fakeValidator, json_encode(['hello'=>'world'])));
        $this->assertEquals("{{popopolol}",$method->invoke($fakeValidator, "{{popopolol}"));
    }

    public function testServeursminecraftorgValidation(){
        $method = new ReflectionMethod(ServeursMinecraftValidation::class, 'hasVoted');
        $method->setAccessible(true);
        $validator = new ServeursMinecraftValidation($this->site);

        $this->assertFalse($method->invoke($validator,"true"));
        $this->assertTrue($method->invoke($validator,"10823"));
        $this->assertFalse($method->invoke($validator,"1"));
        $this->assertFalse($method->invoke($validator,"0"));
        $this->assertFalse($method->invoke($validator,""));
        $this->assertFalse($method->invoke($validator,false));
        $this->assertFalse($method->invoke($validator,true));
    }

    public function testServeurs_minecraftorgValidation(){
        $method = new ReflectionMethod(Serveurs_minecraftValidation::class, 'hasVoted');
        $method->setAccessible(true);
        $validator = new Serveurs_minecraftValidation($this->site);

        $this->assertFalse($method->invoke($validator,[]));
        $this->assertFalse($method->invoke($validator,['votes' => 0]));
        $this->assertFalse($method->invoke($validator,['votes' => "0"]));
        $this->assertTrue($method->invoke($validator,['votes' => 1]));
        $this->assertTrue($method->invoke($validator,['votes' => "1"]));
    }
    public function testListeServeursMinecraftOrgValidation(){
        $method = new ReflectionMethod(ListeServeursMinecraftValidation::class, 'hasVoted');
        $method->setAccessible(true);
        $validator = new ListeServeursMinecraftValidation($this->site);

        $this->assertFalse($method->invoke($validator,0));
        $this->assertFalse($method->invoke($validator,"0"));
        $this->assertFalse($method->invoke($validator,"dwadwa"));
        $this->assertFalse($method->invoke($validator,['test'=>'dwa']));
        $this->assertTrue($method->invoke($validator,1));
        $this->assertTrue($method->invoke($validator,"1"));
    }

    public function testServeursMinecraftComValidation(){
        $method = new ReflectionMethod(ServeursMinecraftComValidation::class, 'hasVoted');
        $method->setAccessible(true);
        $validator = new ServeursMinecraftComValidation($this->site);

        $this->assertFalse($method->invoke($validator,json_decode("{\"authorVote\":true,\"reqDate\":{\"date\":\"2020-04-16 21:10:41.000000\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"lastVote\":{\"date\":\"2019-09-02 07:36:50.000000\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"interY\":0,\"interM\":7,\"interD\":14,\"interH\":13,\"interI\":33,\"ip\":\"109.132.178.32\",\"server_id\":\"4561\"}", true)));
        $this->assertTrue($method->invoke($validator,json_decode("{\"authorVote\":false,\"reqDate\":{\"date\":\"2020-04-16 21:15:46.000000\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"lastVote\":{\"date\":\"2020-04-16 20:42:19.000000\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"interY\":0,\"interM\":0,\"interD\":0,\"interH\":0,\"interI\":33,\"ip\":\"184.160.191.186\",\"server_id\":\"4561\"}", true)));
        $this->assertFalse($method->invoke($validator,json_decode("{\"authorVote\":true,\"lastVote\":false,\"reqDate\":{\"date\":\"2020-04-16 21:16:40.000000\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"ip\":\"109.132.178.322\",\"server_id\":\"4561\"}", true)));
    }

    public function testListeServeursFrValidation(){
        $method = new ReflectionMethod(\App\VoteValidation\fr\liste_serveurs\PlayerVoteValidation::class, 'hasVoted');
        $method->setAccessible(true);
        $validator = new \App\VoteValidation\fr\liste_serveurs\PlayerVoteValidation($this->site);
        $this->assertFalse($method->invoke($validator,json_decode("{ \"success\": false }", true)));
        $this->assertTrue($method->invoke($validator,json_decode("{ \"success\": true, \"prochainVote\": 1587083820 }", true)));
    }

    public function testListeMinecraftServeursComValidation(){
        $method = new ReflectionMethod(\App\VoteValidation\com\liste_minecraft_serveurs\PlayerVoteValidation::class, 'hasVoted');
        $method->setAccessible(true);
        $validator = new \App\VoteValidation\com\liste_minecraft_serveurs\PlayerVoteValidation($this->site);
        $this->assertFalse($method->invoke($validator,[]));
        $this->assertFalse($method->invoke($validator,['result' => 200]));
        $this->assertFalse($method->invoke($validator,['result' => "200"]));
        $this->assertFalse($method->invoke($validator,['result' => 201]));
        $this->assertFalse($method->invoke($validator,['result' => "201"]));
        $this->assertTrue($method->invoke($validator,['result'=>202]));
        $this->assertTrue($method->invoke($validator,['result'=>"202"]));
    }

}
