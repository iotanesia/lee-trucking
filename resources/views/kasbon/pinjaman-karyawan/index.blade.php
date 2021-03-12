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
          <div class="card" id="moneyTransactionHeader">
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
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="moneyTransactionHeader" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="moneyTransactionHeader"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" data-toggle="modal" data-target="#moneyTransactionHeader-modal">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </button>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-moneyTransactionHeader" data-model="moneyTransactionHeader" request-url="{{ route('api-moneyTransactionHeader') }}" on-success-load="successLoadmoneyTransactionHeader">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Pinjaman</th>
                                <th>Sisa Pinjaman</th>
                                <th>Sumber Dana</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
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
                    <div class="col-md-9">                
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
                    <div class="col-md-3"><br><br>
                        <div class="form-group">
                            <div class="input-group input-group-merge form-control-label termin-val">
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-9">
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
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submits-detail">Bayar</button>
        </div>
    </div>
</div>
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
      

<div class="modal fade" id="moneyTransactionHeader-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">ADD Pinjaman Karyawan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="moneyTransactionHeader-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                    <div class="form-group">
                    <label for="moneyTransactionHeader_name">Karyawan</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">Select Karyawan</option>
                    @foreach($user as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="pokok">Jumlah Pinjaman</label>
                    <input type="text" name="pokok" class="form-control">
                </div>
                <div class="form-group">
                    <label for="moneyTransactionHeader_name">Sumber Dana</label>
                    <select name="rek_id" class="form-control rek_id">
                        <option value="">Select Rekening</option>
                    @foreach($no_rek as $row)
                        <option value="{{$row->id}}">{{$row->rek_no}} - {{$row->rek_name}}</option>
                    @endforeach
                    </select>
                </div>
                <!-- <div class="form-group">
                    <label for="termin">Termin</label>
                    <input type="text" name="termin" class="form-control">
                </div> -->
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submit">Simpan</button>
        </div>
    </div>
</div>

<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/pinjaman-karyawan.js')}}"></script>
@endsection

