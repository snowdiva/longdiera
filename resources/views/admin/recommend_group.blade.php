@extends('admin.base')

@section('title', '推荐阅读管理')

@section('breadcrumb')
    <li class="active"> 推荐阅读管理 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示：</strong>推荐阅读完成之后,可在文章管理中进行关联。
                <br>
            </div>
        </div>
        <div class="col-sm-12 dataTables_wrapper">
            <!--搜索栏-->
            <div class="row">
                <div class="col-xs-12">
                    <a href="/webmaster/recommend/group/add" class="btn btn-danger"><i class="icon icon-plus"></i>新建推荐阅读标签</a>
                    <form action="" method="get" >

                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" >
                <thead>
                <tr>
                    <th>编号</th>
                    <th>名称</th>
                    <th>所属分类</th>
                    <th>推荐语编号</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>@if(0 == $item->sort_id)<span class="label label-warning">无分类</span>@else<span class="label label-pink">{{ $sort[$item->sort_id]['name'] }}</span>@endif</td>
                        <td>{{ $item->recommend_id }}</td>
                        <td>{{ date('Y-m-d H:i', $item->create_time) }}</td>
                        <td>
                            <div class="action-buttons">
                                <a class="green" href="/webmaster/recommend/group/edit?id={{ $item->id }}">
                                    <i class="icon icon-pencil bigger-130"></i>编辑
                                </a>

                                {{--<a class="red" href="javascript:;" data-name="delete-btn" data-url="/webmaster/recommend/delete?id={{ $item->id }}">--}}
                                {{--<i class="icon icon-trash bigger-130"></i>删除--}}
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