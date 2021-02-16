  
  $(document).ready(function() {  
    // var accessToken =  window.Laravel.api_token;
    var accessToken =  'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImIwZDA1ZDIyODkwNDlhN2I3MzE2NGNlZjJkZjc5NjNiNzhlZTM5OTJhMmU4ZWU1MDlhZGYyYTIyMTYyYjRjNGMxMDVlOTQxMjFmZmJkN2EwIn0.eyJhdWQiOiIzIiwianRpIjoiYjBkMDVkMjI4OTA0OWE3YjczMTY0Y2VmMmRmNzk2M2I3OGVlMzk5MmEyZThlZTUwOWFkZjJhMjIxNjJiNGM0YzEwNWU5NDEyMWZmYmQ3YTAiLCJpYXQiOjE2MTMxNDkzNzksIm5iZiI6MTYxMzE0OTM3OSwiZXhwIjoxNjQ0Njg1Mzc5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.UnZC9M_PUCj7tm0a-Qs1cPkgKbPO-4uP3KhKnEB5_06OMxg4YMYTehi_uqYh8sgT765TO6-XQ2k9z-Fahe84B3aEzi2f5OU7x07I7e9ZEw5o3Bi33Dgu-yK1vnAdnnIdMDwWIwiwdZ02Nn2I7KRGObRRCK2xEJKHYJZ86z3QNTDkjrQA1xrUCpla97ZdVdcvNGgVkElihOp1Gxi_BcolhjA6qMJGJ0nZEiSTId8kBPpwIqN_3ft5PD6HY432e2eZfe00VdWIgNoXqUVrC9si7-UPXFEModuTh3JxxTKOTRGnCd9o_COuagasqT_4Yqy_85QX9g8qiwAIE-VQnszEZvvYUbLrdaNAEwsaGDKEZgGcVsh0UQaJ06809zUSMMYbrdXYVS7gaMVO_7xhtv4ba91wUvTlX0W-qDbpbx0yxD1j4GtIp6iYPpRSp75yLw_-corvFAg9CFJNRRrm7zS6frviy3CkhTJeOhnKA3UQOVtmJQeLY7gx9TCs6W9F6IyQ8PU3VJ80U6hiKaKQZgJxILm679Wk69u8ye_0vpHVPno2I_vrK685YNn-WyLcYL47mDnHcrwfDw6VohiLxu5JEUKxeM1rVyeVZIMMK4LwOIV2XldwTD3htvdhnmJbZprpdrxckwfQ-3KJc4rZeYlBmfMwUjgfEqwUasG0GO5cTeY';
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
        {"data":"data_json"},
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
    // $('.filter-satuan').change(function () {
    //     table.column( $(this).data('column'))
    //     .search( $(this).val() )
    //     .draw();
    // });
    table.on( 'order.dt search.dt', function () {
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