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
        <div class="col-xl-12">
          <div class="card" id="user"    >
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
                    <input type="text" class="form-control has-primary" name="search_value" data-model="user" placeholder="Search Key">
                    <div class="input-group-append">
                        <a type="button" class="input-group-text btn-sm btn-flat" id="search-data" el-event="search-data" data-model="user"><i class="fa fa-search"></i></a>
                        <a type="button" class="input-group-text btn-sm btn-flat bg-primary text-white"  data-toggle="modal" data-target="#user-modal"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-user" data-model="user" request-url="{{ route('api-user') }}" on-success-load="successLoaduser">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>No</th>
                            <th>UserName</th>
                            <th>Email</th>
                            <th>Role</th>
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
    </div>
  </div>
  
<div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add user</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="user-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                    <div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="user_name">Username</label>
                                <input type="text" class="form-control" name="name" id="user_name" placeholder="user_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="user_parent">Group</label>
                                <select name="group_id" id="group_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($group as $row)
                                    <option value="{{$row->id}}">{{$row->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="password">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="password_confirmation">Password Confirmation</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="password_confirmation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="user_parent">Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Not Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="first_name">First name</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="last_name">Last name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="nomor_hp">Nomor HP</label>
                                <input type="text" class="form-control" name="nomor_hp" id="nomor_hp" placeholder="nomor_hp">
                            </div>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                    <option value=""></option>
                                    @foreach($jk as $row)
                                    <option value="{{$row->id}}">{{$row->param_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="tgl_lahir">Tgl Lahir</label>
                                <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="tgl_lahir">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="agama">Agama</label>
                                <select name="agama" id="agama" class="form-control">
                                    <option value=""></option>
                                    @foreach($agama as $row)
                                    <option value="{{$row->id}}">{{$row->param_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="no_rek">No Rekening</label>
                                <input type="text" class="form-control" name="no_rek" id="no_rek" placeholder="no_rek">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="nama_bank">Nama Bank</label>
                                <input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="nama_bank">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="nama_rekening">Nama Rekening</label>
                                <input type="text" class="form-control" name="nama_rekening" id="nama_rekening" placeholder="nama_rekening">
                            </div>
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
<script src="{{asset('js/user.js')}}"></script>
@endsection
