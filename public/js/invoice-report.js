  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
    // var filter = $(".filter-satuan").val();
    $("#created_at").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });
    var table = $('#table-invoice-bo').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Blfrtip',
    buttons : ['csv','pdf', 'excel','print'
    ],
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-bo-list",
      type: "GET",
    //   data: "where_filter"+"="+filter,
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
        {"data":"tgl_po"},
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

  var tableba = $('#table-invoice-ba').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Blfrtip',
    buttons : ['csv','pdf', 'excel','print'
    ],
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-ba-list",
      type: "GET",
    //   data: "where_filter"+"="+filter,
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
        {"data":"tgl_po"},
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
    dom: 'Blfrtip',
    buttons : ['csv','pdf', 'excel','print'
    ],
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-bj-list",
      type: "GET",
    //   data: "where_filter"+"="+filter,
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
        {"data":"tgl_po"},
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
});