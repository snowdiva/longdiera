<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
            ->orderBy($this->novelTable . '.status', 'ASC')
            ->orderBy($this->novelTable . '.create_at', 'DESC')
            ->paginate(config('admin.page_number'))
            ->toArray();

        return $list;
    }

    public function insertData ($data)
    {
        return DB::table($this->novelTable)
            ->insertGetId($data);
    }

    public function getOneData ($id, $type = '')
    {
        $novel = DB::table($this->novelTable)
            ->whereId($id)
            ->first();

        if (!$novel) return [];

        // 增加时间戳后缀,解决浏览器缓存看不到封面修改问题
        $novel->cover_url = $this->getNovelCover($id, $novel->cover_ext, $type) . '?t=' . time();
        return $novel;
    }

    public function updateData ($data, $id)
    {
        return DB::table($this->novelTable)
            ->whereId($id)
            ->update($data);
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

    public function deleteData ($id, $ext, $trueDelete = false)
    {
        if ($trueDelete) {
            // 删除数据
            $result = DB::table($this->novelTable)
                ->whereId($id)
                ->delete();
            // 删除封面
            if ($result <= 0 || !$this->removeCover($id, $ext)) return false;
        } else {
            $result = DB::table($this->novelTable)
                ->whereId($id)
                ->update(['status' => 99]);

            if ($result <= 0) return false;
        }

        return true;
    }

    public function checkNovelById ($id)
    {
        return DB::table($this->novelTable)
            ->whereId($id)
            ->first();
    }

    public function getNovelCover ($id, $ext = '.jpg', $type = '')
    {
        $type = $type === '' ? '' : 's';
        $novelCover = public_path(config('novel.cover_path')) . floor($id/1000) . '/' . $id . $type . $ext;
        if (!File::exists($novelCover)) return '';

        return env('APP_URL') . '/' . config('novel.cover_path') . '/' . floor($id/1000) . '/' . $id . $type . $ext;
    }

    public function removeCover ($id, $ext)
    {
        $novelCover = public_path(config('novel.cover_path')) . floor($id/1000) . '/' . $id . $ext;
        $novelCoverSmall = public_path(config('novel.cover_path')) . floor($id/1000) . '/' . $id . 's' . $ext;

        if (File::exists($novelCover)) File::delete($novelCover);
        if (File::exists($novelCoverSmall)) File::delete($novelCoverSmall);

        return true;
    }

    /**
     * 删除小说中对应的章节统计数据(count和size)
     * @param $novelId
     * @param $chapterId
     * @param $chapterSize
     */
    public function removeChapterInNovel ($novelId, $chapterSize)
    {
        $originalNovel = DB::table($this->novelTable)
            ->select('chapter_count', 'size')
            ->whereId($novelId)
            ->first();

        return DB::table($this->novelTable)
            ->whereId($novelId)
            ->update([
                'chapter_count' => $originalNovel->chapter_count - 1,
                'size' => $originalNovel->size - intval($chapterSize)
            ]);
    }
}