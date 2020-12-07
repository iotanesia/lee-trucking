<!DOCTYPE html>
<html>

<head>
    <title>::Admin Register::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
    <!-- global css -->
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <!-- end of global css -->
    <!--page level css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link href="{{asset('assets/css/register2.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/hover/css/hover-min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/buttons_sass1.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/advbuttons.css')}}">
    <!--end of page level css-->
</head>
<script>
    window.Laravel = {!! json_encode([
      "csrfToken" => csrf_token(),
      "app_url" => url('/'),
    ]) !!};
</script>

<body class="bg-slider">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row " id="form-login">
        <div class="col-lg-8 col-md-12  col-sm-12 bg-white  col-10 register-content mx-auto ">
            <div class="row">
                <div class="col-12">
                    <div class="header">
                        <h3 class="text-center"  style="color:#000">
                            <b>Sign @if(Route::currentRouteName() == 'login') In @else Up @endIf</b>
                            <small> with</small>
                            <img src="{{asset('assets/img/pages/tsj2.png')}}" alt="logo">
                        </h3>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-md-12">
                <center>
                    <div class="ui-group-buttons">
                        <a @if(Route::currentRouteName() == 'login') href="{{url('/register')}}" @endif class="btn btn-xs @if(Route::currentRouteName() == 'login') btn-primary hvr-pop @endif">
                            Register 
                        </a>
                        <div class="or or-xs"></div>
                        <a @if(Route::currentRouteName() == 'register') href="{{url('/login')}}" @endif class="btn btn-xs @if(Route::currentRouteName() == 'register') btn-success hvr-pop @endif">
                            Login
                        </a>
                    </div>
                </center>
            </div>
            @if(Route::currentRouteName() == 'register')
            <div class="row row-bg-color" id="register">
                <div class="col-lg-12 core-register">
                    <form class="form-horizontal" action="{{ route('register') }}" id="register_form">
                        <!-- CSRF Token -->
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label" for="user_name">USER NAME</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="User Name"
                                            name="name" id="user_name" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group ">
                                    <label class="control-label" for="email">EMAIL</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="Email Address" class="form-control" name="email"
                                            id="email" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group ">
                                    <label class="control-label" for="group_id">Group</label>
                                    <div class="input-group">
                                        <select name="group_id" class="form-control" id="">
                                            @foreach($group as $row)
                                            <option value="{{$row->id}}">{{$row->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row password">
                            <div class="col-lg-6 col-12 ">
                                <div class="form-group ">
                                    <label class="control-label" for="password">PASSWORD</label>
                                    <div class="input-group">
                                        <input type="password" placeholder="Password" class="form-control"
                                            name="password" id="password"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 pl-lg-0 pl-3">
                                <div class="form-group cp-group">
                                    <label class="control-label confirm_pwd" for="password_confirm">CONFIRM PASSWORD</label>
                                    <div class="input-group pull-right">
                                        <input type="password" placeholder="Confirm Password" class="form-control"
                                            name="password_confirmation" id="password_confirm"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group agree-formgroup mt-3 ">
                                <label class="checkbox-inline sr-only" for="terms">Agree to terms and conditions</label>
                                <input type="checkbox" value="1" name="terms" id="terms"/>&nbsp;
                                <label for="terms"> I agree to <a href="#section"> Terms and Conditions</a>.</label>
                        </div>
                        <div class="form-group ">
                                <button type="button" id="btn-save" class="btn btn-primary" >Sign Up</button>
                                <input type="reset" class="btn btn-default" value="Reset" id="dee1"/><br>
                                <hr>
                                <span> Already Have an account? <a href="{{URL::to('login')}}">Login</a></span>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @if(Route::currentRouteName() == 'login')
            <div class="row row-bg-color" id="login">
                <div class="col-lg-12 core-register">
                    <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                        <!-- CSRF Token -->
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group ">
                                    <label class="control-label" for="email">EMAIL</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="Email Address" class="form-control" name="email"
                                            id="email" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 ">
                                <div class="form-group ">
                                    <label class="control-label" for="password">PASSWORD</label>
                                    <div class="input-group">
                                        <input type="password" placeholder="Password" class="form-control"
                                            name="password" id="password"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary" >Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- begining of page level js -->
<script src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/backstretch.js')}}"></script>
<script src="{{asset('assets/js/custom_js/register.js')}}"></script>
<!-- end of page level js -->
<script>
  $("document").ready(function(){
    $("#btn-save").click(function(){
      var data = new FormData($("#register_form")[0]);

      $.ajax({
        url: window.Laravel.app_url + "/api/register",
        type: "POST",
        dataType: "json",
        data: data,
        processData: false,
        contentType: false,
        crossDomain: true,
        headers: {"Authorization": "Bearer " },
        success: function(datas, textStatus, xhr) {
          alert('berhasil');
          location.replace(window.Laravel.app_url+"/login")
        },
        error: function(datas, textStatus, xhr) {
          alert('gagal');
        }
      });
    })
  });
</script>
</body>
</html>
