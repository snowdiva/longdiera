<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RecoRepo
{
    protected $recommandTable = 'sc_recommend';
    protected $recommendGroupTable = 'sc_recommend_group';

    public function getData($sortId = 0)
    {
        if ($sortId > 0) {
            $list = DB::table($this->recommandTable)
                ->where('sort_id', $sortId)
                ->orderBy('id', 'DESC')
                ->paginate(15);
        } else {
            $list = DB::table($this->recommandTable)
                ->orderBy('id', 'DESC')
                ->paginate(15);
        }

        return $list;
    }

    public function getOne($id)
    {
        $list = DB::table($this->recommandTable)
            ->where('id', $id)
            ->first();

        if (!$list) return false;

        return $list;
    }

    public function addData($title, $description, $url, $tag = '')
    {
        $result = DB::table($this->recommandTable)
            ->insert([
                'title' => $title,
                'description' => $description,
                'url' => $url,
                'tag' => $tag,
                'create_time' => time()
            ]);

        if (!$result) return false;

        return true;
    }

    public function editData($id, $data = [])
    {
        if (empty($data)) return false;

        $result = DB::table($this->recommandTable)
            ->where('id', $id)
            ->update($data);

        if (!$result) return false;

        return true;
    }

    public function getGroup($sort_id = -1)
    {
        if ($sort_id >= 0) {
            $list = DB::table($this->recommendGroupTable)
                ->where('sort_id', $sort_id)
                ->orderBy('create_time', 'DESC')
                ->paginate(15);
        } else {
            $list = DB::table($this->recommendGroupTable)
                ->orderBy('create_time', 'DESC')
                ->paginate(15);
        }

        return $list;
    }

    public function getGroupOne($id)
    {
        $list = DB::table($this->recommendGroupTable)
            ->where('id', $id)
            ->first();

        return $list;
    }

    public function addGroup($name, $recommendId, $sortId)
    {
        $result = DB::table($this->recommendGroupTable)
            ->insert([
                'name' => $name,
                'recommend_id' => $recommendId,
                'sort_id' => $sortId,
                'create_time' => time()
            ]);

        if (!$result) return false;

        return true;
    }

    public function editGroup($id, $data = [])
    {
        if (empty($data)) return false;

        $result = DB::table($this->recommendGroupTable)
            ->where('id', $id)
            ->update($data);

        if (!$result) return false;

        return true;
    }

    public function getFontRecommendById($id)
    {
        $recommendIds = DB::table($this->recommendGroupTable)
            ->select('recommend_id')
            ->where('id', $id)
            ->first();

        if (!$recommendIds) return false;
        $recommendIdsArr = explode(',', $recommendIds->recommend_id);

        $list = DB::table($this->recommandTable)
            ->select('title', 'description', 'url', 'tag')
            ->whereIn('id', $recommendIdsArr)
            ->get();

        if (!$list) return false;

        return $list;
    }
}