@extends('admin.base')

@section('title', '编辑标签组')

@section('breadcrumb')
    <li>
        <a href="/webmaster/recommend/group"> 标签组管理 </a>
    </li>

    <li class="active"> 编辑推荐组 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong> 首先建立推荐语标签,然后在此进行推荐组设置。
                <br>
            </div>
        </div>
        <div class="col-sm-12 dataTables_wrapper">
            <div class="row">
                <form action="{{ url('/webmaster/recommend/group/edit') }}" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 推荐组名称 <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="name" placeholder="例:情感生活相关" class="col-xs-10 col-sm-5" value="{{ $list->name }}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle text-danger"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 推荐语标签编号 <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="recommend_id" placeholder="例:1,6,15,24,33" class="col-xs-10 col-sm-5" value="{{ $list->recommend_id }}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle text-danger">*简版直接输入推荐语编号,中间用","(英文半角)隔开,最多填写6个</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label no-padding-right">所属分类</label>

                        <div class="col-xs-12 col-sm-9">
                            <select name="sort_id" class="select2">
                                <option value="0" @if(0 == $list->sort_id) selected="true"@endif>无分类[通用]</option>
                                @foreach($sort as $key => $value)
                                    <option value="{{ $key }}" @if($key == $list->sort_id) selected="true"@endif>{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $list->id }}">

                            <a href="{{ url('/webmaster/recommend/group') }}" class="btn" type="reset">
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