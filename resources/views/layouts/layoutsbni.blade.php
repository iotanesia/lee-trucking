<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Report</title>
  <link rel="icon" href="{{asset('assets/img/brand/favicon.png')}}" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="{{asset('assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/argon.css?v=1.1.0" type="text/css')}}">
  <link rel="stylesheet" href="{{asset('daterangepicker/daterangepicker.css')}}" type="text/css">
  
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">

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

  .filter-aktifiti{
    cursor: pointer;
    box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;
  }
  </style>
  <script src="{{asset('js/jquery3.5.1.js')}}"></script>
</head>

<body>
<div class="preloader" style="display:none">
    <div class="loading">
        <img src="{{url('asssets')}}/img/tenor.gif" width="280">
        <center><p>Harap Tunggu</p></center>
    </div>
</div>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
     <!-- Sidenav -->
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="../../pages/dashboards/dashboard.html">
            <img src="../../assets/img/brand/blue.png" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
            <!-- Sidenav toggler -->
            <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                </div>
            </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Nav items -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-dashboards">
                        <i class="ni ni-chart-pie-35 text-info"></i>
                        <span class="nav-link-text">Report</span>
                    </a>
                    <div class="collapse show" id="navbar-dashboards">
                        <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{url('bni-dashboard')}}" class="nav-link">Report</a>
                        </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            </div>
        </div>
        </div>
    </nav>
    @yield("content")
    
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/js-cookie/js.cookie.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
  <!-- Optional JS -->
  <script src="{{asset('assets/vendor/chart.js/dist/Chart.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/dist/Chart.extension.js')}}"></script>
  <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
  <script src="{{url('assets/js/fslightbox.js')}}"></script>
  <script src="{{asset('assets/vendor/select2/dist/js/select2.min.js')}}"></script>
  <script src="{{asset('daterangepicker/moment.min.js')}}"></script>
  <script src="{{asset('daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-select/js/dataTables.select.min.js')}}"></script>
  <!-- Argon JS -->
  <script src="{{asset('assets/js/argon.js?v=1.1.0')}}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{asset('assets/js/demo.min.js')}}"></script>
</body>

</html>