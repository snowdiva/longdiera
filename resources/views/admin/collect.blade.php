@extends('admin.base')

@section('title', '采集器管理')

@section('breadcrumb')
    <li class="active"> 采集管理 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示：</strong>共  篇文章符合查询条件。
                <br>
            </div>
        </div>
        <div class="col-sm-12 dataTables_wrapper">
            <!--搜索栏-->
            <div class="row">
                <div class="col-sm-12">
                    <a href="/webmaster/collect/add" class="btn btn-danger"><i class="icon icon-plus"></i>新建采集器</a>
                    <form action="" method="get" >

                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" >
                <thead>
                    <tr>
                        <th>编号</th>
                        <th>名称</th>
                        <th>描述</th>
                        <th>创建时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{!! $item->description or '-' !!}</td>
                        <td>{{ date('Y-m-d H:i', $item->create_time) }}</td>
                        <td>@if(0 == $item->status) <span class="label label-default">关闭</span>@elseif(1 == $item->status) <span class="label label-success">开启</span>@else <span class="label label-danger">异常</span>@endif</td>
                        <td>
                            <div class="visible-md visible-lg action-buttons">
                                <a class="green" href="/webmaster/collect/edit/?id={{ $item->id }}">
                                    <i class="icon icon-pencil bigger-130"></i>编辑
                                </a>

                                <a class="blue" href="/webmaster/collect/get/?id={{ $item->id }}">
                                    <i class="icon icon-copy bigger-130"></i>手动采集
                                </a>

                                {{--<a class="red" href="#">--}}
                                    {{--<i class="icon icon-trash bigger-130"></i>--}}
                                {{--</a>--}}
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