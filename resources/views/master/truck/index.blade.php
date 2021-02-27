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
          <div class="card" id="truck"    >
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
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="truck" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" el-event="search-data" data-model="truck"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-slack btn-icon-only rounded-circle float-right mb-2" data-toggle="modal" data-target="#truck-modal">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </button>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-truck" data-model="truck" request-url="{{ route('api-truck') }}" on-success-load="successLoadtruck">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Truck Name</th>
                                <th>Truck plat</th>
                                <th>Truck status</th>
                                <th>Truck corporate asal</th>
                                <th>Truck date join</th>
                                <th>cabang id</th>
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
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Lee-Expedition</a>
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
<div class="modal fade" id="truck-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">Add truck</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="truck-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                <div class="form-group">
                    <label class="form-control-label" for="truck_plat">Truck name</label>
                    <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="truck_name" id="truck_name" placeholder="Truck Name">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-bus-alt"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="truck_plat">Truck Plat</label>
                    <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="truck_plat" id="truck_plat" placeholder="Truck Plat">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-window-maximize"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="truck_corporate_asal">Truck corporate asal</label>
                    <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="truck_corporate_asal" id="truck_corporate_asal" placeholder="Truck Corporate Asal">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="truck_corporate_asal">Status Truck</label>
                    <select name="truck_status" id="truck_status" class="form-control">
                        <option value=""></option>
                    @foreach($status as $row)
                        <option value="{{$row->id}}">{{$row->param_name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="truck_corporate_asal">Supir</label>
                    <select name="driver_id" id="driver_id" class="form-control">
                        <option value=""></option>
                    @foreach($driverList as $row)
                        <option value="{{$row->id}}">{{$row->driver_name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="truck_date_join">Truck date join</label>
                    <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="truck_date_join" id="truck_date_join" placeholder="Truck Date Join">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="cabang_id">Cabang</label>
                    <select name="cabang_id" class="form-control" id="cabang_id">
                        <option value=""></option>
                        @foreach($cabangList as $row)
                            <option value="{{$row->id}}">{{$row->cabang_name}}</option>
                        @endforeach
                    </select>
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
<script src="{{asset('js/truck.js')}}"></script>
@endsection
