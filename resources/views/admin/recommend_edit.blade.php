@extends('admin.base')

@section('title', '编辑推荐标签')

@section('breadcrumb')
    <li>
        <a href="/webmaster/recommend"> 推荐标签管理 </a>
    </li>

    <li class="active"> 编辑推荐标签 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong> 推荐标签一旦生成则不允许随意删除,如需删除请联系管理员。
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="page-header">
            <h1>编辑推荐标签</h1>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form action="{{ url('/webmaster/recommend/edit') }}" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 标题 <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="title" placeholder="不超过12个字" class="col-xs-10 col-sm-5" value="{{ $list->title }}" />
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 推荐文字 <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="description" placeholder="不超过30个字" class="col-xs-10 col-sm-5" value="{{ $list->description }}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 跳转连接 <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="url" placeholder="例:http://m.shucong.com" class="col-xs-10 col-sm-5" value="{{ $list->url }}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 标签 </label>

                        <div class="col-sm-9">
                            <input type="text" name="tag" placeholder="例:情感生活" class="col-xs-10 col-sm-5" value="{{ $list->tag }}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle text-warning">*标签仅允许出现一个,且不与分类关联</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $list->id }}">

                            <a href="{{ url('/webmaster/recommend') }}" class="btn">
                                <i class="icon icon-reply bigger-110"></i>
                                取消操作
                            </a>

                            <button class="btn btn-success" type="submit">
                                <i class="icon icon-ok bigger-110"></i>
                                确认提交
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection