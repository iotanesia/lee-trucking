  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
      // var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
   
    var filter = $(".filter-satuan").val();
    $("#created_at").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });
    var table = $('#table-jurnal').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: true,
    dom: 'Blfrtip',
    buttons : ['csv','pdf', 'excel','print'
    ],
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-jurnal-list",
      type: "GET",
      data: "where_filter"+"="+filter,
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
          "data":"created_at", render: function (data, type, row, meta) {
            return formatDate(data);
          }
        },
        {"data":"nomor_inv"},
        {"data":"nomor_surat_jalan"},
        {"data":"activity_name"},
        {"data":"nominal_debit"},
        {"data":"nominal_credit"},
        {"data":"bank_name"},
        {"data":"rek_name"},
        {"data":"rek_no"},
        {"data":"name"},
        {"data":"table"},
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
      totalCredit = api
          .column(6)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);
      totalDebit = api
          .column(5)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);

          totalLoss = totalDebit - totalCredit;
          totalIncome = totalCredit - totalDebit;
          
          $('tr:eq(0) td:eq(3)', api.table().footer()).html('Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(5)', api.table().footer()).html(convertToRupiah(totalDebit));
          $('tr:eq(0) td:eq(6)', api.table().footer()).html(convertToRupiah(totalCredit));

          $('tr:eq(1) td:eq(3)', api.table().footer()).html('Income (Profit)&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(5)', api.table().footer()).html(convertToRupiah(totalIncome));

          $('tr:eq(2) td:eq(3)', api.table().footer()).html('Loss&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(5)', api.table().footer()).html(convertToRupiah(totalLoss));
    }
  });
  $('.filter-satuan').change(function () {
    table.column( $(this).data('column'))
    .search( $(this).val() )
    .draw();
  });
  function convertToRupiah(angka)
  {
    var rupiah = '';		
    var angkarev = angka.toString().split('').reverse().join('');
    for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
    return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
  }

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
});