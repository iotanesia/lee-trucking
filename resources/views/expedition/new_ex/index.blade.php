@extends('layouts/layouts')
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
                  <h5 class="h3 mb-0">Table {{$title}}</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control has-primary" name="search_value" data-model="expedition" placeholder="Search Key">
                    <div class="input-group-append">
                        <a type="button" class="input-group-text btn-sm btn-flat" id="search-data" el-event="search-data" data-model="expedition"><i class="fa fa-search"></i></a>
                        <a type="button" class="input-group-text btn-sm btn-flat bg-primary text-white"  data-toggle="modal" data-target="#expedition-modal"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-expedition" data-model="expedition" request-url="{{ route('api-expedition') }}" on-success-load="successLoadexpedition">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>Nomor invoice</th>
                            <th>Pabrik Pesanan</th>
                            <th>Nomor Surat Jalan</th>
                            <th>Nama Barang</th>
                            <th>Truck</th>
                            <th>Driver</th>
                            <th>Tanggal invoice</th>
                            <th>Tanggal PO</th>
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
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Liexpedition</a>
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
            <h5 class="modal-title text-white" id="exampleModalLabel">Add expedition</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="expedition-form">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="nomor_inv">Nomor Invoice</label>
                            <input type="text" class="form-control" name="nomor_inv" id="nomor_inv" placeholder="Nomor Invoice">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="tgl_inv">Tanggal Invoice</label>
                            <input type="date" name="tgl_inv" id="tgl_inv" class="form-control" placeholder="Tanggal Invoice">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="pabrik_pesanan">Pabrik Pesanan</label>
                            <input type="text" name="pabrik_pesanan" id="pabrik_pesanan" class="form-control" placeholder="Pabrik Pesanan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Nama Barang">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="jenis_surat_jalan">Jenis Surat Jalan</label>
                            <select name="jenis_surat_jalan" class="form-control" id="jenis_surat_jalan">
                                <option value=""></option>
                                @foreach($sj_type as $row)
                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="ojk">Jumlah Palet</label>
                            <input type="text" name="jumlah_palet" id="jumlah_palet" class="form-control" placeholder="Jumlah Palet">
                        </div>
                    </div>
                </div>
                <br>
                <label class="form-control-label" for="jenis_surat_jalan">Ordering Expedition</label>        
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="jumlah_palet">Tujuan</label>
                            <select name="ojk_id" class="form-control" id="tujuan"></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="tgl_po">Tanggal Kirim</label>
                            <input type="date" name="tgl_po" id="tgl_po" class="form-control" placeholder="Tanggal Kirim">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="ojk">OJK</label>
                            <input class="form-control" id="ojk">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="ojk">OTV</label>
                            <input class="form-control" id="otv">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="toko">Toko</label>
                            <input class="form-control" name="toko" id="toko">
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="no_rek">Nomor Rekening</label>
                            <input class="form-control" name="no_rek" id="no_rek">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="bank_name">Nama Bank</label>
                            <input class="form-control" name="bank_name" id="bank_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="nama_penerima">Nama Penerima</label>
                            <input class="form-control" name="nama_penerima" id="nama_penerima">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="nomor_hp_penerima">Nomor hp Penerima</label>
                            <input class="form-control" name="nomor_hp_penerima" id="nomor_hp_penerima">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
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
                </div>
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="kenek_id">Kenek</label>
                            <input name="" class="form-control" id="kenek_id">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submit">Save changes</button>
        </div>
    </div>
</div>
<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/expedition.js')}}"></script>
<script src="assets/vendor/select2/dist/js/select2.min.js"></script>
<script>

$("document").ready(function() {
    res = false;
    accessToken =  window.Laravel.api_token;
    $("#tujuan").select2({
        dropdownParent: $("#expedition-modal"),
        minimumInputLength : 3,
        placeholder : "Select Tujuan",
        multiple: false,
        ajax: {
            type 	: 'GET',
            url		: '{{route("api-expedition-get-ojk")}}',
            data    :  function (params) {
                        return {
                            kecamatan: params.term
                        }},
            headers	: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            dataType: 'json',
            quietMillis: 100,
            headers: {"Authorization": "Bearer " + accessToken},
            processResults: function (data) {
                $('#search_marga').attr('disabled', false);
                var results = [];
                res = data.data;
                $.each(data.data, function (x, y) {
                    results.push({
                        id: y.id,
                        text: y.kecamatan +' - '+ y.cabang_name
                    });
                });

                return {
                    results: results,
                };
            }
        }
    });

    $("#tujuan").on("change", function() {
        idSelected = $(this).val();

        if(res) {
            $.each(res, function (x, y) {
                if(y.id == idSelected) {
                    $("#ojk").val(y.harga_ojk);
                    $("#otv").val(y.harga_otv);
                }
                
            });
        }

    });
});
</script>
@endsection
