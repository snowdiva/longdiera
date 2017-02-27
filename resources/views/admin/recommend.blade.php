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
                    <a href="/webmaster/recommend/add" class="btn btn-danger"><i class="icon icon-plus"></i>新建推荐阅读标签</a>
                    <form action="" method="get" >

                    </form>


                    {{--<div class="input-append date form_datetime3" data-date="{{ date('Y-m-d H:i:s') }}">\--}}
                    {{--<input size="16" type="text" value="" name="audit_time" readonly="">\--}}
                    {{--<span class="add-on"><i class="icon icon-remove"></i></span>\--}}
                    {{--<span class="add-on"><i class="icon icon-calendar"></i></span>\--}}
                    {{--</div>\--}}

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover dataTable" >
                <thead>
                <tr>
                    <th>编号</th>
                    <th>标题</th>
                    <th>推荐语</th>
                    <th>标签</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->description }}</td>
                        <td><span class="label label-pink arrowed-right">{{ $item->tag }}</span></td>
                        <td>{{ date('Y-m-d H:i', $item->create_time) }}</td>
                        <td>
                            <div class="action-buttons">
                                <a class="green" href="/webmaster/recommend/edit?id={{ $item->id }}">
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