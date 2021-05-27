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
                <h3 class="mb-0">Tracking</h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                    @foreach($detail as $val)
                    <div class="timeline-block">
                        <span class="timeline-step {{$color}}">
                        <i class="{{$icon}}"></i>
                        </span>
                        <div class="timeline-content">
                            <small class="text-muted font-weight-bold">{{date('l, d F Y H:i:s', strtotime($val->created_at))}}</small>
                            <h5 class=" mt-3 mb-0">Note : {{$val->keterangan}}, <br> Nominal : Rp {{number_format($val->nominal)}} </h5>
                            <div class="float-right">
                                <!-- <img src="{{url('/uploads/expedition/'.$val->img)}}" width="100" alt=""> -->
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-pill {{$color}}">{{$val->status_activity}}</span>
                                <span class="badge badge-pill {{$colorEx}}">{{$val->status_approval}}</span>
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
              <h3 class="mb-0 text-white">Detail Expedition</h3>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                <div class="timeline-block">
                  <span class="timeline-step {{$color}}">
                    <i class="{{$icon}}"></i>
                  </span>
                  <div class="timeline-content">
                    <small class="text-light font-weight-bold">{{date('l, d F Y', strtotime($expedition->tgl_inv))}}</small>
                    <h5 class="text-white mt-3 mb-0">{{$expedition->nomor_inv}}</h5>
                    <p class="text-light text-sm mt-1 mb-0">Pabrik Pesanan : {{$expedition->pabrik_pesanan}}<br>Nama Barang : {{$expedition->nama_barang}} <br>Tujuan : {{$expedition->kabupaten}} - {{$expedition->kecamatan}} : {{$expedition->cabang_name}} <br> Truck : {{$expedition->truck_name}} - {{$expedition->truck_plat}} <br> Driver : {{$expedition->driver_name}} <br> Kenek : {{$expedition->kenek_name}} <br> Harga OJK : Rp {{number_format($expedition->harga_ojk)}} <br> Harga OTV : Rp {{number_format($expedition->harga_otv)}} </p>
                    <div class="mt-3">
                      <span class="badge badge-pill {{$color}}">{{$expedition->status_activity}}</span>
                    </div>
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
