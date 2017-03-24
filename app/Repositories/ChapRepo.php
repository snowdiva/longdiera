<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChapRepo
{
    protected $novelTable = 'novel';
    protected $chapterTable = 'chapter';
    protected $userTable = 'user';

    public function getAll($novelId, $order = 'ASC')
    {
        $list = DB::table($this->chapterTable)
            ->where('novel_id', '=', $novelId)
            ->orderBy('order', $order)
            ->get()
            ->toArray();

        return $list;
    }

    /**
     * 添加一篇新的章节
     * @param $novelId
     * @param $chapter
     * @param $content
     * @return bool
     */
    public function insertChapter ($chapter, $content)
    {
        // 检查小说是否存在
        $novel = DB::table($this->novelTable)
            ->select('size', 'chapter_count')
            ->where('id', '=', $chapter['novel_id'])
            ->first();
        if (!$novel) return false;

        // 计算章节字数(剔除所有内容不相关的部分)
        $chapter['size'] = strlen(str_replace([" ","　","\t","\n","\r"], '', strip_tags($content)));

        // 获取当前最后章节的排序号累加3
        if ($chapter['order'] == 0) {
            $lastChapter = DB::table($this->chapterTable)
                ->select('id', 'order')
                ->where('novel_id', '=', $chapter['novel_id'])
                ->orderBy('order', 'DESC')
                ->first();
            if (!$lastChapter) {
                $chapter['order'] = 3;
            } else {
                $chapter['order'] = $lastChapter->order + 3;
            }
        }

        // 写入章节数据库
        $chapterId = DB::table($this->chapterTable)
            ->insertGetId($chapter);
        if (!$chapterId) return false;

        // 更新所属小说相关属性
        $resultUpdateNovel = DB::table($this->novelTable)
            ->where('id', '=', $chapter['novel_id'])
            ->update([
                'size' => intval($novel->size) + $chapter['size'],
                'chapter_count' => intval($novel->chapter_count) + 1
            ]);

        // 生成文本文件
        if (!$chapterId || !$resultUpdateNovel) return false;
        if (!$this->saveContent($chapter['novel_id'], $chapterId, $content)) return false;
        return true;
    }

    /**
     * 获取一篇章节内容
     * @param $chapterId
     * @return array
     */
    public function getOneData ($id)
    {
        $chapter = DB::table($this->chapterTable)
            ->leftJoin($this->novelTable, $this->novelTable . '.id', '=', $this->chapterTable . '.novel_id')
            ->select($this->novelTable . '.id', $this->novelTable . '.title as novel_title', $this->novelTable . '.chinese_title as novel_chinese_title', $this->novelTable . '.publish_status', $this->chapterTable . '.*')
            ->where($this->chapterTable . '.id', '=', $id)
            ->first();

        if (!$chapter) return [];

        $chapter->content = $this->getContent($chapter->novel_id, $id);

        return $chapter;
    }

    public function checkChapterById ($id)
    {
        return DB::table($this->chapterTable)
            ->select('id', 'title', 'novel_id', 'size', 'status')
            ->whereId($id)
            ->first();
    }

    /**
     * 编辑章节内容
     * @param $id
     * @param $chapter
     * @param string $content
     */
    public function updateChapter ($chapter, $content, $id)
    {
        // 首先获取当前章节状态
        $oldChapter = DB::table($this->chapterTable)
            ->whereId($id)
            ->first();
        if (!$oldChapter) return false;

        // 计算章节字数(剔除所有内容不相关的部分)
        $chapter['size'] = strlen(str_replace([" ","　","\t","\n","\r"], '', strip_tags($content)));

        // 获取当前最后章节的排序号累加3
        if ($chapter['order'] == 0) {
            $lastChapter = DB::table($this->chapterTable)
                ->select('id', 'order')
                ->where('novel_id', '=', $chapter['novel_id'])
                ->where('id', '<>', $id)
                ->orderBy('order', 'DESC')
                ->first();
            if (!$lastChapter) {
                $chapter['order'] = 3;
            } else {
                $chapter['order'] = $lastChapter->order + 3;
            }
        }

        // 写入章节数据库
        $resultRows = DB::table($this->chapterTable)
            ->whereId($id)
            ->update($chapter);

        // 更新所属小说相关属性
        $novel = DB::table($this->novelTable)
            ->whereId($oldChapter->novel_id)
            ->first();
        if (!$novel) return false;

        if ($chapter['size'] != $oldChapter->size) {
            $resultUpdateNovel = DB::table($this->novelTable)
                ->whereId($novel->id)
                ->update([
                    'size' => intval($novel->size) - intval($oldChapter->size) + $chapter['size'],
                ]);

            if (!$resultRows || !$resultUpdateNovel) return false;
            if (!$this->saveContent($oldChapter->novel_id, $id, $content)) return false;
        }

        if (!$resultRows) return false;
        return true;
    }

    /**
     * 获取最后一个章节的排序(也可以获取其他)
     * @param $novelId
     * @return int
     */
//    public function getLastChapter($novelId, $select = ['order'])
//    {
//        $chapter = DB::table($this->chapterTable)
//            ->select($select)
//            ->where('status', '>=', '0')
//            ->where('novel_id', $novelId)
//            ->orderBy('order', 'DESC')
//            ->first();
//
//        if (!$chapter) {
//            return 0;
//        } else {
//            return intval($chapter->order);
//        }
//    }

    public function setAudit ($id, $status, $publishAt, $auditorId)
    {
        $result = DB::table($this->chapterTable)
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

    public function deleteData ($id, $novelId, $trueDelete = false)
    {
        if ($trueDelete) {
            $result = DB::table($this->chapterTable)
                ->whereId($id)
                ->delete();
            // 删除小说章节内容txt文件
            if ($result <= 0 || !$this->removeContent($novelId, $id)) return false;
        } else {
            $result = DB::table($this->chapterTable)
                ->whereId($id)
                ->update(['status' => 99]);

            if ($result <= 0) return false;
        }

        return true;
    }

    /**
     * 保存章节内容到txt文件
     * @param $novelId
     * @param $chapterId
     * @param $content
     * @return mixed
     */
    protected function saveContent ($novelId, $chapterId, $content)
    {
        $path = 'novel/' . floor($novelId / 1000) . '/' . $novelId . '/' . $chapterId . '.txt';

        $content = htmlspecialchars($content);

        if (Storage::exists($path)) Storage::delete($path);

        return Storage::put($path, $content);
    }

    /**
     * 获取章节内容
     * @param $novelId
     * @param $chapterId
     * @return array
     */
    protected function getContent ($novelId, $chapterId)
    {
        $path = 'novel/' . floor($novelId / 1000) . '/' . $novelId . '/' . $chapterId . '.txt';

        if (Storage::exists($path)) {
            return htmlspecialchars_decode(Storage::get($path));
        } else {
            return '';
        }
    }

    /**
     * 删除章节txt文本内容
     * @param $novelId
     * @param $chapterId
     * @return bool
     */
    protected function removeContent ($novelId, $chapterId)
    {
        $path = 'novel/' . floor($novelId / 1000) . '/' . $novelId . '/' . $chapterId . '.txt';

        if (Storage::exists($path)) {
            return Storage::delete($path);
        } else {
            return true;
        }
    }
}