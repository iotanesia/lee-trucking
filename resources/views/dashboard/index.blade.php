    
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Viexpedition</title>
  <link rel="icon" href="{{asset('assets/img/brand/favicon.png')}}" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="{{asset('assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/argon.css?v=1.1.0" type="text/css')}}">
  <link rel="stylesheet" href="{{asset('daterangepicker/daterangepicker.css')}}" type="text/css">
  
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
</head>
 
<body>
    <div class="row">
        <div class="row">
            <div class="col-xl-12">
                <div class="card-body">
                    <div class="row">
                        <div class="chart" style="margin-left:20px">
                            <canvas id="bar-chart" width="320%" height="320%" class="chart-canvas"></canvas>
                            <canvas id="pie-chart" width="320%" height="320%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
              
<script src="{{url('assets/chartJs/Chart.min.js')}}"></script>
<script>
    var exBln = {!! json_encode($bulan) !!};
    var exCount = {!! json_encode($total) !!};
    var total_truck = {!! json_encode($total_truck) !!};
    var cabang = {!! json_encode($cabang) !!};
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

<!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/js-cookie/js.cookie.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
  <!-- Optional JS -->
  <script src="{{asset('assets/vendor/chart.js/dist/Chart.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/dist/Chart.extension.js')}}"></script>
  <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
  <script src="{{url('assets/js/fslightbox.js')}}"></script>
  <script src="{{asset('assets/vendor/select2/dist/js/select2.min.js')}}"></script>
  <script src="{{asset('daterangepicker/moment.min.js')}}"></script>
  <script src="{{asset('daterangepicker/daterangepicker.js')}}"></script>

  
  
  <script src="{{asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-select/js/dataTables.select.min.js')}}"></script>
  <!-- Argon JS -->
  <script src="{{asset('assets/js/argon.js?v=1.1.0')}}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{asset('assets/js/demo.min.js')}}"></script>
</body>