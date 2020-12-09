<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Core + Admin Template
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <!--[endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
@yield('header_styles')
<!-- end of global css -->
</head>
<body class="skin-coreplus">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
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
                <li class="nav-item dropdown messages-menu">
                    <a href="#" class="nav-link dropdown-toggle"> <i
                                class="fa fa-fw fa-envelope-o black"></i>
                        <span class="label bg-success">2</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages table-striped">
                        <li class="dropdown-title">New Messages</li>
                        <li class="msg-set message striped-col">
                            <a href="" class="">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar7.jpg')}}"
                                    alt="avatar-image">

                                <div class="message-body"><strong>Ernest Kerry</strong>
                                    <br>
                                    Can we Meet?
                                    <br>
                                    <small>Just Now</small>
                                    <span class="label bg-success label-mini msg-lable">New</span>
                                </div>
                            </a>
                        </li>
                        <li class="msg-set message">
                            <a href="" class="">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar6.jpg')}}"
                                    alt="avatar-image">

                                <div class="message-body"><strong>John</strong>
                                    <br>
                                    Dont forgot to call...
                                    <br>
                                    <small>5 minutes ago</small>
                                    <span class="label bg-success label-mini msg-lable">New</span>
                                </div>
                            </a>
                        </li>
                        <li class="msg-set message striped-col">
                            <a href="" class="">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar5.jpg')}}"
                                    alt="avatar-image">

                                <div class="message-body">
                                    <strong>Wilton Zeph</strong>
                                    <br>
                                    If there is anything else &hellip;
                                    <br>
                                    <small>14/10/2014 1:31 pm</small>
                                </div>

                            </a>
                        </li>
                        <li class="msg-set message">
                            <a href="" class="">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar1.jpg')}}"
                                    alt="avatar-image">
                                <div class="message-body">
                                    <strong>Jenny Kerry</strong>
                                    <br>
                                    Let me know when you free
                                    <br>
                                    <small>5 days ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="msg-set message striped-col">
                            <a href="" class="">
                                <img class="message-image rounded-circle" src="{{asset('assets/img/authors/avatar.jpg')}}"
                                    alt="avatar-image">
                                <div class="message-body">
                                    <strong>Tony</strong>
                                    <br>
                                    Let me know when you free
                                    <br>
                                    <small>5 days ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="#">View All messages</a></li>
                    </ul>

                </li>
                <!--tasks-->
                <li class="nav-item dropdown tasks-menu d-none d-sm-block">
                    <a href="#" class=" nav-link dropdown-toggle">
                        <i class="fa fa-fw fa-edit black"></i>
                        <span class="label bg-primary">4</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li class="dropdown-title ">You Have 4 Tasks</li>
                        <li class="message striped-col">
                            <a href="" class=" ">
                                Design some buttons
                                <small class="pull-right">20%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar bg-primary" style="width: 20%"
                                            role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message">
                            <a href="" class="">
                                Create a nice theme
                                <small class="pull-right">40%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar bg-success" style="width: 40%"
                                            role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <span class="sr-only">40% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message striped-col">
                            <a href="" class="">
                                Some task I need to do
                                <small class="pull-right">60%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar bg-danger" style="width: 60%"
                                            role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <span class="sr-only">60% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message">
                            <a href="" class="">
                                Make beautiful transitions
                                <small class="pull-right">80%</small>
                                <div class="message-body">
                                    <div class="progress progress-xs progress_task">
                                        <div class="progress-bar bg-warning" style="width: 80%"
                                            role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <span class="sr-only">80% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="#"> View All Tasks</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown_message">
                    <a href="#" class="nav-link dropdown-toggle toggle-right">
                        <i class="fa fa-fw fa-comments-o black"></i>
                        <span class="label bg-danger">9</span>
                    </a>
                </li>
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
                        <a href="{{ URL::to('index') }} ">
                            <i class="menu-icon fa fa-fw fa-home"></i>
                            <span class="mm-text ">Dashboard V1</span>
                        </a>
                    </li>
                    <li>
                        @foreach($menus as $key => $row)
                        @if(!$row->menu_url)
                        <a href="#">
                            <i class="menu-icon fa fa-check-square"></i>
                            <span>{{$row->menu_name}}</span>
                            <span class="fa arrow"></span>
                        </a>
                        @else
                        <ul class="sub-menu">
                            <li {!! (Request::is('form_elements') ? 'class="active"' : '') !!}>
                                <a href="{{ $row->menu_url }}">
                                    <i class="fa fa-fw {{$row->menu_icon ? $row->menu_icon : 'fa-circle-o'}}"></i> {{$row->menu_name}}
                                </a>
                            </li>

                        </ul>
                        @endif
                        @endforeach
                    </li>
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
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>
