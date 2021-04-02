  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
      // var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5NTlhYjQ2ZWUwZmFjOWU1ZGYxYTdkMjY0NzE3NmFlZWViYTg5M2ExOTA4NjY0N2ZiNjhiZmUzYTk2MjNkYTk5YWE0YzM0Njg3NWMxY2QzIn0.eyJhdWQiOiIzIiwianRpIjoiNDk1OWFiNDZlZTBmYWM5ZTVkZjFhN2QyNjQ3MTc2YWVlZWJhODkzYTE5MDg2NjQ3ZmI2OGJmZTNhOTYyM2RhOTlhYTRjMzQ2ODc1YzFjZDMiLCJpYXQiOjE2MTUzMDU1OTksIm5iZiI6MTYxNTMwNTU5OSwiZXhwIjoxNjQ2ODQxNTk5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.X1minlba3vJY7FkBVY8Hi_ijTGdvmftNBk17863ItQbGhOUiAMCjK-TEHJst4PJMmZpBQdKa0GcpGwtOgYkCADS4uxYgG6FIuRCXsfetSx23TmF48PSlhMxyeG55i23aHwHUv-Ho7cKXwxOYDEOT10QBKGNYTs-TFzXMheajtxTJvgjGb7VzJCcA8tMn-n3DzKA9mT-ZU4CB9WSoCh4IjAisxRhOf2iC8IYxu_h-L5cC_R4jPirvTcOEtoPgQ752_O0XvDQDFoYH_Rdp0DOy3PkyhJrX3CL6HOAYwAI-ip2X2j4Z9-Hp0ddqFOAAszoauGrTxzgKZGus4VHcQ9NQjsfv7KrAlwLGpS0Zc-jWqfavzMz6OMNpevLc7c3OVVeWN4jUCrJTZCUnQMwZgr2rSN5yJLU20DjSpljN0N2NOot43hf83_K0e8iTsLFnwmLkyh7KezOtkMzHmBXSq1j2sVUs4jsZH-eOsh8Vs7aIFyxC4qIMV6h_mU8oFA1TaGhVyzzW_xLJgl9gGLRDONPP15AT6vmkFD14Ut6tJUbjpBV9FSshJ3JUTP-LjCKbAMao1TkEAOsrG2ag-V9R0pg-cym7Glok57_i_jJwEfbVSFXAD5v2sEo5rp0VVTM3x2hziuXH1q1UmGRg3HgqF0Iw2EVmuRNs7vgZXJwBJA3xFjc"
   
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    var lastDay = new Date(y, m+1, 0);

    var startDate = formatDateReq(firstDay);
    var endDate = formatDateReq(lastDay);

    var table = $('#table-jurnal').DataTable({
    processing: true,
    serverSide: true,
    paging:   false,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-jurnal-list",
      type: "GET",
      data: function (d) {
        d.start_date = startDate;
        d.end_date = endDate;
        d.filter_select = $("#filter_select_jurnal").val();
        d.filter_aktiviti = $("#filter_select_aktiviti_jurnal").val();
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
    var reg = /^\d+$/;
    var minus = reg.test(angka);
    var nominal = angka+'';
    var depan = ' ';
    if(!minus){
      nominal = nominal.replace(/[^\w\s]/gi, '');
      depan = ' -';
    }
    var rupiah = '';		
    var angkarev = nominal.split('').reverse().join('');
    for(var i = 0; i < angkarev.length; i++){
          if(i%3 == 0) {
              rupiah += angkarev.substr(i,3)+'.';
        }
      }
      var result = rupiah.split('',rupiah.length-1).reverse().join('');

      // if(result.charAt(0) == '-'){
      //   result = result.substring(2);
      //    console.log(result);
      //   result = 'Rp. -'+result;
      // }else{
      //   result = 'Rp. '+result;
      // }
    return 'Rp.'+depan+result;
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

  $(function() {
    $('input[name="dateRangeJurnal"]').daterangepicker({
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
      $('#table-jurnal').DataTable().ajax.reload();
    });
  });

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
  $("#filter_select_jurnal").on("change", function() {
    $('#filterSelectJurnal').val($("#filter_select_jurnal").val());
    $('#table-jurnal').DataTable().ajax.reload();
  });

  $("#filter_select_aktiviti_jurnal").on("change", function() {
    $('#filterActivityJurnal').val($("#filter_select_aktiviti_jurnal").val());
    $('#table-jurnal').DataTable().ajax.reload();
  });

  $('#btn-input-balance-jurnal').click(function(e) {
    e.preventDefault();
    $('#modal-pilihan-export-jurnal').modal('hide');
    $('[data-dismiss=modal]').on('click', function (e) {
      $('#balance-jurnal').val('');
      $('#balanceJurnal').val('');
    });
    $('#modal-input-balance-jurnal').modal({backdrop: 'static', keyboard: false});
    $('#modal-input-balance-jurnal').modal('show');
  });
  
  // $('#balance-jurnal').keypress(function(e){
  //   if(e.keyCode != 8 && e.keyCode !=46) {
  //     var text = this.value; 
  //    if(text.length%4 == 3){
  //       this.value = text+'.'; 
  //     }
  //    }
  // });

  $('#balance-jurnal').on('input',function(e){
    $('#balanceJurnal').val($('#balance-jurnal').val());
    if(!($('#balance-jurnal').val().trim())){
      $('#btn-export-jurnal-with-balance').css('display','none');
    }else{
      $('#btn-export-jurnal-with-balance').css('display','block');
      $('#btn-export-jurnal-with-balance').css('color','#FFFFFF');
      $('#btn-export-jurnal-with-balance').css('width','100%');
    }
  });

  $('#balance-jurnal').on('keydown keyup', function(e) {
    
    var regExp = /[a-z]/i;
    var value = String.fromCharCode(e.which) || e.key;

    // No letters
    if (regExp.test(value)) {
      e.preventDefault();
      return false;
    }
  });
});