<?php

namespace App\Http\Controllers\Admin;

use App\Logic\Upload;
use App\Repositories\ArtiRepo;
use App\Repositories\SortRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ArticleController extends AdminController
{
    /**
     * 文章管理
     * @param Request $request
     * @param ArtiRepo $artiRepo
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ArtiRepo $artiRepo, SortRepo $sortRepo)
    {
        $title = $request->input('title', '');
        $sort = $request->input('sort', 0);
        $searchKey = $request->input('search_key', 1);
        $searchValue = $request->input('search_value', '');

        $search = [];
        if ('' != $title) $search['title'] = $title;
        if ($sort > 0) $search['sort'] = $sort;
        if ('' != $searchValue) $search[$searchKey] = $searchValue;

        $list = $artiRepo->getAll($search);
        $sortList = $sortRepo->getSort();

        return view('admin.index', [
            'list' => $list,
            'sort' => $sortList
        ]);
    }

    /**
     * 添加文章视图
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleAdd(SortRepo $sortRepo)
    {
        $sortList = $sortRepo->getSort();
        return view('admin.article_add', ['sort' => $sortList]);
    }

    /**
     * 写入文章
     * @param Request $request
     * @param ArtiRepo $artiRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function articleAddPost(Request $request, ArtiRepo $artiRepo)
    {
        $article = $request->all();
        if ('' == $article['title']) return $this->toError('文章名不能为空');
        $article['sort_id'] = $request->input('sort_id', 0);

        $file = Input::file('cover');
        if (null !== $file) {
            $article['cover_ext'] = $file->getClientOriginalExtension();
        } else {
            $article['cover_ext'] = '';
        }
        if ($novelId = $artiRepo->setArticle($article)) {
            // 处理封面/如果没有上传则暂时不处理
            if ('' != $article['cover']) {
                $upload = new \App\Logic\UploadLogic();
                if (!$upload->cover($file, $novelId)) {
                    return $this->toError('封面上传失败,请在修改小说中重新设置');
                }
            }
            return redirect()->route('article_index');
        } else {
            return $this->toError('操作失败,请联系管理员');
        }
    }

    /**
     * 编辑文章视图
     * @param Request $request
     * @param ArtiRepo $artiRepo
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleEdit(Request $request, ArtiRepo $artiRepo, SortRepo $sortRepo)
    {
        $id = $request->input('id', 0);
        if (0 == $id) return $this->toError('缺少参数');

        $list = $artiRepo->getArticle($id);
        $sortList = $sortRepo->getSort();
        $list->cover_url = $this->getNovelCover($id, $list->cover_ext);

        return view('admin.article_edit', [
            'list' => $list,
            'sort' => $sortList
        ]);
    }

    /**
     * 更新文章
     * @param Request $request
     * @param ArtiRepo $artiRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function articleEditPost(Request $request, ArtiRepo $artiRepo)
    {
        $article = $request->all();
        if ($article['id'] <= 0) return $this->toError('缺少参数');
        $article['sort_id'] = $request->input('sort_id', 0);

        $file = Input::file('cover');

        $hasChange = false;

        if ('' != $article['cover']) {
            $upload = new \App\Logic\UploadLogic();
            if (!$upload->cover($file, $article['id'])) {
                return $this->toError('封面上传失败,请重新设置');
            }
            $hasChange = true;
        }

        if ($artiRepo->editArticle($article)) $hasChange = true;

        if ($hasChange) {
            return redirect()->route('article_index');
        } else {
            return $this->toError('操作失败,请联系管理员');
        }
    }

    public function articleAudit(Request $request, ArtiRepo $artiRepo)
    {
        $id = $request->input('id', 0);
        if ($id <= 0) return $this->toJsonError('缺少参数');
        $audit_time = $request->input('audit_time', 0);
        if ($audit_time != 0) $audit_time .= ':00';// 处理秒

        $user = Session::get('user');

        if ($artiRepo->setAudit($id, $user['user_id'], $audit_time)) {
            return $this->toJsonSucess();
        } else {
            return $this->toJsonError('操作失败,请刷新页面或联系管理员');
        }
    }

    /**
     * 分类管理
     * @param Request $request
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sort(Request $request, SortRepo $sortRepo)
    {
        $list = $sortRepo->getAll();
        $sortList = $sortRepo->getSort();
        return view('admin.sort', [
            'list' => $list,
            'sort' => $sortList
        ]);
    }

    /**
     * 添加分类视图
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortAdd(SortRepo $sortRepo)
    {
        $sortList = $sortRepo->getSort(0);
        return view('admin.sort_add', ['sort' => $sortList]);
    }

    /**
     * 写入分类
     * @param Request $request
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sortAddPost(Request $request, SortRepo $sortRepo)
    {
        $name = $request->input('name', '');
        $shortName = $request->input('short_name', '');
        $chineseName = $request->input('chinese_name', '');
        $sortId = $request->input('sort_id', 0);
        $hotIs = $request->input('hot_is', 0);

        if ('' == $name || '' == $shortName) return $this->toError('分类名称或简称不能为空');

        if ($sortRepo->addData($name, $shortName, $chineseName, $sortId, $hotIs)) {
            return redirect()->route('sort');
        } else {
            return $this->toError('网络错误,请稍后重试');
        }
    }

    /**
     * 编辑分类视图
     * @param Request $request
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortEdit(Request $request, SortRepo $sortRepo)
    {
        $sortId = $request->input('id', 0);

        if (0 == $sortId) return $this->toError('缺少参数');
        $sort = $sortRepo->getOne($sortId);

        if (!$sort) return $this->toError('不存在的分类');

        $sortList = $sortRepo->getSort(0);
        return view('admin.sort_edit', [
            'list' => $sort,
            'sort' => $sortList
        ]);
    }

    /**
     * 更新分类
     * @param Request $request
     * @param SortRepo $sortRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sortEditPost(Request $request, SortRepo $sortRepo)
    {
        $id = $request->input('id', 0);

        if (0 == $id) return $this->toError('缺少参数s');

        $setData['name'] = $request->input('name', '');
        $setData['short_name'] = $request->input('short_name', '');
        $setData['chinese_name'] = $request->input('chinese_name', '');
        $setData['sort_id'] = $request->input('sort_id', 0);
        $setData['hot_is'] = $request->input('hot_is', 0);

        if ('' == $setData['name'] || '' == $setData['short_name']) return $this->toError('分类名称或简称不能为空');

        if ($sortRepo->editData($id, $setData)) {
            return redirect()->route('sort');
        } else {
            return $this->toError('网络错误,请稍后重试');
        }
    }

    public function getNovelCover($novelId, $ext)
    {
        return asset('/storage/cover/' . floor($novelId / 1000) . '/' . $novelId . '.' . $ext);
    }
}