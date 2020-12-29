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
          <div class="card" id="spareparts"    >
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
                    <input type="text" class="form-control has-primary" name="search_value" data-model="spareparts" placeholder="Search Key">
                    <div class="input-group-append">
                        <a type="button" class="input-group-text btn-sm btn-flat" id="search-data" el-event="search-data" data-model="spareparts"><i class="fa fa-search"></i></a>
                        <a type="button" class="input-group-text btn-sm btn-flat bg-primary text-white"  data-toggle="modal" data-target="#spareparts-modal"><i class="fa fa-plus"></i></a>
                        <a type="button" class="input-group-text btn-sm btn-flat bg-success text-white"  data-toggle="modal" data-target="#spareparts-scanner-modal">Scanner</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-spareparts" data-model="spareparts" request-url="{{ route('api-spareparts') }}" on-success-load="successLoadspareparts">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Code Gudang</th>
                                <th>Code Pabrik</th>
                                <th>Spareparts Name</th>
                                <th>Spareparts Jenis</th>
                                <th>Group Name</th>
                                <th>Merek</th>
                                <th>Jumlah Stok</th>
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
      
      <div id="spareparts-scanner-modal" class="modal fade animated" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Scan spareparts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="scanner" id="scanner" placeholder="Scan Code">
                    <hr>
                    <div id="form-scan" style="display:none">
                        <form role="form" id="spareparts-scanner-form">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="scanner_form" id="scanner_form" value="1" disabled>
                            <div class="card-body">
                                <div class="form-group">
                                <label for="spareparts_name">Spare Part name</label>
                                <input type="text" class="form-control" name="sparepart_name" id="sparepart_name" placeholder="spareparts_name">
                            </div>
                            <div class="form-group">
                                <label for="sparepart_status">Status Sparepart</label>
                                <select name="sparepart_status" id="" class="form-control">
                                @foreach($status as $row)
                                    <option value="{{$row->id}}">{{$row->param_name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sparepart_type">Type Sparepart</label>
                                <select name="sparepart_type" id="" class="form-control">
                                @foreach($type as $row)
                                    <option value="{{$row->id}}">{{$row->param_name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="group_sparepart_id">Group Sparepart</label>
                                <select name="group_sparepart_id" id="" class="form-control">
                                @foreach($group as $row)
                                    <option value="{{$row->id}}">{{$row->group_name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="barcode_pabrik">Code Pabrik</label>
                                <input type="text" name="barcode_pabrik" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="barcode_gudang">Code Gudang</label>
                                <input type="text" name="barcode_gudang" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="sparepart_jenis">Jenis Sparepart</label>
                                <input type="text" name="sparepart_jenis" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jumlah_stok">Stok</label>
                                <input type="text" name="jumlah_stok" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="merk_part">merk_part</label>
                                <input type="text" class="form-control" name="merk_part" id="merk_part" placeholder="merk_part">
                            </div>
                            <div class="form-group">
                                <label for="merk_part">image</label>
                                <input type="file" class="form-control" name="img_sparepart" id="img_sparepart">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" el-event="add" id="btn-submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="modal fade" id="spareparts-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add spareparts</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="spareparts-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                    <div class="form-group">
                    <label for="spareparts_name">Spare Part name</label>
                    <input type="text" class="form-control" name="sparepart_name" id="sparepart_name" placeholder="spareparts_name">
                </div>
                <div class="form-group">
                    <label for="sparepart_status">Status Sparepart</label>
                    <select name="sparepart_status" id="" class="form-control">
                    @foreach($status as $row)
                        <option value="{{$row->id}}">{{$row->param_name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="sparepart_type">Type Sparepart</label>
                    <select name="sparepart_type" id="" class="form-control">
                    @foreach($type as $row)
                        <option value="{{$row->id}}">{{$row->param_name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="group_sparepart_id">Group Sparepart</label>
                    <select name="group_sparepart_id" id="" class="form-control">
                    @foreach($group as $row)
                        <option value="{{$row->id}}">{{$row->group_name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="barcode_pabrik">Code Pabrik</label>
                    <input type="text" name="barcode_pabrik" class="form-control">
                </div>
                <div class="form-group">
                    <label for="barcode_gudang">Code Gudang</label>
                    <input type="text" name="barcode_gudang" class="form-control">
                </div>
                <div class="form-group">
                    <label for="sparepart_jenis">Jenis Sparepart</label>
                    <input type="text" name="sparepart_jenis" class="form-control">
                </div>
                <div class="form-group">
                    <label for="jumlah_stok">Stok</label>
                    <input type="text" name="jumlah_stok" class="form-control">
                </div>
                <div class="form-group">
                    <label for="merk_part">merk_part</label>
                    <input type="text" class="form-control" name="merk_part" id="merk_part" placeholder="merk_part">
                </div>
                <div class="form-group">
                    <label for="merk_part">image</label>
                    <input type="file" class="form-control" name="img_sparepart" id="img_sparepart">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submits">Save changes</button>
        </div>
    </div>
</div>

<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/spareparts.js')}}"></script>
@endsection

