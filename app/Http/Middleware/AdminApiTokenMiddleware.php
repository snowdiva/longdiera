<?php

namespace App\Http\Middleware;

use Closure;

class AdminApiTokenMiddleware
{
    public function handle ($request, Closure $next)
    {
        // 登录或者获取用户的访问直接放行
//        if ($request->route()->uri() === 'admin_api/user') return $next($request);

        // 非登录状态验证
        $accessToken = $request->header('Authorization');
        if (!$accessToken) return response()->json(['error' => 401, 'message' => '用户未登录']);
        $userInfo = cache($accessToken, null);
        if ($userInfo === null) return response()->json(['error' => 401, 'message' => '登录已失效,请重新登录']);

        // 用户访问权限认证
        // TODO::等待丰富,暂时使用数据库表实时查询
        if ($userInfo->group_id !== config('admin.admin_group_id')) {
            $authRepo = new \App\Repositories\AuthRepo();
            if (!$authRepo->checkAuth($userInfo->group_id, $request->route()->uri())) return response()->json(['error' => 403, 'message' => '无权访问']);
        }

        // 刷新令牌时间
        cache([$accessToken => $userInfo], config('admin.access_token_time'));

        return $next($request);
    }
}