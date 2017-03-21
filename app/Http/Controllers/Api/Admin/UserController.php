<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\GroupRepo;
use App\Repositories\UserRepo;
use Illuminate\Foundation\Auth\User;
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

        return $this->returnJson([
            'user' => $userInfo,
            'access_token' => $accessToken
        ]);
    }

    public function getUserList (Request $request, UserRepo $userRepo, GroupRepo $groupRepo)
    {
        // 处理查询条件
        $where = [];
        $whereObj = $request->input();
        if (isset($whereObj['field']) && isset($whereObj['value']) && $whereObj['value'] != '') {
            $where[] = [$whereObj['field'], 'like', '%'.$whereObj['value'].'%'];
        }
        if (isset($whereObj['group_id']) && $whereObj['group_id'] != 0) $where[] = ['group_id', '=', $whereObj['group_id']];
        if (isset($whereObj['status'])) {
            if ($whereObj['status'] != -1) {
                $where[] = ['status', '=', $whereObj['status']];
            }
        } else {
            $where[] = ['status', '=', 1];
        }

        // 获取相关数据
        $userList = $userRepo->getData($where);
        $userList['group_list'] = $groupRepo->getList();

        return $this->returnJson($userList);
    }

    public function newUser (Request $request, UserRepo $userRepo)
    {
        // 初始默认值
        $setData = [
            'username' => $request->input('username', ''),
            'password' => $request->input('password', ''),
            'email' => $request->input('email', ''),
            'phone' => $request->input('phone', ''),
            'alias' => $request->input('alias', ''),
            'gender' => $request->input('gender', 0),
            'group_id' => $request->input('group_id', 0),
            'create_at' => time(),
            'status' => $request->input('status', 1)
        ];
        if ($setData['username'] === '') return $this->error('必须提交用户名');
        // 登录名非重检查
        if ($userRepo->checkUser($setData['username'])) return $this->error('登录名已存在,或该用户未彻底删除');
        if ($setData['password'] === '') $setData['password'] = md5($setData['username']);
        $setData['password'] = md5($setData['password']);
        if ($setData['alias'] === '') $setData['alias'] = $setData['username'];

        if ($id = $userRepo->insertData($setData)) {
            $setData['id'] = $id;
            return $this->returnJson($setData);
        } else {
            return $this->error('操作失败,请联系管理员大大');
        }
    }

    public function editUser (Request $request, UserRepo $userRepo)
    {
        // 参数验证
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少用户UID');
        $userInfo = $userRepo->checkUserById($id);
        if (!$userInfo) return $this->error('提交用户不存在');

        // 初始默认值
        $setData = [
            'password' => $request->input('password', ''),
            'email' => $request->input('email', ''),
            'phone' => $request->input('phone', ''),
            'alias' => $request->input('alias', ''),
            'gender' => $request->input('gender', 0),
            'group_id' => $request->input('group_id', 0),
            'status' => $request->input('status', 1)
        ];
        if ($setData['password'] === '') {
            unset($setData['password']);
        } else {
            $setData['password'] = md5($setData['password']);
        }
        if ($setData['alias'] === '') $setData['alias'] = $userInfo->username;

        if ($userRepo->updateData($setData, $id)) {
            $setData['id'] = $id;
            $setData['username'] = $userInfo->username;
            $setData['create_at'] = $userInfo->group_id;
            return $this->returnJson($setData);
        } else {
            return $this->error('操作失败,请联系管理员大大');
        }
    }

    public function deleteUser (Request $request, UserRepo $userRepo)
    {
        // 参数验证
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少用户UID');
        $userInfo = $userRepo->checkUserById($id);
        if (!$userInfo) return $this->error('提交用户不存在');

        if ($userInfo->status === 2) {
            if ($userRepo->deleteData($id, 1)) return $this->success('已经彻底删除');
        } else {
            if ($result = $userRepo->deleteData($id)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }
}