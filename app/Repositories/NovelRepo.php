<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NovelRepo
{
    protected $novelTable = 'novel';
    protected $chapterTable = 'chapter';
    protected $userTable = 'user';

    public function getData ($where = [])
    {
        $list = DB::table($this->novelTable)
            ->leftJoin($this->userTable, $this->userTable . '.id', '=', $this->novelTable . '.auditor_id')
            ->select($this->novelTable . '.*', $this->userTable . '.alias as auditor')
            ->where($where)
            ->orderBy('create_at', 'DESC')
            ->paginate(config('admin.page_number'))
            ->toArray();

        return $list;
    }

    public function setAudit ($id, $status, $publishAt, $auditorId)
    {
        $result = DB::table($this->novelTable)
            ->whereId($id)
            ->update([
                'status' => $status,
                'publish_at' => $publishAt,
                'auditor_id' => $auditorId
            ]);

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData ($id, $trueDelete = false)
    {
        if ($trueDelete) {
            $result = DB::table($this->novelTable)
                ->where('id', $id)
                ->delete();
        } else {
            $result = DB::table($this->novelTable)
                ->where('id', $id)
                ->update(['status' => 99]);
        }

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
}