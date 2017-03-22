<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\ArtiRepo;
use App\Repositories\SortRepo;
use Illuminate\Http\Request;

class SortController extends CommonController
{
    public function getSortList (Request $request, SortRepo $sortRepo)
    {
        // 处理查询条件
        $where = [];
        $whereObj = $request->input();
        if (isset($whereObj['status'])) {
            if ($whereObj['status'] != -1) {
                $where[] = ['status', '=', $whereObj['status']];
            }
        } else {
            $where[] = ['status', '=', 1];
        }

        $list = $sortRepo->getData($where);
        return $this->returnJson($list);
    }

    public function newSort (Request $request, SortRepo $sortRepo)
    {
        $data = [
            'name' => $request->input('name', ''),
            'short_name' => $request->input('short_name', ''),
            'chinese_name' => $request->input('chinese_name', ''),
            'sort_id' => $request->input('sort_id', 0),
            'hot_is' => $request->input('hot_is', 0),
            'status' => $request->input('status', 1),
            'create_at' => time()
        ];
        if ($data['name'] === '' || $data['short_name'] === '' || $data['chinese_name'] === '') return $this->error('中英文名及简称不能为空');
        if ($sortRepo->checkRepitition($data['name'], $data['short_name'], $data['chinese_name'])) return $this->error('分类的英文、中文、简写名称重复,不可新建分类');

        $newId = $sortRepo->insertData($data);

        if ($newId) {
            $data['id'] = $newId;
            return $this->returnJson($data);
        } else {
            return $this->error('创建分类错误');
        }
    }

    public function editSort (Request $request, SortRepo $sortRepo)
    {
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少操作分类的编号');
        $data = [
            'name' => $request->input('name', ''),
            'short_name' => $request->input('short_name', ''),
            'chinese_name' => $request->input('chinese_name', ''),
            'sort_id' => $request->input('sort_id', 0),
            'hot_is' => $request->input('hot_is', 0),
            'status' => $request->input('status', 1)
        ];
        if ($data['name'] === '' || $data['short_name'] === '' || $data['chinese_name'] === '') return $this->error('中英文名及简称不能为空');
        if ($sortRepo->checkRepitition($data['name'], $data['short_name'], $data['chinese_name'], $id)) return $this->error('分类的英文、中文、简写名称重复,不可新建分类');

        $result = $sortRepo->updateData($data, $id);

        if ($result) {
            $data['id'] = $id;
            return $this->returnJson($data);
        } else {
            return $this->error('修改分类操作失败');
        }
    }

    public function deleteSort (Request $request, SortRepo $sortRepo, ArtiRepo $artiRepo)
    {
        // 参数验证
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少分类编号');
        $sortInfo = $sortRepo->checkSortById($id);
        if (!$sortInfo) return $this->error('提交分类不存在');

        if ($sortInfo->status === 2) {
            if ($sortRepo->deleteData($id, 1)) return $this->success('已经彻底删除');
        } else {
            if ($sortRepo->checkSortUsed($id)) return $this->error('当前分类已分配有小说,请先接触与小说的绑定');
            if ($result = $sortInfo->deleteData($id)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }
}