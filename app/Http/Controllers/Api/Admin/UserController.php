<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\GroupRepo;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;

class UserController extends CommonController
{
    public function getInfo (Request $request, UserRepo $userRepo)
    {
        // 首先从缓存中检索信息
        if ($userInfo = cache('user')) return $userInfo;

        // 验证登录信息
        $username = $request->get('username', '');
        $password = $request->get('password');

        $userInfo = $userRepo->checkUser($username, $password);
        if (!$userInfo) return $this->error('用户名或密码错误');

        // 设置缓存方式保存登录状态
        // TODO::建议之后改成Redis状态管理
        $accessToken = md5($username . time() . mt_rand(100, 999));
        cache([$accessToken => $userInfo], config('admin.access_token_time'));

        return $this->toJson([
            'user' => $userInfo,
            'access_token' => $accessToken
        ]);
    }

    public function getUserList (Request $request, UserRepo $userRepo, GroupRepo $groupRepo) {
        // 处理查询条件
        $where = [];
        $whereObj = $request->input();
        if (isset($whereObj['field']) && isset($whereObj['value']) && $whereObj['value'] != '') {
            $where[] = [$whereObj['field'], 'like', '%'.$whereObj['value'].'%'];
        }
        if (isset($whereObj['group_id']) && $whereObj['group_id'] != 0) $where[] = ['group_id', '=', $whereObj['group_id']];
        if (isset($whereObj['status']) && $whereObj['status'] >= 0) $where[] = ['status', '=', $whereObj['status']];

        // 获取相关数据
        $userList = $userRepo->getData($where);
        $userList['group_list'] = $groupRepo->getList();

        return $this->toJson($userList);
    }
}