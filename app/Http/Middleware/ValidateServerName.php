<?php

namespace App\Http\Middleware;

use App\Server;
use Closure;
use Illuminate\Support\Facades\URL;

class ValidateServerName
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
        $server = Server::getByNameOrFail($request->route()->parameter('server_name'));
        Server::setCurrent($server);
        URL::defaults(['server_name' => $server->name]);
        return $next($request);
    }
}
