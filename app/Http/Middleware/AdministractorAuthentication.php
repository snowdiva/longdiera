<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class AdministractorAuthentication
{
    public function handle($request, Closure $next)
    {
        $user = $request->session()->get('user');

        // 用户未登录验证
        if (!$user) {
            return redirect()->route('adminLogin');
        }

        // 用户是否为管理员验证
//        if (!isset($user['admin_auth']) || empty($user['admin_auth'])) {
//            return redirect()->route('home');
//        }

        // 用户是否有操作权限验证
        if ($user['user_id'] != 1) {
//            if (!in_array(Request::path(), $user['admin_auth'])) {
//                return redirect()->route('dashboard');
//            }
        }

        return $next($request);
    }
}