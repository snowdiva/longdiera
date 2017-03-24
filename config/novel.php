<?php
/**
 * 文章相关配置
 */
return [
    'web_name' => '热点',
    // 页面缓存
    'cache_time' => 5, // 单位是[分钟]
    // 数据库查询缓存
    'table_time' => 2, // 单位是[分钟]
    // 评论发言间隔时间
    'comment_rate_time' => 30, // 单位是[秒]

    // 文章热推相关配置
    'hot' => [
        'type' => ['普通推荐', '顶部轮播图推荐', '专栏推荐']
    ],
    'news' => [
        'type' => ['未知', '本站原创', '引用文章', '采集文章']
    ],
    // 小说封面地址
    'cover_temp_path' => 'storage/cover_temp/',
    'cover_path' => 'storage/cover/',
    // 封面存储大小
    'cover_weight' => 300,
    'cover_height' => 400,
];