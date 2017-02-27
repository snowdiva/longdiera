@extends('admin.base')

@section('title', '推荐列表')

@section('breadcrumb')
    <li class="active"> 推荐管理 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong> 创建推荐列表前,首先初始化文章内容,根据文章编号进行推荐设置。
                <br>
            </div>
        </div>

        <div class="col-sm-12">
            <a href="/webmaster/hot/add" class="btn btn-danger" style="margin-bottom: 10px;"><i class="icon icon-plus"></i>添加推荐文章</a>
        </div>

        <div class="col-sm-12 dataTables_wrapper">
            <div class="row">
                <div class="col-xs-12">
                    <form action="{{ url('/webmaster/hot') }}" method="get" >

                        <div class="col-sm-4">
                            <div class="input-group col-sm-12">
                                <label for="">按UID查询</label>
                                <input type="text" name="search_news_id">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group col-sm-12">
                                <label for="">按文章名称查询</label>
                                <input type="text" name="search_title">
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
                    <th>文章编号</th>
                    <th>标题</th>
                    <th>推荐类型</th>
                    <th>排序</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($list->items))
                    @foreach($list as $item)
                        <tr>
                            <td>{{ $item->news_id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{!! config('article.hot.type')[$item->type] !!}</td>
                            <td>{{ $item->order }}</td>
                            <td>{{ date('Y-m-d H:i', $item->create_time) }}</td>
                            <td>{{ date('Y-m-d H:i', $item->update_time) }}</td>
                            <td>{{ $item->state }}</td>
                            <td>
                                <div class="visible-md visible-lg action-buttons">

                                    <a class="blue" href="{{ url('/webmaster/hot/edit', ['news_id' => $item->uid]) }}">
                                        <i class="icon icon-legal bigger-130"></i>编辑
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8"><span class="text-warning">暂无数据...</span></td>
                    </tr>
                @endif
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