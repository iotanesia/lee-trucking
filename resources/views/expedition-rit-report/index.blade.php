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
                  <h5 class="h3 mb-0">Tabel {{$title}}</h5><br/>
                  <ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm">
                    <li class="nav-item submenu">
                        <a class="nav-link active show" id="user-ad" data-toggle="tab" href="#ad" role="tab" aria-selected="true">Rit Tujuan</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-up" data-toggle="tab" href="#up" role="tab" aria-selected="false">Rit Truck</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-ud" data-toggle="tab" href="#ud" role="tab" aria-selected="false">Rit Driver</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
            <div class="tab-content">
              <div id="ad" class="tab-pane in active">
                <form id="form-export-rit-tujuan" method="get" action="{{url('export-rit-tujuan')}}">
                <div class="row">
                      <div class="col-md-4">
                        <input class="form-control" name="tipeFileRitTujuan" id="tipeFileRitTujuan" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <div class="input-group input-group-alternative input-group-merge" 
                            style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                          <div class="input-group-prepend" style="margin-left: 10px;">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                            <input class="form-control" name="dateRangeRitTujuan" placeholder="Pilih Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        </div>
                      </div>
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
                          <a href="#" id="is-excel-rit-tujuan" class="dropdown-item">
                            <i class="fas fa-file-excel"></i>
                            <span>Excel</span>
                          </a>
                          <div class="dropdown-divider"></div>
                          <a href="#" id="is-pdf-rit-tujuan" class="dropdown-item">
                          <i class="fas fa-file-pdf"></i>
                            <span>PDF</span>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                    <br/>
                    <table class="table table-responsive align-items-center table-striped" id="table-rit-tujuan" son-success-load="successLoadexpedition" width="100%">
                        <thead class="bg-gradient-info text-white">
                        <tr style="text-align:center">
                            <th>No</th>
                            <th>Kabupaten</th>
                            <th>Kecamatan</th>
                            <th>Total Ekspedisi</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody style="text-align:center"></tbody>
                    </table>
                </div>
                <div id="up" class="tab-pane in fade">
                  <form id="form-export-rit-truck" method="get" action="{{url('export-rit-truck')}}">
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeRitTruck" id="tipeRitTruck" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeRitTruck" placeholder="Pilih Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
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
                              <a href="#" id="is-excel-rit-truck" class="dropdown-item">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              <div class="dropdown-divider"></div>
                              <a href="#" id="is-pdf-rit-truck" class="dropdown-item">
                              <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                    <br/>
                    <table class="table table-responsive align-items-center table-striped" id="table-rit-truck" son-success-load="successLoadexpedition" width="100%">
                        <thead class="bg-gradient-info text-white">
                        <tr style="text-align:center">
                            <th>No</th>
                            <th>Nama Truck</th>
                            <th>Nomor Plat</th>
                            <th>Total Ekspedisi</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody style="text-align:center"></tbody>
                        </table>
                    </div>
                    <div id="ud" class="tab-pane fade">
                      <form id="form-export-rit-driver" method="get" action="{{url('export-rit-driver')}}">
                        <div class="row">
                              <div class="col-md-4">
                                <input class="form-control" name="tipeFileRitDriver" id="tipeFileRitDriver" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                                <div class="input-group input-group-alternative input-group-merge" 
                                    style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                  <div class="input-group-prepend" style="margin-left: 10px;">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                  </div>
                                    <input class="form-control" name="dateRangeRitDriver" placeholder="Pilih Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                                </div>
                              </div>
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
                                  <a href="#" id="is-excel-rit-driver" class="dropdown-item">
                                    <i class="fas fa-file-excel"></i>
                                    <span>Excel</span>
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a href="#" id="is-pdf-rit-driver" class="dropdown-item">
                                  <i class="fas fa-file-pdf"></i>
                                    <span>PDF</span>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      <br/>
                        <table class="table table-responsive align-items-center table-striped" id="table-rit-driver" son-success-load="successLoadexpedition" width="100%">
                            <thead class="bg-gradient-info text-white">
                            <tr style="text-align:center">
                                <th>No</th>
                                <th>Nama Driver</th>
                                <th>Total Ekspedisi</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody style="text-align:center"></tbody>
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
              &copy; {{date('Y')}} <a href="http://liexpedition.com/" class="font-weight-bold ml-1" target="_blank">Lee-Tracking</a>
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="http://liexpedition.com/" class="nav-link" target="_blank">Contact</a>
              </li>
              <li class="nav-item">
                <a href="http://liexpedition.com//presentation" class="nav-link" target="_blank">About Us</a>
              </li>
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div class="modal fade" id="modal-detail-rit-report-tujuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
              <h5 class="modal-title text-white" id="exampleModalLabel">Detail <label id="kode-detail-rit-report-tujuan"></label></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-detail-rit-report-tujuan" data-model="expedition" request-url="{{ route('api-expedition') }}" on-success-load="successLoadexpedition">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th>Nomor Surat Jalan</th>
                            <th>Nomor invoice</th>
                            <th>Driver</th>
                            <th>Tanggal invoice</th>
                            <th>Tanggal PO</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                <nav aria-label="...">
                    <ul class="pagination justify-content-end mb-0"></ul>
                </nav>
            </div>
                  </div>
            </div>
        <div class="modal-footer">
        </div>
    </div>
  </div>
  
  <script src="{{asset('js/event.js')}}"></script>
  <script src="{{asset('js/expedition-rit-report.js')}}"></script>
  <script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection

