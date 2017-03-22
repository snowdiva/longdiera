<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\AuthRepo;
use App\Repositories\GroupRepo;
use App\Repositories\UserRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends CommonController
{
    public function getInfo (Request $request, GroupRepo $groupRepo)
    {
        // 首先从缓存中检索信息
//        if ($userInfo = cache('user')) return $userInfo;
//
//        // 验证登录信息
//        $username = $request->get('username', '');
//        $password = $request->get('password');
//
//        $userInfo = $userRepo->checkUser($username, $password);
//        if (!$userInfo) return $this->error('用户名或密码错误');
//
//        // 设置缓存方式保存登录状态
//        // TODO::建议之后改成Redis状态管理
//        $accessToken = md5($username . time() . mt_rand(100, 999));
//        cache([$accessToken => $userInfo], config('admin.access_token_time'));
//
//        return $this->returnJson([
//            'user' => $userInfo,
//            'access_token' => $accessToken
//        ]);
    }

    public function getGroupList (Request $request, GroupRepo $groupRepo, AuthRepo $authRepo)
    {
        // 处理查询条件
        $where = [];
        $whereObj = $request->input();
        if (isset($whereObj['status'])) {
            if ($whereObj['status'] != -1) {
                $where[] = ['status', '=', $whereObj['status']];
            }
        } else {
            $where[] = ['status', '=', 1];
        }

        // 获取相关数据
        $list = $groupRepo->getAll($where);
        // 针对前段Transfer插件修改接口返回数据
        $authListArray = $authRepo->getAuthList();
        foreach ($authListArray as $key => $value) {
            $list['auth_list'][$key]['key'] = $value->id;
            $list['auth_list'][$key]['id'] = $value->id;
            $list['auth_list'][$key]['name'] = $value->name;
            $list['auth_list'][$key]['label'] = $value->name;
            $list['auth_list'][$key]['pid'] = $value->pid;
            $list['auth_list'][$key]['description'] = $value->explain;
            $list['auth_list'][$key]['pid'] = $value->pid;
            $list['auth_list'][$key]['disabled'] = false;
        }

        return $this->returnJson($list);
    }

    public function newGroup (Request $request, GroupRepo $groupRepo, AuthRepo $authRepo)
    {
        // 初始默认值
        $setData = [
            'name' => $request->input('name', ''),
            'explain' => $request->input('explain', ''),
            'status' => $request->input('status', 1),
            'amount' => 0,
            'create_at' => time()
        ];
        $groupNewAuthList = $request->input('auth_list', []);
        if ($setData['name'] === '') return $this->error('用户组名称为不能为空');

        $newId = $groupRepo->insertData($setData);
        if (!$newId) return $this->error('添加用户组失败');
        $setGroupAuthResult = $authRepo->updateGroupAuth($groupNewAuthList, $newId);

        if ($setGroupAuthResult) {
            $setData['id'] = $newId;
            return $this->returnJson($setData);
        } else {
            return $this->error('数据操作失败或者完全没有修改');
        }
    }

    public function editGroup (Request $request, GroupRepo $groupRepo, AuthRepo $authRepo)
    {
        // 参数验证
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少用户组GID');
        $group = $groupRepo->checkGroupById($id);
        if (!$group) return $this->error('提交用户组不存在');

        // 判断是否为返回详情
        $getView = $request->input('get', '');
        if ($getView === 'one') {
            $group->auth_list = $authRepo->getGroupAuthList($id);
            return $this->returnJson($group);
        }

        // 初始默认值
        $setData = [
            'name' => $request->input('name', ''),
            'explain' => $request->input('explain', ''),
            'status' => $request->input('status', 1)
        ];
        $groupNewAuthList = $request->input('auth_list', []);
        if ($setData['name'] === '') return $this->error('用户组名称为不能为空');

        $updateResult = $groupRepo->updateData($setData, $id);
        $setGroupAuthResult = $authRepo->updateGroupAuth($groupNewAuthList, $id);

        if ($updateResult > 0 || $setGroupAuthResult) {
            $setData['id'] = $id;
            return $this->returnJson($setData);
        } else {
            return $this->error('数据操作失败或者完全没有修改');
        }
    }

    public function deleteGroup (Request $request, GroupRepo $groupRepo)
    {
        // 参数验证
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少操作组GID');
        $gourp = $groupRepo->checkGroupById($id);
        if (!$gourp) return $this->error('提交用户组不存在');

        if ($gourp->status === 2) {
            if ($groupRepo->deleteData($id, 1)) return $this->success('已经彻底删除');
        } else {
            if ($result = $groupRepo->deleteData($id)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }
}