<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RecoRepo;
use App\Repositories\SortRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecommendController extends AdminController
{
    public function index(RecoRepo $recoRepo, SortRepo $sortRepo)
    {
        $list = $recoRepo->getData();

        return view('admin.recommend', ['list' => $list]);
    }

    public function recommendAdd()
    {
        return view('admin.recommend_add');
    }

    public function recommendAddPost(Request $request, RecoRepo $recoRepo)
    {
        $this->_filter($request->all());

        $title = $request->input('title');
        $description = $request->input('description');
        $url = $request->input('url');
        $tag = $request->input('tag', '');

        $result = $recoRepo->addData($title, $description, $url, $tag);

        if (!$result) return $this->toError('操作失败');

        return redirect()->route('recommend');
    }

    public function recommendEdit(Request $request, RecoRepo $recoRepo)
    {
        $recommend_id = $request->input('id', 0);
        if (0 == $recommend_id) return $this->toError('缺少参数');

        $list = $recoRepo->getOne($recommend_id);

        return view('admin.recommend_edit', ['list' => $list]);
    }

    public function recommendEditPost(Request $request, RecoRepo $recoRepo)
    {
        $id = $request->input('id', 0);
        if ($id <= 0) return $this->toError('缺少参数');

        $this->_filter($request->all());

        $data['title'] = $request->input('title');
        $data['description'] = $request->input('description');
        $data['url'] = $request->input('url');
        $data['tag'] = $request->input('tag', '');

        $result = $recoRepo->editData($id, $data);

        if (!$result) return $this->toError('操作失败');

        return redirect()->route('recommend');
    }

    public function groupIndex(RecoRepo $recoRepo, SortRepo $sortRepo)
    {
        $list = $recoRepo->getGroup();
        $sortList = $sortRepo->getSort();

        return view('admin.recommend_group', [
            'list' => $list,
            'sort' => $sortList
        ]);
    }

    public function groupAdd(SortRepo $sortRepo)
    {
        $sortList = $sortRepo->getSort();

        return view('admin.recommend_group_add', ['sort' => $sortList]);
    }

    public function groupAddPost(Request $request, RecoRepo $recoRepo)
    {
        $this->_filterGroup($request->all());
        $name = $request->input('name', '');
        $recommend_id = $request->input('recommend_id', '');
        $sort_id = $request->input('sort_id', 0);
        // TODO::验证sort_id是否存在

        $recommendId = explode(',', ltrim(trim($recommend_id, ','), ','));
        if (count($recommendId) < 1 || count($recommendId) > 6) return $this->toError('推荐组内的推荐语只能在1~6个之间');

        $result = $recoRepo->addGroup($name, $recommend_id, $sort_id);

        if (!$result) return $this->toError('操作失败');

        return redirect()->route('recommend_group');
    }

    public function groupEdit(Request $request, RecoRepo $recoRepo, SortRepo $sortRepo)
    {
        $id = $request->input('id', 0);
        if (0 == $id) return $this->toError('缺少参数');

        $list = $recoRepo->getGroupOne($id);

        $sortList = $sortRepo->getSort();

        return view('admin.recommend_group_edit', [
            'list' => $list,
            'sort' => $sortList
        ]);
    }

    public function groupEditPost(Request $request, RecoRepo $recoRepo)
    {
        $id = $request->input('id', 0);
        if (0 == $id) return $this->toError('缺少参数');

        $this->_filterGroup($request->all());
        $data['name'] = $request->input('name', '');
        $data['recommend_id'] = $request->input('recommend_id', '');
        $data['sort_id'] = $request->input('sort_id', 0);
        // TODO::验证sort_id是否存在

        $recommendId = explode(',', ltrim(trim($data['recommend_id'], ','), ','));
        if (count($recommendId) < 1 || count($recommendId) > 6) return $this->toError('推荐组内的推荐语只能在1~6个之间');

        $result = $recoRepo->editGroup($id, $data);

        if (!$result) return $this->toError('操作失败');

        return redirect()->route('recommend_group');
    }

    private function _filter($request)
    {
        $validator = Validator::make($request, [
            'title' => 'required|max:50',
            'url' => 'required|max:100',
            'description' => 'required|max:200',
//            'tag' => 'max:20'
        ],[
            'title.required' => '必须填写推荐标题',
            'title.max' => '标题不能超过15个字',
            'url.required' => '跳转连接必须填写',
            'url.max' => '跳转连接过长,请使用短连接',
            'description.required' => '必须填写推荐语',
            'description.max' => '推荐语不能超过100字',
//            'tag.max' => '标签不能超过6个字'
        ]);

        if ($validator->fails()) {
            return $this->toError($validator->errors()->all()[0]);
        }
    }

    private function _filterGroup($request)
    {
        $validator = Validator::make($request, [
            'name' => 'required|max:50',
            'recommend_id' => 'required|max:400'
        ],[
            'name.required' => '必须填写推荐组名称',
            'name.max' => '标题不能超过20个字',
            'recommend_id.required' => '推荐标语不能为空',
            'recommend_id.max' => '推荐标语超出限制数量'
        ]);

        if ($validator->fails()) {
            return $this->toError($validator->errors()->all()[0]);
        }
    }
}