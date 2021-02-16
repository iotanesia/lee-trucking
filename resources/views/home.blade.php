@extends('layouts/layouts')
@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Default</li>
                </ol>
              </nav>
            </div>
          </div>
          <!-- Card stats -->
          <div class="row">
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total Expedition</h5>
                      <span class="h2 font-weight-bold mb-0">{{$total_expedisi->total}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-warning mr-2"><i class="fas fa-spinner"></i> {{$total_on_progress->total}}</span>
                    <span class="text-success mr-2"></i><i class="fas fa-check"></i> {{$total_close->total}}</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Repair Truck</h5>
                      <span class="h2 font-weight-bold mb-0">{{$total_repair->total}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="ni ni-chart-pie-35"></i>               
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fas fa-wrench"></i> {{$total_repairNonBan->total}}</span>
                    <span class="text-success mr-2"><i class="fas fa-ring"></i> {{$total_repairBan->total}}</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total income</h5>
                      <span class="h2 font-weight-bold mb-0">Rp {{$total_income}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                        <i class="ni ni-money-coins"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total Truck</h5>
                      <span class="h2 font-weight-bold mb-0">{{$total_truck->total}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-chart-bar-32"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fas fa-truck"></i></span>
                    <span class="text-nowrap">All Truck</span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-8">
          <div class="card bg-default">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-light text-uppercase ls-1 mb-1">Total</h6>
                  <h5 class="h3 text-white mb-0">Expedisi</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
                <canvas id="bar-chart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Total</h6>
                  <h5 class="h3 mb-0">Truk</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
              <canvas id="pie-chart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-4">
          <!-- Members list group card -->
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Title -->
              <h5 class="h3 mb-0">Supir Rit Terbanyak</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
              <!-- List group -->
              <ul class="list-group list-group-flush list my--3">
                @foreach($driver as $key => $row)
                <li class="list-group-item px-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Avatar -->
                      <a href="#" class="avatar rounded-circle">
                        <img alt="Image placeholder" src="assets/img/theme/team-4.jpg">
                      </a>
                    </div>
                    <div class="col ml--2">
                      <h4 class="mb-0">
                        <a href="#!">{{$row->driver_name}}</a>
                      </h4>
                      @if($row->driver_status == 8)
                      <span class="text-success">●</span>
                      <small>Available</small>
                      @else
                      <span class="text-danger">●</span>
                      <small>Not Available</small>
                      @endif
                    </div>
                    <div class="col-auto">
                    {{$row->total_rit}}
                    </div>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <!-- Checklist -->
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Title -->
              <h5 class="h3 mb-0">Truck Rit Terbanyak</h5>
            </div>
            <!-- Card body -->
            <div class="card-body p-0">
              <!-- List group -->
              <ul class="list-group list-group-flush" data-toggle="checklist">
                @foreach($truckRit as $rows)
                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                  <div class="checklist-item checklist-item-warning">
                    <div class="checklist-info">
                      <h5 class="checklist-title mb-0">{{$rows->truck_name}}</h5>
                      <small>{{$rows->truck_plat}}</small>
                    </div>
                    <div>
                      <div class="custom-control custom-checkbox custom-checkbox-warning">
                      {{$rows->total_rit}}
                      </div>
                    </div>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <!-- Progress track -->
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Title -->
              <h5 class="h3 mb-0">Progress track</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
              <!-- List group -->
              <ul class="list-group list-group-flush list my--3">
                <li class="list-group-item px-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Avatar -->
                      <a href="#" class="avatar rounded-circle">
                        <img alt="Image placeholder" src="assets/img/theme/bootstrap.jpg">
                      </a>
                    </div>
                    <div class="col">
                      <h5>Argon Design System</h5>
                      <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Avatar -->
                      <a href="#" class="avatar rounded-circle">
                        <img alt="Image placeholder" src="assets/img/theme/angular.jpg">
                      </a>
                    </div>
                    <div class="col">
                      <h5>Angular Now UI Kit PRO</h5>
                      <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Avatar -->
                      <a href="#" class="avatar rounded-circle">
                        <img alt="Image placeholder" src="assets/img/theme/sketch.jpg">
                      </a>
                    </div>
                    <div class="col">
                      <h5>Black Dashboard</h5>
                      <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-red" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%;"></div>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Avatar -->
                      <a href="#" class="avatar rounded-circle">
                        <img alt="Image placeholder" src="assets/img/theme/react.jpg">
                      </a>
                    </div>
                    <div class="col">
                      <h5>React Material Dashboard</h5>
                      <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;"></div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
              &copy; {{date('Y')}} <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">ViExpedition</a>
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
<script src="{{url('assets/chartJs/Chart.min.js')}}"></script>
<script>
    var exBln = {!! json_encode($bulan) !!};
    var exCount = {!! json_encode($total) !!};
    var total_truck = {!! json_encode($total_trucks) !!};
    var cabang = {!! json_encode($cabang) !!};
    // console.log(total_truck)
    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
        labels: exBln,
        datasets: [
            {
            label: "Expedisi",        
            borderColor: "#3e95cd",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: exCount,        
            fill: true
            }
        ]
        },
        options: {
        legend: { display: false },
        title: {
            display: true,
            text: 'Expedisi Per Bulan'
        }
        }
    });

    new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
        labels: cabang,
        datasets: [{
            label: "Truk",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: total_truck
        }]
        },
        options: {
        title: {
            display: true,
            text: 'Total Truk'
        }
        }
    });
</script>

@endsection