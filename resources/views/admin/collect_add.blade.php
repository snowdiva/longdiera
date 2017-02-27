@extends('admin.base')

@section('title', '新建采集器')

@section('breadcrumb')
    <li>
        <a href="/webmaster/article/collect"> 采集器管理 </a>
    </li>
    <li class="active"> 新建采集器 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>采集器规则可参考CSS3格式进行设置,如有疑问请于研发部联系。
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-header text-primary"> 添加一个采集器 </h3>

            <form action="/webmaster/collect/add" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">采集器名称:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="name" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">描述:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="description" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">采集器规则:</label>

                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <textarea name="rule" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">启用状态:</label>

                    <div class="col-xs-12 col-sm-9">
                        <select name="status" class="select2">
                            <option value="0">关闭</option>
                            <option value="1" selected="true">启用</option>
                        </select>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="col-sm-4 col-sm-offset-3">
                    {{ csrf_field() }}
                    <a href="/webmaster/article/sort" class="btn btn-default"> 取消操作 </a>
                    <button type="submit" class="btn btn-success"> 确认保存 </button>
                </div>

            </form>
        </div>
    </div>


@endsection