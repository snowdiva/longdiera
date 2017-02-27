<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk">
    <title>{{ $list->title }} - {{ config('article.web_name') }}</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0">
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet">
    <script type="application/javascript" src="{{ asset('js/jquery.2.0.3.min.js') }}"></script>
    <script type="application/javascript" src="{{ asset('assets/js/diy/lang.js') }}"></script>
    <script type="application/javascript" src="{{ asset('js/layer_mobile/layer.js') }}"></script>
    <script src="http://sdk.talkingdata.com/app/h5/v1?appid=6CF2949909164E42A501D3B4EA096A5F&vn=ov1.0&vc=1.0"></script>
</head>
<body>
<header class="header">
    <div class="goback"><a href="javascript:history.back();"></a></div>
    <div class="head-title"></div>
    <div class="go-home"><a href="{{ url('/') }}">返回{{ config('article.web_name') }}</a></div>
</header>
<div class="detail-page">
    <header class="article-header">
        <div class="article-title"><h1>{{ $list->title }}</h1></div>
    </header>
    <div class="article-from">
        <span class="resource">{{ $list->author }}</span>
        <span class="resource" style="size: 10px;color: #ababab;margin-left: 5px;" >{{ $list->read }}阅读</span>
        @if('' != $list->source_url && "''" != $list->source_url)
        <span class="original">{{ $list->source_url }}</span>
        @endif
        <span class="time">{{ date('Y-m-d H:i', $list->audit_time) }}</span>
    </div>
    <article>
        <div class="article-content">
            {!! html_entity_decode($list->content) !!}

            <div class="art-extend">
                <!--如点赞addClass(select),若不喜欢addClass(on)-->
                {{--<span class="like selected">点赞</span>--}}
                {{--<span class="unlike on">不喜欢</span>--}}
            </div>
            {{--<h3 class="title"><span>其他推荐</span></h3>--}}
            @if(!empty($recommend))
            <div class="other">
                @foreach($recommend as $item)
                <a href="{{ $item->url }}" class="o_article">
                    <div class="o_head">
                        <span class="title">{{ $item->title }}</span>
                        {{--<span class="tag">{{ $item->tag }}</span>--}}
                    </div>
                    <div class="desc">
                        {{ $item->description }}
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <h3 class="title"><span>最新评论</span></h3>
            <div class="comment">

            </div>
            <button class="btn-more" data-act="comment_more"> 查看更多评论... </button>
        </div>
    </article>
</div>
<!--评论-->
<div class="comment-input" data-name="comment-cont">
    <div class="wrapper">
        <label class="icon-comment"></label>
        <input type="text" class="input" placeholder="我要说两句" value="" disabled>
    </div>
</div>
<div id="comment-content" style="display: none;">
    <div class="comment-wrapper m-t10">
        <form action="{{ url('/comment/add', ['news_id' => $list->id]) }}" method="post">
            <textarea class="cont-textarea" name="comment" placeholder="写评论..." data-act="comment-cont" id="pcontent"></textarea>
            <a href="javascript:;" class="fr" data-act="comment_btn" >发表</a>
        </form>
    </div>
</div>
<div style="overflow: hidden;width: 0;height: 0;">
    {!! require_once public_path('cs.php') !!}
    {!! '<img src="'._cnzzTrackPageView(1261128811).'" width="0" height="0"/>' !!}
</div>
<script>
    var commentUrl="{{ url('/comment', ['news_id' => $list->id]) }}"
    //评论弹窗
    $('[data-name=comment-cont]').click(function (event) {
        event.preventDefault()
        var _comment = $('#comment-content').html()
        layer.open({
            type: 1
            ,fixed:true
            ,content: _comment
            ,anim: 'up'
            ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: auto; padding:10px 0; border:none;-webkit-animation-duration: .5s; animation-duration: .5s;'
            ,end:function () {
                $('[data-name=face-img]').hide()
            }
        });
    })

    var initComment=function(){
        var thisBtn=$("[data-act=comment_more]")
        thisBtn.removeAttr('data-act')
        var oriBtnText=thisBtn.text()
        thisBtn.text("正在加载...")
        $.post(commentUrl,function(json){
            if(0==json.error){
                var _html=buildComment(json.data.data)
                $(".comment").append(_html)
                if(null!=json.data.next_page_url){
                    commentUrl=json.data.next_page_url
                    thisBtn.attr("data-act","comment_more")
                    thisBtn.text(oriBtnText)
                }else{
                    commentUrl=null
                    thisBtn.text("-已经没有更多评论了-")
                }
            }else{
                layer.open({
                    content:json.message,
                    time:3
                })
            }
        })
    }

    var buildComment=function(data){
        var htmls=''
        $.each(data,function(i,item){
            htmls+='<div class="comment-item">\
                        <div class="avatar"><img src="'+item.face+'"></div>\
                        <div class="nameBar">\
                            <span class="nicname">'+item.username+'</span>\
                        </div>\
                        <div class="time"><span class="t">'+item.post_time+'</span><span>'+item.location+'</span></div>\
                        <div class="contentBox">\
                            <a class="contentInfo">'+item.comment+'</a>\
                        </div>\
                   </div>'
        })
        return htmls
    }

    $(function(){
        // page init
        $("img").each(function(i){
            $(this).width('100%')
            $(this).parent().attr('text-indent', 0)
//            $(this).height($(this).width())
        })
        // comment init
        initComment()



        $("body").on("click","[data-act=comment_more]",function(e){
            e.stopPropagation()
            var thisBtn=$(this)
            thisBtn.removeAttr('data-act')
            var oriBtnText=thisBtn.text()
            thisBtn.text("正在加载...")
            $.post(commentUrl,function(json){
                if(0==json.error){
                    var _html=buildComment(json.data.data)
                    $(".comment").append(_html)
                    if(null!=json.data.next_page_url){
                        commentUrl=json.data.next_page_url
                        thisBtn.attr("data-act","comment_more")
                        thisBtn.text(oriBtnText)
                    }else{
                        commentUrl=null
                        thisBtn.text("-已经没有更多评论了-")
                    }
                }else{
                    layer.open({
                        content:json.message,
                        time:3
                    })
                }
            })
        })

        $("body").on("click","[data-act=comment_btn]",function(e){
            e.stopPropagation()
            var loadingMask=layer.open({type:2})
            var _url=$(this).parents('form').attr('action')
            $.post(_url,$(this).parents('form').serialize(),function(json){
                layer.close(loadingMask)
                if(0==json.error){
                    layer.open({
                        content:json.message,
                        btn:NameSpace.button.ok,
                        shadeClose:false,
                        yes:function(){
                            window.location.reload()
                        }
                    })
                }else{
                    layer.open({
                        content:json.message,
                        time:3
                    })
                }
            }).error(function(json){
                layer.close(loadingMask)
            })
        })
    })
</script>
</body>
</html>
