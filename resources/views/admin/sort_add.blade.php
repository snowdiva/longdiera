@extends('admin.base')

@section('title', '添加分类')

@section('breadcrumb')
    <li>
        <a href="/webmaster/article/sort"> 分类管理 </a>
    </li>
    <li class="active"> 添加分类 </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon icon-remove"></i>
                </button>
                <strong>提示:</strong>分类会在首页中显示,并作为文章的基础标签使用。
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-header text-primary"> 添加一个文章分类 </h3>

            <form action="/webmaster/article/sort_add" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">分类名称:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="name" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">简称:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="short_name" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">中文名:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text" name="chinese_name" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">主分类:</label>

                    <div class="col-xs-12 col-sm-9">
                        <select name="sort_id" class="select2">
                            <option value="0">顶级分类</option>
                            @foreach($sort as $key => $value)
                                <option value="{{ $key }}">{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-2"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">是否热门:</label>

                    <div class="col-xs-12 col-sm-9">
                        <select name="hot_is" class="select2">
                            <option value="0">普通分类</option>
                            <option value="1">热门推荐</option>
                        </select>
                    </div>
                </div>

                <div class="hr hr-dotted"></div>

                <div class="col-sm-4 col-sm-offset-3">
                    {{ csrf_field() }}
                    <a href="/webmaster/article/sort" class="btn btn-default"> 取消 </a>
                    <button type="submit" class="btn btn-success"> 添加 </button>
                </div>

            </form>
        </div>
    </div>


@endsection