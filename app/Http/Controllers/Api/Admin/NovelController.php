<?php

namespace App\Http\Controllers\Api\Admin;

use App\Repositories\SortRepo;
use App\Repositories\NovelRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        $data = [
            'title' => $request->input('title', ''),
            'chinese_title' => $request->input('chinese_title', ''),
            'intro' => $request->input('intro', ''),
            'sort_id' => $request->input('sort_id', 0),
            'author' => $request->input('author', ''),
            'cover_ext' => $request->input('cover_ext', ''),
            'publish_status' => $request->input('publish_status', 0),
            'rate' => $request->input('rate', 1),
            'chapter_count' => 0,
            'size' => 0,
            'create_at' => time(),
            'publish_at' => 0,
            'auditor_id' => 0,
            'status' => 0
        ];
        $coverName = $request->input('cover_name', '');
        if ($data['title'] === '' || $data['chinese_title'] === '') return $this->error('中文或者英文书名不能为空');
        if ($data['author'] === '') $data['author'] = 'unknown';

        $newId = $novelRepo->insertData($data);

        if (!$newId) return $this->error('服务器或者网络君闹情绪了,没有创建成功');

        if ($coverName !== '' && !$this->moveCover($coverName, $newId, $data['cover_ext'])) return $this->success('封面上传失败,请在编辑中重新上传');

        return $this->success('创建新书成功');
    }

    public function editNovel (Request $request, NovelRepo $novelRepo, SortRepo $sortRepo)
    {
        $id = $request->input('id', 0);
        if ($id === 0) return $this->error('缺少修改的小说编号');

        $isShowNovel = $request->input('get', '');
        if ($isShowNovel !== '') {
            $data['novel'] = $novelRepo->getOneData($id);
            $data['sort_list'] = $sortRepo->getSortOptions();
            return $this->returnJson($data);
        }

        $data = [
            'title' => $request->input('title', ''),
            'chinese_title' => $request->input('chinese_title', ''),
            'intro' => $request->input('intro', ''),
            'sort_id' => $request->input('sort_id', 0),
            'author' => $request->input('author', ''),
            'rate' => $request->input('rate', 1),
            'cover_ext' => $request->input('cover_ext', ''),
            'publish_status' => $request->input('publish_status', 0),
            'status' => 0
        ];
        $coverName = $request->input('cover_name', '');
        if ($data['title'] === '' || $data['chinese_title'] === '') return $this->error('中文或者英文书名不能为空');
        if ($data['author'] === '') $data['author'] = 'unknown';

        $result = $novelRepo->updateData($data, $id);

        if ($coverName !== '') {
            $changedCover = $this->moveCover($coverName, $id, $data['cover_ext']);
            if (!$changedCover) return $this->success('封面上传失败,请在编辑中重新上传');
        } else {
            $changedCover = false;
        }

        // 如果数据和图片都修改失败就报错
        if (!$result && !$changedCover) return $this->error('服务器或者网络君闹情绪了,没有修改成功');

        return $this->success('修改小说成功');
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
        if (!$hasNovel) return $this->error('提交小说不存在');

        if ($hasNovel->status === 99) {
            if ($hasNovel->chapter_count > 0) return $this->error('该小说还包含' . $hasNovel->chapter_count . '篇章节,不能清退');
            if ($novelRepo->deleteData($id, $hasNovel->cover_ext, 1)) return $this->success('该书已彻底清退');
        } else {
            if ($result = $novelRepo->deleteData($id, $hasNovel->cover_ext)) return $this->success('已删除');
        }

        return $this->error('没有任何改变');
    }

    /**
     * 处理临时上传的小说封面
     * @param Request $request
     * @param NovelRepo $novelRepo
     * @return mixed
     */
    public function uploadNovelCover (Request $request)
    {
        $coverFile = $request->file('cover');
        if (!$coverFile->isValid()) return $this->error($coverFile->getErrorMessage());

        $newName = md5(time()) . '.' . $coverFile->getClientOriginalExtension();
        try {
            $coverFile->move(public_path(config('cover.cover_temp_path')), $newName);
        } catch (FileException $e) {
            return $this->error($e->getMessage());
        }

        $data = [
            'name' => $newName,
            'url' => env('APP_URL') . '/' . config('novel.cover_temp_path') . $newName,
            'cover_ext' => '.' . $coverFile->getClientOriginalExtension()
        ];

        return $this->returnJson($data);
    }

    /**
     * 从临时文件夹中将封面移动到正常位置
     * @param $coverName
     * @param $id
     */
    public function moveCover ($coverName, $id, $ext)
    {
        // 验证新路径是否存在
        $coverPath = public_path(config('novel.cover_path')) . floor($id/1000);
        if (!is_dir($coverPath)) File::makeDirectory($coverPath, 0777, true, true);
        // 验证临时封面文件是否存在
        $oldCoverName = public_path(config('novel.cover_temp_path')) . '/' . $coverName;
        if (!is_file($oldCoverName)) return $this->error('提交的封面图片已被删除,请稍后重新上传封面');
        // 处理两种封面大小并存储到正式封面位置:cover/
        Image::make($oldCoverName)->resize(config('novel.cover_weight'), config('novel.cover_height'))->save($coverPath . '/' . $id  . $ext);
        Image::make($oldCoverName)->resize(config('novel.cover_weight')/2, config('novel.cover_height')/2)->save($coverPath . '/' . $id . 's' . $ext);

        return true;
    }
}