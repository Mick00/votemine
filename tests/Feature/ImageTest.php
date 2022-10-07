<?php

namespace Tests\Feature;

use App\Exceptions\UserNotLoggedInException;
use App\Exceptions\WrongFileType;
use App\Image;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\SeededTest;

class ImageTest extends SeededTest
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNewfile()
    {
        $this->actingAs(factory(User::class)->create());
        $image = $this->uploadFakeImage();
        $files = Storage::disk('upload')->allFiles();
        $this->assertContains($image->getOriginalFilePath(), $files);
        $this->assertDatabaseHas('images', $image->getAttributes());
    }

    protected function uploadFakeImage(User $user = null):Image{
        Storage::fake('upload');
        return Image::newBanner(UploadedFile::fake()->image('test.jpg'), $user);
    }

    public function testUserRelation(){
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $image = $this->uploadFakeImage();
        $this->assertEquals($user->id, $image->user->id);
    }

    public function testNewFileNotAuthentified(){
        $this->expectException(UserNotLoggedInException::class);
        $file = $this->uploadFakeImage();
    }

    public function testUploadNotAnImage(){
        $this->expectException(WrongFileType::class);
        Storage::fake('upload');
        return Image::newBanner(UploadedFile::fake()->create('notAnImage'));
    }

    public function testNewFileIsNotSavedWhenNotLogged(){
        try {
            $this->uploadFakeImage();
        } catch (UserNotLoggedInException $e){}
        $files = Storage::disk('upload')->allFiles();
        $this->assertEmpty($files);
    }

    public function testUploadAsUser(){
        $file = $this->uploadFakeImage(factory(User::class)->create());
        $files = Storage::disk('upload')->allFiles();
        $this->assertContains($file->getOriginalFilePath(), $files);
        $this->assertDatabaseHas('images', $file->getAttributes());
        $this->assertNotNull($file->user);
    }

    public function testUrl(){
        $this->assertIsString($this->server->banner->url());
    }

    public function testDelete(){
        $image = Image::newBanner(UploadedFile::fake()->image('test.jpg'), factory(User::class)->create());
        $image->createOptimizedFormats();
        $image->delete();
        Storage::disk('upload')->assertMissing($image->getOriginalFilePath());
        $formatSpecs = $image->getFormats();
        foreach($formatSpecs['dimensions'] as $dimensionSlug => $dimension){
            foreach ($formatSpecs['img_formats'] as $imgFormat){
                Storage::disk('upload')
                    ->assertMissing($image->getPath($imgFormat, $dimensionSlug));
            }
        }
        $this->assertDatabaseMissing('images', $image->getAttributes());
    }

    public function testGetPicture(){
        $file = Image::newLogo(UploadedFile::fake()->image('test.jpg'), factory(User::class)->create());
        $formats = $file->getPictures();
        $this->assertEmpty($formats);
        $file->createOptimizedFormats();
        $formats = $file->getPictures();
        $this->assertEquals(
            count(Image::formats['logo']['dimensions'])*count(Image::formats['logo']['img_formats']),
            count($formats));
        $this->assertTrue(isset($formats[0]['src']) && isset($formats[0]['minWidth']));
    }

    public function testCreateOptimizedFormats(){
        $img = Image::newLogo(UploadedFile::fake()->image('test.jpg'), factory(User::class)->create());
        $img->createOptimizedFormats();
        $this->assertTrue($img->optimized);
        $formatSpecs = $img->getFormats();
        foreach($formatSpecs['dimensions'] as $dimensionSlug => $dimension){
            foreach ($formatSpecs['img_formats'] as $imgFormat){
                Storage::disk('upload')
                    ->assertExists($img->getPath($imgFormat, $dimensionSlug));
            }
        }
    }

    public function testCreateOptimizedFormatsFailing(){
        $img = Image::newLogo(UploadedFile::fake()->image('test.jpg'), factory(User::class)->create());
        Storage::disk('upload')
            ->delete($img->getOriginalFilePath());
        $img->createOptimizedFormats();
        $this->assertFalse($img->optimized);
    }
}
