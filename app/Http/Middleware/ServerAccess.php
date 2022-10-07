<?php

namespace App\Http\Middleware;

use App\Server;
use Closure;
use Illuminate\Support\Facades\Gate;

class ServerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $server = Server::current();
        if (Gate::allows('modify-server', $server)){
            return $next($request);
        }
        return redirect()->route('server', ['name'=>$server->name]);
    }
}
