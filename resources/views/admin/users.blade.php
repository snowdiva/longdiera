@extends('admin.base')

@section('title', '用户列表')

@section('breadcrumb')
    <li class="active"> 用户列表 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>本系统仅作用户查询,不可进行操作
                <br>
            </div>
        </div>
        <div class="col-sm-12 dataTables_wrapper">
            <div class="row">
                <div class="col-xs-12">
                    {{--<a href="/webmaster/article/add" class="btn btn-danger"><i class="icon icon-plus"></i>添加文章</a>--}}
                    <form action="{{ url('/webmaster/user/index') }}" method="get" >

                        <div class="col-sm-4">
                            <div class="input-group col-sm-12">
                                <label for="">按UID查询</label>
                                <input type="text" name="search_user_id">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group col-sm-12">
                                <label for="">按用户名查询</label>
                                <input type="text" name="search_uname">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <button class="btn btn-sm btn-success pull-right"> 搜索 </button>
                        </div>

                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" >
                <thead>
                <tr>
                    <th>编号</th>
                    <th>登录名</th>
                    <th>别称</th>
                    <th>注册时间</th>
                    <th>用户组</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->alias }}</td>
                        <td>{{ date('Y-m-d H:i', $item->create_at) }}</td>
                        <td>{{ $item->group_id }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <div class="visible-md visible-lg action-buttons">

                                <a class="blue" href="{{ url('/webmaster/auth/bind', ['user_id' => $item->id]) }}">
                                    <i class="icon icon-legal bigger-130"></i>绑定
                                </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-offset-8 col-sm-4">
                    <div class="page pull-right"> {{ $list->links() }} </div>
                </div>
            </div>
        </div>
    </div>
@endsection