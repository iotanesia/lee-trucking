@extends('layouts/layouts')
@section('content')
<style>
.modal-dialog {
    max-width: 30%;
    height: 100%;
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
        <div class="col-lg-6">
            <div class="card">
            <div class="card-header bg-transparent">
                <h3 class="mb-0">Data Pinjaman Karyawan</h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                    @foreach($pinjaman as $val)
                    <div class="timeline-block">
                        <span class="timeline-step @if($val->status == 'LUNAS') badge-success @else badge-danger @endif">
                        <i class="@if($val->status == 'LUNAS') fa fa-check @else fa fa-times @endif"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted font-weight-bold">{{date('l, d F Y H:i:s', strtotime($val->created_at))}}</small>
                                    <h5 class="text-muted font-weight-bold mt-3 mb-0">Nama Karyawan : {{$val->name_user}} <br>
                                                                                    Pinjaman : Rp {{number_format($val->pokok, 0, ',', '.')}}  <br>
                                                                                    Sisa Pinjaman : Rp {{number_format($val->sisa_pokok, 0, ',', '.')}} <br>
                                                                                    Tanggal Pinjaman : {{$val->date ? date('d-m-Y', strtotime($val->date)) : '-' }}
                                                                                    <br>
                                                                                    <br>
                                        <span class="badge @if($val->status == 'BELUM_LUNAS') badge-danger @else badge-success @endif badge-pill">{{str_replace('_', ' ', $val->status)}} </span>
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-right">
                                    @if($val->status == 'BELUM_LUNAS')
                                        <a style="margin-right:0px; margin-left:10px" class='btn btn-primary btn-sm float-right' href='#' el-event='edit' data-json='{{$val->data_json}}' data-animate-modal='rotateInDownLeft' data-toggle='modal' data-target='#moneyTransactionHeader-modal-detail'><i class='fas fa-money-bill-wave'></i> Bayar</a>
                                    @endif
                                        <a class='btn btn-warning btn-sm btn-detail' href='#' el-event='edit' data-json='{{$val->data_json}}'><i class='fa fa-eye'></i> Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach    
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
          <div class="card bg-gradient-default shadow">
            <div class="card-header bg-transparent">
              <h3 class="mb-0 text-white">Detail Pinjaman</h3>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed" id="blockHtml">
                <div class="timeline-block">
                  <div class="timeline-content">
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
<div class="modal fade" id="moneyTransactionHeader-modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">Detail Pinjaman</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="moneyTransactionHeader-detail-form">
                <input type="hidden" name="transaksi_header_id" id="id">
                <div class="row">
                    <div class="col-md-12">                
                        <label class="form-control-label" for="nominal_termin">Nominal Bayar</label>
                        <div class="form-group">
                            <div class="input-group input-group-merge">                            
                                <input class="form-control" name="nominal_termin" placeholder="Nominal" id="nominal_termin">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="moneyTransactionHeader_name" class="form-control-label">Rekening Tujuan</label>
                            <select name="rek_id" id="rek_id" class="form-control rek_id">
                                <option value="">Select Rekening</option>
                                @foreach($no_rek as $row)
                                <option value="{{$row->id}}">{{$row->rek_no}} - {{$row->rek_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pokok" class="form-control-label">Tanggal Pembayaran</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" name="date" id="tgl_pinjaman" class="form-control" placeholder="Tanggal Pinjaman">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submits-detail">Bayar</button>
        </div>
    </div>
</div>
<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/pinjaman-karyawan.js')}}"></script>
@endsection
