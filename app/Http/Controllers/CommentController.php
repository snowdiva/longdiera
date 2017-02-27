<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Repositories\CommRepo;
use Illuminate\Support\Facades\Session;
use Zhuzhichao\IpLocationZh\Ip;

class CommentController extends CommonController
{
    public function index(Request $request, CommRepo $commRepo, UserRepo $userRepo)
    {
        $newsId = $request->route('news_id', 0);
        if (0 == $newsId) return $this->error('缺少参数');

        $list = $commRepo->getFontComment($newsId);

        if (!$list) $list = [];

        foreach ($list as $item) {
            $item->post_time = $this->getPostTime($item->create_time);
            $item->face = $userRepo->getFace($item->user_id, $item->avatar);
        }

        return $this->toJson($list);
    }

    public function commentAdd(Request $request, CommRepo $commRepo)
    {
        // 评论频率验证
        if (!$this->_filterRate()) return $this->error('您的评论太快了,喝杯茶休息休息~');

        $news_id = $request->route('news_id', 0);
        if (0 == $news_id) return $this->error('缺少参数');
        $comment = $request->get('comment', '');
        if ('' == $comment) return $this->error('您没有任何评论~');

        $comment = $this->_filterComment($comment);

        if (mb_strlen($comment) > 100) return $this->error('评论最多使用100个字');

        $user = Session::get('user');
        $ip = $request->ip();
        $ipLocation = Ip::find($ip);
        $location = implode('', $ipLocation);
        if (!$user) {
            $user_id = 0;
            $username = $ipLocation[1] . $ipLocation[2] . '网友';
            $avatar = 0;
        } else {
            $user_id = $user['user_id'];
            $username = $user['username'];
            $avatar = isset($user['avatar']) ? $user['avatar'] : 0;
        }

        $result = $commRepo->addComment($news_id, $user_id, $username, $avatar, $ip, $location, $comment);

        if (!$result) return $this->error('操作失败');

        // 刷新最后评论时间
        if (Session::has('comment_time')) Session::forget('comment_time');
        Session::put('comment_time', time());
        return $this->success('感谢您的评论');
    }

    /**
     * 获取发送时间距离当前的时间
     * @param $time
     */
    protected function getPostTime($time)
    {
        $elapse = time() - intval($time);

        switch($elapse){
            case $elapse > 86400 :
                $returnTime = date('Y年m月d日 H:i', $time);
                break;
            case $elapse > 3600 :
                $returnTime = floor($elapse/3600) . '小时前';
                break;
            case $elapse > 60 :
                $returnTime = floor($elapse/60) . '分钟前';
                break;
            default:
                $returnTime = $elapse . '秒前';
        }

        return $returnTime;
    }

    private function _filterComment($comment)
    {
        // 防护性过滤
        $comment = htmlspecialchars($comment, 1);

        // 敏感词汇过滤
        $replaceWord = Config::get('comment', []);
        $comment = str_replace($replaceWord['sensitive'], '**', $comment);

        // 引导类过滤
        $comment = preg_replace('/\d{5,13}/', '**', $comment);
        $comment = str_replace($replaceWord['abduction'], '**', $comment);

        // 返回结果
        return $comment;
    }

    private function _filterRate()
    {
        $hasComment = Session::get('comment_time', 0);
        if (0 == $hasComment) return true;

        $commentIntval = time() - intval($hasComment);
        // 30秒评论间隔
        if ($commentIntval <= config('article.comment_rate_time')) {
            return false;
        } else {
            return true;
        }
    }
}