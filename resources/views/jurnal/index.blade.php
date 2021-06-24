@extends('layouts/layouts')

@section('styles')
<style>
  .modal-dialog {
      max-width: 80%;
      height: 100%;
  }
  .toolbar {
    float: left;
}
  </style>
@endsection
@section('content')
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
                  <h5 class="h3 mb-0">Tabel {{$title}}</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form id="form-export-jurnal" method="get" action="{{url('export-jurnal-report')}}">
                <div class="row">
                      <div class="col-md-4">
                        <input class="form-control" name="tipeFileJurnal" id="tipeFileJurnal" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <input class="form-control" name="filterSelectJurnal" id="filterSelectJurnal" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <input class="form-control" name="filterActivityJurnal" id="filterActivityJurnal" placeholder="tipe file" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <input class="form-control" name="balanceJurnal" id="balanceJurnal" placeholder="no invoice" type="text" style="display:none;margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        <div class="input-group input-group-alternative input-group-merge" 
                            style="box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                          <div class="input-group-prepend" style="margin-left: 10px;">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                            <input class="form-control" name="dateRangeJurnal" placeholder="Pilih Rentang Tanggal" type="text" style="margin-right: 30px;text-align: center !important;background-color:transparent !important;cursor:pointer !important;">
                        </div>
                      </div>
                      <div class="col-md-2" >
                        <select class="form-control" id="filter_select_jurnal" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                            <option value="">Semua</option>
                            <option value="DEBIT"> DEBIT </option>
                            <option value="CREDIT"> CREDIT </option>
                        </select>
                      </div>
                      <div class="col-md-4" >
                        <select class="form-control"  id="filter_select_aktiviti_jurnal" style="cursor: pointer;box-shadow: 0 1px 3px rgb(50 50 93 / 77%), 0 1px 0 rgb(0 0 0 / 2%) !important;">
                            <option value="">Semua</option>
                            @foreach($sheetName as $row)
                            <option value="{{$row->sheet_name}}">{{$row->sheet_name}}</option>
                            @endforeach
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
                          <a href="#" id="is-excel-jurnal" class="dropdown-item"  data-toggle="modal" data-target="#modal-pilihan-export-jurnal">
                            <i class="fas fa-file-excel"></i>
                            <span>Excel</span>
                          </a>
                          <div class="dropdown-divider"></div>
                          {{-- <a href="#" id="is-pdf-jurnal" class="dropdown-item" onclick="($('#form-export-jurnal').submit())">
                          <i class="fas fa-file-pdf"></i>
                            <span>PDF</span>
                          </a> --}}
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <br/>
                <div class="">
                    <table class="table table-responsive align-items-center table-striped" id="table-jurnal" son-success-load="successLoadexpedition" width="100%">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor Invoice</th>
                            <th>Nomor Surat Jalan</th>
                            <th>Nama Aktiviti</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Nama Bank</th>
                            <th>Nama Rekening</th>
                            <th>Nomor Rekening</th>
                            <th>Inputter</th>
                            <th>Source</th>
                            <th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr style="font-weight:bold">
                            <td></td>
                              <td style="text-align:left">
                                <td></td>
                                <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                          </tr>
                          <tr style="font-weight:bold">
                            <td></td>
                            <td style="text-align:left">
                              <td></td>
                              <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="font-weight:bold">
                          <td></td>
                          <td style="text-align:left">
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

  <div class="modal fade" id="modal-pilihan-export-jurnal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:40% !important; margin:auto !important;">
          <div class="modal-header" style="background-color:transparent !important;padding:0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body" style="padding:0">
                <form role="form" id="form-pilihan-export-jurnal">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-7">
                        <a type="button" class="btn btn-info" style="color:#FFFFFF; margin:auto" id="btn-input-balance-jurnal">Input Balance</a>
                      </div>
                      <div class="col-md-5">
                        <a type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" onclick="($('#form-export-jurnal').submit())">Eksport</a>
                      </div>
                    </div>
                  </div>
                </form>
            </div>
        </div>
      </div>
  </div>
  
  <div class="modal fade" id="modal-input-balance-jurnal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin:10rem auto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width:40% !important; margin:auto !important;">
            <div class="modal-header" style="background: linear-gradient(
              87deg
              , #11cdef 0, #1171ef 100%) !important;padding:5px !important;">
              <h5 class="modal-title text-white" style="padding:5px" id="exampleModalLabel">INPUT BALANCE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" style="color:#FFFFFF;">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="padding:5px;margin-top: 25px;">
                <form role="form" id="form-input-balance-jurnal">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-2" style="padding-right:0px">  
                          <input type="text" class="form-control" value="Rp" style="font-size: .800rem;border-bottom-right-radius: 0px;
                          border-top-right-radius: 0px;" disabled>
                        </div>
                        <div class="col-md-10" style="padding-left:0px">
                          <input style="font-size: .800rem;border-bottom-left-radius: 0px;
                          border-top-left-radius: 0px;" type="text" class="form-control" name="balance-jurnal"  id="balance-jurnal" placeholder="Balance">
                        </div>
                      </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding:5px">
                <a style="display:none;" type="button" class="btn btn-success" style="color:#FFFFFF; margin:auto" id="btn-export-jurnal-with-balance" onclick="($('#form-export-jurnal').submit())">Eksport</a>
            </div>
          </div>
      </div>
  </div>
  <script src="{{asset('js/event.js')}}"></script>
  <script src="{{asset('js/jurnal-report.js')}}"></script>
  <script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection

