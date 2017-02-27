<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CommRepo
{
    protected $commentTable = 'sc_comment';

    public function getFontComment($newsId, $num = 6)
    {
        $list = DB::table($this->commentTable)
            ->where('news_id', $newsId)
            ->where('status', 1)
            ->orderBy('create_time', 'DESC')
            ->paginate($num);

        if (!$list) return false;

        return $list;
    }

    public function addComment($newsId, $userId, $username, $avatar, $ip, $location, $comment)
    {
        $result = DB::table($this->commentTable)
            ->insert([
                'news_id' => $newsId,
                'user_id' => $userId,
                'username' => $username,
                'avatar' => $avatar,
                'ip' => $ip,
                'location' => $location,
                'comment' => $comment,
                'create_time' => time(),
                'status' => 1
            ]);

        if (!$result) return false;

        return true;
    }
}