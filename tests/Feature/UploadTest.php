<?php

namespace Tests\Feature;

use App\Exceptions\UserNotLoggedInException;
use App\Upload;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\SeededTest;
use Tests\TestCase;

class UploadTest extends SeededTest
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function uploadFakeFile(User $user = null){
        Storage::fake('upload');
        return Upload::newFile(UploadedFile::fake()->image('test.jpg'), $user);
    }

    public function testNewfile()
    {
        $this->actingAs(factory(User::class)->create());
        $file = $this->uploadFakeFile();
        $files = Storage::disk('upload')->allFiles();
        $this->assertContains($file->path, $files);
        $this->assertDatabaseHas('uploads', $file->getAttributes());
    }

    public function testUserRelation(){
        $this->actingAs(factory(User::class)->create());
        $file = $this->uploadFakeFile();
        $this->assertNotNull($file->user);
    }

    public function testNewFileNotAuthentified(){
        $this->expectException(UserNotLoggedInException::class);
        $file = $this->uploadFakeFile();
    }

    public function testNewFileIsNotSavedWhenNotLogged(){
        try {
            $this->uploadFakeFile();
        } catch (UserNotLoggedInException $e){}
        $files = Storage::disk('upload')->allFiles();
        $this->assertEmpty($files);
    }

    public function testUploadAsUser(){
        $file = $this->uploadFakeFile(factory(User::class)->create());
        $files = Storage::disk('upload')->allFiles();
        $this->assertContains($file->path, $files);
        $this->assertDatabaseHas('uploads', $file->getAttributes());
        $this->assertNotNull($file->user);
    }

    public function testUrl(){
        $upload = $this->uploadFakeFile(factory(User::class)->create());
        $this->assertIsString($upload->url());
    }

    public function testDelete(){
        $file = Upload::newFile(UploadedFile::fake()->image('test.jpg'), factory(User::class)->create());
        $file->delete();
        Storage::disk('upload')->assertMissing($file->path);
        $this->assertDatabaseMissing('uploads', $file->getAttributes());
    }
}
