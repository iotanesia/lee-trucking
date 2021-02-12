  
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
    pageLength: 10,
    processing: true,
    serverSide: true,
    ajax: {
      "url": window.Laravel.app_url + "/api/report/get-jurnal-list",
      "type": "GET",
      "data": "where_filter"+"="+filter,
      "headers": {"Authorization": "Bearer " + accessToken},
      "crossDomain": true,
    },
    columns: [
        {"data":"created_at"},
        {"data":"created_at"},
        {"data":"sheet_name"},
        {"data":"jurnal_category"},
        {"data":"name"},
        {"data":"bank_name"},
        {"data":"rek_name"},
        {"data":"rek_no"},
    ],
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadexpedition(data);
    },
  });
  $('.filter-satuan').change(function () {
    table.draw();
  });
});