<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveWWW
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (strpos($request->getHost(), 'www.') === 0) {
            $nonWwwHost = str_replace('www.', '', $request->getHost());
            $newUrl = $request->getScheme() . '://' . $nonWwwHost . $request->getRequestUri();
            return redirect()->to($newUrl, 301); // redirecionamento permanente
        }
        return $next($request);
    }
}
