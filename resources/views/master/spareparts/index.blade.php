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
                <div class="navbar-search navbar-search-light form-inline mr-sm-3">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="sparepart" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="sparepart"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" data-toggle="modal" data-target="#spareparts-modal">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </button>
                <a class="btn btn-icon btn-success mb-2" type="button" data-toggle="modal" data-target="#spareparts-scanner-modal">
                    <span class="btn-inner--icon"><i class="fas fa-barcode text-black"></i></span>
                    <span class="btn-inner--text text-white">Scanner</span>
                </a>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-spareparts" api-route="get-list" data-model="spareparts" request-url="{{ route('api-spareparts') }}" on-success-load="successLoadspareparts">
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
      
      <div id="spareparts-scanner-modal" class="modal fade animated" role="dialog">
            <div class="modal-dialog" style="max-width: 1600px;">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Scan spareparts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="scanner" id="scanner" placeholder="Scan Code">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        </div>
                    </div>
                    <hr>
                    <div id="form-scan" style="display:none">
                        <form role="form" id="spareparts-scanner-form">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="scanner_form" id="scanner_form" value="1" disabled>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label" for="spareparts_name">Spare Part name</label>
                                        <input type="text" class="form-control" name="sparepart_name" id="sparepart_name" placeholder="Spareparts Name">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="sparepart_type">Type Sparepart</label>
                                        <select name="sparepart_type" id="sparepart_type" class="form-control sparepart_type">
                                        @foreach($type as $row)
                                            <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group purchase_type" style="display:none">
                                        <label class="form-control-label" for="purchase_date">Tgl Pembelian</label>
                                        <input type="date" class="form-control" name="purchase_date" id="purchase_date">
                                    </div>
                                    <div class="form-group no_rek">
                                        <label class="form-control-label" for="no_rek">Rekening</label>
                                        <select name="no_rek" id="no_rek" class="form-control no_rek">
                                            <option value=""></option>
                                        @foreach($no_rek as $row)
                                            <option value="{{$row->id}}">{{$row->rek_no}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="sparepart_status">Status Sparepart</label>
                                        <select name="sparepart_status" id="sparepart_status" class="form-control sparepart_status">
                                        @foreach($status as $row)
                                            <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="jumlah_stok">Stok</label>
                                        <div class="input-group mb-2">
                                            <input type="text" name="jumlah_stok" class="form-control col-md-10">
                                            <select name="satuan_type" class="form-control col-md-2 satuan_type" id="satuan_type">
                                                @foreach($satuan as $row)
                                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="merk_part">image</label>
                                        <input type="file" class="form-control" name="img_sparepart" id="img_sparepart">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label" for="barcode_pabrik">Code Pabrik</label>
                                        <input type="text" name="barcode_pabrik" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="sparepart_jenis">Jenis Sparepart</label>
                                        <select name="sparepart_jenis" id="sparepart_jenis" class="form-control sparepart_jenis">                        
                                        <option value=""></option>
                                        @foreach($jenis as $row)
                                            <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group purchase_type" style="display:none">
                                        <label class="form-control-label" for="due_date">Tgl Jatuh Tempo</label>
                                        <input type="date" class="form-control" name="due_date" id="due_date">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="group_sparepart_id">Group Sparepart</label>
                                        <select name="group_sparepart_id" id="group_sparepart_id" class="form-control group_sparepart_id">
                                            <option value=""></option>
                                        @foreach($group as $row)
                                            <option value="{{$row->id}}">{{$row->group_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="merk_part">merk_part</label>
                                        <input type="text" class="form-control" name="merk_part" id="merk_part" placeholder="merk_part">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="amount">Harga</label>
                                        <input type="text" class="form-control" name="amount" id="amount" placeholder="Harga">
                                    </div>
                                </div>
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
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1600px;">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">Add spareparts</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="spareparts-form">
                <input type="hidden" name="id" id="id">
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label" for="spareparts_name">Spare Part name</label>
                            <input type="text" class="form-control" name="sparepart_name" id="sparepart_name" placeholder="Spareparts Name">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="sparepart_type">Type Sparepart</label>
                            <select name="sparepart_type" id="sparepart-type" class="form-control sparepart_type">
                            @foreach($type as $row)
                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group no_rek">
                            <label class="form-control-label" for="no_rek">Rekening</label>
                            <select name="no_rek" id="no_rek" class="form-control no_rek">
                                <option value=""></option>
                            @foreach($no_rek as $row)
                                <option value="{{$row->id}}">{{$row->rek_no}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group purchase_type" style="display:none">
                            <label class="form-control-label" for="purchase_date">Tgl Pembelian</label>
                            <input type="date" class="form-control" name="purchase_date" id="purchase_date">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="sparepart_status">Status Sparepart</label>
                            <select name="sparepart_status" id="" class="form-control sparepart_status">
                            @foreach($status as $row)
                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="group_sparepart_id">Group Sparepart</label>
                            <select name="group_sparepart_id" id="" class="form-control group_sparepart_id">
                            @foreach($group as $row)
                                <option value="{{$row->id}}">{{$row->group_name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="merk_part">Image</label> 
                            <div id="imagescr">
                                  <img id="imgScreen" width="40%" src="{{asset('assets/img/add-photo.png')}}" alt="Not Found" style="background-size: cover; max-width : 120px" />
                                  <input style="margin-left:10px" type="file" name="img_sparepart" required onchange="readURL(this, 1)" value="">
                              </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label" for="barcode_pabrik">Code Pabrik</label>
                            <input type="text" name="barcode_pabrik" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="sparepart_jenis">Jenis Sparepart</label>
                            <select name="sparepart_jenis" id="sparepart-jenis" class="form-control sparepart_jenis">
                                <option value=""></option>
                                @foreach($jenis as $row)
                                <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group purchase_type" style="display:none">
                            <label class="form-control-label" for="due_date">Tgl Jatuh tempo</label>
                            <input type="date" class="form-control" name="due_date" id="due_date">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="jumlah_stok">Stok</label>
                            <div class="input-group mb-2">
                                <input type="text" name="jumlah_stok" class="form-control col-md-10">
                                <select name="satuan_type" class="form-control col-md-2 satuan_type" id="satuan_type">
                                    @foreach($satuan as $row)
                                    <option value="{{$row->param_code}}">{{$row->param_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="merk_part">merk_part</label>
                            <input type="text" class="form-control" name="merk_part" id="merk_part" placeholder="merk_part">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="amount">Harga</label>
                            <input type="text" class="form-control" name="amount" id="amount" placeholder="Harga">
                        </div>
                    </div>
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

