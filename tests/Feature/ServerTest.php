<?php

namespace Tests\Feature;

use App\Image;
use App\Server;
use App\Type;
use App\Upload;
use App\User;
use App\Version;
use App\VoteSite;
use App\VotifierSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;
use Tests\SeededTest;
use Tests\TestCase;
use Watson\Validating\ValidationException;

class ServerTest extends SeededTest
{
    use RefreshDatabase;

    public function testServerToSite()
    {
        $this->server->voteSites()->attach($this->site);
        $this->server->save();

        $fetchedServer = Server::where('name', $this->server->name)->first();
        $this->assertNotNull($fetchedServer);
        $this->assertEquals(1,$fetchedServer->voteSites->count());
    }

    public function testServerToOwner(){
        $this->server->ownedBy()->associate(factory(User::class)->create());
        $this->server->save();
        $this->assertNotNull($this->server->ownedBy);
    }

    public function testServerFactory(){
        $fetchedServer = Server::where('name', $this->server->name)->first();
        $this->assertNotNull($fetchedServer->ownedBy);
    }

    public function testIsOwnedBy(){
        $this->assertTrue($this->server->isOwnedBy($this->server->ownedBy));
        $this->assertFalse($this->server->isOwnedBy(factory(User::class)->make()));
        $this->assertFalse($this->server->isOwnedBy(factory(User::class)->create()));
    }

    public function testBannerAndLogo(){
        $this->assertNotNull($this->server->banner);
        $this->assertNotNull($this->server->logo);
    }

    public function testSetBannerAttribute(){
        $this->actingAs($this->server->ownedBy);
        $newFile = Image::newBanner(UploadedFile::fake()->image('test.jpg'));
        $this->server->banner = $newFile;
        $this->assertEquals($newFile, $this->server->banner);
    }

    public function testSetBannerAttributeWhenNull(){
        $this->actingAs($this->server->ownedBy);
        $server = factory(Server::class)->create(['banner_id'=>null]);
        $newFile = Image::newBanner(UploadedFile::fake()->image('test.jpg'));
        $server->banner = $newFile;
        $this->assertEquals($newFile, $server->banner);
    }

    public function testSetLogoAttribute(){
        $this->actingAs($this->server->ownedBy);
        $newFile = Image::newLogo(UploadedFile::fake()->image('test.jpg'));
        $this->server->logo = $newFile;
        $this->assertEquals($newFile, $this->server->logo);
    }

    public function testSetLogoAttributeWhenNull(){
        $this->actingAs($this->server->ownedBy);
        $server = factory(Server::class)->create(['logo_id'=>null]);
        $newFile = Image::newLogo(UploadedFile::fake()->image('test.jpg'));
        $server->logo = $newFile;
        $this->assertEquals($newFile, $server->logo);
    }

    public function testUniqueNameRule(){
        $this->expectException(ValidationException::class);
        $name = "server_name";
        $first = factory(Server::class)->create(['name'=>$name]);
        $this->assertTrue($first->isValid());
        $second = factory(Server::class)->create(['name'=>$name]);
        $this->assertFalse($second->isValid());
        $second->name = "other_name";
        $this->assertTrue($second->isValid());
        $second->name = $name;
        $this->assertFalse($second->isValid());
    }

    public function testPortRule(){
        $server = factory(Server::class)->create(['port'=>0]);
        $this->assertTrue($server->isValid());
        $server->port = -1;
        $this->assertFalse($server->isValid());
        $server->port = 65535;
        $this->assertTrue($server->isValid());
        $server->port = 65536;
        $this->assertFalse($server->isValid());
        $server->port = "a string";
        $this->assertFalse($server->isValid());
        $server->port = 0.5;
        $this->assertFalse($server->isValid());
        $server->port = "0.5";
        $this->assertFalse($server->isValid());
        $server->port = "65535test";
        $this->assertFalse($server->isValid());
        $server->port = null;
        $this->assertTrue($server->isValid());
    }

    public function testNullPortSave(){
        $server = factory(Server::class)->make(['port'=>null]);
        $server->save();
        $this->assertEquals(25565, $server->port);
    }

    public function testVersion(){
        $server = factory(Server::class)->make(['version_id'=>Version::first()->id]);
        $server->save();
        $this->assertNotNull($server->version);
    }

    public function testInvalidVersion(){
        $this->expectException(ValidationException::class);
        $server = factory(Server::class)->create(['version_id'=>'109090']);
    }

    public function testTypeRelation(){
        $this->server->types()->attach(Type::first());
        $this->assertEquals(1,$this->server->types->count());
    }

    public function testIsVotifierEnabled(){
        $server = factory(Server::class)->create();
        $this->assertFalse($server->isVotifierEnabled());
        $server = factory(Server::class)->create(['votifier_settings_id'=>factory(VotifierSettings::class)->create()]);
        $this->assertTrue($server->isVotifierEnabled());
    }

    public function testVotifierSettingsRelation(){
        $this->server->votifierSettings()->associate(factory(VotifierSettings::class)->create());
        $this->assertTrue($this->server->isVotifierEnabled());
    }

    public function testUpdateUnreachableServer(){
        $server = new Server([
            'ip'=> 'localhost',
            'port' => 25565,
            'playerCount' => -1,
        ]);
        $server->updated_at = Date::now();
        $server->updateIfNeeded();
        $this->assertEquals(-1, $server->playerCount);
        $server->updated_at = Date::now()->subMinutes(12);
        $server->updateIfNeeded();
        $this->assertNotEquals(-1, $server->playerCount);
    }

    public function testCurrent(){
        $name = 'test';
        $server = new Server(['name'=> $name]);
        Server::setCurrent($server);
        $this->assertNotNull(Server::current());
    }

    public function testGetByNameOrFailFailing(){
        $this->expectException(\Exception::class);
        Server::getByNameOrFail('non-existing server hello');
    }

    public function testGetByNameOrFailSuccess(){
        $server = factory(Server::class)->create();
        $this->assertEquals($server->id, Server::getByNameOrFail($server->name)->id);
    }

}
