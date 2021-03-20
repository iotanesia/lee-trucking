@extends('layouts/layouts')
@section('content')
<style>
.modal-dialog {
    max-width: 80%;
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
                <h3 class="mb-0">Riwayat Sparepart</h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                    @foreach($stkHistorySparePart as $val)
                    <div class="timeline-block">
                        <span class="timeline-step">
                        <i class=""></i>
                        </span>
                        <div class="timeline-content">
                            <small class="text-muted font-weight-bold">{{date('l, d F Y H:i:s', strtotime($val->created_at))}}</small>
                            <h5 class="text-muted font-weight-bold mt-3 mb-0">Name Sparepart : {{$val->sparepart_name}} <br> 
                                                   Nominal : Rp {{number_format($val->amount, 0, ',', '.')}} <br>
                                                   Sparepart Jenis : {{$val->sparepart_jenis}} <br> 
                                                   Barcode Gudang : {{$val->barcode_gudang}} <br> 
                                                   Barcode Pabrik : {{$val->barcode_pabrik}} <br>
                                                   Sparepart Type : {{$val->sparepart_type}} <br>
                                                   Tanggal Pembelian : {{$val->purchase_date}} <br>
                                                   Tanggal Jatuh Tempo : {{$val->due_date}} <br>
                                                   Stok : {{$val->jumlah_stok}} - {{$val->satuan_type}} <br>
                                                   Tipe Transaksi : <span class='@if($val->transaction_type == "IN") text-success @else text-danger @endif' >{{$val->transaction_type}}</span> <br>
                            </h5>
                            <div class="float-right">
                                <!-- <img src="{{url('/uploads/expedition/'.$val->img)}}" width="100" alt=""> -->
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-pill">{{$val->status_activity}}</span>
                                <span class="badge badge-pill">{{$val->status_approval}}</span>
                                @if($val->img)
                                <a target="_blank" href="{{url('/uploads/expedition/'.$val->img)}}">
                                    <span class="badge badge-pill badge-secondary" data-toggle="tooltip" data-placement="top" title="Bukti Transfer"><i class="ni ni-image"></i></span>
                                </a>
                                @endif
                                @if($val->img_tujuan)
                                <a target="_blank" href="{{url('/uploads/expedition/'.$val->img_tujuan)}}">
                                    <span class="badge badge-pill badge-secondary" data-toggle="tooltip" data-placement="top" title="Gambar Tujuan"><i class="ni ni-album-2"></i></span>
                                </a>
                                @endif
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
              <h3 class="mb-0 text-white">Image Sparepart</h3>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                <div class="timeline-block">
                  <span class="timeline-step">
                    <i class=""></i>
                  </span>
                  <div class="timeline-content">
                    <img style="width:100%" src="{{url('/uploads/sparepart/'.$sparePart->img_sparepart)}}" alt="">
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
<script src="{{asset('js/event.js')}}"></script>
@endsection
