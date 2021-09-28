@extends('layouts/layoutsbni')
@section('content')
<div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom" style="background-color:#f15a23 !important">
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
                        <img alt="Image placeholder" src="../../assets/img/theme/team-1.jpg" class="avatar rounded-circle">
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
                        <img alt="Image placeholder" src="../../assets/img/theme/team-2.jpg" class="avatar rounded-circle">
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
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="../../assets/img/theme/team-3.jpg" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>5 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">Your posts have been liked a lot.</p>
                      </div>
                    </div>
                  </a>
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="../../assets/img/theme/team-4.jpg" class="avatar rounded-circle">
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
                        <img alt="Image placeholder" src="../../assets/img/theme/team-5.jpg" class="avatar rounded-circle">
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
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ni ni-ungroup"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default dropdown-menu-right">
                <div class="row shortcuts px-4">
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                    <small>Calendar</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                      <i class="ni ni-email-83"></i>
                    </span>
                    <small>Email</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                      <i class="ni ni-credit-card"></i>
                    </span>
                    <small>Payments</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                      <i class="ni ni-books"></i>
                    </span>
                    <small>Reports</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                      <i class="ni ni-pin-3"></i>
                    </span>
                    <small>Maps</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                      <i class="ni ni-basket"></i>
                    </span>
                    <small>Shop</small>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="../../assets/img/theme/team-4.jpg">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">John Snow</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-settings-gear-65"></i>
                  <span>Settings</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-calendar-grid-58"></i>
                  <span>Activity</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-support-16"></i>
                  <span>Support</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
    <!-- Header -->
    <div class="header bg-primary pb-6" style="background-color:#f15a23 !important">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Report</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Report</a></li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral" style="background-color:#005e6a !important; color:#ffffff !important">New</a>
              <a href="#" class="btn btn-sm btn-neutral" style="background-color:#005e6a !important; color:#ffffff !important">Filters</a>
            </div>
          </div>
          <!-- Card stats -->
          <div class="row">
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-12">
          <div class="card bg-default" style="background-color:#ffffff !important">
            <div class="card-header bg-transparent" style="background-color:#005e6a !important">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-light text-uppercase ls-1 mb-1">Report SL</h6>
                  <h5 class="h3 text-white mb-0">Overview</h5>
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li class="nav-item mr-2 mr-md-0">
                      <a href="{{url('bni-dashboard-detail')}}" class="nav-link py-2 px-3 active" style="background-color:#ffffff !important; color: #005e6a !important; font-weight:600 !important">
                        <span class="d-none d-md-block">See All</span>
                        <span class="d-md-none">M</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
                <div class="chart-container" >
                    <canvas id="bar-chart" class="chart-canvas"></canvas>
                </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-xl-12">
          <div class="card" id="expedition">
            <div class="card-header bg-transparent" style="background-color:#005e6a !important">
              <div class="row align-items-center">
                  <h5 class="h3 mb-0" style="color:#ffffff !important;padding-left: 10px;">Tabel Data SL</h5><br/>
                  <!-- <ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm">
                    <li class="nav-item submenu">
                        <a class="nav-link active show" id="user-ad" data-toggle="tab" href="#ad" role="tab" aria-selected="true">BO</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-up" data-toggle="tab" href="#up" role="tab" aria-selected="false">BA</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-ud" data-toggle="tab" href="#ud" role="tab" aria-selected="false">BJ</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-uf" data-toggle="tab" href="#uf" role="tab" aria-selected="false">BF</a>
                    </li>
                  </ul> -->
              </div>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div id="ad" class="tab-pane in active">
                  <form id="form-export-bo" method="get" action="{{url('export-bo')}}">
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeFileBO" id="tipeFileBO" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <input class="form-control" name="noInvoiceBO" id="noInvoiceBO" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeBO" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-select-bo" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="NON_TUNAI">NON TUNAI</option>
                            </select>
                          </div>     
                           <!-- <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-bo" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div> -->
                          <!-- <div class="col-md-2" >
                            <select class="form-control" id="filter-export-bo" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
                          </div> -->
                        <div id="tag-cloud-widget" class="col-md-2">  
                          <div class="content">  
                            <a class="nav-link input-group input-group-alternative input-group-merge" href="#" style="padding: .37rem .75rem;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="fas fa-file-export"></i>
                                </span>
                                <div class="media-body d-none d-lg-block" style="margin-top: 4px;margin-right: 20px;text-align:center">
                                  <span class="mb-0 text-sm  font-weight-bold">Eksport</span>
                                </div>
                              </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left">
                              <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Eksport</h6>
                              </div>
                              <a href="#" id="is-excel-bo" class="dropdown-item">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <br/><br/><br/>
                        <!-- <div style='margin-left:10px'>
                            <label class="switch">
                              <input type="checkbox" id="cbPpn10Bo" checked>
                              <span class="slider round">PPN 10%</span>
                            </label>
                            <label class="switch">
                              <input type="checkbox" id="cbPph23Bo" checked>
                              <span class="slider round">PPH 23</span>
                            </label>
                        </div> -->
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-bo" son-success-load="successLoadexpedition" width="100%">
                      <thead class="bg-gradient-info text-white" style="background:linear-gradient(90deg, #005e6a 0, #f15a23 100%) !important">
                      <tr>
                          <th>No</th>
                          <th>Wil</th>
                          <th>Unit</th>
                          <th>Produk</th>
                          <th>CIF</th>
                          <th>No Rek</th>
                          <th>Nama Nasabah</th>
                          <th>Kol</th>
                          <th>Maks Krd</th>
                          <th>BK Debit</th>
                          <th>Retrukturasi</th>
                          <th>Flag Covid</th>
                          <th>Desk Flag Covid</th>
                          <th>Flag</th>
                          <th>Dates</th>
                      </tr>
                      </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
              &copy; 2019 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
              </li>
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </div>
<script src="{{url('assets/chartJs/Chart.min.js')}}"></script>

<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('assets/vendor/select2/dist/js/select2.min.js')}}"></script>
<script>
    var exBln = {!! json_encode($sl_label) !!};
    var exCount = {!! json_encode($sl_count) !!};
    var total_truck = [1,2,3,4];
    var cabang = [1,2,3,4];
    // console.log(total_truck)
    new Chart(document.getElementById("bar-chart"), {
        type: 'horizontalBar',
        data: {
        labels: exBln,
        datasets: [
                {
                    label: "SL",        
                    borderColor: "#3e95cd",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850", "#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850", "#3e95cd", "#8e5ea2"],
                    data: exCount,        
                    fill: true
                }
            ]
        },
        options: {
            legend: { display: false },
            indexAxis: 'y',
            title: {
                display: true,
                text: 'Report SL'
            },
            scales: {
                yAxes: [{
                    ticks: {
                       beginAtZero: true
                    }
                }]
            },
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Chart.js Horizontal Bar Chart'
                }
            }
        }
    });

    new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
        labels: cabang,
        datasets: [{
            label: "Truk",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850", "#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850", "#3e95cd", "#8e5ea2"],
            data: total_truck
        }]
        },
        options: {
            title: {
                display: true,
                text: 'Total Truk'
            }
        }
    });
</script>
<script>
  
  $(document).ready(function() {  
   var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var fd = new Date(y, m, 1);
    var firstDay = formatDate(fd);
    var ld = new Date(y, m + 1, 0);
    var lastDay = formatDate(ld);

    var startDateBO = formatDateReq(firstDay);
    var endDateBO = formatDateReq(lastDay);

    var table = $('#table-invoice-bo').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: window.Laravel.app_url + "/api/bni/get-data",
      type: "GET",
      data: function (d) {
        d.start_date = startDateBO;
        d.end_date = endDateBO;
    },
      crossDomain: true,
    },
    columns: [
        {
          "data": null, "sortable": false,
            render: function (data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {"data":"wil"},
        {"data":"unit"},
        {"data":"produk"},
        {"data":"cif"},
        {"data":"no_rek"},
        {"data":"nama_nasabah"},
        {"data":"kol"},  
        {
          "data":"MaksKrd", render: function (data, type, row, meta) {
            return convertToRupiah(data);
          }
        },
        {
          "data":"bk_debit", render: function (data, type, row, meta) {
            return convertToRupiah(data);
          }
        },
        {"data":"restrukturisasi"},
        {"data":"flag_covid"},
        {"data":"desk_flag_covid"},
        {"data":"flag"},
        {
          "data":"dates", render: function (data, type, row, meta) {
            return formatDate(data);
          }
        },
    ],
  
    scrollCollapse: false,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
    },
  });

  
  $(function() {
    $('input[name="dateRangeBO"]').daterangepicker({
      opens: 'right',
      showDropdowns: true,
    locale: {
        format:'DD MMMM YYYY',
        separator:' - ',
        applyLabel: 'Pilih',
        cancelLabel: 'Batal',
        customRangeLabel:'Custom',
        daysOfWeek:[
            'Min',
            'Sen',
            'Sel',
            'Rab',
            'Kam',
            'Jum',
            'Sab'
        ],
        monthNames:[
          'January',
          'February',
          'March',
          'April',
          'May',
          'June',
          'July',
          'August',
          'September',
          'October',
          'November',
          'December'
      ],
        firstDay:'1'
    },
      startDate: formatDate(firstDay),
      endDate: formatDate(lastDay)
    },
    function(start, end, label) {
      startDateBO = start.format('YYYY-MM-DD');
      endDateBO = end.format('YYYY-MM-DD');
      $('#table-invoice-bo').DataTable().ajax.reload();
    });
  });

  function formatDate(date) {
    var d = new Date(date),
        bulan = d.getMonth(),
        day = '' + d.getDate(),
        year = d.getFullYear();

        switch(bulan) {
          case 0: bulan = "January"; break;
          case 1: bulan = "February"; break;
          case 2: bulan = "March"; break;
          case 3: bulan = "April"; break;
          case 4: bulan = "May"; break;
          case 5: bulan = "June"; break;
          case 6: bulan = "July"; break;
          case 7: bulan = "August"; break;
          case 8: bulan = "September"; break;
          case 9: bulan = "October"; break;
          case 10: bulan = "November"; break;
          case 11: bulan = "December"; break;
        }

 
    if (day.length < 2) 
        day = '0' + day;
    var result = [day, bulan, year].join(' ');
    // console.log(result);
    return result;
  }

  function formatDateReq(date) {
    var d = new Date(date),
    month = '' + (d.getMonth()+1),
    day = '' + d.getDate(),
    year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }

  $("#is-excel-bo").click(function(e) {
    e.preventDefault();
    // alert("excel");
    $("#tipeFileBO").val("excel");
   
    // return false;
  });

  function convertToRupiah(angka)
  {
    var rupiah = '';		
    var angkarev = angka.toString().split('').reverse().join('');
    for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
    return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
  }
});
</script>
@endsection