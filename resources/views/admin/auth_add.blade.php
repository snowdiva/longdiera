@extends('admin.base')

@section('title', '添加权限节点')

@section('breadcrumb')
    <li>
        <a href="/webmaster/auth/index"> 权限列表 </a>
    </li>

    <li class="active"> 添加权限节点 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong> 所有新增加功能必须增加该项目方可设置权限,否则除管理员外无法使用。
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="page-header">
            <h1>添加权限</h1>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form action="{{ url('/webmaster/auth/add') }}" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 标识名称(纯英文) <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="name" placeholder="例:system" class="col-xs-10 col-sm-5" />
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 权限名称 <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <input type="text" name="alias" placeholder="例:系统管理" class="col-xs-10 col-sm-5" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 访问路由 </label>

                        <div class="col-sm-9">
                            <input type="text" name="url" placeholder="例:webmaster/dashboard" class="col-xs-10 col-sm-5" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle text-warning">*顶级菜单不要填写</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 权限功能描述 </label>

                        <div class="col-sm-9">
                            <input type="text" name="description" placeholder="例:系统管理模块" class="col-xs-10 col-sm-5" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 权限归属 </label>

                        <div class="col-sm-6">
                            <select class="col-sm-6" name="pid">
                                <option value="0">无(当前为顶级)</option>
                                @foreach($list as $item)
                                <option value="{{ $item->id }}">@if(0 == $item->pid)顶级菜单:@else普通菜单:@endif{{ $item->alias }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 是否菜单显示 </label>

                        <div class="col-xs-3">
                            <label>
                                <input name="type" class="ace ace-switch ace-switch-7" type="checkbox" value="1" />
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            {{ csrf_field() }}

                            <a href="{{ url('/webmaster/auth/index') }}" class="btn" type="reset">
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