<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Liexpedition
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    <!--[endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/hover/css/hover-min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/advanced_modals.css')}}">

    <script src="js/jquery3.5.1.js"></script>
@yield('header_styles')
<!-- end of global css -->
<script>
    window.Laravel = {!! json_encode([
      "csrfToken" => csrf_token(),
      "api_token" => Auth::user()->tokens,
      "app_url" => url('/'),
    ]) !!};
</script>
<style>
.fixed-footer .app-footer .app-footer__inner{box-shadow:0.3rem -0.46875rem 2.1875rem rgba(4,9,20,0.02),0.3rem -0.9375rem 1.40625rem rgba(4,9,20,0.02),0.3rem -0.25rem 0.53125rem rgba(4,9,20,0.04),0.3rem -0.125rem 0.1875rem rgba(4,9,20,0.02)}
/* Apply second set of CSS rules */
.preloader {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
z-index: 9999;
background-color: rgba(0, 0, 0, 0.1);
}
.preloader .loading {
position: absolute;
left: 50%;
top: 50%;
transform: translate(-50%,-50%);
font: 14px arial;
}
</style>
</head>
<body class="skin-coreplus">
<div class="preloader">
    <div class="loading">
        <img src="{{url('asssets')}}/img/tenor.gif" width="280">
        <center><p>Harap Tunggu</p></center>
    </div>
</div>
<!-- header logo: style can be found in header-->
<header class="header">
    <nav class="navbar navbar-expand-md navbar-static-top">
        <a href="index " class="logo navbar-brand">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <h3>Liexpedition</h3>
        </a>
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <i
                        class="fa fa-fw fa-bars"></i>
            </a>
        </div>
        <div class="navbar-collapse " id="navbarNav">
            <div class="navbar-right ml-auto">
            <ul class="nav navbar-nav ">
                <!-- Notifications: style can be found in dropdown-->
                <li class="nav-item dropdown notifications-menu">
                    <a href="#" class="nav-link dropdown-toggle" >
                        <i class="fa fa-fw fa-bell-o black"></i>
                        <span class="label bg-warning">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li class="dropdown-title">You have 8 notifications</li>

                        <li class="message striped-col">
                            <a href="" class=" icon-not">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar3.jpg')}}"
                                    alt="avatar-image">

                                <div class="message-body">
                                    <strong>John Doe</strong>
                                    <br>
                                    5 members joined today
                                    <br>
                                    <span class="noti-date">Just now</span>
                                </div>

                            </a>
                        </li>
                        <li class="message">
                            <a href="" class=" icon-not">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar.jpg')}}"
                                    alt="avatar-image">
                                <div class="message-body">
                                    <strong>Tony</strong>
                                    <br>
                                    likes a photo of you
                                    <br>
                                    <span class="noti-date">5 min</span>
                                </div>
                            </a>
                        </li>
                        <li class="message striped-col">
                            <a href="" class=" icon-not">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar6.jpg')}}"
                                    alt="avatar-image">

                                <div class="message-body">
                                    <strong>John</strong>
                                    <br>
                                    Dont forgot to call...
                                    <br>
                                    <span class="noti-date">11 min</span>

                                </div>
                            </a>
                        </li>
                        <li class="message">
                            <a href="" class=" icon-not">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar1.jpg')}}"
                                    alt="avatar-image">
                                <div class="message-body">
                                    <strong>Jenny Kerry</strong>
                                    <br>
                                    Very long description here...
                                    <br>
                                    <span class="noti-date">1 Hour</span>
                                </div>
                            </a>
                        </li>
                        <li class="message striped-col">
                            <a href="" class=" icon-not ">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar7.jpg')}}"
                                    alt="avatar-image">

                                <div class="message-body">
                                    <strong>Ernest Kerry</strong>
                                    <br>
                                    2 members joined today
                                    <br>
                                    <span class="noti-date">3 Days</span>

                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="#"> View All Notifications</a></li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown-->
                <li class="nav-item dropdown user user-menu">
                    <a href="#" class="nav-link dropdown-toggle padding-user pt-3">
                        <img src="{{asset('assets/img/authors/avatar1.jpg')}}" width="35"
                            class="rounded-circle img-fluid pull-left"
                            height="35" alt="User Image">
                        <div class="riot">
                            <div>
                                {{Auth::user()->name}}
                                <span>
                                        <i class="fa fa-caret-down"></i>
                                    </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('assets/img/authors/avatar1.jpg')}}" class="rounded-circle" alt="User Image">
                            <p> {{Auth::user()->name}}</p>
                        </li>
                        <!-- Menu Body -->
                        <li class="p-t-3 nav-item" ><a href="{{ URL :: to('user_profile') }}" class="nav-link"> <i class="fa fa-fw fa-user"></i> My
                                Profile </a>
                        </li>
                        <li role="presentation "></li>
                        <li class="nav-item"><a href="{{ URL :: to('edit_user') }}" class="nav-link"> <span><i class="fa fa-fw fa-gear"></i> Account Settings</span>
                            </a></li>
                        <li role="presentation" class="dropdown-divider"></li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ URL :: to('lockscreen') }} ">
                                    <i class="fa fa-fw fa-lock"></i>
                                    Lock
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-fw fa-sign-out"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            <div id="menu" role="navigation">
                <div class="nav_profile">
                    <div class="media profile-left">
                        <a class="pull-left profile-thumb" href="#">
                            <img src="{{asset('assets/img/authors/avatar1.jpg')}}" class="rounded-circle" alt="User Image">
                        </a>
                        <div class="content-profile pl-3">
                            <h4 class="media-heading">
                                {{Auth::user()->name}}
                            </h4>
                            <ul class="icon-list list-inline">
                                <li>
                                    <a href="{{ URL::to('users') }} ">
                                        <i class="fa fa-fw fa-user"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::to('lockscreen') }} ">
                                        <i class="fa fa-fw fa-lock"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::to('edit_user') }} ">
                                        <i class="fa fa-fw fa-gear"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-fw fa-sign-out"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="navigation">
                    <li>
                        <a href="{{ url('/') }} ">
                            <i class="menu-icon fa fa-fw fa-home"></i>
                            <span class="mm-text ">Dashboard</span>
                        </a>
                    </li>
                    @foreach($menus as $key => $row)
                    <li>
                        @if(!$row->menu_url && !$row->menu_parent)
                        <a href="#">
                            <i class="menu-icon fa fa-check-square"></i>
                            <span>{{$row->menu_name}}</span>
                            <span class="fa arrow"></span>
                        </a>
                        @endif
                        <ul class="sub-menu">
                        @foreach($menus as $keys => $rows)
                            @if($rows->menu_parent == $row->id)
                                <li>
                                    <a href="{{ $rows->menu_url }}">
                                        <i class="fa fa-fw {{$row->menu_icon ? $row->menu_icon : 'fa-circle-o'}}"></i> {{$rows->menu_name}}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        </ul>
                    </li>
                    @endforeach
                    
                </ul>
                <!-- / .navigation -->
            </div>
            <!-- menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
    <aside class="right-side">
        <!-- Content -->
        @yield('content')
    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/advanced_modals.js')}}"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>
