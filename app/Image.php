<?php

namespace App;

use App\Exceptions\WrongFileType;
use App\Jobs\OptimizeImage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;
use ImagickPixel;
use RecursiveDirectoryIterator;
use SplFileInfo;

/**
 * Class Image
 * @package App
 * @property integer id
 * @property string folder
 * @property string original_name
 * @property string filename
 * @property string mime
 * @property string extension
 * @property string format
 * @property boolean optimized
 * @property User user
 */
class Image extends Model
{

    use Uploadable;

    const formats = [
        'logo' => [
            'dimensions'=>[
                'md'=>[200,200,600],
                'sm'=>[100,100,0],
            ],
            'img_formats'=>['webp','png']
        ],
        'banner' => [
            'dimensions'=>[
                'xl'=>[1140,300,1100],
                'lg'=>[960,300,800],
                'md'=>[720,300,600],
                'sm'=>[540,200,0]],
            'img_formats'=>['webp','jpg'],
        ],
    ];

    protected $fillable = [
        'folder', 'client_filename', 'mime', 'filename', 'extension',
        'format', 'optimized'
    ];

    protected $attributes = [
        'optimized' => false,
    ];

    public function getPictures(){
        if (!$this->optimized) return [];
        $quality = 85;
        $formatSpecs = self::formats[$this->format];
        $srcs = [];
        foreach($formatSpecs['dimensions'] as $dimensionSlug => $dimension){
            foreach ($formatSpecs['img_formats'] as $imgFormat){
                $srcs[] = [
                    'format' => $imgFormat,
                    'src' => asset(Storage::url('uploads/'.$this->getPath($imgFormat, $dimensionSlug, $quality))),
                    'minWidth' => $dimension[2],
                ];
            }
        }
        return $srcs;
    }

    public function createOptimizedFormats(){
        $quality = 85;
        $formatSpecs = $this->getFormats();
        $errors = false;
        foreach($formatSpecs['dimensions'] as $dimensionSlug => $dimension){
            foreach ($formatSpecs['img_formats'] as $imgFormat){
                try {
                    $imgBlob = Storage::disk('upload')
                        ->get($this->getOriginalFilePath());
                    $img = self::optimize($imgBlob, $dimension[0], $dimension[1], $imgFormat, $quality);
                    Storage::disk('upload')
                        ->put($this->getPath($imgFormat, $dimensionSlug, $quality), $img->getImageBlob());
                } catch (\Exception $e) {
                    echo $e->getMessage().", Format:".$imgFormat;
                    $errors = true;
                }
            }
        }
        $this->optimized = $errors? false : true;
        $this->save();
    }

    public function getFormats(){
        return self::formats[$this->format];
    }

    public function getPath($img_format, $size, $quality = 85){
        return $this->folder.$this->getFilename($img_format, $size, $quality);
    }

    private function getFilename($img_format, $size, $quality = 85){
        return $this->filename."-".$this->format.".${size}-q${quality}.${img_format}";
    }

    public function url():string {
        return asset(Storage::url('uploads/'.$this->getOriginalFilePath()));
    }

    public function getOriginalFilePath(){
        return $this->folder.$this->filename.".".$this->extension;
    }

    public function delete()
    {
        Storage::disk('upload')->delete($this->getOriginalFilePath());
        if ($this->optimized){
            $formatSpecs = $this->getFormats();
            foreach($formatSpecs['dimensions'] as $dimensionSlug => $dimension){
                foreach ($formatSpecs['img_formats'] as $imgFormat){
                    Storage::disk('upload')
                        ->delete($this->getPath($imgFormat, $dimensionSlug));
                }
            }
        }
        return parent::delete();
    }

    public static function newBanner(UploadedFile $file, User $user = null):Image{
        return self::newImage($file,'banner', $user);
    }

    public static function newLogo(UploadedFile $file, User $user = null):Image{
        return self::newImage($file,'logo', $user);
    }

    public static function newImage(UploadedFile $file, $format, User $user = null):Image{
        self::assertMimeTypeIsValid($file->getClientMimeType());
        $image = new Image([
            'client_filename' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'format' => $format,
        ]);
        $path = $image->saveFile($file,$user);
        $filename = Str::replaceFirst($image->folder,'',$path);
        $image->filename = Str::beforeLast($filename,'.');
        $image->extension = Str::afterLast($filename, '.');
        $image->save();
        OptimizeImage::dispatch($image);
        return $image;
    }

    protected function saveToFolder():string
    {
        $this->folder = 'images/'.date("d-m-Y");
        return $this->folder;
    }

    public static function assertMimeTypeIsValid($mime){
        $acceptedMimes =  ['image/jpeg','image/png'];
        if(!in_array($mime, $acceptedMimes)){
            throw new WrongFileType($acceptedMimes);
        }
    }

    public static function optimizeVotingSiteLogos(){
        $width = 300;
        $heigth = 100;
        $quality = 85;
        $formats = ['png','webp'];
        $logosFolder = "app/public/logos/";
        foreach (new RecursiveDirectoryIterator(storage_path($logosFolder)) as $file){
            if ($file->isDir()) continue;
            if (self::isAlreadyOptimized($file->getFilename())) continue;
            foreach ($formats as $format){
                $imgBlob = file_get_contents($file->getRealPath());
                $img = self::optimize($imgBlob, $width, $heigth, $format, $quality);
                $img->writeImage(self::makePath($file,$width,$quality, $format));
            }
        }
    }

    private static function isAlreadyOptimized($filename){
        return preg_match('/\.w[0-9]+-q[0-9]+\./',$filename);
    }

    private static function optimize($blob, $width, $heigth, $format = "webp", $quality = 85):Imagick{
        $img = new Imagick();
        $img->readImageBlob($blob);
        $img->setImageCompressionQuality($quality);
        $img->resizeImage($width, $heigth, imagick::FILTER_UNDEFINED, 0.9, true);
        $img->setImageFormat($format);
        $img->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
        $img->setBackgroundColor(new ImagickPixel('transparent'));
        return $img;
    }

    private static function makePath(SplFileInfo $fileInfo, $width, $quality, $format = 'webp'){
        $newFileName = Str::replaceLast($fileInfo->getExtension(),'',$fileInfo->getFilename());
        $newFileName .= "w${width}-q${quality}.${format}";
        return $fileInfo->getPath().'/'.$newFileName;
    }

}
