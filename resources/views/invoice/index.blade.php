@extends('layouts/layouts')
@section('content')
<style>
  .modal-dialog {
      max-width: 80%;
      height: 100%;
  }
  .toolbar {
    float: left;
  } 
  .card .table td, .card .table th {
    padding-left: 1.2rem !important;
    padding-right: 1.2rem !important;
    padding-top: 0.7rem !important;
    padding-bottom: 0.7rem !important;
  }
  </style>
    <div class="header bg-gradient-info pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">{{$title}}</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
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
          <div class="card" id="expedition"    >
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Data {{$title}}</h6>
                  <h5 class="h3 mb-0">Table {{$title}}</h5><br/>
                  <ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm">
                    <li class="nav-item submenu">
                        <a class="nav-link active show" id="user-ad" data-toggle="tab" href="#ad" role="tab" aria-selected="true">BO</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-up" data-toggle="tab" href="#up" role="tab" aria-selected="false">BA</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-ud" data-toggle="tab" href="#ud" role="tab" aria-selected="false">BJ</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
            <div class="tab-content">
              <div id="ad" class="tab-pane in active">
                {{-- <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                  <div class="form-group mb-0" style="border-radius:0.5rem;box-shadow: 0 1px 3px rgb(50 50 93 / 65%), 0 1px 0 rgb(0 0 0 / 2%);">
                    <div class="input-group input-group-alternative input-group-merge" style="border-radius:0.5rem;">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input class="form-control" placeholder="Nomor Invoice" type="text">
                    </div>
                  </div>
                  <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </form>
                <br/> --}}
                   <form id="form-export-bo" method="get" action="{{url('export-bo')}}">
                      <div class="input-group input-group-alternative input-group-merge col-md-4" 
                          style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                          <input class="form-control" name="dateRangeBO" placeholder="Pilih Rentang Tanggal" type="text" style="text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                      
                      </div>
                   </form>
                    <br/>
                    <div id="tag-cloud-widget">  
                      <div class="content">  
                        <a href="#" onclick="($('#form-export-bo').submit())" class="btn btn-primary" id="export-bo">Export</a>
                      </div>
                    </div>
                    <br/>
                    <table class="table table-responsive align-items-center table-striped" id="table-invoice-bo" son-success-load="successLoadexpedition" width="100%">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Surat Jalan</th>
                            <th>Tujuan</th>
                            <th>Plat</th>
                            <th>Qty Palet</th>
                            <th>Rit</th>
                            <th>Nama Toko</th>
                            <th>Harga/Rit</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr style="font-weight:bold">
                              <td style="text-align:left">
                              <td></td>
                              <td></td>
                              <td style="text-align:center"></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                          </tr>
                          <tr style="font-weight:bold">
                            <td style="text-align:left">
                                <td></td>
                                <td></td>
                                <td style="text-align:center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr style="font-weight:bold">
                                <td style="text-align:left">
                                <td></td>
                                <td></td>
                                <td style="text-align:center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="color:#f02e2e;"></td>
                        </tr>
                        <tr style="font-weight:bold">
                            <td style="text-align:left">
                            <td></td>
                            <td></td>
                            <td style="text-align:center"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="background-color:#f2de68;"></td>
                        </tr>
                      </tfoot>
                    </table>
                </div>
                <div id="up" class="tab-pane in fade">
                  <form id="form-export-ba" method="get" action="{{url('export-ba')}}">
                    <div class="input-group input-group-alternative input-group-merge col-md-4" 
                          style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input class="form-control" name="dateRangeBA" placeholder="Pilih Rentang Tanggal" type="text" style="text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                    </div>
                  </form>
                    <br/>
                    <div id="tag-cloud-widget">  
                      <div class="content">  
                        <a href="#" onclick="($('#form-export-ba').submit())" class="btn btn-primary" id="export-ba">Export</a>
                      </div>
                    </div>
                    <br/>
                    <table class="table table-responsive align-items-center table-striped" id="table-invoice-ba" son-success-load="successLoadexpedition" width="100%">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Surat Jalan</th>
                            <th>Tujuan</th>
                            <th>Plat</th>
                            <th>Qty Palet</th>
                            <th>Rit</th>
                            <th>Nama Toko</th>
                            <th>Harga/Rit</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr style="font-weight:bold">
                            <td style="text-align:left">
                            <td></td>
                            <td></td>
                            <td style="text-align:center"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="font-weight:bold">
                            <td style="text-align:left">
                                <td></td>
                                <td></td>
                                <td style="text-align:center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr style="font-weight:bold">
                            <td style="text-align:left">
                                <td></td>
                                <td></td>
                                <td style="text-align:center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="color:#f02e2e;"></td>
                        </tr>
                        <tr style="font-weight:bold">
                            <td style="text-align:left">
                                <td></td>
                                <td></td>
                                <td style="text-align:center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="background-color:#f2de68;"></td>
                        </tr>
                        </tfoot>
                        </table>
                    </div>
                    <div id="ud" class="tab-pane fade">
                      <form id="form-export-bj" method="get" action="{{url('export-bj')}}">
                        <div class="input-group input-group-alternative input-group-merge col-md-4" 
                            style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                          <input class="form-control" name="dateRangeBJ" placeholder="Pilih Rentang Tanggal" type="text" style="text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        </div>
                      </form>
                      <br/>
                      <div id="tag-cloud-widget">  
                        <div class="content">  
                          <a href="#" onclick="($('#form-export-bj').submit())" class="btn btn-primary" id="export-bj">Export</a>
                        </div>
                      </div>
                      <br/>
                        <table class="table table-responsive align-items-center table-striped" id="table-invoice-bj" son-success-load="successLoadexpedition" width="100%">
                            <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Surat Jalan</th>
                                <th>Tujuan</th>
                                <th>Plat</th>
                                <th>Qty Palet</th>
                                <th>Rit</th>
                                <th>Nama Toko</th>
                                <th>Harga/Rit</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr style="font-weight:bold">
                                  <td style="text-align:left">
                                  <td></td>
                                  <td></td>
                                  <td style="text-align:center"></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                          </tfoot>
                        </table>
                </div>
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
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Lee-Tracking</a>
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Contact</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="{{asset('js/event.js')}}"></script>
  <script src="{{asset('js/invoice-report.js')}}"></script>
  <script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection

