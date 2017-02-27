<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArtiRepo
{
    protected $novelTable = 'novel';

    public function getAll($where = [])
    {
        $searchFiled = ['sort', 'title', 'author', 'auditor'];

        if (!is_array($where) || empty($where)) {
            $list = DB::table($this->novelTable)
                ->where('status', '>=', 0)
                ->orderBy('create_at', 'DESC')
                ->paginate(15);
        } else {
            // 查询条件搜索
//            if (in_array($where)) {
//                foreach ($where as $key => $value) {
//                    if (in_array($key, $searchFiled)) {
//                        $where[$key] = $value;
//                    }
//                }
//            }

            $list = DB::table($this->novelTable)
                ->where($where)
                ->where('status', '>=', 0)
                ->orderBy('create_at', 'DESC')
                ->paginate(15);
        }

        return $list;
    }

    /**
     * 前台获取文章列表
     * @param $sortId   获取分类
     */
    public function getFontArticleList()
    {
        $list = DB::table($this->novelTable)
            ->where('status', 1)
            ->orderBy('publish_at', 'DESC')
            ->paginate(10);

        return $list;
    }

    public function getFontArticle($id)
    {
        $article = DB::table($this->novelTable)
            ->where([
                ['status', 1],
                ['id', $id],
                ['type', '>', 0]
            ])
            ->first();

        if (!$article) {
            return false;
        }

        $article->content = $this->getContent($article->id);

        // 单独处理封面
        if ('' != $article->cover) {
            $article->cover = explode(',', $article->cover);
        }

        return $article;
    }

    public function getArticle($id, $status = 0)
    {
        $where = [
            'id' => $id
        ];

        if (0 != $status) {
            $where['status'] = intval($status);
        }
        $article = DB::table($this->novelTable)
            ->where($where)
            ->first();

        if (!$article) {
            return false;
        }

        return $article;
    }

    public function setArticle($article)
    {
        $data = [
            'title' => $article['title'],
            'chinese_title' => $article['chinese_title'],
            'intro' => $article['intro'],
            'sort_id' => $article['sort_id'],// 之后提示错误信息
            'author' => $article['author'],
            'publish_status' => $article['publish_status'],
            'cover_ext' => $article['cover_ext'],
            'chapter_count' => 0,
            'size' => 0,
            'create_at' => time(),
            'publish_at' => 0,
            'auditor_id' => 0,
            'status' => 0
        ];

        $data['author'] = '' == $article['author'] ? 'unknown' : $article['author'];

        $newId = DB::table($this->novelTable)
            ->insertGetId($data);

        if ($newId) {
            return $newId;
        } else {
            return false;
        }
    }

    public function editArticle($article)
    {
        $data = [
            'title' => $article['title'],
            'chinese_title' => $article['chinese_title'],
            'intro' => $article['intro'],
            'sort_id' => $article['sort_id'],
            'author' => $article['author'],
            'publish_status' => $article['publish_status'],
            'status' => 0
        ];

        if (isset($article['cover_ext'])) $data['cover_ext'] = $article['cover_ext'];

        $data['author'] = '' == $article['author'] ? 'unknown' : $article['author'];

        $result = DB::table($this->novelTable)
            ->where('id', $article['id'])
            ->update($data);

        if ($result) {
            return true;
        } else {
            return false;
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

        $result = DB::table($this->novelTable)
            ->whereId($id)
            ->update($setData);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function addRead($newsId)
    {
        $result = DB::table($this->novelTable)
            ->where('id', $newsId)
            ->increment('read');

        if (!$result) return false;

        return true;
    }

    protected function saveContent($newsId, $content)
    {
        $path = 'news/' . floor($newsId / 1000);

        return Storage::put($path . '/' . $newsId . '.txt', $content);
    }

    protected function getContent($newsId)
    {
        $path = 'news/' . floor($newsId / 1000);

        return Storage::get($path . '/' . $newsId . '.txt');
    }

}