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
  .switch {
  position: relative;
  display: inline-block;
  width: 100px;
  margin: 7px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  text-align: -webkit-right;
  font-size:9pt;
  padding: 8px;
  font-weight: 600;
  color:#FFFFFF;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
  text-align: -webkit-left;
  font-size:9pt;
  color:#ffffff;
  padding: 8px;
  font-weight: 600;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(65px);
  -ms-transform: translateX(65px);
  transform: translateX(65px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 40px;
}

.slider.round:before {
  border-radius: 50%;
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
                  @if($user->cabang_id == 6 || $user->cabang_id == 11)
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
                   @elseif($user->cabang_id == 7 || $user->cabang_id == 10)  
                    <li class="nav-item submenu">
                        <a class="nav-link active show" id="user-do" data-toggle="tab" href="#do" role="tab" aria-selected="true">DO</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-da" data-toggle="tab" href="#da" role="tab" aria-selected="false">DA</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-dj" data-toggle="tab" href="#dj" role="tab" aria-selected="false">DJ</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-df" data-toggle="tab" href="#df" role="tab" aria-selected="false">DF</a>
                    </li>
                  @endif
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="tab-content">
              @if($user->cabang_id == 6 || $user->cabang_id == 11)
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
                           <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-bo" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-bo" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                              <a href="#" id="is-excel-bo" class="dropdown-item" data-toggle="modal" data-target="#modal-export-bo">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              {{-- <div class="dropdown-divider"></div>
                              <a href="#" id="is-pdf-bo" class="dropdown-item" data-toggle="modal" data-target="#modal-export-bo">
                              <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                              </a> --}}
                            </div>
                          </div>
                        </div>
                        <br/><br/><br/>
                        <div style='margin-left:10px'>
                            <label class="switch">
                              <input type="checkbox" id="cbPpn10Bo" checked>
                              <span class="slider round">PPN 10%</span>
                            </label>
                            <label class="switch">
                              <input type="checkbox" id="cbPph23Bo" checked>
                              <span class="slider round">PPH 23</span>
                            </label>
                        </div>
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-bo" son-success-load="successLoadexpedition" width="100%">
                      <thead class="bg-gradient-info text-white">
                      <tr>
                          <th>No</th>
                          <th>Status Pemeriksaan</th>
                          <th>Status Export</th>
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
                            <td></td>
                            <td></td>
                            <td style="text-align:right"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="font-weight:bold" id="trPpn10Bo">
                          <td style="text-align:left">
                              <td></td>
                            <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align:right"></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPph23Bo">
                              <td style="text-align:left">
                              <td></td>
                            <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align:right"></td>
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
                          <td></td>
                          <td></td>
                          <td style="text-align:right"></td>
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
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeFileBA" id="tipeFileBA" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <input class="form-control" name="noInvoiceBA" id="noInvoiceBA" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeBA" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-select-ba" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="NON_TUNAI">NON TUNAI</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-ba" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-ba" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                              <a href="#" id="is-excel-ba" class="dropdown-item" data-toggle="modal" data-target="#modal-export-ba">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              {{-- <div class="dropdown-divider"></div>
                              <a href="#" id="is-pdf-ba" class="dropdown-item" data-toggle="modal" data-target="#modal-export-ba">
                              <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                              </a> --}}
                            </div>
                          </div>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <div style='margin-left:10px'>
                          <label class="switch">
                            <input type="checkbox" id="cbPpn10Ba" checked>
                            <span class="slider round">PPN 10%</span>
                          </label>
                          <label class="switch">
                            <input type="checkbox" id="cbPph23Ba" checked>
                            <span class="slider round">PPH 23</span>
                          </label>
                        </div>
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-ba" son-success-load="successLoadexpedition" width="100%">
                    <thead class="bg-gradient-info text-white">
                      <tr>
                          <th>No</th>
                          <th>Status Pemeriksaan</th>
                          <th>Status Export</th>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPpn10Ba">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPph23Ba">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                    <div class="row">
                      <div class="col-md-4">
                        <input class="form-control" name="tipeFileBJ" id="tipeFileBJ" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <input class="form-control" name="noInvoiceBJ" id="noInvoiceBJ" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <div class="input-group input-group-alternative input-group-merge" 
                                    style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                          <div class="input-group-prepend" style="margin-left: 10px;">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                          <input class="form-control" name="dateRangeBJ" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        </div>
                      </div>
                      <div class="col-md-2" >
                        <select class="form-control" id="filter-select-bj" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                            <option value="">Semua</option>
                            <option value="TUNAI">TUNAI</option>
                            <option value="NON_TUNAI">NON TUNAI</option>
                        </select>
                      </div>
                      <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-bj" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-bj" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                            <a href="#" id="is-excel-bj" class="dropdown-item" data-toggle="modal" data-target="#modal-export-bj">
                              <i class="fas fa-file-excel"></i>
                              <span>Excel</span>
                            </a>
                            {{-- <div class="dropdown-divider"></div>
                            <a href="#" id="is-pdf-bj" class="dropdown-item" data-toggle="modal" data-target="#modal-export-bj">
                            <i class="fas fa-file-pdf"></i>
                              <span>PDF</span>
                            </a> --}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-bj" son-success-load="successLoadexpedition" width="100%">
                    <thead class="bg-gradient-info text-white">
                      <tr>
                        <th>No</th>
                        <th>Status Pemeriksaan</th>
                        <th>Status Export</th>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                <div id="uf" class="tab-pane in fade">
                  <form id="form-export-bf" method="get" action="{{url('export-bf')}}">
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeFileBF" id="tipeFileBF" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <input class="form-control" name="noInvoiceBF" id="noInvoiceBF" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeBF" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-select-bf" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="NON_TUNAI">NON TUNAI</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-bf" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-bf" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
                          </div>
                        <div id="tag-cloud-widget" class="col-md-2">  
                          <div class="content">  
                            <a class="nav-link input-group input-group-alternative input-group-merge" href="#" style="padding: .37rem .75rem;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="fas fa-file-export"></i>
                                </span>
                                <div class="media-body d-none d-lg-block" style="margin-top: 4px;margin-right: 20px;text-align:center">
                                  <span class="mb-0 text-sm  font-weight-bold">Eksports</span>
                                </div>
                              </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left">
                              <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Eksport</h6>
                              </div>
                              <a href="#" id="is-excel-bf" class="dropdown-item" data-toggle="modal" data-target="#modal-export-bf">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              <div class="dropdown-divider"></div>
                            </div>
                          </div>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <div style='margin-left:10px'>
                        <label class="switch">
                          <input type="checkbox" id="cbPpn10Bf" checked>
                          <span class="slider round">PPN 10%</span>
                        </label>
                        <label class="switch">
                          <input type="checkbox" id="cbPph23Bf" checked>
                          <span class="slider round">PPH 23</span>
                        </label>
                        </div>
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-bf" son-success-load="successLoadexpedition" width="100%">
                    <thead class="bg-gradient-info text-white">
                      <tr>
                          <th>No</th>
                          <th>Status Pemeriksaan</th>
                          <th>Status Export</th>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPpn10Bf">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPph23Bf">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
              @elseif($user->cabang_id == 7 || $user->cabang_id == 10)  
                <div id="do" class="tab-pane in active">
                  <form id="form-export-do" method="get" action="{{url('export-do')}}">
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeFileDO" id="tipeFileDO" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <input class="form-control" name="noInvoiceDO" id="noInvoiceDO" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeDO" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-select-do" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="NON_TUNAI">NON TUNAI</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-do" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-do" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                              <a href="#" id="is-excel-do" class="dropdown-item" data-toggle="modal" data-target="#modal-export-do">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              {{-- <div class="dropdown-divider"></div>
                              <a href="#" id="is-pdf-do" class="dropdown-item" data-toggle="modal" data-target="#modal-export-do">
                              <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                              </a> --}}
                            </div>
                          </div>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <div style='margin-left:10px'>
                            <label class="switch">
                              <input type="checkbox" id="cbPpn10Do" checked>
                              <span class="slider round">PPN 10%</span>
                            </label>
                            <label class="switch">
                              <input type="checkbox" id="cbPph23Do" checked>
                              <span class="slider round">PPH 23</span>
                            </label>
                          </div>
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-do" son-success-load="successLoadexpedition" width="100%">
                      <thead class="bg-gradient-info text-white">
                      <tr>
                          <th>No</th>
                          <th>Status Pemeriksaan</th>
                          <th>Status Export</th>
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
                            <td></td>
                            <td></td>
                            <td style="text-align:right"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="font-weight:bold" id="trPpn10Do">
                          <td style="text-align:left">
                              <td></td>
                            <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align:right"></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPph23Do">
                              <td style="text-align:left">
                              <td></td>
                            <td></td>
                              <td></td>
                              <td></td>
                              <td style="text-align:right"></td>
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
                          <td></td>
                          <td></td>
                          <td style="text-align:right"></td>
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
                <div id="da" class="tab-pane in fade">
                  <form id="form-export-da" method="get" action="{{url('export-da')}}">
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeFileDA" id="tipeFileDA" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <input class="form-control" name="noInvoiceDA" id="noInvoiceDA" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeDA" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-select-da" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="NON_TUNAI">NON TUNAI</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-da" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-da" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                              <a href="#" id="is-excel-da" class="dropdown-item" data-toggle="modal" data-target="#modal-export-da">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              {{-- <div class="dropdown-divider"></div>
                              <a href="#" id="is-pdf-da" class="dropdown-item" data-toggle="modal" data-target="#modal-export-da">
                              <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                              </a> --}}
                            </div>
                          </div>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <div style='margin-left:10px'>
                            <label class="switch">
                              <input type="checkbox" id="cbPpn10Da" checked>
                              <span class="slider round">PPN 10%</span>
                            </label>
                            <label class="switch">
                              <input type="checkbox" id="cbPph23Da" checked>
                              <span class="slider round">PPH 23</span>
                            </label>
                        </div>
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-da" son-success-load="successLoadexpedition" width="100%">
                    <thead class="bg-gradient-info text-white">
                      <tr>
                          <th>No</th>
                          <th>Status Pemeriksaan</th>
                          <th>Status Export</th>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPpn10Da">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPph23Da">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                <div id="dj" class="tab-pane fade">
                  <form id="form-export-dj" method="get" action="{{url('export-dj')}}">
                    <div class="row">
                      <div class="col-md-4">
                        <input class="form-control" name="tipeFileDJ" id="tipeFileDJ" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <input class="form-control" name="noInvoiceDJ" id="noInvoiceDJ" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <div class="input-group input-group-alternative input-group-merge" 
                                    style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                          <div class="input-group-prepend" style="margin-left: 10px;">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                          <input class="form-control" name="dateRangeDJ" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        </div>
                      </div>
                      <div class="col-md-2" >
                        <select class="form-control" id="filter-select-dj" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                            <option value="">Semua</option>
                            <option value="TUNAI">TUNAI</option>
                            <option value="NON_TUNAI">NON TUNAI</option>
                        </select>
                      </div>
                      <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-dj" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-dj" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                            <a href="#" id="is-excel-dj" class="dropdown-item" data-toggle="modal" data-target="#modal-export-dj">
                              <i class="fas fa-file-excel"></i>
                              <span>Excel</span>
                            </a>
                            {{-- <div class="dropdown-divider"></div>
                            <a href="#" id="is-pdf-dj" class="dropdown-item" data-toggle="modal" data-target="#modal-export-dj">
                            <i class="fas fa-file-pdf"></i>
                              <span>PDF</span>
                            </a> --}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-dj" son-success-load="successLoadexpedition" width="100%">
                    <thead class="bg-gradient-info text-white">
                      <tr>
                        <th>No</th>
                        <th>Status Pemeriksaan</th>
                        <th>Status Export</th>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                <div id="df" class="tab-pane in fade">
                  <form id="form-export-df" method="get" action="{{url('export-df')}}">
                    <div class="row">
                          <div class="col-md-4">
                            <input class="form-control" name="tipeFileDF" id="tipeFileDF" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <input class="form-control" name="noInvoiceDF" id="noInvoiceDF" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            <div class="input-group input-group-alternative input-group-merge" 
                                style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                              <div class="input-group-prepend" style="margin-left: 10px;">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                              </div>
                                <input class="form-control" name="dateRangeDF" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                            </div>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-select-df" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="TUNAI">TUNAI</option>
                                <option value="NON_TUNAI">NON TUNAI</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-periksa-df" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di periksa</option>
                                <option value="false">Belum di periksa</option>
                            </select>
                          </div>
                          <div class="col-md-2" >
                            <select class="form-control" id="filter-export-df" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                                <option value="">Semua</option>
                                <option value="true">Sudah di export</option>
                                <option value="false">Belum di export</option>
                            </select>
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
                              <a href="#" id="is-excel-df" class="dropdown-item" data-toggle="modal" data-target="#modal-export-df">
                                <i class="fas fa-file-excel"></i>
                                <span>Excel</span>
                              </a>
                              {{-- <div class="dropdown-divider"></div>
                              <a href="#" id="is-pdf-df" class="dropdown-item" data-toggle="modal" data-target="#modal-export-df">
                              <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                              </a> --}}
                            </div>
                          </div>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <div style='margin-left:10px'>
                        <label class="switch">
                          <input type="checkbox" id="cbPpn10Df" checked>
                          <span class="slider round">PPN 10%</span>
                        </label>
                        <label class="switch">
                          <input type="checkbox" id="cbPph23Df" checked>
                          <span class="slider round">PPH 23</span>
                        </label>
                        </div>
                      </div>
                  </form>
                  <br/>
                  <table class="table table-responsive align-items-center table-striped" id="table-invoice-df" son-success-load="successLoadexpedition" width="100%">
                    <thead class="bg-gradient-info text-white">
                      <tr>
                          <th>No</th>
                          <th>Status Pemeriksaan</th>
                          <th>Status Export</th>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPpn10Df">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="font-weight:bold" id="trPph23Df">
                        <td style="text-align:left">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
                        <td></td>
                        <td></td>
                        <td style="text-align:right"></td>
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
              @endif
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

  <div class="modal fade" id="modal-export-do" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:30% !important; margin:auto !important;">
            <div class="modal-header" style="background: linear-gradient(
              87deg
              , #11cdef 0, #1171ef 100%) !important;">
              <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form role="form" id="form-no-invoice-do">
                <input type="hidden" name="id" id="id">
                  <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="no-invoice-do" id="no-invoice-do" placeholder="Nomor Invoice">
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-do" onclick="($('#form-export-do').submit())">Eksport</a>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-da" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="width:30% !important; margin:auto !important;">
        <div class="modal-header" style="background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;">
          <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" id="form-no-invoice-da">
            <input type="hidden" name="id" id="id"/>
            <div class="card-body">
              <div class="form-group">
                <input type="text" class="form-control" name="no-invoice-da" id="no-invoice-da" placeholder="Nomor Invoice">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-da" onclick="($('#form-export-da').submit())">Eksport</a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-dj" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="width:30% !important; margin:auto !important;">
        <div class="modal-header" style="background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;">
          <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" id="form-no-invoice-dj">
          <input type="hidden" name="id" id="id">
            <div class="card-body">
              <div class="form-group">
                <input type="text" class="form-control" name="no-invoice-dj" id="no-invoice-dj" placeholder="Nomor Invoice">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-dj" onclick="($('#form-export-dj').submit())">Eksport</a>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-df" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" style="width:30% !important; margin:auto !important;">
        <div class="modal-header" style="background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;">
          <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" id="form-no-invoice-df">
            <input type="hidden" name="id" id="id"/>
            <div class="card-body">
              <div class="form-group">
                <input type="text" class="form-control" name="no-invoice-df" id="no-invoice-df" placeholder="Nomor Invoice">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-df" onclick="($('#form-export-df').submit())">Eksport</a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-bf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:30% !important; margin:auto !important;">
            <div class="modal-header" style="background: linear-gradient(
              87deg
              , #11cdef 0, #1171ef 100%) !important;">
              <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form role="form" id="form-no-invoice-bf">
                <input type="hidden" name="id" id="id">
                  <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="no-invoice-bf" id="no-invoice-bf" placeholder="Nomor Invoice">
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-bf" onclick="($('#form-export-bf').submit())">Eksport</a>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-bo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:30% !important; margin:auto !important;">
            <div class="modal-header" style="background: linear-gradient(
              87deg
              , #11cdef 0, #1171ef 100%) !important;">
              <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form role="form" id="form-no-invoice-bo">
                <input type="hidden" name="id" id="id">
                  <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="no-invoice-bo" id="no-invoice-bo" placeholder="Nomor Invoice">
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-bo" onclick="($('#form-export-bo').submit())">Eksport</a>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-ba" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:30% !important; margin:auto !important;">
            <div class="modal-header" style="background: linear-gradient(
              87deg
              , #11cdef 0, #1171ef 100%) !important;">
              <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form role="form" id="form-no-invoice-ba">
                <input type="hidden" name="id" id="id">
                  <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="no-invoice-ba" id="no-invoice-ba" placeholder="Nomor Invoice">
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-ba" onclick="($('#form-export-ba').submit())">Eksport</a>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="modal-export-bj" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:30% !important; margin:auto !important;">
          <div class="modal-header" style="background: linear-gradient(
              87deg
              , #11cdef 0, #1171ef 100%) !important;">
              <h5 class="modal-title text-white" id="exampleModalLabel">NOMOR INVOICE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true text-white">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form role="form" id="form-no-invoice-bj">
                <input type="hidden" name="id" id="id">
                  <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="no-invoice-bj" id="no-invoice-bj" placeholder="Nomor Invoice">
                    </div>
                  </div>
                </form>
            </div>
        <div class="modal-footer">
            <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-bj" onclick="($('#form-export-bj').submit())">Eksport</a>
        </div>
    </div>
  </div>
  <script src="{{asset('js/event.js')}}"></script>
  <script src="{{asset('js/invoice-report.js')}}"></script>
  <script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection

