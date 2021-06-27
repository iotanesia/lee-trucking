  
  
  $(document).ready(function() {  
    
    var accessToken =  window.Laravel.api_token;
    // var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var fd = new Date(y, m, 1);
    var firstDay = formatDate(fd);
    var ld = new Date(y, m + 1, 0);
    var lastDay = formatDate(ld);
    
    var startDateTujuan = formatDateReq(firstDay);
    var endDateTujuan = formatDateReq(lastDay);
    var startDateTruck = formatDateReq(firstDay);
    var endDateTruck = formatDateReq(lastDay);
    var startDateDriver = formatDateReq(firstDay);
    var endDateDriver = formatDateReq(lastDay);
    var detailId = '';
    var tableRitTujuan = $('#table-rit-tujuan').DataTable({
    processing: true,
    serverSide: true, 
    autoWidth: true,
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
        {"data":"total_ojk"},
        {"data":"total_otv"},
        {
            "data": null,
            render: function (data, type, row) {
              param = data.kabupaten+', '+data.kecamatan;
              return '<a href="#" onclick="return openModalDetail(\'Tujuan\',\'' + param + '\',\'' + data.ojk_id + '\',\'' + startDateTujuan + '\',\'' + endDateTujuan + '\')" data-toggle="modal" data-target="#modal-detail-rit-report" class="btn btn-success" style="padding-right:5px !important;padding-left:5px !important;margin-top:-10px !important;font-size:8pt !important;padding:3px !important">Detail</a>';
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
          {"data":"total_ojk"},
          {"data":"total_otv"},
          {
            "data": null,
            render: function (data, type, row) {
              param = data.truck_name+', '+data.truck_plat;
              return '<a href="#" onclick="return openModalDetail(\'Truck\',\'' + param + '\',\'' + data.truck_id + '\',\'' + startDateTruck + '\',\'' + endDateTruck + '\')" data-toggle="modal" data-target="#modal-detail-rit-report" class="btn btn-success" style="padding-right:5px !important;padding-left:5px !important;margin-top:-10px !important;font-size:8pt !important;padding:3px !important">Detail</a>';
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
          {"data":"total_ojk"},
          {"data":"total_otv"},
          {
            "data": null,
            render: function (data, type, row) {
              param = data.driver_name;
              return '<a href="#" onclick="return openModalDetail(\'Driver\',\'' + param + '\',\'' + data.driver_id + '\',\'' + startDateDriver + '\',\'' + endDateDriver + '\')" data-toggle="modal" data-target="#modal-detail-rit-report" class="btn btn-success" style="padding-right:5px !important;padding-left:5px !important;margin-top:-10px !important;font-size:8pt !important;padding:3px !important">Detail</a>';
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
              'January',
              'February',
              'March',
              'April',
              'May',
              'June',
              'July',
              'August',
              'September',
              'October',
              'November',
              'December'
          ],
          firstDay:'1'
      },
      startDate: formatDate(firstDay),
      endDate: formatDate(lastDay)
      },
      function(start, end, label) {
        startDateTujuan = start.format('YYYY-MM-DD');
        endDateTujuan = end.format('YYYY-MM-DD');
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
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ],
          firstDay:'1'
      },
      startDate: formatDate(firstDay),
      endDate: formatDate(lastDay)
      },
      function(start, end, label) {
        startDateDriver = start.format('YYYY-MM-DD');
        endDateDriver = end.format('YYYY-MM-DD');
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
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ],
          firstDay:'1'
      },
        startDate: formatDate(firstDay),
        endDate: formatDate(lastDay)
      },
      function(start, end, label) {
        startDateTruck = start.format('YYYY-MM-DD');
        endDateTruck = end.format('YYYY-MM-DD');
        $('#table-rit-truck').DataTable().ajax.reload();
      });
    });

    function formatDate(date) {
          var d = new Date(date),
          bulan = d.getMonth(),
          day = '' + d.getDate(),
          year = d.getFullYear();

          switch(bulan) {
            case 0: bulan = "January"; break;
            case 1: bulan = "February"; break;
            case 2: bulan = "March"; break;
            case 3: bulan = "April"; break;
            case 4: bulan = "May"; break;
            case 5: bulan = "June"; break;
            case 6: bulan = "July"; break;
            case 7: bulan = "August"; break;
            case 8: bulan = "September"; break;
            case 9: bulan = "October"; break;
            case 10: bulan = "November"; break;
            case 11: bulan = "December"; break;
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

function openModalDetail(_ritBy, _param, _whereValue, _startDate, _endDate){
  
  var accessToken =  window.Laravel.api_token;
  // var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
 $('#kode-detail-rit-report').text('Rit '+_ritBy+' '+_param);

 var tableRitDetail = $('#table-detail-rit-report').DataTable({
  processing: true,
  serverSide: true, autoWidth: true,
  ajax: {
    url: window.Laravel.app_url + "/api/report/get-detail-rit-list",
    type: "GET",
    data: function (d) {
      d.rit_by = _ritBy;
      d.where_value = _whereValue;
      d.start_date = _startDate;
      d.end_date = _endDate;
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
      {"data":"nomor_surat_jalan"},
      {"data":"nomor_inv"},
      {"data":"driver_name"},
      {"data":"harga_ojk"},
      {"data":"harga_otv"},
      {
        "data":"tgl_inv", render: function (data, type, row, meta) {
          return formatDateModal(data);
        }
      },
      {
        "data":"tgl_po", render: function (data, type, row, meta) {
          return formatDateModal(data);
        }
      },
      {"data":"tujuan"},
      {
        "data": null,
        render: function (data, type, row) {
        if(data.status_activity == 'SUBMIT') {
            classColor = 'badge-success';
            
        } else if(data.status_activity == 'APPROVAL_OJK_DRIVER') {
            classColor = 'badge-warning';
    
        } else if(data.status_activity == 'DRIVER_MENUJU_TUJUAN') {
            classColor = 'badge-info';
    
        } else if(data.status_activity == 'DRIVER_SAMPAI_TUJUAN') {
            classColor = 'badge-gradient-warning';
        
        } else {
            classColor = 'badge-danger';
    
        }
          return '<span class="badge '+classColor+'">'+ data.status_name +' '+ data.otv_payment_method +'</span>';
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

$('#modal-detail-rit-report').on('hidden.bs.modal', function(e) {
  $('#table-detail-rit-report').dataTable().fnDestroy();
});

    function formatDateModal(date) {
      var d = new Date(date),
          bulan = d.getMonth(),
          day = '' + d.getDate(),
          year = d.getFullYear();

          switch(bulan) {
            case 0: bulan = "January"; break;
            case 1: bulan = "February"; break;
            case 2: bulan = "March"; break;
            case 3: bulan = "April"; break;
            case 4: bulan = "May"; break;
            case 5: bulan = "June"; break;
            case 6: bulan = "July"; break;
            case 7: bulan = "August"; break;
            case 8: bulan = "September"; break;
            case 9: bulan = "October"; break;
            case 10: bulan = "November"; break;
            case 11: bulan = "December"; break;
          }


      if (day.length < 2) 
          day = '0' + day;
      var result = [day, bulan, year].join(' ');
      // console.log(result);
      return result;
    } 
}
