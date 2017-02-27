<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>{{ config('article.web_name') }}</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0">
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('plugin/dropload/dropload.css') }}" type="text/css" rel="stylesheet">
    <script type="application/javascript" src="{{ asset('js/jquery.2.0.3.min.js') }}"></script>
    <script type="application/javascript" src="{{ asset('plugin/dropload/dropload.min.js') }}"></script>
    <script src="http://sdk.talkingdata.com/app/h5/v1?appid=6CF2949909164E42A501D3B4EA096A5F&vn=ov1.0&vc=1.0"></script>
</head>
<body>
<header class="header">
    <div class="goback"><a href="javascript:history.back();"></a></div>
    <div class="head-title">{{ config('article.web_name') }}</div>
</header>
<div class="nav">
    @foreach($sort as $item)
    <span @if($this_sort == $item->id)class="active"@endif><a href="{{ url('/', ['sort_id' => $item->id]) }}">{{ $item->short_name }}</a></span>
    @endforeach
</div>
<div class="content_list">
    @foreach($list['data'] as $item)
        <section>
        @if(2 == $item->type)
            <a href="{{ $item->source_url }}" class="link">
        @else
            <a href="{{ url('/article', ['article_id' => $item->id]) }}" class="link">
        @endif
            @if(count($item->cover) >= 3)
                <p class="title">{{ $item->title }}</p>
                <div class="img-content reset-img-box" style="overflow: hidden;">
                    <div class="img-wrap">
                        <img src="{{ url('/') }}/storage/images/{{ $item->cover[0] }}">
                    </div>
                    <div class="img-wrap">
                        <img src="{{ url('/') }}/storage/images/{{ $item->cover[1] }}">
                    </div>
                    <div class="img-wrap">
                        <img src="{{ url('/') }}/storage/images/{{ $item->cover[2] }}">
                    </div>
                </div>
                <div class="info">
                    <div>
                        <span class="from">{{ $item->author }}</span>
                        {{--<span class="comment">367条评论</span>--}}
                        <span class="time">{{ date('Y-m-d H:i', $item->audit_time) }}</span>
                    </div>
                </div>
            @else
                <div class="art-img  reset-img-box">
                    <img src="{{ url('/') }}/storage/images/{{ $item->cover[0] }}">
                </div>
                <div class="desc">
                    <p class="title">{{ $item->title }}</p>
                    <div class="info">
                        <div>
                            <span class="from">{{ $item->author }}</span>
                            {{--<span class="comment">{{ $item->read }}人阅读</span>--}}
                            <span class="time">{{ date('Y-m-d H:i', $item->audit_time) }}</span>
                        </div>
                    </div>
                </div>
        @endif
            </a>
        </section>
    @endforeach
</div>
<div style="overflow: hidden;width: 0;height: 0;">
    {!! require_once public_path('cs.php') !!}
    {!! '<img src="'._cnzzTrackPageView(1261128811).'" width="0" height="0"/>' !!}
</div>
</body>
<script type="application/javascript">
var _thisSort="{{ $this_sort }}"
var _thisPage=1
var _lastPage={{ $list['last_page'] }}
var _host="{{ url('/') }}"
var _url="{{ url('/get_index') }}"
var buildSection=function(data){
    var _html=''
    $.each(data.data,function(i,item){

        _html+='<section>'
        if(item.type==2){
            _html+='<a href="'+item.source_url+'" class="link">'
        }else{
            _html+='<a href="'+_host+'/article/'+item.id+'" class="link">'
        }
        if(item.cover.length >=3){

            _html+='<p class="title">'+item.title+'</p>\
                    <div class="img-content reset-img-box">\
                    <div class="img-wrap">\
                    <img src="'+item.cover[0]+'">\
                    </div>\
                    <div class="img-wrap">\
                    <img src="'+item.cover[1]+'">\
                    </div>\
                    <div class="img-wrap">\
                    <img src="'+item.cover[2]+'">\
                    </div>\
                    </div>\
                    <div class="info">\
                    <div>\
                    <span class="from">'+item.author+'</span>\
                    <span class="time">'+item.audit_time+'</span>\
                    </div>\
                    </div>'
        }else{
            _html+='<div class="art-img reset-img-box">\
                    <img src="'+item.cover[0]+'">\
                    </div>\
                    <div class="desc">\
                    <p class="title">'+item.title+'</p>\
                    <div class="info">\
                    <div>\
                    <span class="from">'+item.author+'</span>\
                    <span class="time">'+item.audit_time+'</span>\
                    </div>\
                    </div>\
                    </div>'
        }
        _html+='</a></section>'
    })
    return _html
}
var resetImage=function(){
    $("img").each(function(i){
//        var imgWidth=$(this).width()
//        $(this).height(parseInt(imgWidth)*2/3)
//        $(this).parents('.reset-img-box').height(parseInt(imgWidth)*2/3)
//        $(this).parents('.img-content').height(parseInt(imgWidth)*2/3)
    })
}
$(function(){
    resetImage()

    $(".content_list").dropload({
        scrollArea : window,
        loadDownFn : function(me){
            if(_thisPage==_lastPage){
                me.noData(true)
                me.resetload()
            }else{
                console.log(_thisPage)
                _thisPage++
                $.post(_url,{"page":_thisPage,"sort_id":_thisSort},function(json){
                    if(0==json.error){
                        var _data=eval("("+json.data+")")
                        // TODO::累加文章列表
                        var section=buildSection(_data)
                        $(".dropload-down").before(section)
                        resetImage()
                        if(null==_data.next_page_url){me.noData(true)}
                    }else{
                        console.log(json.msg)
                    }
                    me.resetload()
                },"json")
            }
        }
    })
})
</script>
</html>
