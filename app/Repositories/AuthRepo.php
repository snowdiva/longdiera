<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AuthRepo
{
    protected $authTable = 'authority';
    protected $groupTable = 'group';
    protected $groupAuthTable = 'group_aruthority';

    public function getAll()
    {
        $list = DB::table($this->authTable)
            ->get();

        $adminMenu = $this->formatMenu($list);

        return $adminMenu;
    }

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
     * @param $userId
     * @param bool $isAdmin 程序控制是否获取全部列表
     * @return mixed
     */
    public function getListMenu($userId, $isAdmin = false)
    {
        if ($isAdmin) {
            $list = DB::table($this->authTable)
                ->paginate(15);
        } else {
            $list = DB::table($this->groupAuthTable)
                ->leftJoin($this->authTable, $this->groupAuthTable . '.authority_id', '=', $this->authTable . '.id')
                ->where($this->groupAuthTable . '.user_id', $userId)
                ->paginate(15);
        }

        return $list;
    }

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