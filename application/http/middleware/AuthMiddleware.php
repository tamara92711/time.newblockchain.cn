<?php

namespace app\http\middleware;

use think\facade\Session;

class AuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has('user_id')) {
            return redirect('/login_form');
        } 
        return $next($request);
    }
}
