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
          <div class="card" id="repair-truck-report">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Data {{$title}}</h6>
                  <h5 class="h3 mb-0">Table {{$title}}</h5><br/>
                </div>
              </div>
            </div>
            
            <div class="card-body">
              <form id="form-export" method="get" action="{{url('export-truck-repair')}}">
                <div class="row">
                      <div class="col-md-4">
                        <input class="form-control" name="tipeFile" id="tipeFile" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <div class="input-group input-group-alternative input-group-merge" 
                            style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                          <div class="input-group-prepend" style="margin-left: 10px;">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                            <input class="form-control" name="dateRange" placeholder="Pilih Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <a id="clearDate" href="#" style="color:#adb5bd;padding: 8px;font-size: 13pt;padding-right: 15px;">x</a>
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
                          <a href="#" id="is-excel" class="dropdown-item" data-toggle="modal" data-target="#modal-export">
                            <i class="fas fa-file-excel"></i>
                            <span>Excel</span>
                          </a>
                          <div class="dropdown-divider"></div>
                          <a href="#" id="is-pdf" class="dropdown-item" data-toggle="modal" data-target="#modal-export">
                          <i class="fas fa-file-pdf"></i>
                            <span>PDF</span>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <br/>
                <div class="">
                    <table class="table table-responsive align-items-center table-striped" id="table-repair-truck-report" son-success-load="successLoadexpedition" style="width: 100%;">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th style="min-width: 200px;text-align:center">Kode Repair</th>
                            <th style="min-width: 200px;text-align:center">Nama Truk</th>
                            <th style="min-width: 200px;text-align:center">Tanggal Repair</th>
                            <th style="min-width: 200px;text-align:center">Total</th>
                            <th style="min-width: 70px;text-align:center">Aksi</th>
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

  <div class="modal fade" id="modal-detail-truck-repair" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
              <h5 class="modal-title text-white" id="exampleModalLabel">Detail <label id="kode-detail-repair-truck"></label></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="">
                        <table class="table table-responsive align-items-center table-striped" id="table-detail-repair-truck-report" son-success-load="successLoadexpedition" style="width: 100%;">
                            <thead class="bg-gradient-info text-white" style="text-align:center">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Sparepart</th>
                                <th>Barcode Gudang</th>
                                <th>Barcode Pabrik</th>
                                <th>Tipe Sparepart</th>
                                <th>Jumlah Stok</th>
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Tipe Satuan</th>
                            </tr>
                            </thead>
                            <tbody style="text-align:center"></tbody>
                        </table>
                    </div>
                  </div>
                  </div>
            </div>
        <div class="modal-footer">
        </div>
    </div>
  </div>
  <script src="{{asset('js/event.js')}}"></script>
  <script src="{{asset('js/truck-repair-report.js')}}"></script>
  <script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection

