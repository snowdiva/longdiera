<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\AuthRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorityController extends CommonController
{
    public function getAuthList (Request $request, AuthRepo $authRepo)
    {
        // 组合查询条件
        $where = [];
        $whereObj = $request->input();
        if (isset($whereObj['field']) && isset($whereObj['value']) && $whereObj['value'] != '') {
            $where[] = [$whereObj['field'], 'like', '%'.$whereObj['value'].'%'];
        }
        if (isset($whereObj['pid']) && $whereObj['pid'] != -1) $where[] = ['pid', '=', $whereObj['pid']];
        if (isset($whereObj['type']) && $whereObj['type'] != -1) $where[] = ['type', '=', $whereObj['type']];
        if (isset($whereObj['status'])) {
            if ($whereObj['status'] != -1) {
                $where[] = ['status', '=', $whereObj['status']];
            }
        } else {
            $where[] = ['status', '=', 1];
        }

        $user = cache($request->header('Authorization'));
        if ($user->group_id === config('admin.admin_group_id')) {
            $list = $authRepo->getData($where);
        } else {
            $list = $authRepo->getData($where, $user->group_id);
        }

        $list['parent_auth_list'] = $authRepo->getOptions();

        return $this->returnJson($list);
    }

    public function newAuth (Request $request, AuthRepo $authRepo)
    {
        $data = [
            'name' => $request->input('name', ''),
            'explain' => $request->input('explain', ''),
            'authority' => $request->input('authority', ''),
            'type' => intval($request->input('type', 0)),
            'pid' => intval($request->input('pid', 0)),
            'status' => intval($request->input('status', 1)),
            'create_at' => time()
        ];
        if ($data['name'] === '') return $this->error('名称不能为空');
        if ($data['pid'] === 0 && $data['type'] !== 0) return $this->error('模块权限的类型不能为菜单或者节点');
        if ($data['type'] !== 0 && $data['authority'] === '') return $this->error('菜单和权限节点必须包含验证字段');

        $id = $authRepo->insertdata($data);

        if ($id) {
            return $this->returnJson($id);
        } else {
            return $this->error('似乎添加失败了');
        }
    }

    public function editAuth (Request $request, AuthRepo $authRepo)
    {
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少操作编号');

        $data = [
            'name' => $request->input('name', ''),
            'explain' => $request->input('explain', ''),
            'authority' => $request->input('authority', ''),
            'type' => intval($request->input('type', 0)),
            'pid' => intval($request->input('pid', 0)),
            'status' => intval($request->input('status', 1))
        ];
        if ($data['name'] === '') return $this->error('名称不能为空');
        if ($data['pid'] === 0 && $data['type'] !== 0) return $this->error('模块权限的类型不能为菜单或者节点');
        if ($data['type'] !== 0 && $data['authority'] === '') return $this->error('菜单和权限节点必须包含验证字段');

        $result = $authRepo->updatedata($data, $id);

        if ($result) {
            $data['id'] = $id;
            return $this->returnJson($id);
        } else {
            return $this->error('似乎添加失败了');
        }
    }

    public function deleteAuth (Request $request, AuthRepo $authRepo)
    {
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少操作编号');
        $hasAuth = $authRepo->checkAuthById($id);
        if (!$hasAuth) return $this->error('提交用户不存在');
        $usedAuth = $authRepo->checkAuthUsed($id);
        if ($usedAuth) return $this->error('该权限已被' . $usedAuth->group_id . '号权限组绑定,无法删除');

        if ($hasAuth->status === 2) {
            if ($authRepo->deleteData($id, 1)) return $this->success('已经彻底删除');
        } else {
            if ($result = $authRepo->deleteData($id)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }
}