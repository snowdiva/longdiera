<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')"/>
    <meta name="description" content="@yield('description')"/>
    <meta name="copyright" content="@yield('copyright')"/>
    <meta name="author" content="@yield('author')"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- basic styles -->

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css"/>

    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/css/web-awesome-ie7.min.css"/>
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->

    <!-- <link rel="stylesheet" href="/assets/web/googleapis.css" /> -->

    <!-- ace styles -->

    <link rel="stylesheet" href="/assets/css/ace.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-skins.min.css"/>

    <link rel="stylesheet" href="{{ asset('/assets/css/common.css') }}"/>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css"/>
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->

    <script src="/assets/js/ace-extra.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->
    @section('page_css')
    @show
</head>

<body>
<div class="navbar navbar-default" id="navbar">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="{{ url('webmaster/dashboard') }}" class="navbar-brand">
                <small>
                    <i class="icon icon-leaf"></i>
                    LongDiEra.com 管理后台
                </small>
            </a><!-- /.brand -->
        </div><!-- /.navbar-header -->

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                @section('top_message')

                    <li class="light-blue">
                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            <img class="nav-user-photo" src="/assets/avatars/user.jpg" alt="Jason's Photo"/>
                            <span class="user-info">
                                @if(date('G', time()) <= 12)
                                    <small>上午好,</small>
                                @elseif(date('G', time()) <= 18)
                                    <small>下午好,</small>
                                @else
                                    <small>晚上好,</small>
                                @endif
                                {{ Session::get('user.username') }}
                            </span>

                            <i class="icon icon-caret-down"></i>
                        </a>

                        <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                            <li>
                                <a href="#">
                                    <i class="icon icon-cog"></i>
                                    管理设置
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="icon icon-user"></i>
                                    个人信息
                                </a>
                            </li>

                            <li class="divider"></li>

                            <li>
                                <a href="{{ url('webmaster/logout') }}">
                                    <i class="icon icon-off"></i>
                                    注销
                                </a>
                            </li>
                        </ul>
                    </li>
            </ul><!-- /.ace-nav -->
        </div><!-- /.navbar-header -->
    </div><!-- /.container -->
</div>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>

        <div class="sidebar" id="sidebar">
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <a class="btn btn-success">
                        <i class="icon icon-signal"></i>
                    </a>

                    <a class="btn btn-info">
                        <i class="icon icon-pencil"></i>
                    </a>

                    <a class="btn btn-warning">
                        <i class="icon icon-group"></i>
                    </a>

                    <a class="btn btn-danger">
                        <i class="icon icon-cogs"></i>
                    </a>
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>

                    <span class="btn btn-info"></span>

                    <span class="btn btn-warning"></span>

                    <span class="btn btn-danger"></span>
                </div>

            </div>

            <ul class="nav nav-list">

                <li class="active">
                    <a href="{{ url('webmaster/dashboard') }}">
                        <i class="icon icon-dashboard"></i>
                        <span class="menu-text"> 控制台 </span>
                    </a>
                </li>

                <li>
                    <a href="javascript:;">
                        <i class="icon icon-cog"></i>
                        <span class="menu-text"> 系统设置 </span>
                    </a>
                </li>

                <li>
                    <a href="javascript:;" class="dropdown-toggle">
                        <i class="icon icon-user"></i>
                        <span class="menu-text"> 人员管理 </span>

                        <b class="icon icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/webmaster/user/index">
                                <i class="icon-double-angle-right"></i>
                                用户列表
                            </a>
                        </li>

                        <li>
                            <a href="/webmaster/group/index">
                                <i class="icon-double-angle-right"></i>
                                权限组列表
                            </a>
                        </li>

                        <li>
                            <a href="/webmaster/auth/index">
                                <i class="icon-double-angle-right"></i>
                                权限列表
                            </a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="dropdown-toggle">
                        <i class="icon icon-book"></i>
                        <span class="menu-text"> 内容管理 </span>

                        <b class="icon icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/webmaster/article/sort">
                                <i class="icon-double-angle-right"></i>
                                分类管理
                            </a>
                        </li>

                        <li>
                            <a href="/webmaster/article/index">
                                <i class="icon-double-angle-right"></i>
                                小说管理
                            </a>
                        </li>

                        <li>
                            <a href="/webmaster/chapter/index">
                                <i class="icon-double-angle-right"></i>
                                小说章节管理
                            </a>
                        </li>

                        {{--<li>--}}
                            {{--<a href="/webmaster/recommend/group">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--推荐组管理--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li>--}}
                            {{--<a href="/webmaster/recommend">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--推荐语单独标签管理--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li>--}}
                            {{--<a href="/webmaster/hot">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--首页推荐管理--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li>--}}
                            {{--<a href="/webmaster/collect">--}}
                                {{--<i class="icon-double-angle-right"></i>--}}
                                {{--采集管理--}}
                            {{--</a>--}}
                        {{--</li>--}}

                    </ul>
                </li>

            </ul><!-- /.nav-list -->

            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon icon-angle-double-left" data-icon1="icon icon-angle-double-left"
                   data-icon2="icon icon-angle-double-right"></i>
            </div>

            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'collapsed')
                } catch (e) {
                }
            </script>
        </div>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try {
                        ace.settings.check('breadcrumbs', 'fixed')
                    } catch (e) {
                    }
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="icon icon-home home-icon"></i>
                        <a href="{{ url('webmaster/dashboard') }}">首页</a>
                    </li>

                    @section('breadcrumb')

                    @show

                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input"
                                   id="nav-search-input" autocomplete="off"/>
                            <i class="icon icon-search nav-search-icon"></i>
                        </span>
                    </form>
                </div><!-- #nav-search -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                    @section('content')

                    @show
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div><!-- /.main-content -->

        <div class="ace-settings-container" id="ace-settings-container">
            <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                <i class="icon icon-cog bigger-150"></i>
            </div>

            <div class="ace-settings-box" id="ace-settings-box">
                <div>
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                            <option data-skin="default" value="#438EB9">#438EB9</option>
                            <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                            <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                            <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                        </select>
                    </div>
                    <span>&nbsp; Choose Skin</span>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar"/>
                    <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar"/>
                    <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs"/>
                    <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl"/>
                    <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container"/>
                    <label class="lbl" for="ace-settings-add-container">
                        Inside
                        <b>.container</b>
                    </label>
                </div>
            </div>
        </div><!-- /#ace-settings-container -->
    </div><!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon icon-angle-double-up icon icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->

<!-- <script src="/assets/js/jquery-2.0.3.min.js"></script> -->

<!-- <![endif]-->

<!--[if IE]>
<script src="/assets/js/jquery-1.10.2.min.js"></script>
<![endif]-->

<!--[if !IE]> -->

<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if ("ontouchend" in document) document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/typeahead-bs2.min.js"></script>

<!-- page specific plugin scripts -->

<!-- ace scripts -->
<script src="{{ asset('/assets/js/diy/lang.js') }}"></script>
<script src="{{ asset('/assets/js/diy/define.js') }}"></script>

<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>
<script src="{{ asset('/assets/js/bootbox.min.js') }}"></script>

<script src="{{ asset('/assets/js/diy/common.js') }}"></script>

@section('page_js')
@show

</body>
</html>
