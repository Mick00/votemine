<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Server;
use App\VoteSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VotingSiteController extends Controller
{
    public function index(){
        return view('servers.voting-site',['server'=>Server::current()]);
    }

    public function addSite(Request $request){
        $server = Server::current();
        $data = $this->validateSiteData($request);
        VoteSite::find($data['site'])->addRegisteredServer($server,$data['id']);
        return $this->redirectToVotingSiteEdition($server,'The voting site has been added successfully');

    }

    public function redirectToVotingSiteEdition(Server $server, $message){
        return redirect()->route('server.site')
            ->withSuccess(__($message));
    }

    private function validateSiteData(Request $request){
        return $request->validate([
            'site'  => 'required',
            'id'    => 'required',
        ]);
    }

    public function updateSite(Request $request){
        $server = Server::current();
        $data = $this->validateSiteData($request);
        VoteSite::find($data['site'])->defineServerId($server,$data['id']);
        return $this->redirectToVotingSiteEdition($server,'Site id updated');
    }
}
