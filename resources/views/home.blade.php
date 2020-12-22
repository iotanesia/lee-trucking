@extends('layouts/default')

    {{-- Page title --}}
    @section('title')

    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
{{--    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" />--}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/chartist/css/chartist.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/morrisjs/morris.css')}}" />
{{--    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/awesomebootstrapcheckbox/css/awesome-bootstrap-checkbox.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/dashboard1.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/awesomebootstrapcheckbox/css/build.css')}}">

    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        
        <!--section ends-->
        <section class="content sec-mar">
            <div class="row">
                <div class="col-md-12">
                    <div class="row tiles-row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 tile-bottom">
                            <div class="canvas-interactive-wrapper1">
                                <canvas id="canvas-interactive1"></canvas>
                                <div class="cta-wrapper1">
                                    <div class="widget" data-count=".num" data-from="0"
                                         data-to="99.9" data-suffix="%" data-duration="2">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-fw fa-shopping-cart fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box" class="count-box">119</div>
                                                <span class="title">Today Expedition</span>
                                            </div>
                                            <div class="text-center">
                                                <span><i class="fa fa-level-up" aria-hidden="true"></i></span>
                                                <strong>12 more Expedition</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 tile-bottom">
                            <div class="widget" data-count=".num" data-from="0"
                                 data-to="512" data-duration="3">
                                <div class="canvas-interactive-wrapper2">
                                    <canvas id="canvas-interactive2"></canvas>
                                    <div class="cta-wrapper2">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-fw fa-paw fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box2" class="count-box">316</div>
                                                <span class="title">Repair Truck</span>
                                            </div>
                                            <div class="text-center">
                                                <span><i class="fa fa-level-up" aria-hidden="true"></i></span>
                                                <strong>60 Bounce Rate</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 tile-bottom">
                            <div class="widget" data-suffix="k" data-count=".num"
                                 data-from="0" data-to="310" data-duration="4" data-easing="false">
                                <div class="canvas-interactive-wrapper3">
                                    <canvas id="canvas-interactive3"></canvas>
                                    <div class="cta-wrapper3">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-fw fa-usd fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box3" class="count-box">544</div>
                                                <span class="title">Total income</span>
                                            </div>
                                            <div class="text-center">
                                                <span><i class="fa fa-level-up" aria-hidden="true"></i></span>
                                                <strong>120 more income</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 tile-bottom">
                            <div class="widget">
                                <div class="canvas-interactive-wrapper4">
                                    <canvas id="canvas-interactive4"></canvas>
                                    <div class="cta-wrapper4">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-bar-chart-o fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box4" class="count-box">1598</div>
                                                <span class="title">Total Truck</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Site Activity</h3>
                            <ul class="nav nav-tabs nav-float " dir="rtl" role="tablist" id="myTab" >
                                <li class="nav-item">
                                    <a href="#profile" id="profile-tab" role="tab" data-toggle="tab" class="nav-link active" aria-controls="home" aria-selected="true">Sales </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade in show active" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xl-8 col-md-12 col-12 sales-tab">
                                            <div id="basicFlotLegend"></div>
                                            <div id="placeholder" style="width:100%; height: 291px"></div>
                                        </div>
                                        <div class="col-xl-4 col-md-12 col-xs-12">
                                            <div id="donut" style="width:94%; height: 300px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-sm-6">
                    <div class="card panel-widget">
                        <div class="card-header">
                            <h3 class="card-title">Dedicated Server Load</h3>
                        </div>
                        <div class="card-body">
                            <div class="server-load">
                                
                            </div>
                            <div>
                                <div id="flotchart2"></div>
                            </div>
                            <div class="col-12 server-buttons">
                                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6">
                    <div class="card panel-widget">
                        <div class="card-header revenue">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="row">
                                    <div class="col-md-6 col-6 rev-divider card-title">
                                        <h4>$365.76</h4>
                                        <small>Gross Revenue</small>
                                    </div>
                                    <div class="col-md-6 col-6 card-title">
                                        <h4>$245.34</h4>
                                        <small>Net Revenue</small>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="revenue-chart">
                            <div class="ct-chart1 ct-perfect-fourth ct-perfect-fourth1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 col-sm-12 task_panel">
                    <div class="card panel-widget">
                        <div class="card-header">
                            <h3 class="card-title">Tasks </h3>
                        </div>
                        <div class="card-body task-row">
                            <form class="row list_of_items">
                                <div class="todolist_list showactions">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-8 nopadmar custom_textbox1">

                                        <div class="todoitemcheck form-check abc-checkbox abc-checkbox-info">
                                            <input class="form-check-input striked" id="checkbox4" type="checkbox">
                                            <label class="form-check-label sr-only" for="checkbox4"></label>
                                        </div>
                                        <div class="todotext  todoitemjs"> Advertisement</div>
                                    </div>
                                    <div class="col-lg-2 col-md-2  col-sm-2 col-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-8 nopadmar custom_textbox1">
                                        {{--<div class="todoitemcheck checkbox checkbox-info">--}}
                                            {{--<input type="checkbox" class="striked">--}}
                                            {{--<label>--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                        <div class="todoitemcheck form-check abc-checkbox abc-checkbox-info">
                                            <input class="form-check-input striked" id="checkbox4" type="checkbox">
                                            <label class="form-check-label sr-only" for="checkbox4"></label>
                                        </div>
                                        <div class="todotext  todoitemjs">Get-together</div>
                                    </div>
                                    <div class="col-lg-2 col-md-2  col-sm-2 col-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-8 nopadmar custom_textbox1">
                                        {{--<div class="todoitemcheck checkbox checkbox-info">--}}
                                            {{--<input type="checkbox" class="striked">--}}
                                            {{--<label>--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                        <div class="todoitemcheck form-check abc-checkbox abc-checkbox-info">
                                            <input class="form-check-input striked" id="checkbox4" type="checkbox">
                                            <label class="form-check-label sr-only" for="checkbox4"></label>
                                        </div>
                                        <div class="todotext  todoitemjs"> Test Crew Meet</div>
                                    </div>
                                    <div class="col-lg-2 col-md-2  col-sm-2 col-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-8 nopadmar custom_textbox1">
                                        {{--<div class="todoitemcheck checkbox checkbox-info">--}}
                                            {{--<input type="checkbox" class="striked">--}}
                                            {{--<label>--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                        <div class="todoitemcheck form-check abc-checkbox abc-checkbox-info">
                                            <input class="form-check-input striked" id="checkbox4" type="checkbox">
                                            <label class="form-check-label sr-only" for="checkbox4"></label>
                                        </div>
                                        <div class="todotext  todoitemjs">Technical</div>
                                    </div>
                                    <div class="col-lg-2 col-md-2  col-sm-2 col-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-8 nopadmar custom_textbox1">
                                        {{--<div class="todoitemcheck checkbox checkbox-info">--}}
                                            {{--<input type="checkbox" class="striked">--}}
                                            {{--<label>--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                        <div class="todoitemcheck form-check abc-checkbox abc-checkbox-info">
                                            <input class="form-check-input striked" id="checkbox4" type="checkbox">
                                            <label class="form-check-label sr-only" for="checkbox4"></label>
                                        </div>
                                        <div class="todotext  todoitemjs">Non-Technical</div>
                                    </div>
                                    <div class="col-lg-2 col-md-2  col-sm-2 col-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="todolist_list adds task add_new">
                                <form role="form" id="main_input_box" class="form-inline row mx-0">
                                    <div class="form-group pull-left col-lg-8 col-md-9 col-sm-9 col-6 px-2">
                                        <label class="control-label sr-only" for="custom_textbox">Add Task</label>
                                        <input id="custom_textbox" name="item" type="text" required
                                               placeholder="Add list item here" class="form-control"/>
                                    </div>
                                    <span class="col-lg-3 col-md-3 col-sm-3 col-3  ">
                                        <input type="submit" value="Add Task"
                                               class="btn btn-primary add_button add_task"/>
                                        <input type="button" value="Save" class="btn btn-info save_todo"/>
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-sm-6 col-12">
                    <div class="card panel-widget">
                        <div class="card-header">
                            <h3 class="card-title">Project Status</h3>
                        </div>
                        <div class="card-body">
                            <div id="hero-bar" class="graph"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card panel-widget">
                        <div class="card-header">
                            <h3 class="card-title">Top Driver</h3>
                        </div>
                        <div class="card-body">
                            <ul class="basic-list">
                                <li><img src="{{asset('assets/img/chrome.png')}}" alt="chrome">Google Chrome
                                    <span class="right badge badge-success text-white pull-right">42.8%</span></li>
                                <li><img src="{{asset('assets/img/firefox.png')}}" alt="firefox">Firefox
                                    <span class="right badge badge-danger text-white pull-right">16.9%</span></li>
                                <li><img src="{{asset('assets/img/safari.png')}}" alt="safari">Safari
                                    <span class="right badge badge-primary text-white pull-right">15.5%</span></li>
                                <li><img src="{{asset('assets/img/opera.png')}}" alt="opera">Opera
                                    <span class="right badge badge-info text-white pull-right">11.8%</span></li>
                                <li><img src="{{asset('assets/img/Ie.png')}}" alt="Internet Explorer">Internet Explorer
                                    <span class="right badge badge-danger text-white pull-right">3.2%</span></li>
                                <li><img src="{{asset('assets/img/mobile.png')}}" alt="mobile">Mobile
                                    <span class="right badge badge-warning text-white pull-right">3%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 col-md-12 col-12 social-widgets">
                    <div class="social-tiles">
                        <div class="social google">
                            <i class="fa fa-google" aria-hidden="true"></i>
                            <span class="visible-cont pull-right text-center"><span>6.3k</span><br>Connected</span>
                            <span class="overlay text-center"><span>6.3k</span><br>Connected</span>
                        </div>
                        <div class="social facebook">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                            <span class="visible-cont pull-right text-center"><span>5.7k</span><br>Likes</span>
                            <span class="overlay text-center"><span>5.7k</span><br>Likes</span>
                        </div>
                        <div class="social twitter">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                            <span class="visible-cont pull-right text-center"><span>3.3k</span><br>Followers</span>
                            <span class="overlay text-center"><span>3.3k</span><br>Followers</span>
                        </div>
                        <div class="social linkedin">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                            <span class="visible-cont pull-right text-center"><span>2.3k</span><br>Connected</span>
                            <span class="overlay text-center"><span>2.3k</span><br>Connected</span>
                        </div>
                        <div class="social youtube">
                            <i class="fa fa-youtube-square" aria-hidden="true"></i>
                            <span class="visible-cont pull-right text-center"><span>7.3k</span><br>Hits</span>
                            <span class="overlay text-center"><span>7.3k</span><br>Hits</span>
                        </div>
                        <div class="social dribbble">
                            <i class="fa fa-dribbble" aria-hidden="true"></i>
                            <span class="visible-cont pull-right text-center"><span>100</span><br>Shots</span>
                            <span class="overlay text-center"><span>100</span><br>Shots</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<div id="qn"></div>
<!-- begining of page level js -->
<script src="{{asset('assets/js/backstretch.js')}}"></script>
<!--sales tiles-->
<script type="text/javascript" src="{{asset('assets/vendors/countupcircle/js/jquery.countupcircle.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/granim/js/granim.min.js')}}"></script>
<!-- end of sales tiles -->
<!-- Flot tab2-->
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.time.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.symbol.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.pie.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.stack.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flot.tooltip/js/jquery.flot.tooltip.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotspline/js/jquery.flot.spline.min.js')}}" ></script>
<!-- end of flot tab2 -->
<script type="text/javascript" src="{{asset('assets/vendors/chartist/js/chartist.min.js')}}"></script>
<!--morris donut-->
<script type="text/javascript" src="{{asset('assets/vendors/morrisjs/morris.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/raphael.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/nvd3/js/nv.d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/stream_layers.js')}}"></script>
<!--maps-->
<script src="{{asset('assets/vendors/bower-jvectormap/js/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('assets/vendors/bower-jvectormap/js/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- end of maps -->
<script type="text/javascript" src="{{asset('assets/js/dashboard1.js')}}"></script>
    <!-- end of page level js -->
@stop

<script>
  window.Laravel = {!! json_encode([
    "csrfToken" => csrf_token(),
    "app_url" => url('/'),
  ]) !!};

  function menuDetail(id) {
    $.ajax({
      url: window.Laravel.app_url + "/menu/get-list-detail",
      type: "GET",
      data: 'id'+'='+id,
      dataType: "json",
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoadMenuDetail(data);
      },
    });
  }
  
  $("#search").keyup(function(e){
    var data = $(this).val();
    if(e.keyCode == 13) {
      $.ajax({
        url: window.Laravel.app_url + "/menu/get-list-search",
        type: "GET",
        data: 'where_value'+'='+data,
        dataType: "json",
        crossDomain: true,
        beforeSend: function( xhr ) { 
          $('.preloader').show();
        },
        success: function(data, textStatus, xhr) {
          $('.preloader').hide();
          successLoadMenu(data);
        },
      });
    }
  });

  var successLoadMenuDetail = (function(responses, dataModel) {
    var blockContent = "";
    var blockTitle = "";
    var responses = responses.responses;
    
    menu_code = responses.menu_code;
    menu = responses.menu;
    content_title = responses.content_title;
    content = responses.content;
    id = responses.id;
    
    
    blockTitle += menu +
                  "<div class='page-title-subheading'>Pertamina</div>";

    blockContent += "<div class='card-body'>"+
                      content+
                    "</div>";

    $("#blockContent").html(blockContent);
    $("#blockTitle").html(blockTitle);
  });

  var successLoadMenu = (function(responses, dataModel) {
    var blockTitle = "";
    var blockContent = "";
    var htmllinkRows = "";
    var menuFirst = "";
    var content_titleFirst = "";
    var contentFirst = "";
    var responses = responses.responses;
    
    for(var i = 0; i < responses.length; i++) {
      menu_code = responses[i].menu_code;
      menu = responses[i].menu;
      content_title = responses[i].content_title;
      content = responses[i].content;
      id = responses[i].id;

      menuFirst = responses[0].menu;
      content_titleFirst = responses[0].content_title;
      contentFirst = responses[0].content;

      htmllinkRows += "<li>"+
                        "<a href='#"+menu_code+"' onClick='menuDetail("+id+")' class=''>"+
                        "<i class='metismenu-icon pe-7s-monitor'></i>"+
                            menu+
                        "</a>"+
                      "</li>";
    }

    blockTitle += menuFirst +
                  "<div class='page-title-subheading'>Pertamina</div>";

    blockContent += "<div class='card-body slide-text'>"+
                      contentFirst+
                    "</div>";

    $("#blockContent").html(blockContent);
    $("#blockTitle").html(blockTitle);
    $("#blockLinkHtml").html(htmllinkRows);
  });

</script>
