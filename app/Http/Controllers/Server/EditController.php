<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Image;
use App\Server;
use App\Type;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EditController extends Controller
{

    public function index(){
        $server = Server::current();
        return view('servers.edit', ['server' => $server]);
    }

    public function update(Request  $request){
        $server = Server::current();
        $this->updateAssets($request, $server);
        $server->update($request->input());
        $checkedTypes = Type::getCheckedTypesFromInput($request->input());
        $server->types()->sync($checkedTypes);
        return $this->redirectToEdit($server,'Your server has been updated');

    }

    private function updateAssets(Request $request, Server $server){
        if ($request->hasFile('banner')){
            $banner = Image::newBanner($request->file('banner'));
            $server->banner = $banner;
        }
        if ($request->hasFile('logo')){
            $logo = Image::newLogo($request->file('logo'));
            $server->logo = $logo;
        }
        $server->save();
    }

    public function redirectToEdit(Server $server, $message){
        return redirect()->route('server.edit',['name'=>$server->name])
            ->withSuccess(__($message));
    }
}
