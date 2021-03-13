  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
    // var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
   
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    var lastDay = new Date(y, m+1, 0);

    var startDateBO = formatDateReq(firstDay);
    var endDateBO = formatDateReq(lastDay);
    var startDateBA = formatDateReq(firstDay);
    var endDateBA = formatDateReq(lastDay);
    var startDateBJ = formatDateReq(firstDay);
    var endDateBJ = formatDateReq(lastDay);

    var table = $('#table-invoice-bo').DataTable({
    processing: true,
    searching: false,
    serverSide: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-bo-list",
      type: "GET",
      data: function (d) {
        d.start_date = startDateBO;
        d.end_date = endDateBO;
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
        {
          "data":"tgl_po", render: function (data, type, row, meta) {
            return formatDate(data);
          }
        },
        {"data":"nomor_surat_jalan"},
        {"data":"kabupaten"},
        {"data":"truck_plat"},
        {"data":"jumlah_palet"},
        {"data":"rit"},
        {"data":"toko"},
        {"data":"harga_per_rit"},
        {"data":"total"},
    ],
    scrollCollapse: false,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
    },
    initComplete: function() {
        $('.btn-group > .btn:not(:last-child):not(.dropdown-toggle), .btn-group > .btn-group:not(:last-child) > .btn').css('margin-right','5px');
        $('.dataTables_length, .dataTables_info, .dt-buttons').css('padding-left','10px');
        $('.dataTables_filter ').css('padding-right','10px');
    },
    "footerCallback": function (row, data, start, end, display) {
      var api = this.api(), data;
      
      // Remove the formatting to get integer data for summation
      var intVal = function (i) {
          return typeof i === 'string' ?
              i.replace(/[\Rp.,]/g, '')*1 :
              typeof i === 'number' ?
                  i : 0;
      };
      // Total over all pages
      totalInvoice = api
          .column(8)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);
 

          ppn10 = (totalInvoice*10)/100;
          pph23 = (totalInvoice*2)/100;
          totalKeseluruhan = totalInvoice + ppn10 + pph23;

          $('tr:eq(0) td:eq(3)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(9)', api.table().footer()).html(convertToRupiah(totalInvoice));

          $('tr:eq(1) td:eq(3)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(9)', api.table().footer()).html(convertToRupiah(ppn10));

          $('tr:eq(2) td:eq(3)', api.table().footer()).html('PPH 23&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(9)', api.table().footer()).html(convertToRupiah(pph23));

          $('tr:eq(3) td:eq(3)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(3) td:eq(9)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
    }
    });

    var tableba = $('#table-invoice-ba').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: window.Laravel.app_url + "/api/report/get-invoice-ba-list",
        type: "GET",
        data: function (d) {
          d.start_date = startDateBA;
          d.end_date = endDateBA;
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
          {
            "data":"tgl_po", render: function (data, type, row, meta) {
              return formatDate(data);
            }
          },
          {"data":"nomor_surat_jalan"},
          {"data":"kabupaten"},
          {"data":"truck_plat"},
          {"data":"jumlah_palet"},
          {"data":"rit"},
          {"data":"toko"},
          {"data":"harga_per_rit"},
          {"data":"total"},
      ],
      scrollCollapse: true,
      "language": {
          "paginate": {
              "previous": '<i class="fas fa-angle-left"></i>',
              "next": '<i class="fas fa-angle-right"></i>'
          }
      },
      initComplete: function() {
          $('.btn-group > .btn:not(:last-child):not(.dropdown-toggle), .btn-group > .btn-group:not(:last-child) > .btn').css('margin-right','5px');
          $('.dataTables_length, .dataTables_info, .dt-buttons').css('padding-left','0px');
          $('.dataTables_filter ').css('padding-right','0px');
      },
      "footerCallback": function (row, data, start, end, display) {
        var api = this.api(), data;
        
        // Remove the formatting to get integer data for summation
        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\Rp.,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        // Total over all pages
        totalInvoice = api
            .column(8)
            .data()
            .reduce(function(a, b) {
              if((a != NaN || a != 0) && (b != NaN || b != 0)){
                return intVal(a) + intVal(b);
              }
            }, 0);
  

            ppn10 = (totalInvoice*10)/100;
            pph23 = (totalInvoice*2)/100;
            totalKeseluruhan = totalInvoice + ppn10 + pph23;

            $('tr:eq(0) td:eq(3)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
            $('tr:eq(0) td:eq(9)', api.table().footer()).html(convertToRupiah(totalInvoice));

            $('tr:eq(1) td:eq(3)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
            $('tr:eq(1) td:eq(9)', api.table().footer()).html(convertToRupiah(ppn10));

            $('tr:eq(2) td:eq(3)', api.table().footer()).html('PPH 23&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
            $('tr:eq(2) td:eq(9)', api.table().footer()).html(convertToRupiah(pph23));

            $('tr:eq(3) td:eq(3)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
            $('tr:eq(3) td:eq(9)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
      }
    });
 
  var tablebj = $('#table-invoice-bj').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-bj-list",
      type: "GET",data: function (d) {
        d.start_date = startDateBJ;
        d.end_date = endDateBJ;
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
        {
          "data":"tgl_po", render: function (data, type, row, meta) {
            return formatDate(data);
          }
        },
        {"data":"nomor_surat_jalan"},
        {"data":"kabupaten"},
        {"data":"truck_plat"},
        {"data":"jumlah_palet"},
        {"data":"rit"},
        {"data":"toko"},
        {"data":"harga_per_rit"},
        {"data":"total"},
    ],
    scrollCollapse: true,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
    },
    initComplete: function() {
        $('.btn-group > .btn:not(:last-child):not(.dropdown-toggle), .btn-group > .btn-group:not(:last-child) > .btn').css('margin-right','5px');
        $('.dataTables_length, .dataTables_info, .dt-buttons').css('padding-left','0px');
        $('.dataTables_filter ').css('padding-right','0px');
    },
    "footerCallback": function (row, data, start, end, display) {
      var api = this.api(), data;
      
      // Remove the formatting to get integer data for summation
      var intVal = function (i) {
          return typeof i === 'string' ?
              i.replace(/[\Rp.,]/g, '')*1 :
              typeof i === 'number' ?
                  i : 0;
      };
      // Total over all pages
      totalInvoice = api
          .column(8)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);
 

          ppn10 = (totalInvoice*10)/100;
          pph23 = (totalInvoice*2)/100;
          totalKeseluruhan = totalInvoice + ppn10 + pph23;

          $('tr:eq(0) td:eq(3)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(9)', api.table().footer()).html(convertToRupiah(totalInvoice));

          $('tr:eq(1) td:eq(3)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(9)', api.table().footer()).html(convertToRupiah(ppn10));

          $('tr:eq(2) td:eq(3)', api.table().footer()).html('PPH 23&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(9)', api.table().footer()).html(convertToRupiah(pph23));

          $('tr:eq(3) td:eq(3)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(3) td:eq(9)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
    }
  });
    table.on('order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    tableba.on('order.dt search.dt', function () {
      table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  } ).draw();

  tablebj.on('order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
} ).draw();
  function convertToRupiah(angka)
  {
    var rupiah = '';		
    var angkarev = angka.toString().split('').reverse().join('');
    for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
    return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
  }

  $(function() {
    $('input[name="dateRangeBO"]').daterangepicker({
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
      startDateBO = start.format('YYYY-MM-DD');
      endDateBO = end.format('YYYY-MM-DD');
      $('#table-invoice-bo').DataTable().ajax.reload();
    });
  });
  
  $(function() {
    $('input[name="dateRangeBA"]').daterangepicker({
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
      startDateBA = start.format('YYYY-MM-DD');
      endDateBA = end.format('YYYY-MM-DD');
      $('#table-invoice-ba').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeBJ"]').daterangepicker({
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
      startDateBJ = start.format('YYYY-MM-DD');
      endDateBJ = end.format('YYYY-MM-DD');
      $('#table-invoice-bj').DataTable().ajax.reload();
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

  $("#is-pdf").click(function(e) {
    e.preventDefault();
   
    $("#tipeFile").val("pdf");
    // return false;
  });

  $("#is-excel").click(function(e) {
    e.preventDefault();
    $("#tipeFile").val("excel");
   
    // return false;
  });
 
});