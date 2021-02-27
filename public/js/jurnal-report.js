  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
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
        {"data":"created_at"},
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
          .column(3)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);
      totalDebit = api
          .column(2)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);

          totalLoss = totalDebit - totalCredit;
          totalIncome = totalCredit - totalDebit;
          
          $('tr:eq(0) td:eq(0)', api.table().footer()).html('Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(2)', api.table().footer()).html(convertToRupiah(totalDebit));
          $('tr:eq(0) td:eq(3)', api.table().footer()).html(convertToRupiah(totalCredit));

          $('tr:eq(1) td:eq(0)', api.table().footer()).html('Income (Profit)&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(2)', api.table().footer()).html(convertToRupiah(totalIncome));

          $('tr:eq(2) td:eq(0)', api.table().footer()).html('Loss&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(2)', api.table().footer()).html(convertToRupiah(totalLoss));
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
});