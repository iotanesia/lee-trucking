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
                            <input type="text" id="btn-search-trigger" class="form-control has-primary" name="search_value" data-model="spareparts" placeholder="Search Key">
                        </div>
                    </div>
                    <a type="button" class="input-group-text btn-sm btn-flat" style="display:none" id="search-data" api-route="get-list-unpaid" el-event="search-data" data-model="spareparts"><i class="fa fa-search"></i></a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-striped" id="table-spareparts" data-model="spareparts" request-url="{{ route('api-spareparts') }}" on-success-load="successLoadspareparts">
                        <thead class="bg-gradient-info text-white">
                            <tr>
                                <th>No</th>
                                <th>Code Gudang</th>
                                <th>Code Pabrik</th>
                                <th>Spareparts Name</th>
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
      

<div class="modal fade" id="spareparts-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1600px;">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">Add Stok</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="spareparts-form">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="scanner_form" id="scanner_form" value="1">
                <table class="table table-bordered" id="table-spareparts-detail">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>SparePart Type</th>
                            <th>Amount</th>
                            <th>Stok</th>
                            <th>Total Amount</th>
                            <th>No Rek</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>              
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submits">Save changes</button>
        </div>
    </div>
</div>

<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/unpaid-spareparts.js')}}"></script>
@endsection

