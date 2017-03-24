<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\ChapRepo;
use App\Repositories\NovelRepo;
use Illuminate\Http\Request;

class ChapterController extends CommonController
{
    public function getChapterList (Request $request, ChapRepo $chapRepo, NovelRepo $novelRepo)
    {
        // TODO:: 组合查询条件,待章节更新功能上线再处理

        $novelId = $request->input('id', 0);
        if ($novelId === 0) return $this->error('缺少操作小说编号');

        $data = $chapRepo->getAll($novelId);

        return $this->returnJson($data);
    }

    public function newChapter (Request $request, ChapRepo $chapRepo)
    {
        $novelId = $request->input('novel_id', 0);
        if ($novelId <= 0) return $this->error('缺少小说编号');
        $content = trim($request->input('content', ''));
        if (strlen($content) <= 0) return $this->error('请输入内容');
        $chapter = [
            'title' => $request->input('title', ''),
            'chinese_title' => $request->input('chinese_title'),
            'novel_id' => $novelId,
            'intro' => $request->input('intro', ''),
            'order' => $request->input('order', 0),
            'create_at' => time(),
            'publish_at' => 0,
            'auditor_id' => 0,
            'status' => 0
        ];
        if ($chapter['title'] === '') return $this->error('章节标题不能为空');

        if ($chapRepo->insertChapter($chapter, $content)) {
            return $this->success($chapter['title'] . ' 创建成功');
        } else {
            return $this->error('创建章节失败,请刷新当前页重新创建');
        }
    }

    public function editChapter (Request $request, ChapRepo $chapRepo)
    {
        $id = $request->input('id', 0);
        if ($id <= 0) return $this->error('缺少操作章节编号');

        // 检查是否为获取编辑章节列表
        $showChapter = $request->get('get', '');
        if ($showChapter && $showChapter === 'one') return $this->returnJson($chapRepo->getOneData($id));

        // 组合编辑数据
        $content = trim($request->input('content', ''));
        if (strlen($content) <= 0) return $this->error('请输入内容');
        $chapter = [
            'title' => $request->input('title', ''),
            'chinese_title' => $request->input('chinese_title'),
            'intro' => $request->input('intro', ''),
            'order' => $request->input('order', 0),
            'publish_at' => 0,
            'auditor_id' => 0,
            'status' => 0
        ];
        if ($chapter['title'] === '') return $this->error('章节标题不能为空');

        if ($chapRepo->updateChapter($chapter, $content, $id)) {
            return $this->success($chapter['title'] . ' 编辑成功');
        } else {
            return $this->error('编辑章节失败,请刷新当前页重新创建');
        }
    }

    public function auditChapter (Request $request, ChapRepo $chapRepo)
    {
        $id = $request->input('id', 0);
        $status = $request->input('status', 1);
        if ($id === 0) return $this->error('缺少操作编号');
        if ($status == 1) {
            $publish_at = time();
        } else {
            $publish_date = $request->input('publish_date', '');
            if ($publish_date === '') return $this->error('定时发布必须填写发布时间');
            $publish_at = strtotime($publish_date);
        }
        $userInfo = cache($request->header('Authorization'));

        if ($chapRepo->setAudit($id, $status, $publish_at, $userInfo->id)) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败,请稍后重试');
        }
    }

    public function deleteChapter (Request $request, ChapRepo $chapRepo, NovelRepo $novelRepo)
    {
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少操作编号');
        $hasChapter = $chapRepo->checkChapterById($id);
        if (!$hasChapter) return $this->error('提交章节不存在');

        if ($hasChapter->status === 99) {
            if ($chapRepo->deleteData($id, $hasChapter->novel_id, 1)) {
                // 更新小说字数、章节数状态
                if (!$novelRepo->removeChapterInNovel($hasChapter->novel_id, $hasChapter->size)) return $this->error('章节对应小说字段更新失败');
                return $this->success('该章节已彻底清退');
            }
        } else {
            if ($result = $chapRepo->deleteData($id, $hasChapter->novel_id)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }
}