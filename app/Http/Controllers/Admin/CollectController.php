<?php

namespace App\Http\Controllers\Admin;

class CollectController extends AdminController
{
    /**
     * 采集器管理
     * @param Request $request
     * @param CollRepo $collRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collect(Request $request, CollRepo $collRepo)
    {
        $list = $collRepo->getAll();
        return view('admin.collect', ['list' => $list]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collectAdd()
    {
        return view('admin.collect_add');
    }

    /**
     * 采集器写入视图
     * @param Request $request
     * @param CollRepo $collRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function collectAddPost(Request $request, CollRepo $collRepo)
    {
        $collect = $request->all();

        if ('' == $collect['name']) return $this->toError('名称不能为空');
        if ('' == $collect['rule']) return $this->toError(('规则不能为空'));

        if ($collRepo->addOneCollect($collect)) {
            return redirect()->route('collect');
        } else {
            return $this->toError('操作失败,请联系管理员');
        }
    }

    /**
     * 获取一个采集器明细
     * @param Request $request
     * @param CollRepo $collRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collectGet(Request $request, CollRepo $collRepo)
    {
        $id = $request->input('id', 0);

        if (0 == $id) return $this->toError('缺少参数');

        $collect = $collRepo->getOne($id);

        if (!$collect) return $this->toError('不存在的采集器');
        return view('admin.collect_get_one', ['list' => $collect]);
    }

    public function collect_test()
    {
        // 测试图片获取

        $rule = [
            'title' => ['.artCon>h1', 'text'],
            'release_time' => ['span.pubTime', 'text'],
            'content' => ['.artText>table>tbody>tr>td', 'html']
        ];
//        $thisContent = QueryList::Query('http://emotion.pclady.com.cn/165/1652027.html', $rule, '.artCon', 'utf-8', 'gb2312');
        $thisContent = QueryList::Query('http://emotion.pclady.com.cn/165/1652027.html', $rule, '.artCon', 'utf-8', 'gb2312')->getData(function($item){
            // 图片抓取到本地
            $item['content'] = QueryList::run('DImage', [
                'content' => $item['content'],
                'image_path' => 'public/storage/images/' . date('Ym'),
                'www_root' => base_path(),
                'attr' => ['src']
            ]);
            return $item;
        });

        dump($thisContent);die;

        // 获取列表
        $getListCount = 1;
        $getListUrl = 'http://www.pclady.com.cn/3g/other/emotion/';
        $articleList = [];
        $i=0;
        do{
            if ($i > 0) {
                $thisUrl = $getListUrl . 'index_' . $i . '.html';
            } else {
                $thisUrl = $getListUrl;
            }
//            $thisList = QueryList::Query($thisUrl, [
//                'url' => ['.i-title>a', 'href']
//            ]);
            $thisList = $this->getApiCurl($getListUrl);

            $articleList = array_merge($articleList, $thisList['articleList']);
            $i++;
        }while($getListCount >= $i);

//        dump($articleList);die;
        // 获取单篇文章内容
        // 3g站抓取规则
//        $rule = [
//            'title' => ['.u-title', 'text'],
//            'release_time' => ['.u-artInfo>span', 'text'],
//            'content' => ['.m-txtCont', 'html']
//        ];
        // pc站抓取规则
        $rule = [
            'title' => ['.artCon>h1', 'text'],
            'release_time' => ['span.pubTime', 'text'],
            'content' => ['.artText>table>tbody>tr>td', 'html']
        ];

        $setData = [];
        foreach ($articleList as $values) {
            $thisContent = QueryList::Query($values['main_url'], $rule, '', 'utf-8', 'gb2312')->getData(function($item){
                // 图片抓取到本地
                $item['content'] = QueryList::run('DImage', [
                    'content' => $item['content'],
                    'image_path' =>  '/storage/images/' . date('Ym'),
                    'www_root' => public_path(),
                    'attr' => ['src']
                ]);
                return $item;
            });

//            $thisContent = QueryList::Query($values['main_url'], $rule);
//            dump($thisContent);die;
            dump('正在处理《' . $thisContent[0]['title'] . '》,请稍后...');

            $setData[] = [
                'title' => $thisContent[0]['title'],
                'author' => '绝对隐私',
                'sort_id' => 1,
                'type' => 3,
                'cover' => $values['wapFirstPic'],
                'source_url' => $values['wap_3g_url'],
                'release_time' => strtotime($thisContent[0]['release_time'] . ':00'),
                'content' => str_replace(["\n", "\r", "\t"], "", htmlspecialchars($thisContent[0]['content'])),
                'read' => 0,
                'like' => 0,
                'unlike' => 0,
                'create_time' => time(),
                'collect_id' => 1,
                'auditor' => 0,
                'audit_time' => 0,
                'status' => 0
            ];
        }
        dump($setData);die;

        // 写入数据库
        if (DB::table('sc_news')->insert($setData)) {
            return true;
        } else {
            return false;
        }
    }

    protected function getApiCurl($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        $output = curl_exec($curl);

        curl_close($curl);

        // 过去回调函数标识
        $out = str_replace(['callback(', ')'], '', $output);
        $out = json_decode($out, 1);

        return $out;
    }
}