@extends('admin.base')

@section('title', '分类管理')

@section('breadcrumb')
    <li class="active"> 分类管理 </li>
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
                    <a href="/webmaster/article/sort_add" class="btn btn-danger"><i class="icon icon-plus"></i>添加分类</a>
                    {{--<a href="#modal-table" data-toggle="modal" class="btn btn-danger"><i class="icon icon-plus"></i>添加分类</a>--}}
                    <form action="" method="get" >

                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" >
                <thead>
                    <tr>
                        <th>编号</th>
                        <th>全称</th>
                        <th>简写</th>
                        <th>中文名</th>
                        <th>所属分类</th>
                        <th>热门推荐</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->short_name }}</td>
                        <td>{{ $item->chinese_name }}</td>
                        <td>@if($item->sort_id == 0) - @else {!! $sort[$item->sort_id]['name'] !!} @endif</td>
                        <td>@if($item->hot_is == 1) <span class="text-danger">热门</span> @else <span class="text-info">普通</span> @endif</td>
                        <td>
                            <div class="visible-md visible-lg action-buttons">
                                <a class="green" href="/webmaster/article/sort_edit/?id={{ $item->id }}">
                                    <i class="icon icon-pencil bigger-130"></i>
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