<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class HotRepo
{
    protected $hotTable = 'sc_hot';

    public function getAll($where = [])
    {
        $list = DB::table($this->hotTable)
            ->where($where)
            ->paginate(config('admin.page_number'));

        if (!$list) return false;

        return $list;
    }
}