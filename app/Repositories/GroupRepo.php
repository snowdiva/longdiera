<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GroupRepo
{
    protected $groupTable = 'group';

    /**
     * 获取所有分组列表,带有分页类
     * @return mixed
     */
    public function getAll($where = [])
    {
        $groupList = DB::table($this->groupTable)
            ->where($where)
            ->paginate(config('admin.page_number'))
            ->toArray();

        return $groupList;
    }

    /**
     * 获得一个用于显示的列表
     * @return array
     */
    public function getList()
    {
        $groupList = DB::table($this->groupTable)
            ->select('id', 'name')
            ->where('status', '>', 0)
            ->get()->toArray();

        return $groupList;
    }
}