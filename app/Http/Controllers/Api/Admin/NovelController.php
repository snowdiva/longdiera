<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\SortRepo;
use App\Repositories\NovelRepo;
use Illuminate\Http\Request;

class NovelController extends CommonController
{
    protected $novelTable = 'novel';

    public function getNovelList (Request $request, NovelRepo $novelRepo, SortRepo $sortRepo)
    {
        $where = [];
        $whereObj = $request->input();
        if (isset($whereObj['field']) && isset($whereObj['value']) && $whereObj['value'] != '') {
            $where[] = [$this->novelTable . '.' . $whereObj['field'], 'like', '%'.$whereObj['value'].'%'];
        }
        if (isset($whereObj['sort_id']) && $whereObj['sort_id'] != -1) $where[] = [$this->novelTable . '.sort_id', '=', $whereObj['sort_id']];
        if (isset($whereObj['status']) && $whereObj['status'] != -1) $where[] = [$this->novelTable . '.status', '=', $whereObj['status']];

        $list = $novelRepo->getData($where);
        $list['sort_list'] = $sortRepo->getSortOptions();

        return $this->returnJson($list);
    }

    public function newNovel (Request $request, NovelRepo $novelRepo)
    {

    }

    public function editNovel (Request $request, NovelRepo $novelRepo)
    {

    }

    public function auditNovel (Request $request, NovelRepo $novelRepo)
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

        if ($novelRepo->setAudit($id, $status, $publish_at, $userInfo->id)) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败,请稍后重试');
        }
    }

    public function deleteNovel (Request $request, NovelRepo $novelRepo)
    {
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少操作编号');
        $hasNovel = $novelRepo->checkNovelById($id);
        if (!$hasNovel) return $this->error('提交用户不存在');

        if ($hasNovel->status === 99) {
            //
            // TODO:: 删除小说章节逻辑及对应文本文件、封面逻辑
            //
            if ($novelRepo->deleteData($id, 1)) return $this->success('该书已彻底清退');
        } else {
            if ($result = $novelRepo->deleteData($id)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }
}