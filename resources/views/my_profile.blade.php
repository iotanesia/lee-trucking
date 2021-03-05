@extends('layouts/layouts')
@section('content')
<style>
.card-img-top {
    width: 100%;
    border-top-left-radius: calc(.375rem - 1px);
    border-top-right-radius: calc(.375rem - 1px);
    height: 450px;
}
</style>
    <div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-size: cover; background-position: center top;">
      <!-- Mask -->
      <span class="mask bg-gradient-default opacity-8"></span>
      <!-- Header container -->
      <div class="container-fluid d-flex align-items-center">
        <div class="row">
          <div class="col-lg-7 col-md-10">
            <h1 class="display-2 text-white">Hello {{Auth::user()->name}}</h1>
            <p class="text-white mt-0 mb-5">This is your profile page. You can see the progress you've made with your work and manage your projects or assigned tasks</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
            <img src="{{url('/assets/img/LOGO PNG CMYK 300 DPI-01.png')}}" width="10" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="@if($user_detail['foto_profil']) {{url('uploads/profilephoto/'.$user_detail['foto_profil'])}} @else ../../assets/img/theme/team-4.jpg @endif" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
              <div class="d-flex justify-content-between">
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center">
                    
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h5 class="h3">
                {{Auth::user()->name}}<span class="font-weight-light"></span>
                </h5>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i>{{$group->group_name}} 
                </div>
                <div class="h5 mt-4">
                  <i class="ni business_briefcase-24 mr-2"></i> 
                </div>
                <div>
                  <i class="ni education_hat mr-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 order-xl-1">
          
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Edit profile </h3>
                </div>
                <div class="col-4 text-right">
                  <!-- <a href="#!" class="btn btn-sm btn-primary">Settings</a> -->
                </div>
              </div>
            </div>
            <div class="card-body">
              <form action="{{url('/update-profile')}}" method="POST" enctype="multipart/form-data">
              @csrf
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">Username</label>
                        <input type="text" id="input-username" class="form-control" placeholder="Username" name="name" value="{{Auth::user()->name}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email address</label>
                        <input type="email" id="input-email" class="form-control" placeholder="admin@example.com" name="email" value="{{Auth::user()->email}}">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">First name</label>
                        <input type="text" id="input-first-name" class="form-control" placeholder="First name" name="first_name" value="{{$user_detail['first_name']}}">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Last name</label>
                        <input type="text" id="input-last-name" class="form-control" placeholder="Last name" name="last_name" value="{{$user_detail['last_name']}}">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Jenis Kelain</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value=""></option>
                            @foreach($jk as $row)
                            <option value="{{$row->id}}" @if($user_detail['jenis_kelamin'] == $row->id) selected @endif>{{$row->param_name}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Agama</label>
                        <select name="agama" id="agama" class="form-control">
                            <option value=""></option>
                            @foreach($agama as $row)
                            <option value="{{$row->id}}" @if($user_detail['agama'] == $row->id) selected @endif>{{$row->param_name}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Tanggal Lahir</label>
                        <input type="text" id="tgl_lahir" class="form-control" placeholder="Tanggal Lahir" name="tgl_lahir" value="{{$user_detail['tgl_lahir']}}">
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Contact information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-city">Nomor Rekening</label>
                        <input type="text" id="input-city" class="form-control" placeholder="no_rek" name="no_rek" value="{{$user_detail['no_rek']}}">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-country">Nama Bank</label>
                        <input type="text" id="input-country" class="form-control" placeholder="nama_bank" name="nama_bank" value="{{$user_detail['nama_bank']}}">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-country">Nama Rekening</label>
                        <input type="text" id="input-postal-code" class="form-control" name="nama_rekening" value="{{$user_detail['nama_rekening']}}" placeholder="nama_rekening code">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Nomor Hp</label>
                        <input id="nomor_hp" class="form-control" placeholder="nomor_hp" name="nomor_hp" value="{{$user_detail['nomor_hp']}}" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Alamat</label>
                        <textarea id="input-address" class="form-control" placeholder="Home Address" name="alamat" type="text"> {{$user_detail['alamat']}} </textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Upload Foto</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-city">Foto</label>
                        <input type="file" id="input-city" class="form-control" name="foto_profil">
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <button type="submit" class="btn btn-primary">Simpan</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Iotanesia</a>
            </div>
          </div>
          <div class="col-lg-6">
          </div>
        </div>
      </footer>
    </div>
  </div>
  
<script src="{{asset('daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('daterangepicker/daterangepicker.js')}}"></script>
<script src="assets/vendor/select2/dist/js/select2.min.js"></script>
<script src="{{asset('js/event.js')}}"></script>
<script>
    $("#tgl_lahir").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });
    $("#agama").select2({
        placeholder:"Select Agama"
    });
    $("#jenis_kelamin").select2({
        placeholder:"Select Jenis Kelamin"
    });
</script>
@endsection