<?php

namespace App\Http\Controllers;

use App\Repositories\ArtiRepo;
use App\Repositories\RecoRepo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class ArticleController extends CommonController
{
    public function show(ArtiRepo $artiRepo, RecoRepo $recoRepo)
    {
        $articleId = intval(Request::route('article_id'));
        if ($articleId <= 0) return redirect()->route('home');

        if (!Cache::has('news_' . $articleId)) {
            $article = $artiRepo->getFontArticle($articleId);
            if (!$article) return redirect()->route('home');

            $recommend = $recoRepo->getFontRecommendById($article->reco_group_id);

            $article->read = $this->readFormat($article->read);

            $htmls = view('article', [
                'list' => $article,
                'recommend' => $recommend
            ])->__toString();

            Cache::put('news_' . $articleId, $htmls, Config::get('article.cache_time'));
        }

        // 添加阅读次数
        $artiRepo->addRead($articleId);

        return Cache::get('news_' . $articleId);
    }

    protected function readFormat(int $size)
    {
        if (0 == $size || !$size) return 0;

        switch ($size) {
            case $size / 10000 >= 1 :
                $size = floor($size / 10000) . '万+';
                break;
            default :
        }

        return $size;
    }

    public function articleTest()
    {
//        dump('test start');


    }
}
