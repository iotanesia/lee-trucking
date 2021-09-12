  
  $(document).ready(function() {  
    var accessToken =  window.Laravel.api_token;
    // var accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI4Mzc3NDQ2M2QyOTFiODNkM2Q2OTliNjNjYTc0NzBkYzA0ZWE2MmFlNDMyNzNkNTFhY2Y1NDM4YjI3YjdiODdmNjU0ZjIyYzAwMzBiM2U0In0.eyJhdWQiOiI1IiwianRpIjoiYjgzNzc0NDYzZDI5MWI4M2QzZDY5OWI2M2NhNzQ3MGRjMDRlYTYyYWU0MzI3M2Q1MWFjZjU0MzhiMjdiN2I4N2Y2NTRmMjJjMDAzMGIzZTQiLCJpYXQiOjE2MjM0NjkzNTksIm5iZiI6MTYyMzQ2OTM1OSwiZXhwIjoxNjU1MDA1MzU5LCJzdWIiOiIxODAiLCJzY29wZXMiOltdfQ.jBNYevbbLnEOvpxZzlK-zOC7rB4I3MVM7ks9jxO27yhzQ5Dwd8hk1_pfcqsawoEXG1Em-k8Npy43QzAn_eWvWzVHsXVzXinnFqqJjYk33A-UrBSrzuXkFxDmPZSTLzDHus_t7qCuDONLWib8f6w1C1-e6znb--R-H4Z4y8VLeRDMMwtIy6E9wwikcYaPmUjSV1e1bj9xZNC82CBhtarkm3eMNXuiDTi02J0RMzJgx3ySfJLF4nWhF0oS-spf0i2aOBXtFt_AE-37wNn2uIndXu3fnpuFQYrn__xIy66-NidXrjgapL5P9N-93TQ9jbYzhmtBoF30Ab1hwNmTFkdd0ZtsE0fW56fspWQUll9ySiU6OvKGNxO0WOOM47sNVeQSk0V-i2oadrTbPbhzOAgSbOwMymH3oY3dvIWrYaYQcURHivZQfOGiRXrBZpy2UdrNT9cJCxv2l734m-Y8inTZbzjtHbxOugEY_NIxlz5C7SHOBT9tmhk2mdimr8gtvki2XHFY8Pc0z4lnzVFVgDx6VcMaVF0DpCtE2SGz_VzDRGqj3sHtsH_bsKeNGi7hdUY2E8M7kGav7feKieq_d2NgeVAzb4EI27zm2agYCsSX59InhwDts8b_2_bSudDv-n2EXJQSICoPK41gEtvlIOGywwXAtDnfQyu36ZdTI3j8Af8"
   
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var fd = new Date(y, m, 1);
    var firstDay = formatDate(fd);
    var ld = new Date(y, m + 1, 0);
    var lastDay = formatDate(ld);

    var startDateBO = formatDateReq(firstDay);
    var endDateBO = formatDateReq(lastDay);
    var startDateBA = formatDateReq(firstDay);
    var endDateBA = formatDateReq(lastDay);
    var startDateBJ = formatDateReq(firstDay);
    var endDateBJ = formatDateReq(lastDay);
    var startDateBF = formatDateReq(firstDay);
    var endDateBF = formatDateReq(lastDay);

    
    var startDateDO = formatDateReq(firstDay);
    var endDateDO = formatDateReq(lastDay);
    var startDateDA = formatDateReq(firstDay);
    var endDateDA = formatDateReq(lastDay);
    var startDateDJ = formatDateReq(firstDay);
    var endDateDJ = formatDateReq(lastDay);
    var startDateDF = formatDateReq(firstDay);
    var endDateDF = formatDateReq(lastDay);

  var table = $('#table-invoice-bo').DataTable({
  processing: true,
  serverSide: true,
  ajax: {
    url: window.Laravel.app_url + "/api/report/get-invoice-bo-list",
    type: "GET",
    data: function (d) {
      d.start_date = startDateBO;
      d.end_date = endDateBO;
      d.filter = $("#filter-select-bo").val();
      d.filter_periksa = $("#filter-periksa-bo").val();
      d.filter_export = $("#filter-export-bo").val();
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
        data:   "is_read_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-bo-active"> <label class="editor-bo-label">Belum Diperiksa</label>';
            }
            return data;
        }
      },
      {
        data:   "is_export_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-export-bo-active"> <label class="editor-export-bo-label">Belum di export</label>';
            }
            return data;
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
  "rowCallback": function (row, data) {
    // Set the checked state of the checkbox in the table
    if(data.is_read_invoice_report == true){
      $('input.editor-bo-active', row).prop('checked', true);
      $('label.editor-bo-label', row).text('Sudah Diperiksa');
    }else{
      $('input.editor-bo-active', row).prop('checked', false);
      $('label.editor-bo-label', row).text('Belum Diperiksa');
    }
    
    if(data.is_export_invoice_report == true){
      $('input.editor-export-bo-active', row).prop('checked', true);
      $('label.editor-export-bo-label', row).text('Sudah di export');
    }else{
      $('input.editor-export-bo-active', row).prop('checked', false);
      $('label.editor-export-bo-label', row).text('Belum di export');
    }

    $('input.editor-bo-active', row).on('change', function () {
      var isTruePeriksa = false;
      if($(this).prop('checked')){
        isTruePeriksa=true;
      }else{
        isTruePeriksa =false;
      }
      var datasss = { id : data.id, is_read : isTruePeriksa}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
        type: "POST",
        dataType: "json",
        data: datasss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTruePeriksa){
            $('label.editor-bo-label', row).text('Sudah Diperiksa');
          }else{
            $('label.editor-bo-label', row).text('Belum Diperiksa');
          }
        }
      });
    });

    $('input.editor-export-bo-active', row).on('change', function () {
      var isTrueExport = false;
      if($(this).prop('checked')){
        isTrueExport=true;
      }else{
        isTrueExport =false;
      }
      var datassss = { id : data.id, is_export : isTrueExport}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-export",
        type: "POST",
        dataType: "json",
        data: datassss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTrueExport){
            $('label.editor-export-bo-label', row).text('Sudah di export');
          }else{
            $('label.editor-export-bo-label', row).text('Belum di export');
          }
        }
      });
    });
  },
  scrollCollapse: false,
  "language": {
      "paginate": {
          "previous": '<i class="fas fa-angle-left"></i>',
          "next": '<i class="fas fa-angle-right"></i>'
      }
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
        .column(10)
        .data()
        .reduce(function(a, b) {
          if((a != NaN || a != 0) && (b != NaN || b != 0)){
            return intVal(a) + intVal(b);
          }
        }, 0);


        ppn10 = (totalInvoice*10)/100;
        pph23 = (totalInvoice*2)/100;
        // totalKeseluruhan = totalInvoice + ppn10 + pph23;
        if(($('#cbPpn10Bo').is(':checked') != true) && ($('#cbPph23Bo').is(':checked') != true)){
          totalKeseluruhan = totalInvoice;
        }else if(($('#cbPpn10Bo').is(':checked') == true) && ($('#cbPph23Bo').is(':checked') != true)){
          totalKeseluruhan = totalInvoice + ppn10;
        }else if(($('#cbPpn10Bo').is(':checked') != true) && ($('#cbPph23Bo').is(':checked') == true)){
          totalKeseluruhan = totalInvoice + pph23;
        }else{
          totalKeseluruhan = totalInvoice + ppn10 + pph23;
        }
        
        $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;:');
        $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));

        $('tr:eq(1) td:eq(4)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
        $('tr:eq(1) td:eq(11)', api.table().footer()).html(convertToRupiah(ppn10));

        $('tr:eq(2) td:eq(4)', api.table().footer()).html('PPH 23&nbsp;&nbsp;:');
        $('tr:eq(2) td:eq(11)', api.table().footer()).html(convertToRupiah(pph23));

        $('tr:eq(3) td:eq(4)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;:');
        $('tr:eq(3) td:eq(11)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
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
        d.filter = $("#filter-select-ba").val();
        d.filter_periksa = $("#filter-periksa-ba").val();
        d.filter_export = $("#filter-export-ba").val();
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
        data:   "is_read_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-ba-active"> <label class="editor-ba-label">Belum Diperiksa</label>';
            }
            return data;
        }
      },
      {
        data:   "is_export_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-export-ba-active"> <label class="editor-export-ba-label">Belum di export</label>';
            }
            return data;
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
  "rowCallback": function (row, data) {
    // Set the checked state of the checkbox in the table
    if(data.is_read_invoice_report == true){
      $('input.editor-ba-active', row).prop('checked', true);
      $('label.editor-ba-label', row).text('Sudah Diperiksa');
    }else{
      $('input.editor-ba-active', row).prop('checked', false);
      $('label.editor-ba-label', row).text('Belum Diperiksa');
    }

    if(data.is_export_invoice_report == true){
      $('input.editor-export-ba-active', row).prop('checked', true);
      $('label.editor-export-ba-label', row).text('Sudah di export');
    }else{
      $('input.editor-export-ba-active', row).prop('checked', false);
      $('label.editor-export-ba-label', row).text('Belum di export');
    }
    
    $('input.editor-ba-active', row).on('change', function () {
      var isTruePeriksa = false;
      if($(this).prop('checked')){
        isTruePeriksa=true;
      }else{
        isTruePeriksa =false;
      }
      var datasss = { id : data.id, is_read : isTruePeriksa}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
        type: "POST",
        dataType: "json",
        data: datasss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTruePeriksa){
            $('label.editor-ba-label', row).text('Sudah Diperiksa');
          }else{
            $('label.editor-ba-label', row).text('Belum Diperiksa');
          }
        }
      });
    });

    $('input.editor-export-ba-active', row).on('change', function () {
      var isTrueExport = false;
      if($(this).prop('checked')){
        isTrueExport=true;
      }else{
        isTrueExport =false;
      }
      var datassss = { id : data.id, is_export : isTrueExport}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-export",
        type: "POST",
        dataType: "json",
        data: datassss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTruePeriksa){
            $('label.editor-export-ba-label', row).text('Sudah di export');
          }else{
            $('label.editor-export-ba-label', row).text('Belum di export');
          }
        }
      });
    });
  },
    scrollCollapse: true,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
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
          .column(10)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);


          ppn10 = (totalInvoice*10)/100;
          pph23 = (totalInvoice*2)/100;

          if(($('#cbPpn10Ba').is(':checked') != true) && ($('#cbPph23Ba').is(':checked') != true)){
            totalKeseluruhan = totalInvoice;
          }else if(($('#cbPpn10Ba').is(':checked') == true) && ($('#cbPph23Ba').is(':checked') != true)){
            totalKeseluruhan = totalInvoice + ppn10;
          }else if(($('#cbPpn10Ba').is(':checked') != true) && ($('#cbPph23Ba').is(':checked') == true)){
            totalKeseluruhan = totalInvoice + pph23;
          }else{
            totalKeseluruhan = totalInvoice + ppn10 + pph23;
          }

          $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));

          $('tr:eq(1) td:eq(4)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(11)', api.table().footer()).html(convertToRupiah(ppn10));

          $('tr:eq(2) td:eq(4)', api.table().footer()).html('PPH 23&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(11)', api.table().footer()).html(convertToRupiah(pph23));

          $('tr:eq(3) td:eq(4)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;:');
          $('tr:eq(3) td:eq(11)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
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
        d.filter = $("#filter-select-bj").val();
        d.filter_periksa = $("#filter-periksa-bj").val();
        d.filter_export = $("#filter-export-bj").val();
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
        data:   "is_read_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-bj-active"> <label class="editor-bj-label">Belum Diperiksa</label>';
            }
            return data;
        }
      },
      {
        data:   "is_export_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-export-bj-active"> <label class="editor-export-bj-label">Belum di export</label>';
            }
            return data;
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
  "rowCallback": function (row, data) {
    // Set the checked state of the checkbox in the table
    if(data.is_read_invoice_report == true){
      $('input.editor-bj-active', row).prop('checked', true);
      $('label.editor-bj-label', row).text('Sudah Diperiksa');
    }else{
      $('input.editor-bj-active', row).prop('checked', false);
      $('label.editor-bj-label', row).text('Belum Diperiksa');
    }

    if(data.is_export_invoice_report == true){
      $('input.editor-export-bj-active', row).prop('checked', true);
      $('label.editor-export-bj-label', row).text('Sudah di export');
    }else{
      $('input.editor-export-bj-active', row).prop('checked', false);
      $('label.editor-export-bj-label', row).text('Belum di export');
    }
    
    $('input.editor-bj-active', row).on('change', function () {
      var isTruePeriksa = false;
      if($(this).prop('checked')){
        isTruePeriksa=true;
      }else{
        isTruePeriksa =false;
      }
      var datasss = { id : data.id, is_read : isTruePeriksa}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
        type: "POST",
        dataType: "json",
        data: datasss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTruePeriksa){
            $('label.editor-bj-label', row).text('Sudah Diperiksa');
          }else{
            $('label.editor-bj-label', row).text('Belum Diperiksa');
          }
        }
      });
    });

    $('input.editor-export-bj-active', row).on('change', function () {
      var isTrueExport = false;
      if($(this).prop('checked')){
        isTrueExport=true;
      }else{
        isTrueExport =false;
      }
      var datassss = { id : data.id, is_export : isTrueExport}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-export",
        type: "POST",
        dataType: "json",
        data: datassss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTrueExport){
            $('label.editor-export-bj-label', row).text('Sudah di export');
          }else{
            $('label.editor-export-bj-label', row).text('Belum di export');
          }
        }
      });
    });
  },
    scrollCollapse: true,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
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
          .column(10)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);

          $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));
    }
  });

  var tablebf = $('#table-invoice-bf').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-bf-list",
      type: "GET",
      data: function (d) {
        d.start_date = startDateBF;
        d.end_date = endDateBF;
        d.filter = $("#filter-select-bf").val();   
        d.filter_periksa = $("#filter-periksa-bf").val();
        d.filter_export = $("#filter-export-bf").val();
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
        data:   "is_read_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-bf-active"> <label class="editor-bf-label">Belum Diperiksa</label>';
            }
            return data;
        }
      },
      {
        data:   "is_export_invoice_report",
        render: function ( data, type, row ) {
            if ( type === 'display' ) {
                return '<input type="checkbox" class="editor-export-bf-active"> <label class="editor-export-bf-label">Belum di export</label>';
            }
            return data;
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
  "rowCallback": function (row, data) {
    // Set the checked state of the checkbox in the table
    if(data.is_read_invoice_report == true){
      $('input.editor-bf-active', row).prop('checked', true);
      $('label.editor-bf-label', row).text('Sudah Diperiksa');
    }else{
      $('input.editor-bf-active', row).prop('checked', false);
      $('label.editor-bf-label', row).text('Belum Diperiksa');
    }

    if(data.is_export_invoice_report == true){
      $('input.editor-export-bf-active', row).prop('checked', true);
      $('label.editor-export-bf-label', row).text('Sudah di export');
    }else{
      $('input.editor-export-bf-active', row).prop('checked', false);
      $('label.editor-export-bf-label', row).text('Belum di export');
    }
    
    $('input.editor-bf-active', row).on('change', function () {
      var isTruePeriksa = false;
      if($(this).prop('checked')){
        isTruePeriksa=true;
      }else{
        isTruePeriksa =false;
      }
      var datasss = { id : data.id, is_read : isTruePeriksa}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
        type: "POST",
        dataType: "json",
        data: datasss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTruePeriksa){
            $('label.editor-bf-label', row).text('Sudah Diperiksa');
          }else{
            $('label.editor-bf-label', row).text('Belum Diperiksa');
          }
        }
      });
    });

    $('input.editor-export-bf-active', row).on('change', function () {
      var isTrueExport = false;
      if($(this).prop('checked')){
        isTrueExport=true;
      }else{
        isTrueExport =false;
      }
      var datassss = { id : data.id, is_export : isTrueExport}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-export",
        type: "POST",
        dataType: "json",
        data: datassss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTrueExport){
            $('label.editor-export-bf-label', row).text('Sudah di export');
          }else{
            $('label.editor-export-bf-label', row).text('Belum di export');
          }
        }
      });
    });
  },
    scrollCollapse: true,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
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
          .column(10)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);


          ppn10 = (totalInvoice*10)/100;
          pph23 = (totalInvoice*2)/100;

          if(($('#cbPpn10Bf').is(':checked') != true) && ($('#cbPph23Bf').is(':checked') != true)){
            totalKeseluruhan = totalInvoice;
          }else if(($('#cbPpn10Bf').is(':checked') == true) && ($('#cbPph23Bf').is(':checked') != true)){
            totalKeseluruhan = totalInvoice + ppn10;
          }else if(($('#cbPpn10Bf').is(':checked') != true) && ($('#cbPph23Bf').is(':checked') == true)){
            totalKeseluruhan = totalInvoice + pph23;
          }else{
            totalKeseluruhan = totalInvoice + ppn10 + pph23;
          }

          $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));

          $('tr:eq(1) td:eq(4)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(11)', api.table().footer()).html(convertToRupiah(ppn10));

          $('tr:eq(2) td:eq(4)', api.table().footer()).html('PPH 23&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(11)', api.table().footer()).html(convertToRupiah(pph23));

          $('tr:eq(3) td:eq(4)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;:');
          $('tr:eq(3) td:eq(11)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
    }
  });

  var tabledo = $('#table-invoice-do').DataTable({
    processing: true,
    searching: false,
    serverSide: true,
    ajax: {
      url: window.Laravel.app_url + "/api/report/get-invoice-do-list",
      type: "GET",
      data: function (d) {
        d.start_date = startDateDO;
        d.end_date = endDateDO;
        d.filter = $("#filter-select-do").val();
        d.filter_periksa = $("#filter-periksa-do").val();
        d.filter_export = $("#filter-export-do").val();
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
          data:   "is_read_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-do-active"> <label class="editor-do-label">Belum Diperiksa</label>';
              }
              return data;
          }
        },
        {
          data:   "is_export_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-export-do-active"> <label class="editor-export-do-label">Belum di export</label>';
              }
              return data;
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
    "rowCallback": function (row, data) {
      // Set the checked state of the checkbox in the table
      if(data.is_read_invoice_report == true){
        $('input.editor-do-active', row).prop('checked', true);
        $('label.editor-do-label', row).text('Sudah Diperiksa');
      }else{
        $('input.editor-do-active', row).prop('checked', false);
        $('label.editor-do-label', row).text('Belum Diperiksa');
      }

      if(data.is_export_invoice_report == true){
        $('input.editor-export-do-active', row).prop('checked', true);
        $('label.editor-export-do-label', row).text('Sudah di export');
      }else{
        $('input.editor-export-do-active', row).prop('checked', false);
        $('label.editor-export-do-label', row).text('Belum di export');
      }

        
    $('input.editor-do-active', row).on('change', function () {
      var isTruePeriksa = false;
      if($(this).prop('checked')){
        isTruePeriksa=true;
      }else{
        isTruePeriksa =false;
      }
      var datasss = { id : data.id, is_read : isTruePeriksa}
      $.ajax({
        url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
        type: "POST",
        dataType: "json",
        data: datasss,
        headers: {"Authorization": "Bearer " + accessToken},
        dataType: "text",
        success: function(resultData) {
          if(isTruePeriksa){
            $('label.editor-do-label', row).text('Sudah Diperiksa');
          }else{
            $('label.editor-do-label', row).text('Belum Diperiksa');
          }
        }
      });
    });
      
      $('input.editor-export-do-active', row).on('change', function () {
        var isTrueExport = false;
        if($(this).prop('checked')){
          isTrueExport=true;
        }else{
          isTrueExport =false;
        }
        var datassss = { id : data.id, is_export : isTrueExport}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-export",
          type: "POST",
          dataType: "json",
          data: datassss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTrueExport){
              $('label.editor-export-do-label', row).text('Sudah di export');
            }else{
              $('label.editor-export-do-label', row).text('Belum di export');
            }
          }
        });
      });
    },
    scrollCollapse: false,
    "language": {
        "paginate": {
            "previous": '<i class="fas fa-angle-left"></i>',
            "next": '<i class="fas fa-angle-right"></i>'
        }
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
          .column(10)
          .data()
          .reduce(function(a, b) {
            if((a != NaN || a != 0) && (b != NaN || b != 0)){
              return intVal(a) + intVal(b);
            }
          }, 0);
  
  
          ppn10 = (totalInvoice*10)/100;
          pph23 = (totalInvoice*2)/100;
          // totalKeseluruhan = totalInvoice + ppn10 + pph23;
          if(($('#cbPpn10Do').is(':checked') != true) && ($('#cbPph23Do').is(':checked') != true)){
            totalKeseluruhan = totalInvoice;
          }else if(($('#cbPpn10Do').is(':checked') == true) && ($('#cbPph23Do').is(':checked') != true)){
            totalKeseluruhan = totalInvoice + ppn10;
          }else if(($('#cbPpn10Do').is(':checked') != true) && ($('#cbPph23Do').is(':checked') == true)){
            totalKeseluruhan = totalInvoice + pph23;
          }else{
            totalKeseluruhan = totalInvoice + ppn10 + pph23;
          }
          
          $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;:');
          $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));
  
          $('tr:eq(1) td:eq(4)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
          $('tr:eq(1) td:eq(11)', api.table().footer()).html(convertToRupiah(ppn10));
  
          $('tr:eq(2) td:eq(4)', api.table().footer()).html('PPH 23&nbsp;&nbsp;:');
          $('tr:eq(2) td:eq(11)', api.table().footer()).html(convertToRupiah(pph23));
  
          $('tr:eq(3) td:eq(4)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;:');
          $('tr:eq(3) td:eq(11)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
    }
    });
    
    var tableda = $('#table-invoice-da').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: window.Laravel.app_url + "/api/report/get-invoice-da-list",
        type: "GET",
        data: function (d) {
          d.start_date = startDateDA;
          d.end_date = endDateDA;
          d.filter = $("#filter-select-da").val();
          d.filter_periksa = $("#filter-periksa-da").val();
          d.filter_export = $("#filter-export-da").val();
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
          data:   "is_read_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-da-active"> <label class="editor-da-label">Belum Diperiksa</label>';
              }
              return data;
          }
        },
        {
          data:   "is_export_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-export-da-active"> <label class="editor-export-da-label">Belum di export</label>';
              }
              return data;
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
    "rowCallback": function (row, data) {
      // Set the checked state of the checkbox in the table
      if(data.is_read_invoice_report == true){
        $('input.editor-da-active', row).prop('checked', true);
        $('label.editor-da-label', row).text('Sudah Diperiksa');
      }else{
        $('input.editor-da-active', row).prop('checked', false);
        $('label.editor-da-label', row).text('Belum Diperiksa');
      }

      if(data.is_export_invoice_report == true){
        $('input.editor-export-da-active', row).prop('checked', true);
        $('label.editor-export-da-label', row).text('Sudah di export');
      }else{
        $('input.editor-export-da-active', row).prop('checked', false);
        $('label.editor-export-da-label', row).text('Belum di export');
      }
      
      $('input.editor-da-active', row).on('change', function () {
        var isTruePeriksa = false;
        if($(this).prop('checked')){
          isTruePeriksa=true;
        }else{
          isTruePeriksa =false;
        }
        var datasss = { id : data.id, is_read : isTruePeriksa}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
          type: "POST",
          dataType: "json",
          data: datasss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTruePeriksa){
              $('label.editor-da-label', row).text('Sudah Diperiksa');
            }else{
              $('label.editor-da-label', row).text('Belum Diperiksa');
            }
          }
        });
      });

      $('input.editor-export-da-active', row).on('change', function () {
        var isTrueExport = false;
        if($(this).prop('checked')){
          isTrueExport=true;
        }else{
          isTrueExport =false;
        }
        var datassss = { id : data.id, is_export : isTrueExport}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-export",
          type: "POST",
          dataType: "json",
          data: datassss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTrueExport){
              $('label.editor-export-da-label', row).text('Sudah di export');
            }else{
              $('label.editor-export-da-label', row).text('Belum di export');
            }
          }
        });
      });
    },
      scrollCollapse: true,
      "language": {
          "paginate": {
              "previous": '<i class="fas fa-angle-left"></i>',
              "next": '<i class="fas fa-angle-right"></i>'
          }
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
            .column(10)
            .data()
            .reduce(function(a, b) {
              if((a != NaN || a != 0) && (b != NaN || b != 0)){
                return intVal(a) + intVal(b);
              }
            }, 0);
  
  
            ppn10 = (totalInvoice*10)/100;
            pph23 = (totalInvoice*2)/100;
  
            if(($('#cbPpn10Da').is(':checked') != true) && ($('#cbPph23Da').is(':checked') != true)){
              totalKeseluruhan = totalInvoice;
            }else if(($('#cbPpn10Da').is(':checked') == true) && ($('#cbPph23Da').is(':checked') != true)){
              totalKeseluruhan = totalInvoice + ppn10;
            }else if(($('#cbPpn10Da').is(':checked') != true) && ($('#cbPph23Da').is(':checked') == true)){
              totalKeseluruhan = totalInvoice + pph23;
            }else{
              totalKeseluruhan = totalInvoice + ppn10 + pph23;
            }
  
            $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;:');
            $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));
  
            $('tr:eq(1) td:eq(3)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
            $('tr:eq(1) td:eq(11)', api.table().footer()).html(convertToRupiah(ppn10));
  
            $('tr:eq(2) td:eq(4)', api.table().footer()).html('PPH 23&nbsp;&nbsp;:');
            $('tr:eq(2) td:eq(11)', api.table().footer()).html(convertToRupiah(pph23));
  
            $('tr:eq(3) td:eq(4)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;:');
            $('tr:eq(3) td:eq(11)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
      }
    });
   
    var tabledj = $('#table-invoice-dj').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: window.Laravel.app_url + "/api/report/get-invoice-dj-list",
        type: "GET",data: function (d) {
          d.start_date = startDateDJ;
          d.end_date = endDateDJ;
          d.filter = $("#filter-select-dj").val();
          d.filter_periksa = $("#filter-periksa-dj").val();
          d.filter_export = $("#filter-export-dj").val();
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
          data:   "is_read_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-dj-active"> <label class="editor-dj-label">Belum Diperiksa</label>';
              }
              return data;
          }
        },
        {
          data:   "is_export_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-export-dj-active"> <label class="editor-export-dj-label">Belum di export</label>';
              }
              return data;
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
    "rowCallback": function (row, data) {
      // Set the checked state of the checkbox in the table
      if(data.is_read_invoice_report == true){
        $('input.editor-dj-active', row).prop('checked', true);
        $('label.editor-dj-label', row).text('Sudah Diperiksa');
      }else{
        $('input.editor-dj-active', row).prop('checked', false);
        $('label.editor-dj-label', row).text('Belum Diperiksa');
      }
      if(data.is_export_invoice_report == true){
        $('input.editor-export-dj-active', row).prop('checked', true);
        $('label.editor-export-dj-label', row).text('Sudah di export');
      }else{
        $('input.editor-export-dj-active', row).prop('checked', false);
        $('label.editor-export-dj-label', row).text('Belum di export');
      }
      
      $('input.editor-dj-active', row).on('change', function () {
        var isTruePeriksa = false;
        if($(this).prop('checked')){
          isTruePeriksa=true;
        }else{
          isTruePeriksa =false;
        }
        var datasss = { id : data.id, is_read : isTruePeriksa}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
          type: "POST",
          dataType: "json",
          data: datasss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTruePeriksa){
              $('label.editor-dj-label', row).text('Sudah Diperiksa');
            }else{
              $('label.editor-dj-label', row).text('Belum Diperiksa');
            }
          }
        });
      });

      $('input.editor-export-dj-active', row).on('change', function () {
        var isTrueExport = false;
        if($(this).prop('checked')){
          isTrueExport=true;
        }else{
          isTrueExport =false;
        }
        var datassss = { id : data.id, is_export : isTrueExport}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-export",
          type: "POST",
          dataType: "json",
          data: datassss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTrueExport){
              $('label.editor-export-dj-label', row).text('Sudah di export');
            }else{
              $('label.editor-export-dj-label', row).text('Belum di export');
            }
          }
        });
      });
    },
      scrollCollapse: true,
      "language": {
          "paginate": {
              "previous": '<i class="fas fa-angle-left"></i>',
              "next": '<i class="fas fa-angle-right"></i>'
          }
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
            .column(10)
            .data()
            .reduce(function(a, b) {
              if((a != NaN || a != 0) && (b != NaN || b != 0)){
                return intVal(a) + intVal(b);
              }
            }, 0);
  
            $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:');
            $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));
      }
    });
  
    var tabledf = $('#table-invoice-df').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: window.Laravel.app_url + "/api/report/get-invoice-bf-list",
        type: "GET",
        data: function (d) {
          d.start_date = startDateDF;
          d.end_date = endDateDF;
          d.filter = $("#filter-select-df").val();
          d.filter_periksa = $("#filter-periksa-df").val();
          d.filter_export = $("#filter-export-df").val();
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
          data:   "is_read_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-df-active"> <label class="editor-df-label">Belum Diperiksa</label>';
              }
              return data;
          }
        },
        {
          data:   "is_export_invoice_report",
          render: function ( data, type, row ) {
              if ( type === 'display' ) {
                  return '<input type="checkbox" class="editor-export-df-active"> <label class="editor-export-df-label">Belum di periksa</label>';
              }
              return data;
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
    "rowCallback": function (row, data) {
      // Set the checked state of the checkbox in the table
      if(data.is_read_invoice_report == true){
        $('input.editor-df-active', row).prop('checked', true);
        $('label.editor-df-label', row).text('Sudah Diperiksa');
      }else{
        $('input.editor-df-active', row).prop('checked', false);
        $('label.editor-df-label', row).text('Belum Diperiksa');
      }

      if(data.is_export_invoice_report == true){
        $('input.editor-export-df-active', row).prop('checked', true);
        $('label.editor-export-df-label', row).text('Sudah di export');
      }else{
        $('input.editor-export-df-active', row).prop('checked', false);
        $('label.editor-export-df-label', row).text('Belum di export');
      }

      $('input.editor-df-active', row).on('change', function () {
        var isTruePeriksa = false;
        if($(this).prop('checked')){
          isTruePeriksa=true;
        }else{
          isTruePeriksa =false;
        }
        var datasss = { id : data.id, is_read : isTruePeriksa}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-periksa",
          type: "POST",
          dataType: "json",
          data: datasss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTruePeriksa){
              $('label.editor-df-label', row).text('Sudah Diperiksa');
            }else{
              $('label.editor-df-label', row).text('Belum Diperiksa');
            }
          }
        });
      });    
      
      $('input.editor-export-df-active', row).on('change', function () {
        var isTrueExport = false;
        if($(this).prop('checked')){
          isTrueExport=true;
        }else{
          isTrueExport =false;
        }
        var datassss = { id : data.id, is_export : isTrueExport}
        $.ajax({
          url: window.Laravel.app_url + "/api/report/post-change-status-export",
          type: "POST",
          dataType: "json",
          data: datassss,
          headers: {"Authorization": "Bearer " + accessToken},
          dataType: "text",
          success: function(resultData) {
            if(isTrueExport){
              $('label.editor-export-df-label', row).text('Sudah di export');
            }else{
              $('label.editor-export-df-label', row).text('Belum di export');
            }
          }
        });
      });
    },
      scrollCollapse: true,
      "language": {
          "paginate": {
              "previous": '<i class="fas fa-angle-left"></i>',
              "next": '<i class="fas fa-angle-right"></i>'
          }
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
            .column(10)
            .data()
            .reduce(function(a, b) {
              if((a != NaN || a != 0) && (b != NaN || b != 0)){
                return intVal(a) + intVal(b);
              }
            }, 0);
  
  
            ppn10 = (totalInvoice*10)/100;
            pph23 = (totalInvoice*2)/100;
  
            if(($('#cbPpn10Df').is(':checked') != true) && ($('#cbPph23Df').is(':checked') != true)){
              totalKeseluruhan = totalInvoice;
            }else if(($('#cbPpn10Df').is(':checked') == true) && ($('#cbPph23Df').is(':checked') != true)){
              totalKeseluruhan = totalInvoice + ppn10;
            }else if(($('#cbPpn10Df').is(':checked') != true) && ($('#cbPph23Df').is(':checked') == true)){
              totalKeseluruhan = totalInvoice + pph23;
            }else{
              totalKeseluruhan = totalInvoice + ppn10 + pph23;
            }
  
            $('tr:eq(0) td:eq(4)', api.table().footer()).html('Total Invoie&nbsp;&nbsp;:');
            $('tr:eq(0) td:eq(11)', api.table().footer()).html(convertToRupiah(totalInvoice));
  
            $('tr:eq(1) td:eq(4)', api.table().footer()).html('PPN 10%&nbsp;&nbsp;:');
            $('tr:eq(1) td:eq(11)', api.table().footer()).html(convertToRupiah(ppn10));
  
            $('tr:eq(2) td:eq(4)', api.table().footer()).html('PPH 23&nbsp;&nbsp;:');
            $('tr:eq(2) td:eq(11)', api.table().footer()).html(convertToRupiah(pph23));
  
            $('tr:eq(3) td:eq(4)', api.table().footer()).html('Total Keseluruhan Invoice&nbsp;&nbsp;:');
            $('tr:eq(3) td:eq(11)', api.table().footer()).html(convertToRupiah(totalKeseluruhan));
      }
    });

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
      startDateBJ = start.format('YYYY-MM-DD');
      endDateBJ = end.format('YYYY-MM-DD');
      $('#table-invoice-bj').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeBF"]').daterangepicker({
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
      startDateBF = start.format('YYYY-MM-DD');
      endDateBF = end.format('YYYY-MM-DD');
      $('#table-invoice-bf').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeDO"]').daterangepicker({
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
      startDateDO = start.format('YYYY-MM-DD');
      endDateDO = end.format('YYYY-MM-DD');
      $('#table-invoice-do').DataTable().ajax.reload();
    });
  });
  
  $(function() {
    $('input[name="dateRangeDA"]').daterangepicker({
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
      startDateDA = start.format('YYYY-MM-DD');
      endDateDA = end.format('YYYY-MM-DD');
      $('#table-invoice-da').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeDJ"]').daterangepicker({
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
      startDateDJ = start.format('YYYY-MM-DD');
      endDateDJ = end.format('YYYY-MM-DD');
      $('#table-invoice-dj').DataTable().ajax.reload();
    });
  });

  $(function() {
    $('input[name="dateRangeDF"]').daterangepicker({
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
      startDateDF = start.format('YYYY-MM-DD');
      endDateDF = end.format('YYYY-MM-DD');
      $('#table-invoice-df').DataTable().ajax.reload();
    });
  });
  
  $("#filter-select-bo").on("change", function() {
    $('#table-invoice-bo').DataTable().ajax.reload();
  });

  $("#filter-select-ba").on("change", function() {
    $('#table-invoice-ba').DataTable().ajax.reload();
  });

  $("#filter-select-bj").on("change", function() {
    $('#table-invoice-bj').DataTable().ajax.reload();
  });

  $("#filter-select-bf").on("change", function() {
    $('#table-invoice-bf').DataTable().ajax.reload();
  });

  $("#filter-select-do").on("change", function() {
    $('#table-invoice-do').DataTable().ajax.reload();
  });

  $("#filter-select-da").on("change", function() {
    $('#table-invoice-da').DataTable().ajax.reload();
  });

  $("#filter-select-dj").on("change", function() {
    $('#table-invoice-dj').DataTable().ajax.reload();
  });

  $("#filter-select-df").on("change", function() {
    $('#table-invoice-df').DataTable().ajax.reload();
  });


  $("#filter-periksa-bo").on("change", function() {
    $('#table-invoice-bo').DataTable().ajax.reload();
  });

  $("#filter-export-bo").on("change", function() {
    $('#table-invoice-bo').DataTable().ajax.reload();
  });

  $("#filter-periksa-ba").on("change", function() {
    $('#table-invoice-ba').DataTable().ajax.reload();
  });

  $("#filter-export-ba").on("change", function() {
    $('#table-invoice-ba').DataTable().ajax.reload();
  });

  $("#filter-periksa-bj").on("change", function() {
    $('#table-invoice-bj').DataTable().ajax.reload();
  });

  $("#filter-export-bj").on("change", function() {
    $('#table-invoice-bj').DataTable().ajax.reload();
  });

  $("#filter-periksa-bf").on("change", function() {
    $('#table-invoice-bf').DataTable().ajax.reload();
  });

  $("#filter-export-bf").on("change", function() {
    $('#table-invoice-bf').DataTable().ajax.reload();
  });

  $("#filter-periksa-do").on("change", function() {
    $('#table-invoice-do').DataTable().ajax.reload();
  });

  $("#filter-export-do").on("change", function() {
    $('#table-invoice-do').DataTable().ajax.reload();
  });

  $("#filter-periksa-da").on("change", function() {
    $('#table-invoice-da').DataTable().ajax.reload();
  });

  $("#filter-export-da").on("change", function() {
    $('#table-invoice-da').DataTable().ajax.reload();
  });

  $("#filter-periksa-df").on("change", function() {
    $('#table-invoice-df').DataTable().ajax.reload();
  });

  $("#filter-export-df").on("change", function() {
    $('#table-invoice-df').DataTable().ajax.reload();
  });

  $("#filter-periksa-dj").on("change", function() {
    $('#table-invoice-dj').DataTable().ajax.reload();
  });

  $("#filter-export-dj").on("change", function() {
    $('#table-invoice-dj').DataTable().ajax.reload();
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

  $("#is-pdf-bo").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileBO").val("pdf");
    // return false;
  });

  $("#is-excel-bo").click(function(e) {
    e.preventDefault();
    // alert("excel");
    $("#tipeFileBO").val("excel");
   
    // return false;
  });
 
  $("#is-pdf-ba").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileBA").val("pdf");
    // return false;
  });

  $("#is-excel-ba").click(function(e) {
    e.preventDefault();
    $("#tipeFileBA").val("excel");
   
    // return false;
  });
 
  $("#is-pdf-bj").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileBJ").val("pdf");
    // return false;
  });

  $("#is-excel-bj").click(function(e) {
    e.preventDefault();
    $("#tipeFileBJ").val("excel");
   
    // return false;
  });

  $("#is-pdf-bf").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileBF").val("pdf");
    // return false;
  });

  $("#is-excel-bf").click(function(e) {
    e.preventDefault();
    $("#tipeFileBF").val("excel");
   
    // return false;
  });

  $("#is-pdf-do").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileDO").val("pdf");
    // return false;
  });

  $("#is-excel-do").click(function(e) {
    e.preventDefault();
    // alert("excel");
    $("#tipeFileDO").val("excel");
   
    // return false;
  });
 
  $("#is-pdf-da").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileDA").val("pdf");
    // return false;
  });

  $("#is-excel-da").click(function(e) {
    e.preventDefault();
    $("#tipeFileDA").val("excel");
   
    // return false;
  });
 
  $("#is-pdf-dj").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileDJ").val("pdf");
    // return false;
  });

  $("#is-excel-dj").click(function(e) {
    e.preventDefault();
    $("#tipeFileDJ").val("excel");
   
    // return false;
  });

  $("#is-pdf-df").click(function(e) {
    e.preventDefault();
   
    $("#tipeFileDF").val("pdf");
    // return false;
  });

  $("#is-excel-df").click(function(e) {
    e.preventDefault();
    $("#tipeFileDF").val("excel");
   
    // return false;
  });


  $('#no-invoice-bo').on('input',function(e){
    $('#noInvoiceBO').val($('#no-invoice-bo').val());
    if(!($('#no-invoice-bo').val().trim())){
      $('#btn-export-bo').css('display','none');
    }else{
      $('#btn-export-bo').css('display','block');
    }
  });
  $('#no-invoice-ba').on('input',function(e){
    $('#noInvoiceBA').val($('#no-invoice-ba').val());
    if(!($('#no-invoice-ba').val().trim())){
      $('#btn-export-ba').css('display','none');
    }else{
      $('#btn-export-ba').css('display','block');
    }
  });
  $('#no-invoice-bj').on('input',function(e){
    $('#noInvoiceBJ').val($('#no-invoice-bj').val());
    if(!($('#no-invoice-bj').val().trim())){
      $('#btn-export-bj').css('display','none');
    }else{
      $('#btn-export-bj').css('display','block');
    }
  });

  $('#no-invoice-bf').on('input',function(e){
    $('#noInvoiceBF').val($('#no-invoice-bf').val());
    if(!($('#no-invoice-bf').val().trim())){
      $('#btn-export-bf').css('display','none');
    }else{
      $('#btn-export-bf').css('display','block');
    }
  });

  $('#no-invoice-do').on('input',function(e){
    $('#noInvoiceDO').val($('#no-invoice-do').val());
    if(!($('#no-invoice-do').val().trim())){
      $('#btn-export-do').css('display','none');
    }else{
      $('#btn-export-do').css('display','block');
    }
  });
  $('#no-invoice-da').on('input',function(e){
    $('#noInvoiceDA').val($('#no-invoice-da').val());
    if(!($('#no-invoice-da').val().trim())){
      $('#btn-export-da').css('display','none');
    }else{
      $('#btn-export-da').css('display','block');
    }
  });
  $('#no-invoice-dj').on('input',function(e){
    $('#noInvoiceDJ').val($('#no-invoice-dj').val());
    if(!($('#no-invoice-dj').val().trim())){
      $('#btn-export-dj').css('display','none');
    }else{
      $('#btn-export-dj').css('display','block');
    }
  });

  $('#no-invoice-df').on('input',function(e){
    $('#noInvoiceDF').val($('#no-invoice-df').val());
    if(!($('#no-invoice-df').val().trim())){
      $('#btn-export-df').css('display','none');
    }else{
      $('#btn-export-df').css('display','block');
    }
  });

  $('#cbPpn10Bo').click(function() {
    if(this.checked){
      $('#table-invoice-bo').DataTable().ajax.reload(function() {
        $('#trPpn10Bo').show();
      });
    }else{
      $('#table-invoice-bo').DataTable().ajax.reload(function() {
        $('#trPpn10Bo').hide();
      });
    }
  });

  $('#cbPph23Bo').click(function() {
    if(this.checked){
      $('#table-invoice-bo').DataTable().ajax.reload(function() {
        $('#trPph23Bo').show();
      });
    }else{
      $('#table-invoice-bo').DataTable().ajax.reload(function() {
        $('#trPph23Bo').hide();
      });
    }
  });

  $('#cbPpn10Ba').click(function() {
    if(this.checked){
      $('#table-invoice-ba').DataTable().ajax.reload(function() {
        $('#trPpn10Ba').show();
      });
    }else{
      $('#table-invoice-ba').DataTable().ajax.reload(function() {
        $('#trPpn10Ba').hide();
      });
    }
  });

  $('#cbPph23Ba').click(function() {
    if(this.checked){
      $('#table-invoice-ba').DataTable().ajax.reload(function() {
        $('#trPph23Ba').show();
      });
    }else{
      $('#table-invoice-ba').DataTable().ajax.reload(function() {
        $('#trPph23Ba').hide();
      });
    }
  });
  $('#cbPpn10Bf').click(function() {
    if(this.checked){
      $('#table-invoice-bf').DataTable().ajax.reload(function() {
        $('#trPpn10Ba').show();
      });
    }else{
      $('#table-invoice-bf').DataTable().ajax.reload(function() {
        $('#trPpn10Bf').hide();
      });
    }
  });
  $('#cbPph23Bf').click(function() {
    if(this.checked){
      $('#table-invoice-bf').DataTable().ajax.reload(function() {
        $('#trPph23Bf').show();
      });
    }else{
      $('#table-invoice-bf').DataTable().ajax.reload(function() {
        $('#trPph23Bf').hide();
      });
    }
  });

  $('#cbPpn10Do').click(function() {
    if(this.checked){
      $('#table-invoice-do').DataTable().ajax.reload(function() {
        $('#trPpn10Do').show();
      });
    }else{
      $('#table-invoice-do').DataTable().ajax.reload(function() {
        $('#trPpn10Do').hide();
      });
    }
  });

  $('#cbPph23Do').click(function() {
    if(this.checked){
      $('#table-invoice-do').DataTable().ajax.reload(function() {
        $('#trPph23Do').show();
      });
    }else{
      $('#table-invoice-do').DataTable().ajax.reload(function() {
        $('#trPph23Do').hide();
      });
    }
  });

  $('#cbPpn10Da').click(function() {
    if(this.checked){
      $('#table-invoice-da').DataTable().ajax.reload(function() {
        $('#trPpn10Da').show();
      });
    }else{
      $('#table-invoice-da').DataTable().ajax.reload(function() {
        $('#trPpn10Da').hide();
      });
    }
  });

  $('#cbPph23Da').click(function() {
    if(this.checked){
      $('#table-invoice-da').DataTable().ajax.reload(function() {
        $('#trPph23Da').show();
      });
    }else{
      $('#table-invoice-da').DataTable().ajax.reload(function() {
        $('#trPph23Da').hide();
      });
    }
  });
  $('#cbPpn10Df').click(function() {
    if(this.checked){
      $('#table-invoice-df').DataTable().ajax.reload(function() {
        $('#trPpn10Da').show();
      });
    }else{
      $('#table-invoice-df').DataTable().ajax.reload(function() {
        $('#trPpn10Df').hide();
      });
    }
  });
  $('#cbPph23Df').click(function() {
    if(this.checked){
      $('#table-invoice-df').DataTable().ajax.reload(function() {
        $('#trPph23Df').show();
      });
    }else{
      $('#table-invoice-df').DataTable().ajax.reload(function() {
        $('#trPph23Df').hide();
      });
    }
  });
});

