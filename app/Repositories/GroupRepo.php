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

    public function insertData ($data)
    {
        return DB::table($this->groupTable)
            ->insertGetId($data);
    }

    public function updateData ($data, $id)
    {
        return DB::table($this->groupTable)
            ->whereId($id)
            ->update($data);
    }

    public function checkGroupById ($groupId)
    {
        return DB::table($this->groupTable)
            ->whereId($groupId)
            ->first();
    }

    public function deleteData ($id, $trueDelete = false)
    {
        if ($trueDelete) {
            $result = DB::table($this->groupTable)
                ->where('id', $id)
                ->delete();
        } else {
            $result = DB::table($this->groupTable)
                ->where('id', $id)
                ->update(['status' => 2]);
        }

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
}