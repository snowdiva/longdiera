<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AuthRepo
{
    protected $authTable = 'authority';
    protected $groupTable = 'group';
    protected $userTable = 'user';
    protected $groupAuthTable = 'group_authority';

    public function getAll()
    {
        $list = DB::table($this->authTable)
            ->get();

        $adminMenu = $this->formatMenu($list);

        return $adminMenu;
    }

    //-----------api接口
    public function getData ($where = [], $userGroupId = -1)
    {
        if ($userGroupId !== -1) {
            // 非超级管理员只能分配自己已具有的权限
            $userAuthId = DB::table($this->groupAuthTable)
                ->select('id')
                ->where('group_id', '=', $userGroupId)
                ->get()
                ->toArray();

            $userAuthId = implode(',', $userAuthId);
            $where[] = ['id', 'in', '('.$userAuthId.')'];
        }

        $authList = DB::table($this->authTable)
            ->where($where)
            ->orderBy('create_at', 'DESC')
            ->paginate(config('admin.page_number'))
            ->toArray();

        return $authList;
    }

    public function insertData ($data)
    {
        $id = DB::table($this->authTable)
            ->insertGetId($data);

        return $id;
    }

    public function updatedata ($data, $id)
    {
        $effect = DB::table($this->authTable)
            ->where('id', '=', $id)
            ->update($data);

        if ($effect > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getOptions ($pid = -1)
    {
        $where[] = ['status', '=', 1];
        $where[] = ['type', '<', 2];
        if ($pid !== -1) $where[] = ['pid', '=', $pid];

        $opsionList = DB::table($this->authTable)
            ->select('id', 'name', 'pid')
            ->where($where)
            ->orderBy('pid')
            ->get()
            ->toArray();

        return $opsionList;
    }

    public function checkAuthById ($id)
    {
        return DB::table($this->authTable)
            ->whereId($id)
            ->first();
    }

    public function checkAuthUsed ($id)
    {
        return DB::table($this->groupAuthTable)
            ->where('authority_id', '=', $id)
            ->first();
    }

    public function deleteData ($id, $trueDelete = false)
    {
        if ($trueDelete) {
            $result = DB::table($this->authTable)
                ->where('id', $id)
                ->delete();
        } else {
            $result = DB::table($this->authTable)
                ->where('id', $id)
                ->update(['status' => 2]);
        }

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAuthList ($group_id = -1)
    {
        $fields = ['id', 'name', 'pid', 'explain'];
        if ($group_id === -1) {
            $list = DB::table($this->authTable)
                ->select($fields)
                ->whereStatus(1)
                ->orderBy('pid')
                ->get()
                ->toArray();
        } else {
            $promissionAuth = DB::table($this->groupAuthTable)
                ->select('authority_id')
                ->where('group_id', '=', $group_id)
                ->get();

            $whereArray = [];
            foreach ($promissionAuth as $item) {
                $whereArray[] = $item->authority_id;
            }

            $list = DB::table($this->authTable)
                ->select($fields)
                ->whereStatus(1)
                ->whereIn($whereArray)
                ->orderBy('pid')
                ->get()
                ->toArray();
        }

        return $list;
    }

    public function getGroupAuthList ($groupId)
    {
        $list = DB::table($this->groupAuthTable)
            ->select('authority_id')
            ->where('group_id', '=', $groupId)
            ->get();

        $authList = [];
        foreach ($list as $item) {
            $authList[] = $item->authority_id;
        }

        return $authList;
    }

    public function updateGroupAuth (Array $authList, $groupId)
    {
        // 如果存在就先删除原纪录
        if (DB::table($this->groupAuthTable)->where('group_id', '=', $groupId)->first()) {
            DB::table($this->groupAuthTable)
                ->where('group_id', $groupId)
                ->delete();
        }

        // 如果是删除权限则直接返回成功
        if (empty($authList)) return true;

        // 重组新的权限记录
        $updateData = [];
        foreach ($authList as $key => $value) {
            $updateData[$key]['group_id'] = $groupId;
            $updateData[$key]['authority_id'] = $value;
        }
        $result = DB::table($this->groupAuthTable)
            ->insert($updateData);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查用户组是否拥有某个权限
     * @param $groupId
     * @param $auth_name
     * @return mixed
     */
    public function checkAuth ($groupId, $auth_name)
    {
        return DB::table($this->groupAuthTable)
            ->leftJoin($this->authTable, $this->authTable . '.id', '=', $this->groupAuthTable . '.authority_id')
            ->where($this->groupAuthTable . '.group_id', '=', $groupId)
            ->where($this->authTable . '.authority', '=', $auth_name)
            ->first();
    }

    //----------api接口代码段结束

    public function getList($userId = 0)
    {
        if ($userId > 0) {
            $list = DB::table($this->authToUserTable)
                ->leftJoin($this->authTable, $this->authToUserTable . '.authority_id', '=', $this->authTable . '.id')
                ->where($this->authToUserTable . '.user_id', $userId)
                ->get();
        } else {
            $list = DB::table($this->authTable)
                ->get();
        }

        $adminMenu = $this->formatMenu($list);

        return $adminMenu;
    }

    public function getOne($id)
    {
        $list = DB::table($this->authTable)
            ->where('id', $id)
            ->first();

        return $list;
    }

    /**
     * 获取某个权限节点的项目
     * 默认获取所有菜单项目
     * @return mixed
     */
    public function getPidMenu($pid = -1)
    {
        $where = ['type' => 1];
        if ($pid >= 0) $where['pid'] = $pid;

        $list = DB::table($this->authTable)
            ->where($where)
            ->orderBy('pid', 'ASC')
            ->get();

        return $list;
    }

    /**
     * 用于后台权限管理的列表
     * @param $userId 如果填写-1,则表示所有权限的管理员
     * @return mixed
     */
//    public function getAuthList ($userId)
//    {
//        if ($userId === -1) {
//            $list = DB::table($this->authTable)
//                ->where('status', '=', 1)
//                ->get()
//                ->toArray();
//        } else {
//            $list = DB::table($this->groupAuthTable)
//                ->leftJoin($this->authTable, $this->groupAuthTable . '.authority_id', '=', $this->authTable . '.id')
//                ->rightJoin($this->userTable, $this->groupAuthTable . '.group_id', '=', $this->userTable . '.id')
//                ->where($this->userTable . '.id', $userId)
//                ->where($this->authTable . '.status', '=', 1)
//                ->get()
//                ->toArray();
//        }
//
//        return $list;
//    }

    public function addAuth($name, $alias, $url, $description, $type, $pid)
    {
        $data = [
            'name' => $name,
            'alias' => $alias,
            'url' => $url,
            'description' => $description,
            'type' => $type,
            'pid' => $pid,
            'create_time' => time()
        ];

        // 验证唯一性
        $checkName = DB::table($this->authTable)
            ->where('name', $data['name'])
            ->orWhere('url', $data['url'])
            ->first();
        if ($checkName) return false;

        $result = DB::table($this->authTable)
            ->insert($data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取所有已有权限
     * @param $userId
     */
    public function getAuthority($userId)
    {
        $authString = DB::table($this->authToUserTable)
            ->select('url')
            ->leftJoin($this->authTable, $this->authToUserTable . '.authority_id', '=', $this->authTable . '.id')
            ->where($this->authToUserTable . '.user_id', $userId)
            ->where($this->authTable . '.url', '<>', '')
            ->get();

        $returnArr = [];

        foreach ($authString as $items) {
            $returnArr[] = $items->url;
        }

        return $returnArr;
    }

    public function userBindAuth($userId, $authorityIds)
    {
        // 清空权限操作
        if ('' == $authorityIds) {
            $result = DB::table($this->authToUserTable)
                ->where('user_id', $userId)
                ->delete();

            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        $authArr = explode(',', $authorityIds);
        $insertData = [];
        foreach ($authArr as $k => $v) {
            if (intval($v) <= 0) continue;
            $insertData[$k]['user_id'] = $userId;
            $insertData[$k]['authority_id'] = $v;
        }

        DB::transaction(function() use($userId, $insertData){
            DB::table($this->authToUserTable)
                ->where('user_id', $userId)
                ->delete();
            DB::table($this->authToUserTable)
                ->insert($insertData);
        });

        return true;
    }

    public function getUserAuthority($userId)
    {
        $authString = DB::table($this->authToUserTable)
            ->select('id', 'alias', 'url')
            ->leftJoin($this->authTable, $this->authToUserTable . '.authority_id', '=', $this->authTable . '.id')
            ->where($this->authToUserTable . '.user_id', $userId)
            ->get();

        $returnArr = [
            'authority' => [],
            'authorities' => ''
        ];

        foreach ($authString as $k=>$v) {
            $returnArr['authority'][$v->id]['id'] = $v->id;
            $returnArr['authority'][$v->id]['alias'] = $v->alias;
            $returnArr['authority'][$v->id]['url'] = $v->url;
            $returnArr['authorities'] .= $v->id . ',';
        }

        $returnArr['authorities'] = rtrim($returnArr['authorities'], ',');

        return $returnArr;
    }

    /**
     * 获取无限级分类菜单列表
     * @param $result
     * @param int $id
     * @return array
     */
    protected function formatMenu($result, $id = 0)
    {
        $arr = [];

        foreach ($result as $items) {
            if (1 != $items->type) continue;
            if ($items->pid == $id) {
                $children =  $this->formatMenu($result, $items->id);
                if (!empty($children)) {
                    $arr[] = [
                        'id' => $items->id,
                        'name' => $items->name,
                        'alias' => $items->alias,
                        'url' => $items->url,
                        'pid' => $items->pid,
                        'children' => $children
                    ];
                } else {
                    $arr[] = [
                        'id' => $items->id,
                        'name' => $items->name,
                        'alias' => $items->alias,
                        'url' => $items->url,
                        'pid' => $items->pid
                    ];
                }
            }
        }

        return $arr;
    }
}