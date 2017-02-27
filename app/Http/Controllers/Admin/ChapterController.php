<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ArtiRepo;
use Illuminate\Http\Request;
use App\Repositories\ChapRepo;
use Illuminate\Support\Facades\Session;

class ChapterController extends AdminController
{
    public function index(Request $request, ChapRepo $chapRepo)
    {
        $search = $request->input();

        // 处理搜索关键字
        $list = $chapRepo->getAll();

        return view('admin.chapter', ['list' => $list]);
    }

    public function add(Request $request, ArtiRepo $artiRepo)
    {
        $novelId = $request->get('novel_id', 0);

        if (0 == $novelId) {
            $novel['novel_id'] = $novelId;
            $novel['novel_name'] = '';
        } else {
            $result = $artiRepo->getArticle($novelId);
            $novel['novel_id'] = $result->id;
            $novel['novel_name'] = $result->title;
            $novel['novel_chinese_name'] = $result->chinese_title;
        }

        return view('admin.chapter_add', $novel);
    }

    public function addPost(Request $request, ChapRepo $chapRepo)
    {
        $novelId = $request->input('novel_id', 0);
        if ($novelId <= 0) return $this->toError('缺少小说编号');
        $content = trim($request->input('content', ''));
        if (strlen($content) <= 0) return $this->toError('请输入内容');
        $chapter = [
            'title' => $request->input('title', ''),
            'chinese_title' => $request->input('chinese_title'),
            'intro' => $request->input('intro', ''),
            'order' => $request->input('order', 1)
        ];
        if ('' == $chapter['title']) return $this->toError('章节标题不能为空');

        if (!$chapRepo->setChpater($novelId, $chapter, $content)) {
            return $this->toError('数据操作有误,请联系管理员');
        } else {
            return redirect()->route('chapter_index');
        }
    }

    public function edit(Request $request, ChapRepo $chapRepo)
    {
        $chapterId = $request->input('id', 0);
        if (0 == $chapterId) return $this->toError('缺少操作章节编号');

        $list = $chapRepo->getChapter($chapterId);

        return view('admin.chapter_edit', ['list' => $list]);
    }

    public function editPost(Request $request, ChapRepo $chapRepo)
    {
        $id = $request->input('id', 0);
        if (0 == $id) return $this->toError('误操作数据');
        $content = trim($request->input('content', ''));
        if (strlen($content) <= 0) return $this->toError('请输入内容');
        $chapter = [
            'title' => $request->input('title', ''),
            'chinese_title' => $request->input('chinese_title'),
            'intro' => $request->input('intro', ''),
            'order' => $request->input('order', 1)
        ];
        if ('' == $chapter['title']) return $this->toError('章节标题不能为空');

        if (!$chapRepo->editChapter($id, $chapter, $content)) {
            return $this->toError('数据操作有误,请联系管理员');
        } else {
            return redirect()->route('chapter_index');
        }
    }

    public function delete()
    {

    }

    public function audit(Request $request, ChapRepo $chapRepo)
    {
        $id = $request->input('id', 0);
        if ($id <= 0) return $this->toJsonError('缺少参数');
        $audit_time = $request->input('audit_time', 0);
        if ($audit_time != 0) $audit_time .= ':00';// 处理秒

        $user = Session::get('user');

        if ($chapRepo->setAudit($id, $user['user_id'], $audit_time)) {
            return $this->toJsonSucess();
        } else {
            return $this->toJsonError('操作失败,请刷新页面或联系管理员');
        }
    }
}