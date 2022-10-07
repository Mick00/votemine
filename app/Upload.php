<?php

namespace App;

use App\Exceptions\UserNotLoggedInException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


/**
 * Class Upload
 * @package App
 * @property integer id
 * @property string path
 * @property string original_name
 * @property User user
 */
class Upload extends Model
{
    use Uploadable;
    protected $fillable = [
      'path', 'client_filename', 'mime',
    ];

    protected function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function url():string {
        return asset(Storage::url('uploads/'.$this->path));
    }

    public static function newFile(UploadedFile $file, User $user = null):Upload{
        $upload = new Upload([
            'client_filename' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
        ]);
        $upload->path = $upload->saveFile($file,$user);
        $upload->save();
        return $upload;
    }

}
