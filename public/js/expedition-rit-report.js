  
  
  $(document).ready(function() {  
    // var accessToken =  window.Laravel.api_token;
    var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
   
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    var lastDay = new Date(y, m+1, 0);

    var startDateTujuan = formatDateReq(firstDay);
    var endDateTujuan = formatDateReq(lastDay);
    var startDateTruck = formatDateReq(firstDay);
    var endDateTruck = formatDateReq(lastDay);
    var startDateDriver = formatDateReq(firstDay);
    var endDateDriver = formatDateReq(lastDay);
    var detailId = '';
    var tableRitTujuan = $('#table-rit-tujuan').DataTable({
    processing: true,
    serverSide: true, autoWidth: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-ekspedisi-rit-tujuan-list",
      type: "GET",
      data: function (d) {
        d.start_date = startDateTujuan;
        d.end_date = endDateTujuan;
      },
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
    },
    columns: [
        {
          "data": null, "sortable": false,
            render: function (data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {"data":"kabupaten"},
        {"data":"kecamatan"},
        {"data":"total_ekspedisi"},
        {
            "data": null,
            render: function (data, type, row) {
              return '<a href="#" onclick="return openModalDetail(\'' + data.kecamatan + '\')" data-toggle="modal" data-target="#modal-detail-rit-report-tujuan" class="btn btn-success" style="padding-right:5px !important;padding-left:5px !important;margin-top:-10px !important;font-size:8pt !important;padding:3px !important">Detail</a>';
            }
        }
    ],
    scrollCollapse: true,
    language: {
        paginate: {
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>'
        }
    }
  });

  var tableRitTruck = $('#table-rit-truck').DataTable({
    processing: true,
    serverSide: true, autoWidth: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-ekspedisi-rit-truck-list",
      type: "GET",data: function (d) {
        d.start_date = startDateTruck;
        d.end_date = endDateTruck;
      },
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
    },
    columns: [
        {
          "data": null, "sortable": false,
            render: function (data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {"data":"truck_name"},
        {"data":"truck_plat"},
        {"data":"total_ekspedisi"},
        {
            "data": null,
            render: function (data, type, row) {
              return '<a href="#" data-toggle="modal" data-target="#modal-detail-truck-repair" class="btn btn-success" style="padding-right:5px !important;padding-left:5px !important;margin-top:-10px !important;font-size:8pt !important;padding:3px !important">Detail</a>';
            }
        }
    ],
    scrollCollapse: true,
    language: {
        paginate: {
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>'
        }
    }
  });

  var tableRitDriver = $('#table-rit-driver').DataTable({
    processing: true,
    serverSide: true, autoWidth: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-ekspedisi-rit-driver-list",
      type: "GET",data: function (d) {
        d.start_date = startDateDriver;
        d.end_date = endDateDriver;
      },
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
    },
    columns: [
        {
          "data": null, "sortable": false,
            render: function (data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {"data":"driver_name"},
        {"data":"total_ekspedisi"},
        {
            "data": null,
            render: function (data, type, row) {
              return '<a href="#" data-toggle="modal" data-target="#modal-detail-truck-repair" class="btn btn-success" style="padding-right:5px !important;padding-left:5px !important;margin-top:-10px !important;font-size:8pt !important;padding:3px !important">Detail</a>';
            }
        }
    ],
    scrollCollapse: true,
    language: {
        paginate: {
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>'
        }
    }
  });

  $(function() {
    $('input[name="dateRangeRitTujuan"]').daterangepicker({
      opens: 'right',
      showDropdowns: true,
    locale: {
        format:'DD MMMM YYYY',
        separator:' - ',
        applyLabel: 'Pilih',
        cancelLabel: 'Batal',
        customRangeLabel:'Custom',
        daysOfWeek:[
            'Min',
            'Sen',
            'Sel',
            'Rab',
            'Kam',
            'Jum',
            'Sab'
        ],
        monthNames:[
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        firstDay:'1'
    },
    startDate: formatDate(firstDay),
    endDate: formatDate(lastDay)
    },
    function(start, end, label) {
      startDate = start.format('YYYY-MM-DD');
      endDate = end.format('YYYY-MM-DD');
      $('#table-rit-tujuan').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeRitDriver"]').daterangepicker({
      opens: 'right',
      showDropdowns: true,
    locale: {
        format:'DD MMMM YYYY',
        separator:' - ',
        applyLabel: 'Pilih',
        cancelLabel: 'Batal',
        customRangeLabel:'Custom',
        daysOfWeek:[
            'Min',
            'Sen',
            'Sel',
            'Rab',
            'Kam',
            'Jum',
            'Sab'
        ],
        monthNames:[
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        firstDay:'1'
    },
    startDate: formatDate(firstDay),
    endDate: formatDate(lastDay)
    },
    function(start, end, label) {
      startDate = start.format('YYYY-MM-DD');
      endDate = end.format('YYYY-MM-DD');
      $('#table-rit-driver').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeRitTruck"]').daterangepicker({
      opens: 'right',
      showDropdowns: true,
    locale: {
        format:'DD MMMM YYYY',
        separator:' - ',
        applyLabel: 'Pilih',
        cancelLabel: 'Batal',
        customRangeLabel:'Custom',
        daysOfWeek:[
            'Min',
            'Sen',
            'Sel',
            'Rab',
            'Kam',
            'Jum',
            'Sab'
        ],
        monthNames:[
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        firstDay:'1'
    },
    startDate: formatDate(firstDay),
    endDate: formatDate(lastDay)
    },
    function(start, end, label) {
      startDate = start.format('YYYY-MM-DD');
      endDate = end.format('YYYY-MM-DD');
      $('#table-rit-truck').DataTable().ajax.reload();
    });
  });

  function formatDate(date) {
    var d = new Date(date),
        bulan = d.getMonth(),
        day = '' + d.getDate(),
        year = d.getFullYear();

        switch(bulan) {
          case 0: bulan = "Januari"; break;
          case 1: bulan = "Februari"; break;
          case 2: bulan = "Maret"; break;
          case 3: bulan = "April"; break;
          case 4: bulan = "Mei"; break;
          case 5: bulan = "Juni"; break;
          case 6: bulan = "Juli"; break;
          case 7: bulan = "Agustus"; break;
          case 8: bulan = "September"; break;
          case 9: bulan = "Oktober"; break;
          case 10: bulan = "November"; break;
          case 11: bulan = "Desember"; break;
         }

 
    if (day.length < 2) 
        day = '0' + day;
    var result = [day, bulan, year].join(' ');
    // console.log(result);
    return result;
  }

  function formatDateReq(date) {
    var d = new Date(date),
    month = '' + (d.getMonth()+1),
    day = '' + d.getDate(),
    year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }
  

 $('#clearDate').click(function(e){
    e.preventDefault();
    $('input[name="dateRange"]').val('');
    $('input[name="dateRange"]').attr("placeholder","Pilih Tanggal");
    startDate = '';
    endDate = '';   
    $('#table-repair-truck-report').DataTable().ajax.reload();
 })

  $("#is-excel").click(function(e) {
    e.preventDefault();
    $("#tipeFile").val("excel");
   
    // return false;
  });
 
  $("#is-pdf").click(function(e) {
    e.preventDefault();
   
    $("#tipeFile").val("pdf");
    // return false;
  });
});

   
function openModalDetail(filter){
  
    // var accessToken =  window.Laravel.api_token;
    var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
   $('#kode-detail-rit-report-tujuan').text(filter);

  var tableDetailRepairTruck = $('#table-detail-rit-report-tujuan').DataTable({
    processing: true,
    serverSide: true, autoWidth: true,
    ajax: {
      url: window.Laravel.app_url + "/api/expedition/get-list",
      type: "GET",
      dataType: "json",
      data: function (d) {
        d.where_value = filter;
      },
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoadexpedition(data);
      }
    }
  });

  function formatDate(date) {
    var d = new Date(date),
        bulan = d.getMonth(),
        day = '' + d.getDate(),
        year = d.getFullYear();

        switch(bulan) {
          case 0: bulan = "Januari"; break;
          case 1: bulan = "Februari"; break;
          case 2: bulan = "Maret"; break;
          case 3: bulan = "April"; break;
          case 4: bulan = "Mei"; break;
          case 5: bulan = "Juni"; break;
          case 6: bulan = "Juli"; break;
          case 7: bulan = "Agustus"; break;
          case 8: bulan = "September"; break;
          case 9: bulan = "Oktober"; break;
          case 10: bulan = "November"; break;
          case 11: bulan = "Desember"; break;
         }

 
    if (day.length < 2) 
        day = '0' + day;
    var result = [day, bulan, year].join(' ');
    // console.log(result);
    return result;
  } 
  $('#modal-detail-rit-report-tujuan').on('hidden.bs.modal', function () {
    $('#table-detail-rit-report-tujuan').dataTable().fnDestroy();
});
}

var successLoadexpedition = (function(responses, dataModel) {
      
  var tableRows = "";
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    nomor_inv = responses.data[i].nomor_inv;
    pabrik_pesanan = responses.data[i].pabrik_pesanan;
    nomor_surat_jalan = responses.data[i].nomor_surat_jalan;
    nama_barang = responses.data[i].nama_barang;
    truck_name = responses.data[i].truck_name;
    truck_plat = responses.data[i].truck_plat;
    driver_name = responses.data[i].driver_name;
    tgl_inv = responses.data[i].tgl_inv;
    tgl_po = responses.data[i].tgl_po;
    kecamatan = responses.data[i].kecamatan;
    kabupaten = responses.data[i].kabupaten;
    cabang_name = responses.data[i].cabang_name;
    status_name = responses.data[i].status_name;
    approval_name = responses.data[i].approval_name;
    otv_payment_method = responses.data[i].otv_payment_method;

    if(def(otv_payment_method) !== '-') {
        var payment = ' - '+otv_payment_method;
    
    } else {
        var payment = '';
    }

    data_json = responses.data[i].data_json;

    if(responses.data[i].status_activity == 'SUBMIT') {
        classColor = 'badge-success';
        
    } else if(responses.data[i].status_activity == 'APPROVAL_OJK_DRIVER') {
        classColor = 'badge-warning';

    } else if(responses.data[i].status_activity == 'DRIVER_MENUJU_TUJUAN') {
        classColor = 'badge-info';

    } else if(responses.data[i].status_activity == 'DRIVER_SAMPAI_TUJUAN') {
        classColor = 'badge-gradient-warning';
    
    } else {
        classColor = 'badge-danger';

    }

    if(responses.data[i].approval_code == 'APPROVED') {
        classColors = 'badge-success';

    } else if(responses.data[i].approval_code == 'REVISION') {
        classColors = 'badge-warning';

    } else if(responses.data[i].approval_code == 'WAITING_OWNER') {
        classColors = 'badge-info';
    
    } else if(responses.data[i].approval_code == null){
        approval_name = '-';
        classColors = '';

    } else {
        classColors = 'badge-danger';
    }


    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ nomor_surat_jalan +"</td>"+
                   "<td>"+ nomor_inv +"</td>"+
                   "<td>"+ driver_name +"</td>"+
                   "<td>"+ dateFormat(tgl_inv) +"</td>"+
                   "<td>"+ dateFormat(tgl_po) +"</td>"+
                   "<td>"+ kabupaten +" - "+ kecamatan +" - "+ cabang_name +"</td>"+
                   "<td> <span class='badge "+classColor+"'>"+ status_name + payment +"</span></td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-warning btn-xs btn-sm' href='"+window.Laravel.app_url+"/expedition-tracking/"+id+"'><i class='fas fa-eye'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-detail-rit-report-tujuan tbody").html(tableRows);
  paginate(responses, 'expedition');
  $(".preloader").hide();
});