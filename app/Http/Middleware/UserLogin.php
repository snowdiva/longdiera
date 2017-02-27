<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserLogin
{
    protected $loginUrl = 'http://m.shucong.com/login';

    public function handle($request, Closure $next)
    {
        // TODO::登录功能涉及业务逻辑设计,本次暂缓
//        $user = Session::get('user');
//        if (!$user) {
//            $back_url = $request->getUri();
//
//
//        }

        return $next($request);
    }
}