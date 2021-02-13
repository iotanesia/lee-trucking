  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
    var filter = $(".filter-satuan").val();
    // var accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImIwZDA1ZDIyODkwNDlhN2I3MzE2NGNlZjJkZjc5NjNiNzhlZTM5OTJhMmU4ZWU1MDlhZGYyYTIyMTYyYjRjNGMxMDVlOTQxMjFmZmJkN2EwIn0.eyJhdWQiOiIzIiwianRpIjoiYjBkMDVkMjI4OTA0OWE3YjczMTY0Y2VmMmRmNzk2M2I3OGVlMzk5MmEyZThlZTUwOWFkZjJhMjIxNjJiNGM0YzEwNWU5NDEyMWZmYmQ3YTAiLCJpYXQiOjE2MTMxNDkzNzksIm5iZiI6MTYxMzE0OTM3OSwiZXhwIjoxNjQ0Njg1Mzc5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.UnZC9M_PUCj7tm0a-Qs1cPkgKbPO-4uP3KhKnEB5_06OMxg4YMYTehi_uqYh8sgT765TO6-XQ2k9z-Fahe84B3aEzi2f5OU7x07I7e9ZEw5o3Bi33Dgu-yK1vnAdnnIdMDwWIwiwdZ02Nn2I7KRGObRRCK2xEJKHYJZ86z3QNTDkjrQA1xrUCpla97ZdVdcvNGgVkElihOp1Gxi_BcolhjA6qMJGJ0nZEiSTId8kBPpwIqN_3ft5PD6HY432e2eZfe00VdWIgNoXqUVrC9si7-UPXFEModuTh3JxxTKOTRGnCd9o_COuagasqT_4Yqy_85QX9g8qiwAIE-VQnszEZvvYUbLrdaNAEwsaGDKEZgGcVsh0UQaJ06809zUSMMYbrdXYVS7gaMVO_7xhtv4ba91wUvTlX0W-qDbpbx0yxD1j4GtIp6iYPpRSp75yLw_-corvFAg9CFJNRRrm7zS6frviy3CkhTJeOhnKA3UQOVtmJQeLY7gx9TCs6W9F6IyQ8PU3VJ80U6hiKaKQZgJxILm679Wk69u8ye_0vpHVPno2I_vrK685YNn-WyLcYL47mDnHcrwfDw6VohiLxu5JEUKxeM1rVyeVZIMMK4LwOIV2XldwTD3htvdhnmJbZprpdrxckwfQ-3KJc4rZeYlBmfMwUjgfEqwUasG0GO5cTeY';
    $("#created_at").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });
    var table = $('#table-jurnal').DataTable({
    processing: true,
    serverSide: true,
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
        {"data":"created_at"},
        {"data":"sheet_name"},
        {"data":"jurnal_category"},
        {"data":"name"},
        {"data":"bank_name"},
        {"data":"rek_name"},
        {"data":"rek_no"},
    ],
  });
  $('.filter-satuan').change(function () {
    table.column( $(this).data('column'))
    .search( $(this).val() )
    .draw();
});
});