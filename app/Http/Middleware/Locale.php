<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class Locale
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

        $validLocales = ['en','fr'];
        $locale = $request->route()->locale;
        if ($locale == null && in_array($request->getLocale(), $validLocales)){
            $this->setLocale($request->getLocale());
            return $next($request);
        }
        if (in_array($locale,$validLocales)){
            $this->setLocale($locale);
            return $next($request);
        }
        $params = $request->route()->parameters();
        $params['locale'] = App::getLocale();
        return redirect()->route($request->route()->action['as'],$params);
    }

    private function setLocale($locale){
        URL::defaults(['locale' => $locale]);
        App::setLocale($locale);
    }
}
