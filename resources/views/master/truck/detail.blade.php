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
                <h3 class="mb-0">Data Detail Ban</h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                    @foreach($ban as $val)
                    <div class="timeline-block">
                        <span class="timeline-step badge-warning">
                        <i class="fa fa-cogs"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted font-weight-bold">{{date('l, d F Y H:i:s', strtotime($val->created_at))}}</small>
                                    <h5 class="text-muted font-weight-bold mt-3 mb-0">Nama Ban : {{$val->name_ban}} <br>
                                                                                    Code Ban : {{$val->code_ban}} <br>
                                                                                    Total Ritasi : {{$val->total_ritasi}}  <br>
                                                                                    Batas Ritasi : {{$val->batas_ritasi }} <br>
                                                                                    Deskripsi : {{$val->desc }}<br>
                                                                                    <br>
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-right">
                                        <a style="margin-right:0px; margin-left:10px" class='btn btn-primary btn-sm float-right' href='#' el-event='edit' data-json='{{$val->data_json}}' data-animate-modal='rotateInDownLeft' data-toggle='modal' data-target='#moneyTransactionHeader-modal-detail'><i class='fas fa-tools'></i> Repair</a>
                                        <a class='btn btn-warning btn-sm btn-detail' href='#' el-event='edit' history-json='{{$val->history_json}}'><i class='fa fa-eye'></i> Detail</a>
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
              <h3 class="mb-0 text-white">Detail Truck</h3>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed" id="blockHtml">
                <div class="timeline-block">
                  <div class="timeline-content">
                    <h5 class="text-muted font-weight-bold mt-3 mb-0">Nama Truk : {{$truck->truck_name}} <br>
                                                                    Plat : {{$truck->truck_plat}} <br>
                                                                    Cabang : {{$truck->truck_corporate_asal}}  <br>
                                                                    Jumlah Ban : {{$truck->jumlah_ban }} <br>
                                                                    <br>
                    </h5>
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
            <h5 class="modal-title text-white" id="exampleModalLabel">Repair Ban</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="moneyTransactionHeader-detail-form">
                <input type="hidden" name="id" id="id">
                <!-- <div class="row">
                    <div class="col-md-12">                
                        <label class="form-control-label" for="nominal_termin">Ganti Ban</label>
                        <div class="form-group">
                            <div class="input-group input-group-merge">                            
                                <input class="form-control" name="nominal_termin" placeholder="Nominal" id="nominal_termin">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pokok" class="form-control-label">Name Ban</label>
                            <div class="input-group input-group-merge">
                            <input class="form-control" name="name_ban" placeholder="Name Ban" id="name_ban">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pokok" class="form-control-label">Description</label>
                            <div class="input-group input-group-merge">
                            <input class="form-control" name="description" placeholder="Description" id="description">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submit-detail">Ganti Ban</button>
        </div>
    </div>
</div>
<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/stkRepairBanHeader.js')}}"></script>
@endsection
