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
          <div class="card" id="stkRepairHeader"    >
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
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="stkRepairHeader" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="stkRepairHeader"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" data-toggle="modal" data-target="#stkRepairHeader-modal">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </button>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-stkRepairHeader" data-model="stkRepairHeader" request-url="{{ route('api-stkRepairHeader') }}" on-success-load="successLoadstkRepairHeader">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Kode Repair</th>
                                <th>Truk</th>
                                <th>Supir</th>
                                <th>Tanggal Service</th>
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
<div class="modal fade" id="stkRepairHeader-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Repair Truck</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="stkRepairHeader-form">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-bus-front-12"></i></span>
                                </div>
                                <select name="truck_id" class="form-control" id="truck_id">
                                    @foreach($truck as $row)
                                    <option value="{{$row->id}}">{{$row->truck_name}} - {{$row->truck_plat}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row col-md-12">
                    <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" id="add-row">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" id="tblBlock">
                            <thead>
                                <tr>
                                    <th>Sparepart</th>
                                    <th>Jumlah</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
<script src="{{asset('js/stkRepairHeader.js')}}"></script>
<script>
    var i = 1;
    $("#add-row").click(function() {
        var j = i;
        var tblBlock = '';
            tblBlock = `<tr id="tr-`+i+`">
                            <td>
                                <select name="sparepart_detail[sparepart_id][]" class="form-control sparepart" id="sparepart">
                                    <option value=""></option>
                                    @foreach($sparepart as $val)
                                    <option value="{{$val->id}}">{{$val->sparepart_name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="sparepart_detail[jumlah_stock][]" class="form-control"></td>
                            <td><a class='btn btn-danger btn-icon-only btn-sm btn-delete' data-id="`+j+`" href='#'><i class='fa fa-trash'></i></a></td>
                        </tr>`;

        $("#tblBlock tbody").append(tblBlock);
        $(".btn-delete").click(function() {
            var id = $(this).attr("data-id");
            $("#tr-"+id).remove();
        });


    });
</script>
@endsection
