<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChapRepo
{
    protected $novelTable = 'novel';
    protected $chapterTable = 'chapter';
    protected $userTable = 'user';

    /**
     * 获取所有章节列表
     * @param array $where
     * @return array
     */
    public function getAll($where = [])
    {
        $list = DB::table($this->chapterTable)
            ->leftJoin($this->novelTable, $this->novelTable . '.id', '=', $this->chapterTable . '.novel_id')
            ->leftJoin($this->userTable, $this->userTable . '.id', '=', $this->chapterTable . '.auditor_id')
            ->select($this->userTable . '.username as auditor', $this->novelTable . '.id', $this->novelTable . '.title as novel_title', $this->novelTable . '.chinese_title as novel_chinese_title', $this->novelTable . '.publish_status', $this->chapterTable . '.*')
            ->where($where)
            ->orderBy($this->chapterTable . '.create_at', 'DESC')
            ->paginate(15);

        if ($list) {
            return $list;
        } else {
            return [];
        }
    }

    /**
     * 添加一篇新的章节
     * @param $novelId
     * @param $chapter
     * @param $content
     * @return bool
     */
    public function setChpater($novelId, $chapter, $content)
    {
        // 检查小说是否存在
        $novel = DB::table($this->novelTable)
            ->select('size', 'chapter_count')
            ->where('id', '=', $novelId)
            ->first();

        if (!$novel) return false;

        // 整理写入数据
        $chapter['novel_id'] = $novelId;
        $chapter['size'] = strlen(strip_tags($content));
        $chapter['order'] = $this->getLastChapter($novelId) + 1;
        $chapter['auditor_id'] = 0;
        $chapter['publish_at'] = 0;
        $chapter['create_at'] = time();
        $chapter['status'] = 0;

        // 写入数据库
        $chapterId = DB::table($this->chapterTable)
            ->insertGetId($chapter);

        if ($chapterId) {
            $resultUpdateNovel = DB::table($this->novelTable)
                ->where('id', '=', $novelId)
                ->update([
                    'size' => intval($novel->size) + $chapter['size'],
                    'chapter_count' => intval($novel->chapter_count) + 1
                ]);
        }

        // 生成文本文件
        if ($chapterId) {
            if (!$this->saveContent($novelId, $chapterId, $content)) return false;
            return true;
        }
    }

    /**
     * 获取一篇章节内容
     * @param $chapterId
     * @return array
     */
    public function getChapter($chapterId)
    {
        $chapter = DB::table($this->chapterTable)
            ->leftJoin($this->novelTable, $this->novelTable . '.id', '=', $this->chapterTable . '.novel_id')
            ->select($this->novelTable . '.id', $this->novelTable . '.title as novel_title', $this->novelTable . '.chinese_title as novel_chinese_title', $this->novelTable . '.publish_status', $this->chapterTable . '.*')
            ->where($this->chapterTable . '.id', '=', $chapterId)
            ->first();

        if (!$chapter) return [];

        $chapter->content = $this->getContent($chapter->novel_id, $chapter->id);

        return $chapter;
    }

    /**
     * 编辑章节内容
     * @param $id
     * @param $chapter
     * @param string $content
     */
    public function editChapter($id, $chapter, $content = '')
    {
        // 检查章节是否存在
        $oldChapter = DB::table($this->chapterTable)
            ->whereId($id)
            ->first();
        if (!$oldChapter) return false;

        // 修改文本内容
        if ('' != $content) {
            if (!$this->saveContent($oldChapter->novel_id, $id, $content)) return false;
            $chapter['size'] = strlen(strip_tags($content));
        }

        // 写入数据库
        $chapterUpdateResut = DB::table($this->chapterTable)
            ->whereId($id)
            ->update($chapter);

        $novel = DB::table($this->novelTable)
            ->select('size')
            ->whereId($oldChapter->novel_id)
            ->first();

        if ($chapterUpdateResut) {
            $newSize = intval($novel->size) - $oldChapter->size + $chapter['size'];
            DB::table($this->novelTable)
                ->whereId($oldChapter->novel_id)
                ->update([
                    'size' => $newSize
                ]);
        }

        return true;
    }

    /**
     * 获取最后一个章节的排序(也可以获取其他)
     * @param $novelId
     * @return int
     */
    public function getLastChapter($novelId, $select = ['order'])
    {
        $chapter = DB::table($this->chapterTable)
            ->select($select)
            ->where('status', '>=', '0')
            ->where('novel_id', $novelId)
            ->orderBy('order', 'DESC')
            ->first();

        if (!$chapter) {
            return 0;
        } else {
            return intval($chapter->order);
        }
    }

    public function setAudit($id, $userId, $auditTime = 0)
    {
        $setData = [
            'auditor_id' => $userId,
            'publish_at' => time(),
            'status' => 1
        ];

        if ($auditTime > 0) {
            $setData['publish_at'] = strtotime($auditTime);
            $setData['status'] = 2;
        }

        $result = DB::table($this->chapterTable)
            ->whereId($id)
            ->update($setData);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存章节内容到txt文件
     * @param $novelId
     * @param $chapterId
     * @param $content
     * @return mixed
     */
    protected function saveContent($novelId, $chapterId, $content)
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
    protected function getContent($novelId, $chapterId)
    {
        $path = 'novel/' . floor($novelId / 1000) . '/' . $novelId . '/' . $chapterId . '.txt';

        if (Storage::exists($path)) {
            return htmlspecialchars_decode(Storage::get($path));
        } else {
            return '';
        }
    }
}