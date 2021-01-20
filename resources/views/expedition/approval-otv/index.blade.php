@extends('layouts/layouts')
@section('content')
<style>
.modal-dialog {
    max-width: 80%;
    height: 100%;
}.form-control:disabled, .form-control[readonly] {
    background-color:#ffffff;
}
.select2-container--default.select2-container--disabled .select2-selection--single {
    background-color: #ffff;
    cursor: default;
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
                  <h5 class="h3 mb-0">Table {{$title}}</h5>
                </div>
                <div class="navbar-search navbar-search-light form-inline mr-sm-3">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="expedition" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="expedition"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-expedition" api-route="get-list-approval-otv" data-model="expedition" request-url="{{ route('api-expedition') }}" on-success-load="successLoadexpedition">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th>Nomor invoice</th>
                            <th>Truck Plat</th>
                            <th>Driver</th>
                            <th>Tanggal invoice</th>
                            <th>Tanggal PO</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th>Status Approval</th>
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
      </div>
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Viexpedition</a>
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
  
<div class="modal fade" id="expedition-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
            <h5 class="modal-title text-white" style="margin-right:50px" id="exampleModalLabel">Expedition</h5>
            <button type="button" class="btn btn-success btn-sm" id="btn-submit" style="border-radius:20px">Approve</button>
            <button type="button" class="btn btn-warning btn-sm" el-event="edit" style="border-radius:20px" id="btn-revision">Revision</button>
            <button type="button" class="btn btn-danger btn-sm" el-event="edit" style="border-radius:20px" id="btn-reject">Reject</button>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="expedition-form">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="update_lates_status" id="update_lates_status" value="true">
                <input type="hidden" name="status_activity" id="status_activity" value="DRIVER_SELESAI_EKSPEDISI">
                <input type="hidden" name="status_approval" id="status_approval" value="">
                <input type="hidden" name="ex_id" id="ex_id">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                </div>
                                <input disabled class="form-control" name="nomor_inv" id="nomor_inv" placeholder="Nomor Invoice" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input disabled type="text" name="pabrik_pesanan" id="pabrik_pesanan" class="form-control" placeholder="Pabrik Pesanan">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                </div>
                                <input disabled type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Nama Barang">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                </div>
                                <input disabled type="text" name="jumlah_palet" id="jumlah_palet" class="form-control" placeholder="Jumlah Palet">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input disabled type="text" name="tgl_inv" id="tgl_inv" class="form-control" placeholder="Tanggal Invoice">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="jenis_surat_jalan" class="form-control" id="jenis_surat_jalan">
                                <option value=""></option>
                                @foreach($sj_type as $row)
                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <label class="form-control-label" for="jenis_surat_jalan">Ordering Expedition</label>        
                <hr class="bg-info" style="margin-top:0px">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="jumlah_palet">Tujuan</label>
                            <select name="ojk_id" class="form-control" id="tujuan"></select>
                        </div>
                    </div>
                    <div class="col-md-3">                
                        <label class="form-control-label" for="ojk">OJK</label>
                        <div class="form-group">
                            <div class="input-group input-group-merge">                            
                                <input disabled class="form-control" name="harga_ojk" placeholder="Harga OJK" id="ojk">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="ojk">OTV</label>
                            <div class="input-group input-group-merge">
                                <input disabled class="form-control" name="harga_otv" id="otv" placeholder="Harga OTV" >
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="tgl_po">Tanggal Kirim</label>
                            <div class="input-group input-group-merge">
                                <input disabled type="text" name="tgl_po" id="tgl_po" class="form-control" placeholder="Tanggal Kirim">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="toko">Toko</label>
                            <div class="input-group input-group-merge">
                                <input disabled class="form-control" name="toko" id="toko" placeholder="Toko" type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="far fa-building"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="ojk">Payment Method</label>
                            <select name="otv_payment_method" id="otv_payment_method" class="form-control">
                                <option value=""></option>
                                @foreach($payment_method as $row)
                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="no_rek">Nomor Rekening Driver</label>
                            <div class="input-group input-group-merge">
                                <input disabled class="form-control" name="no_rek" id="no_rek" placeholder="Nomor Rekening Driver">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="bank_name">Nama Bank</label>
                            <div class="input-group input-group-merge">
                                <input disabled class="form-control" name="bank_name" id="bank_name" placeholder="Nama Bank">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-landmark"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="nama_penerima">Nama Penerima Rekening</label>
                            <div class="input-group input-group-merge">
                                <input disabled class="form-control" name="nama_penerima" placeholder="Nama Penerima Rekening" id="nama_penerima">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-landmark"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="nomor_hp_penerima">Nomor hp Penerima</label>
                            <div class="input-group input-group-merge">
                                <input disabled class="form-control" name="nomor_hp_penerima" id="nomor_hp_penerima" placeholder="Nomor Hp">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="bg-info">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="bank_name">Truck</label>
                            <select name="truck_id" id="truck_id" class="form-control">
                                <option value=""></option>
                                @foreach($truck as $row)
                                <option value="{{$row->id}}">{{$row->truck_plat}} - {{$row->truck_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="ojk">Supir</label>
                            <select name="driver_id" id="driver_id" class="form-control">
                                <option value=""></option>
                                @foreach($driver as $row)
                                <option value="{{$row->id}}">{{$row->driver_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="kenek_id">Kenek</label>
                            <select name="kenek_id" id="kenek_id" class="form-control">
                                <option value=""></option>
                                @foreach($kenek as $row)
                                <option value="{{$row->id}}">{{$row->kenek_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer bg-gradient-secondary">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/expedition-approval-otv.js')}}"></script>
<script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection
