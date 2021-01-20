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
          <div class="card" id="ojk"    >
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
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="ojk" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="ojk"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">        
                <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" data-toggle="modal" data-target="#ojk-modal">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </button>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-ojk" data-model="ojk" request-url="{{ route('api-ojk') }}" on-success-load="successLoadojk">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th><b>No</th>
                            <th><b>Cabang</th>
                            <th><b>Provinsi</th>
                            <th><b>Kabupaten</th>
                            <th><b>Kecamatan</th>
                            <th><b>Jarak KM</th>
                            <th><b>Harga OJK</th>
                            <th><b>Harga OTV</th>
                            <th><b>Action</th>
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
<div class="modal fade" id="ojk-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">ADD OJK</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="ojk-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                    <div class="form-group">
                    <label for="ojk_name">Cabang</label>
                    <select name="cabang_id" id="cabang_id" class="form-control">
                        <option value="">Select Cabang</option>
                    @foreach($cabangList as $row)
                        <option value="{{$row->id}}">{{$row->cabang_name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="ojk_status">Provinsi</label>
                    <select name="provinsi_id" id="select-provinsi" class="form-control">
                        <option value="">Select Provinsi</option>
                    @foreach($provinsiList as $row)
                        <option value="{{$row->id}}">{{$row->provinsi}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="ojk_status">kabupaten</label>
                    <select name="kabupaten_id" id="select-kabupaten" class="form-control">
                        <option value="">Select Kabupaten</option>
                    @foreach($kabupatenList as $row)
                        <option value="{{$row->id}}">{{$row->kabupaten}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="ojk_status">kecamatan</label>
                    <select name="kecamatan_id" id="select-kecamatan" class="form-control">
                        <option value="">Select Kecamatan</option>
                    @foreach($kecamatanList as $row)
                        <option value="{{$row->id}}">{{$row->kecamatan}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="jarak_km">Jarak</label>
                    <input type="text" name="jarak_km" class="form-control">
                </div>
                <div class="form-group">
                    <label for="harga_ojk">Harga OJK</label>
                    <input type="text" name="harga_ojk" class="form-control">
                </div>
                <div class="form-group">
                    <label for="harga_otv">Harga Otv</label>
                    <input type="text" name="harga_otv" class="form-control">
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
<script src="{{asset('js/ojk.js')}}"></script>
@endsection

