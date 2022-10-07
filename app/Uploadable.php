<?php


namespace App;


use App\Exceptions\UserNotLoggedInException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait Uploadable
{

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function delete()
    {
        Storage::disk('upload')->delete($this->path);
        return parent::delete();
    }

    protected function saveFile(UploadedFile $file, User $user = null):string{
        if ($user == null) {
            if (Auth::check()){
                $this->user()->associate(Auth::user());
            } else {
                throw new UserNotLoggedInException();
            }
        } else {
            $this->user()->associate($user);
        }
        return $file->store($this->saveToFolder(), 'upload');
    }

    protected function saveToFolder():string{
        return date("d-m-Y");
    }
}
