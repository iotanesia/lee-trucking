@extends('layouts/layouts')
@section('content')
<style>
.table-condensed thead tr:nth-child(2),
.table-condensed tbody {
  display: none
}
.daterangepicker select.yearselect{
    width:60%
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
          <div class="card" id="bonusDriverRit"    >
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Data {{$title}}</h6>
                  <h5 class="h3 mb-0">Table {{$title}}</h5>
                  <br>
                  <ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm">
                    <li class="nav-item submenu">
                        <a class="nav-link active show" id="user-ad" data-toggle="tab" href="#ad" role="tab" aria-selected="true">Driver</a>
                    </li>
                    <li class="nav-item submenu">
                        <a class="nav-link" id="user-up" href="{{url('bonus-kenek-rit')}}" aria-selected="false">Kenek</a>
                    </li>
                  </ul>
                </div>
                <div class="col-md-2">
                    <select class="form-control m-1" id="tahun-select">
                        <option value=""></option>
                        @foreach($tahun as $val)
                            <option value="{{$val['years']}}">{{$val['years']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control m-1" id="bulan-select">
                        <option value=""></option>
                        @foreach($bulan as $key => $val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="navbar-search navbar-search-light form-inline mr-sm-3">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="bonusDriverRit" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="bonusDriverRit"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
            <div class="tab-content">
              <div id="ad" class="tab-pane in active">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-bonusDriverRit" data-model="bonusDriverRit" request-url="{{ route('api-bonusDriverRit') }}" on-success-load="successLoadbonusDriverRit">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Driver</th>
                                <th>Truck</th>
                                <th>Total Rit Driver</th>
                                <th>Total Rit Truck</th>
                                <th>Reward Jenis</th>
                                <th>Pendapatan</th>
                                <th>Bonus</th>
                                <th>Total Pendapatan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0"></ul>
                    </nav>
                </div>
              </div>
              <div id="up" class="tab-pane in">
              <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-bonusDriverRit" data-model="bonusDriverRit" request-url="{{ route('api-bonusDriverRit') }}" on-success-load="successLoadbonusDriverRit">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Kenek</th>
                                <th>Truck</th>
                                <th>Total Rit Kenek</th>
                                <th>Total Rit Truck</th>
                                <th>Reward Jenis</th>
                                <th>Pendapatan</th>
                                <th>Bonus</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0"></ul>
                    </nav>
                </div>
              </div>
            </div>
                <!-- <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" data-toggle="modal" data-target="#bonusDriverRit-modal">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </button> -->
                
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
  </div>
<div class="modal fade" id="bonusDriverRit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add bonusDriverRit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="bonusDriverRit-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                    <div class="form-group">
                    <label for="bonusDriverRit_name">bonusDriverRit Plat</label>
                    <input type="text" class="form-control" name="bonusDriverRit_name" id="bonusDriverRit_name" placeholder="bonusDriverRit_name">
                </div>
                <div class="form-group">
                    <label for="alamat">bonusDriverRit corporate asal</label>
                    <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="alamat"> </textarea>
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
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
<script src="{{asset('js/bonusDriverRit.js')}}"></script>
@endsection
