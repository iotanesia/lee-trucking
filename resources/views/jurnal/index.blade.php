@extends('layouts/layouts')

@section('styles')
<style>
  .modal-dialog {
      max-width: 80%;
      height: 100%;
  }
  .toolbar {
    float: left;
}
  </style>
@endsection
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
                <div class="col-md-2">
              
                  <select data-column="1" class="form-control col-sm-4 filter-satuan" placeholder="Filter berdasarkan kategori jurnal">
                    <option value=""></option>
                    <option value="DEBIT"> DEBIT </option>
                    <option value="CREDIT"> CREDIT </option>
                </select>
                </div>
             
              </div>
            </div>
            <div class="card-body">
                <div class="">
                    <table class="table table-responsive align-items-center table-striped" id="table-jurnal" son-success-load="successLoadexpedition" width="100%">
                        <thead class="bg-gradient-info text-white">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Aktiviti</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Nama Bank</th>
                            <th>Nama Rekening</th>
                            <th>Nomor Rekening</th>
                            <th>Inputter</th>
                            <th>Source</th>
                            <th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                          <tr style="font-weight:bold">
                              <td style="text-align:left">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                          </tr>
                          <tr style="font-weight:bold">
                            <td style="text-align:left">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="font-weight:bold">
                          <td style="text-align:left">
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                      </tr>
                      </tfoot>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Lee-Tracking</a>
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
  <script src="{{asset('js/event.js')}}"></script>
  <script src="{{asset('js/jurnal-report.js')}}"></script>
  <script src="assets/vendor/select2/dist/js/select2.min.js"></script>
@endsection

