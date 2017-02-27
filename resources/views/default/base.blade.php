<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@yield('title') - LongDiEra</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    @section('head_seo')
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <meta name="copyright" content=""/>
        <meta name="author" content=""/>
    @show
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    @section('css')
        <link href="{{ asset('default/css/style.css') }}" rel="stylesheet" type="text/css" />
    @show
</head>
<body>
<div class="main">
    <div class="header">
        <div class="header_resize">
            @section('head_logo')
            <div class="logo">
                <h1><a href="index.html">LongDi <span> Era</span></a><small>LongDiEra.com</small></h1>
            </div>
            @show

            <div class="clr"></div>
            <div class="menu_nav">
                <ul>
                    @section('navigation')
                        <li class="active"><a href="{{ $_ENV['APP_URL'] }}">Home</a></li>
                        <li><a href="{{ url('novel') }}">Novel</a></li>
                        <li><a href="{{ url('news') }}">News</a></li>
                        <li><a href="{{ url('encyclopedia') }}">Encyclopedia</a></li>
                        <li><a href="{{ url('aboutus') }}">About Us</a></li>
                        <li><a href="{{ url('wiki') }}">Wiki</a></li>
                    @show
                </ul>

                @section('head_search')
                <div class="search">
                    <form id="form" name="form" method="post" action="{{ url('search') }}">
                      <span>
                      <input name="q" type="text" class="keywords" id="textfield" maxlength="50" value="Search..." />

                      <input name="b" type="image" src="{{ asset('default/images/search.gif') }}" class="button" />
                      </span>
                    </form>
                </div>
                @show
            </div>
            <div class="clr"></div>
        </div>
    </div>
    <div class="clr"></div>
    <div class="content">
        <div class="content_resize">
            @section('content')

            @show

            @section('sidebar')
            <div class="sidebar">
                <div class="gadget">
                    <h2>Sidebar Menu</h2>
                    <div class="clr"></div>
                    <ul class="sb_menu">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">TemplateInfo</a></li>
                        <li><a href="#">Style Demo</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Archives</a></li>
                        <li><a href="#" title="Website Templates">Web Templates</a></li>
                    </ul>
                </div>
                <div class="gadget">
                    <h2><span>Sponsors</span></h2>
                    <div class="clr"></div>
                    <ul class="ex_menu">
                        <li><a href="#" title="Website Templates">DreamTemplate</a><br />
                            Over 6,000+ Premium Web Templates</li>
                        <li><a href="http://www.templatesold.com" title="WordPress Themes">TemplateSOLD</a><br />
                            Premium WordPress &amp; Joomla Themes</li>
                        <li><a href="#" title="Affordable Hosting">ImHosted.com</a><br />
                            Affordable Web Hosting Provider</li>
                        <li><a href="#" title="Stock Icons">MyVectorStore</a><br />
                            Royalty Free Stock Icons</li>
                        <li><a href="#" title="Website Builder">Evrsoft</a><br />
                            Website Builder Software &amp; Tools</li>
                        <li><a href="#" title="CSS Templates">CSS Hub</a><br />
                            Premium CSS Templates</li>
                    </ul>
                </div>
                <div class="gadget">
                    <h2>Wise Words</h2>
                    <div class="clr"></div>
                    <p>   <img src="images/test_1.gif" alt="image" width="20" height="18" /> <em>We can let circumstances rule us, or we can take charge and rule our lives from within </em>.<img src="images/test_2.gif" alt="image" width="20" height="18" /></p>
                    <p style="float:right;"><strong>Earl Nightingale</strong></p>
                </div>
            </div>
            @show
            <div class="clr"></div>
        </div>
    </div>
    <div class="fbg">
        <div class="fbg_resize">
            @section('bottom')
            <div class="col c1">
                <h2><span>Image Gallery</span></h2>
                <a href="#"><img src="images/gallery_1.jpg" width="58" height="58" alt="pix" /></a> <a href="#"><img src="images/gallery_2.jpg" width="58" height="58" alt="pix" /></a> <a href="#"><img src="images/gallery_3.jpg" width="58" height="58" alt="pix" /></a> <a href="#"><img src="images/gallery_4.jpg" width="58" height="58" alt="pix" /></a> <a href="#"><img src="images/gallery_5.jpg" width="58" height="58" alt="pix" /></a> <a href="#"><img src="images/gallery_6.jpg" width="58" height="58" alt="pix" /></a> </div>
            <div class="col c2">
                <h2><span>Lorem Ipsum</span></h2>
                <p>Lorem ipsum dolor<br />
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec libero. Suspendisse bibendum. Cras id urna. Morbi tincidunt, orci ac convallis aliquam, lectus turpis varius lorem, eu posuere nunc justo tempus leo. Donec mattis, purus nec placerat bibendum, dui pede condimentum odio, ac blandit ante orci ut diam.</p>
            </div>
            <div class="col c3">
                <h2><span>Contact</span></h2>
                <p>Nullam quam lorem, tristique non vestibulum nec, consectetur in risus. Aliquam a quam vel leo gravida gravida eu porttitor dui. Nulla pharetra, mauris vitae interdum dignissim, justo nunc suscipit ipsum. <a href="#">mail@example.com</a><br />
                    <a href="#">+1 (123) 444-5677</a></p>
            </div>
            @show
            <div class="clr"></div>
        </div>

        <div class="footer">
            @section('footer')
            <p class="lr">© Copyright <a href="javascript:;">LongDiEra</a>.</p>
            <p class="lf"><a href="http://www.longdiera.com/" title="LongDiEra.com" target="_blank">LongDiEra.com</a></p>
            <div class="clr"></div>
            @show
        </div>
    </div>
</div>
</body>
@section('js')
    <script type="text/javascript" src="{{ asset('default/js/cufon-yui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('default/js/arial.js') }}"></script>
    <script type="text/javascript" src="{{ asset('default/js/cuf_run.js') }}"></script>
@show
</html>
