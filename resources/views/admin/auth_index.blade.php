@extends('admin.base')

@section('title', '权限列表')

@section('breadcrumb')
    <li class="active"> 全下列表 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>权限节点一般由开发人员控制,需要增删请于管理员联系。
                <br>
            </div>
        </div>
        <div class="col-sm-12 dataTables_wrapper">
            <div class="row">
                <div class="col-xs-12">
                    <a href="/webmaster/auth/add" class="btn btn-danger"><i class="icon icon-plus"></i>定义权限节点</a>
                    <form action="" method="get" >

                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" >
                <thead>
                <tr>
                    <th>编号</th>
                    <th>权限名称</th>
                    <th>别称</th>
                    <th>控制连接</th>
                    <th>描述</th>
                    <th>类型</th>
                    <th>上级权限</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->alias }}</td>
                        <td>{{ $item->url }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->pid }}</td>
                        <td>{{ date('Y-m-d H:i', $item->create_time) }}</td>
                        <td>
                            <div class="visible-md visible-lg action-buttons">

                                <a class="blue" href="{{ url('/webmaster/auth/edit', ['id' => $item->id]) }}">
                                    <i class="icon icon-pencil bigger-130"></i>修改
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