<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SortRepo
{
    protected $sortTable = 'sort';

    public function getAll()
    {
        $list = DB::table($this->sortTable)
            ->where('status', '>', 0)
            ->paginate(15);

        return $list;
    }

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