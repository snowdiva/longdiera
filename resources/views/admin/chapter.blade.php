@extends('admin.base')

@section('title', '小说章节管理')

@section('breadcrumb')
    <li class="active"> 小说章节管理 </li>
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
                <div class="col-xs-12">
                    <a href="/webmaster/chapter/add" class="btn btn-danger"><i class="icon icon-plus"></i>手动添加章节</a>
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
                    <th>章节编号</th>
                    <th>小说名称</th>
                    <th>章节名称</th>
                    <th>字数</th>
                    <th>创建时间</th>
                    <th>作品状态</th>
                    <th>发布(审核)时间</th>
                    <th>审核人编号</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->novel_chinese_title }}({{ $item->novel_title }})</td>
                        <td>{{ $item->chinese_title }}({{ $item->title }})</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ date('Y-m-d', $item->create_at) }}</td>
                        <td>@if(1 == $item->publish_status) <span class="text-danger">已完结</span>@elseif(0 == $item->status) <span class="text-success">连载中</span>@elseif(2 == $item->status) <span class="label label-warning" data-rel="tooltip" title="{{ date('Y-m-d H:i:s', $item->audit_time) }}">定时发布</span>@else <span class="label label-default">已删除</span> @endif</td>
                        <td>@if(1 == $item->status) <span class="label label-success arrowed" data-rel="tooltip" title="审核时间:{{ date('Y-m-d H:i:s', $item->publish_at) }}"> 已发布 </span>@elseif(0 == $item->status) <span class="label label-danger"> 待审核 </span>@elseif(2 == $item->status) <span class="label label-warning" data-rel="tooltip" title="发布时间:{{ date('Y-m-d H:i:s', $item->publish_at) }}">定时发布</span>@else <span class="label label-default">已删除</span> @endif</td>
                        <td>{{ $item->auditor or '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <a class="green" href="/webmaster/chapter/edit/?id={{ $item->id }}">
                                    <i class="icon icon-pencil bigger-130"></i>编辑
                                </a>

                                @if($item->status <= 0)
                                    <a class="blue" href="javascript:;" data-name="audit-btn" data-url="/webmaster/chapter/audit/?id={{ $item->id }}" data-default-time="{{ date('Y-m-d H:i') }}">
                                        <i class="icon icon-check-circle bigger-130"></i>审核
                                    </a>
                                @endif

                                <a class="red" href="javascript:;" data-name="delete-btn" data-url="/webmaster/chapter/delete/?id={{ $item->id }}">
                                    <i class="icon icon-trash bigger-130"></i>删除
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

@section('page_css')
    <link rel="stylesheet" href="{{ asset('/assets/css/datetimepicker/bootstrap-datetimepicker.min.css') }}"></link>
@endsection

@section('page_js')
    <script type="application/javascript" src="{{ asset('/assets/js/datepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="application/javascript">
        $(function(){
            $('[data-rel=tooltip]').tooltip()
        })
    </script>
@endsection