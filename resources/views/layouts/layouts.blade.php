<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Viexpedition</title>
  <link rel="icon" href="{{asset('assets/img/brand/favicon.png')}}" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="{{asset('assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/argon.css?v=1.1.0" type="text/css')}}">
  <link rel="stylesheet" href="{{asset('daterangepicker/daterangepicker.css')}}" type="text/css">
  
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
  <script src="{{asset('js/jquery3.5.1.js')}}"></script>
  <script>
      window.Laravel = {!! json_encode([
      "csrfToken" => csrf_token(),
      "api_token" => Auth::user()->tokens,
      "app_url" => url('/'),
      "group_id" => Auth::user()->group_id
      ]) !!};
  </script>
</head>

<body>
<div class="preloader" style="display:none">
    <div class="loading">
        <img src="{{url('asssets')}}/img/tenor.gif" width="280">
        <center><p>Harap Tunggu</p></center>
    </div>
</div>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="{{url('/')}}">
            <h2 class="text-blue"><b>Viexpedition</b></h2>
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
            @foreach($menus as $key => $row)
            <li class="nav-item">
              @if(!$row->menu_parent && $row->menu_name != 	"Mobile Expedition")
              <a class="nav-link" @if($row->menu_url) href="{{$row->menu_url}}" @else href="#navbar-dashboards-{{$row->id}}" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-dashboards-{{$row->id}}" @endif>
                <i class="ni ni-bullet-list-67 text-primary"></i>
                <span class="nav-link-text">{{$row->menu_name}}</span>
              </a>
              @endif
              @foreach($menus as $keys => $rows)
                @if($rows->menu_parent == $row->id)
                <div class="collapse" id="navbar-dashboards-{{$rows->menu_parent}}">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{ $rows->menu_url }}" class="nav-link">{{$rows->menu_name}}</a>
                    </li>
                  </ul>
                </div>
                @endif
              @endforeach
            </li>
            @endforeach
          </ul>
          <!-- Divider -->
          <!-- <hr class="my-3"> -->
          <!-- Heading -->
          <!-- <h6 class="navbar-heading p-0 text-muted">Documentation</h6> -->
          <!-- Navigation -->
        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-gradient-info border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Search form -->
          <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
            <div class="form-group mb-0">
              <div class="input-group input-group-alternative input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" placeholder="Search" type="text">
              </div>
            </div>
            <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </form>
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
            <li class="nav-item d-sm-none">
              <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="ni ni-zoom-split-in"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ni ni-bell-55"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                <!-- Dropdown header -->
                <div class="px-3 py-3">
                  <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">13</strong> notifications.</h6>
                </div>
                <!-- List group -->
                <div class="list-group list-group-flush">
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{asset('assets/img/theme/team-1.jpg')}}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                      </div>
                    </div>
                  </a>
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{asset('assets/img/theme/team-2.jpg')}}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>3 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">A new issue has been reported for Argon.</p>
                      </div>
                    </div>
                  </a>
                </div>
                <!-- View all -->
                <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="{{asset('assets/img/theme/team-4.jpg')}}">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">{{Auth::user()->name}}</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="{{url('/my-profile')}}" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#!" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div id="deletedModal" class="modal fade" style="z-index:99999">
        <div class="modal-dialog modal-confirm modal-dialog-centered" style="max-width:400px">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <img src="{{url('assets/new/img/ilustrationFailed.png')}}" class="rounded mx-auto d-block" alt="delete-icon" width="150">
                    <h4 class="modal-title w-100" style="margin-left:25%">Anda Yakin Menghapus?</h4>
                </div>
                <div class="modal-body">
                    <input type="text" hidden  name="id" value="12" id="id_delete">
                    <p>Data Yang sudah di Hapus tidak dapat di kembalikan lagi.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger btn-lg btn-deleted">Delete</button>
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="successModal" class="modal fade">
        <div class="modal-dialog modal-confirm modal-dialog-centered" style="max-width:400px">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <img src="{{url('assets/new/img/ilustrationSuccess.png')}}" class="rounded mx-auto d-block" alt="delete-icon" width="150">
                </div>
                <div class="modal-body">
                    <center>
                        <h2>Data Berhasil Di Simpan</h2>
                    </center>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-lg" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
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
  <!-- Argon JS -->
  <script src="{{asset('assets/js/argon.js?v=1.1.0')}}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{asset('assets/js/demo.min.js')}}"></script>
</body>

</html>