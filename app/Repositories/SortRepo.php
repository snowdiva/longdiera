<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SortRepo
{
    protected $sortTable = 'sort';
    protected $novelTable = 'novel';

    // -----Api方法
    public function getData($where = [])
    {
        $list = DB::table($this->sortTable)
            ->where($where)
            ->paginate(config('admin.page_number'))
            ->toArray();

        return $list;
    }

    public function insertData(Array $data)
    {
        $id = DB::table($this->sortTable)
            ->insertGetId($data);

        return $id;
    }

    public function updateData(Array $data, $id)
    {
        $result = DB::table($this->sortTable)
            ->whereId($id)
            ->update($data);

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData ($id, $trueDelete = false)
    {
        if ($trueDelete) {
            $result = DB::table($this->sortTable)
                ->where('id', $id)
                ->delete();
        } else {
            $result = DB::table($this->sortTable)
                ->where('id', $id)
                ->update(['status' => 2]);
        }

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getSortOptions ($sortId = -1)
    {
        $where = [['status', '=', 1]];
        if ($sortId !== -1) $where[] = ['sort_id', '=', $sortId];

        $sortList = DB::table($this->sortTable)
            ->select('id', 'name', 'short_name', 'chinese_name', 'sort_id', 'hot_is')
            ->where($where)
            ->orderBy('hot_is', 'DESC')
            ->get()
            ->toArray();

        return $sortList;
    }

    /**
     * 检查重名
     * @param $name
     * @param $shortName
     * @param $chineseName
     * @param $id
     * @return bool
     */
    public function checkRepitition ($name, $shortName, $chineseName, $id = -1)
    {
        $where = [
            ['name', '=', $name],
            ['short_name', '=', $shortName],
            ['chinese_name', '=', $chineseName]
        ];
        if ($id !== -1) $where[] = ['id', '<>', $id];
        $hasSort = DB::table($this->sortTable)
            ->where($where)
            ->first();

        if ($hasSort) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查分类是否存在
     * @param $id
     * @return mixed
     */
    public function checkSortById ($id)
    {
        return DB::table($this->sortTable)
            ->whereId($id)
            ->first();
    }

    /**
     * 检查分类是否已分配小说
     * @param $id
     * @return mixed
     */
    public function checkSortUsed ($id)
    {
        return DB::table($this->novelTable)
            ->where('sort_id', $id)
            ->first();
    }

    // -----Api方法结束

    public function getOne($sortId)
    {
        $sort = DB::table($this->sortTable)
            ->where('status', '>', 0)
            ->where('id', $sortId)
            ->first();

        if (!$sort) return false;

        return $sort;
    }

    public function getSort($pid = false)
    {
        if (is_int($pid)) {
            $sorts = DB::table($this->sortTable)
                ->where('sort_id', $pid)
                ->where('status', '>', 0)
                ->get();
        } else {
            $sorts = DB::table($this->sortTable)
                ->where('status', '>', 0)
                ->get();
        }

        $sortArr = [];

        foreach ($sorts as $items) {
            $sortArr[$items->id]['name'] = $items->name;
            $sortArr[$items->id]['short_name'] = $items->short_name;
        }

        return $sortArr;
    }

    public function addData($name, $shortName, $chineseName, $sortId, $hotIs)
    {
        $result = DB::table($this->sortTable)
            ->insert([
                'name' => $name,
                'short_name' => $shortName,
                'chinese_name' => $chineseName,
                'sort_id' => $sortId,
                'hot_is' => $hotIs,
                'create_at' => time(),
                'status' => 1
            ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function editData($id, $setData)
    {
        $result = DB::table($this->sortTable)
            ->where('id', $id)
            ->update($setData);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}