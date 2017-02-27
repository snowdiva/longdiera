<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>LongDiEra - 后台管理系统</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- basic styles -->

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css" />

    <!--[if IE 7]>
    <link rel="stylesheet" href="../assets/css/web-awesome-ie7.min.css" />
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->

    <!-- <link rel="stylesheet" href="../assets/web/googleapis.css" /> -->

    <!-- ace styles -->

    <link rel="stylesheet" href="../assets/css/ace.min.css" />
    <link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="../assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <script src="../assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="icon icon-leaf green"></i>
                            <span class="red">LongDiEra</span>
                            <span class="white">管理系统</span>
                        </h1>
                        <h4 class="blue">&copy; LongDiEra.com</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="icon icon-coffee green"></i>
                                        管理员帐号登录
                                    </h4>

                                    <div class="space-6"></div>

                                    <form action="{{ url('webmaster/login') }}" method="post">
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon icon-right">
															<input name="user_name" type="text" class="form-control" placeholder="管理员账号" />
															<i class="icon icon-user"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon icon-right">
															<input name="password" type="password" class="form-control" placeholder="登录密码" />
															<i class="icon icon-lock"></i>
														</span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <label class="inline">
                                                    <input name="week_login" type="checkbox" class="ace" value="1"/>
                                                    <span class="lbl"> 一周免登录</span>
                                                </label>
                                                {{ csrf_field() }}
                                                <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                    <i class="icon icon-key"></i>
                                                    登录
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>

                                </div><!-- /widget-main -->

                            </div><!-- /widget-body -->
                        </div><!-- /login-box -->

                    </div><!-- /position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->

<!-- <script src="../assets/js/jquery.2.0.3.min.js"></script> -->

<!-- <![endif]-->

<!--[if IE]>
<script src="../assets/js/jquery.1.10.2.min.js"></script>
<![endif]-->

<!--[if !IE]> -->

<script type="text/javascript">
    window.jQuery || document.write("<script src='../assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='../assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if("ontouchend" in document) document.write("<script src='../assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<!-- inline scripts related to this page -->

<script type="text/javascript">
    function show_box(id) {
        jQuery('.widget-box.visible').removeClass('visible');
        jQuery('#'+id).addClass('visible');
    }
</script>
</body>
</html>
