<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TokenController extends Controller
{
    public function index(){
        return view('servers.token',['server'=>Server::current()]);
    }

    public function generateToken(Request $request){
        $server = Server::current();
        $data = $this->validateTokenData($request);
        $token = $server->createToken($data['key_name']);
        return $this->redirectToToken($server,'')
            ->with('modal',json_encode([
                'title' =>__('Copy your key now'),
                'body'=>__('Copy the key now. This is the only time you can see it.'),
                'type'=>'copy',
                'value'=>$token->plainTextToken]));
    }

    private function validateTokenData(Request $request){
        return $request->validate([
            'key_name' => 'required'
        ]);
    }

    public function deleteToken(Request $request, $name){
        $server = Server::current();
        $data = $this->validateTokenData($request);
        $server->tokens()->where('name','=',$data['key_name'])->delete();
        return $this->redirectToToken($server,'Token deleted');
    }

    public function redirectToToken(Server $server, $message){
        return redirect()->route('server.token')
            ->withSuccess(__($message));
    }
}
