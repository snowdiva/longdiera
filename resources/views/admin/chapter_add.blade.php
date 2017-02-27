@extends('admin.base')

@section('title', '手动添加章节')

@include('UEditor::head')

@section('breadcrumb')
    <li>
        <a href="/webmaster/chapter/index"> 小说章节管理 </a>
    </li>
    <li class="active"> 手动添加章节 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>为小说添加一篇章节。
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @if(!isset($novel_name) || '' == $novel_id)
                <form action="/webmaster/chapter/add" class="form-horizontal" method="post">

                <div class="form-group">
                    <label for="" class="control-label col-xs-12 col-sm-3 no-padding-right text-danger">*必须输入章节序号</label>

                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="number" name="novel_id" id="">
                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>
            @else
                <h3 class="page-header text-primary"> 为《{{ $novel_name }}》(《({{ $novel_chinese_name }})》)手动添加章节 </h3>

                <form action="/webmaster/chapter/add" class="form-horizontal" method="post">

                    <input type="hidden" name="novel_id" value="{{ $novel_id }}">
            @endif

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">标题(英文):</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="title" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">标题(中文名):</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="chinese_title" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">章节描述:</label>

                    <div class="col-xs-12 col-sm-9">
                        <textarea name="intro" rows="3" class="col-xs-12 col-sm-6"></textarea>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">章节顺序:</label>

                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="number" name="order" class="col-xs-12 col-sm-6" />
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
                    <a href="/webmaster/chapter/index" class="btn btn-default"> 取消 </a>
                    <button type="submit" class="btn btn-success"> 添加 </button>
                </div>

            </form>
        </div>

    </div>
    </div>

@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-1.10.3.full.min.css') }}" />
    {{--<link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}" />--}}
@endsection

@section('page_js')
    <script src="{{ asset('assets/js/jquery-ui-1.10.3.full.min.js') }}"></script>
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
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}')
            })
        })

    </script>
@endsection