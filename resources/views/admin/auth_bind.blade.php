@extends('admin.base')

@section('title', '用户权限列表')

@section('breadcrumb')
    <li>
        <a href="/webmaster/user/index"> 用户列表 </a>
    </li>

    <li class="active"> 用户权限列表 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong> 暂时开放简版,后续增加复杂用户绑定功能。
                <br>
            </div>
        </div>
        <div class="col-sm-12 dataTables_wrapper">
            <div class="row">
                <form action="{{ url('/webmaster/auth/bind') }}" class="form-horizontal" role="form" method="post">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 标识名称(纯英文) <span class="text-danger">[必填]</span> </label>

                        <div class="col-sm-9">
                            <strong class="text-info">{{ $user['username'] }}</strong>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 设置权限 </label>

                        <div class="col-sm-9">
                            <input type="text" name="authority_ids" placeholder="例:系统管理" class="col-xs-10 col-sm-5" value="{{ $list['authorities'] }}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle text-danger">*简版直接输入权限编号,中间用","(英文半角)隔开</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label no-padding-right">已有权限</label>

                        <div class="col-sm-9">
                            @foreach($list['authority'] as $item)
                            <span class="label label-info"> {{ $item['id'] }}:{{ $item['alias'] }} [{{ $item['url'] }}] </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $user['user_id'] }}">

                            <a href="{{ url('/webmaster/user/index') }}" class="btn" type="reset">
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