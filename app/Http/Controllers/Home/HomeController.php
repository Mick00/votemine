<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Image;
use App\Server;
use App\Type;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home.index');
    }

    public function myProfile(){
        return view('home.profile');
    }

    public function addServerPage(){
        return view('home.add-server');
    }

    public function addServerPost(Request $request){
        $server = new Server($request->input());
        Auth::user()->servers()->save($server);
        $this->updateAssets($request, $server);
        $checkedTypes = Type::getCheckedTypesFromInput($request->input());
        $server->types()->sync($checkedTypes);
        return redirect()->route('server', ['server_name'=>$server->name])->withSuccess(__('Your server has been created'));
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
}
