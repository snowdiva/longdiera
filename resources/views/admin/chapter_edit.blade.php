@extends('admin.base')

@section('title', '手动修改章节')

@include('UEditor::head')

@section('breadcrumb')
    <li>
        <a href="/webmaster/chapter/index"> 小说章节管理 </a>
    </li>
    <li class="active"> 手动修改章节 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>为小说修改一篇章节。
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-header text-primary"> 为《{{ $list->novel_title }}》(《({{ $list->novel_chinese_title }})》)手动添加章节 </h3>

            <form action="/webmaster/chapter/edit" class="form-horizontal" method="post">

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">标题(英文):</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="title" class="col-xs-12 col-sm-6" value="{{ $list->title }}" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">标题(中文名):</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="chinese_title" class="col-xs-12 col-sm-6" value="{{ $list->chinese_title }}" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">章节描述:</label>

                    <div class="col-xs-12 col-sm-9">
                        <textarea name="intro" rows="3" class="col-xs-12 col-sm-6">{{ $list->intro }}</textarea>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">章节顺序:</label>

                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="number" name="order" class="col-xs-12 col-sm-6" value="{{ $list->order }}" />
                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">章节内容:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div id="container" name="content" type="text/plain">

                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="col-sm-4 col-sm-offset-3">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $list->id }}">
                    <a href="/webmaster/chapter/index" class="btn btn-default"> 取消 </a>
                    <button type="submit" class="btn btn-success"> 修改 </button>
                </div>

            </form>
        </div>

    </div>
    </div>

@endsection

@section('page_css')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-1.10.3.full.min.css') }}" />--}}
    {{--<link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}" />--}}
@endsection

@section('page_js')
{{--    <script src="{{ asset('assets/js/jquery-ui-1.10.3.full.min.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>--}}
    <script>
        window.UEDITOR_HOME_URL = '{{ route('save_image') }}'

        jQuery(function($){
                    {{--$( "#tabs" ).tabs();--}}

            var ue=UE.getEditor('container', {
                        toolbars: [
                            ['fullscreen', 'source', 'undo', 'redo', '|', 'bold', 'italic', 'underline', 'fontborder', 'skrikethrough', 'removeformat', 'formatmatch', '|', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
                        ]
                    })
            ue.ready(function(){
                ue.setContent("{!! $list->content !!}")
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}')
            })
        })

    </script>
@endsection