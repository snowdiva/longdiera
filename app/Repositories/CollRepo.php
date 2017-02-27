<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CollRepo
{
    protected $collectTable = 'sc_collect';
    protected $collectField = ['name', 'description', 'rule', 'status'];

    public function getAll()
    {
        $list = DB::table($this->collectTable)
            ->where('status', '>=', 0)
            ->paginate(15);

        return $list;
    }

    public function getOne($id)
    {
        $collect = DB::table($this->collectTable)
            ->where('id', $id)
            ->where('status', '>=', 0)
            ->first();

        if (!$collect) return false;

        return $collect;
    }

    public function addOneCollect($collect)
    {
        if (!is_array($collect)) return false;

        foreach ($collect as $key => $value) {
            if (!in_array($key, $this->collectField)) {
                unset($collect[$key]);
            }
        }

        $collect['create_time'] = time();

        $result = DB::table($this->collectTable)
            ->insert($collect);

        if (!$result) return false;
        return true;
    }
}