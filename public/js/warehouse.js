$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/warehouse/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadwarehouse(data);
    },
  });

  $("#btn-submits").click(function(){
    var event = $("#warehouse-modal #btn-submits").attr("el-event");
    var data = new FormData($("#warehouse-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/warehouse/" + event + "",
      type: "POST",
      dataType: "json",
      data: data,
      processData: false,
      contentType: false,
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) {
        $('.preloader').show();
    },
    success: function(datas, textStatus, xhr) {
        $("#successModal").modal("show");
        $("#warehouse-modal").modal("hide");
        $("#warehouse-scanner-modal").modal("hide");
        $('.preloader').hide();
        document.getElementById("search-data").click();
      },
      error: function(datas, textStatus, xhr) {
        $('.preloader').hide();
        msgError = "";
        for(var item in datas.responseJSON.errors) {
          msgError += datas.responseJSON.errors[item][0] + "*";
        }
        alert(msgError);
      }
    });
  })

    $(".sparepart_status").select2({
        placeholder:"Select Status"
    });

    $(".sparepart_type").select2({
        placeholder:"Select Type"
    });

    $(".sparepart_type").select2({
        placeholder:"Select Type"
    });

    $(".sparepart_jenis").select2({
        placeholder:"Select Jenis"
    });

    $(".group_sparepart_id").select2({
        placeholder:"Select Group"
    });

  $("#driver_join_date").daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    singleDatePicker : true,
  });

  $("#btn-submit").click(function(){
    var event = $("#warehouse-scanner-modal #btn-submit").attr("el-event");
    var data = new FormData($("#warehouse-scanner-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/warehouse/" + event + "",
      type: "POST",
      dataType: "json",
      data: data,
      processData: false,
      contentType: false,
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) {
        $('.preloader').show();
    },
    success: function(datas, textStatus, xhr) {
        $("#successModal").modal("show");
        $("#warehouse-modal").modal("hide");
        $("#warehouse-scanner-modal").modal("hide");
        $('.preloader').hide();
        document.getElementById("search-data").click();
      },
      error: function(datas, textStatus, xhr) {
        $('.preloader').hide();
        msgError = "";
        for(var item in datas.responseJSON.errors) {
          msgError += datas.responseJSON.errors[item][0] + "*";
        }
        alert(msgError);
      }
    });
  })

  $("#warehouse-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);
    var src = '{{asset("assets/img/add-photo.png")}}';

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);

      bindToForm($("#warehouse-modal"), dataJSON);

      $("#warehouse-form").find("input[name=id]").val(dataJSON.id);
      $("#warehouse-modal #btn-submits").attr("el-event", "edit");
      $("#warehouse-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      $("#warehouse-form").find("input[name=jumlah_stok]").attr("disabled", true);
      $("#warehouse-form").find("input[name=barcode_pabrik]").attr("disabled", true);
      $("#warehouse-form").find("input[name=due_date]").attr("disabled", true);
      $("#warehouse-form").find("select[name=sparepart_jenis]").attr("disabled", true).trigger("change");
      $("#warehouse-form").find("input[name=purchase_date]").attr("disabled", true);
      $("#warehouse-form").find("select[name=sparepart_type]").attr("disabled", true);
      $("#warehouse-form").find("select[name=satuan_type]").attr("disabled", true);
      $("#warehouse-form").find("input[name=amount]").attr("disabled", true);
      $("#warehouse-form #imgScreen").attr("src", dataJSON.img_sparepart);
      
      
    } else {
      $("#warehouse-form").find("input[name=id]").val(null);
      $("#warehouse-modal #btn-submits").attr("el-event", "add");
      $("#warehouse-form").find("textarea[name=content]").summernote("code", "");
      $("#warehouse-form").find("input[name=jumlah_stok]").attr("disabled", false);
      $("#warehouse-form").find("input[name=barcode_pabrik]").attr("disabled", false);
      $("#warehouse-form").find("input[name=due_date]").attr("disabled", false);
      $("#warehouse-form").find("select[name=sparepart_jenis]").attr("disabled", false).trigger("change");
      $("#warehouse-form").find("input[name=purchase_date]").attr("disabled", false);
      $("#warehouse-form").find("select[name=sparepart_type]").attr("disabled", false);
      $("#warehouse-form").find("select[name=satuan_type]").attr("disabled", false);
      $("#warehouse-form").find("input[name=amount]").attr("disabled", false);
      resetForm("#warehouse-form");
    }
  });

  $("#warehouse-scanner-modal").on("shown.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);
    $("#scanner").val("");
    $("#scanner").focus();
    $("#form-scan").hide();
  });
});

var successLoadwarehouse = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    sparepart_name = responses.data[i].sparepart_name;
    sparepart_jenis = responses.data[i].sparepart_jenis;
    merk_part = responses.data[i].merk_part;
    barcode_pabrik = responses.data[i].barcode_pabrik;
    barcode_warehouse = responses.data[i].barcode_gudang;
    group_name = responses.data[i].group_name;
    jumlah_stok = responses.data[i].jumlah_stok;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ def(barcode_warehouse) +"</td>"+
                   "<td>"+ def(barcode_pabrik) +"</td>"+
                   "<td>"+ def(sparepart_name) +"</td>"+
                   "<td>"+ def(group_name) +"</td>"+
                   "<td>"+ def(merk_part) +"</td>"+
                   "<td>"+ def(jumlah_stok) +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#warehouse-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-danger btn-sm btn-delete' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-warehouse tbody").html(tableRows);
  paginate(responses, 'warehouse');
  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/warehouse/delete",
        type: "POST",
        dataType: "json",
        data:"id"+"="+id,
        headers: {"Authorization": "Bearer " + accessToken},
        crossDomain: true,
        beforeSend: function( xhr ) { 
          $('.preloader').show();
        },
        success: function(data, textStatus, xhr) {
          alert('Data berhasil di hapus');
          $('.preloader').hide();
          document.getElementById("search-data").click();
        },
        error: function(datas, textStatus, xhr) {
            $('.preloader').hide();
        }
      });
    }
  })

  $("#warehouse-scanner-modal #scanner").on('change', function(event) {
        $(this).select();
        ids = $(this).val();
        var accessToken =  window.Laravel.api_token;

        if(ids.length > 4) {
            $.ajax({
                url: window.Laravel.app_url + "/api/warehouse/get-sparepart-detail",
                type: "GET",
                dataType: "json",
                data:"id"+"="+ids,
                headers: {"Authorization": "Bearer " + accessToken},
                crossDomain: true,
                beforeSend: function( xhr ) { 
                  $('.preloader').show();
                },
                success: function(data, textStatus, xhr) {
                   $("#form-scan").show();
                   var dataJSON = data.data.data_json;
                   var dataJSON = JSON.parse(dataJSON);
 
                   $("#warehouse-scanner-form").find("input[name=id]").val(dataJSON.id);
                   $("#warehouse-scanner-form").find("input[name=scanner_form]").attr("disabled", false);
                   $("#warehouse-scanner-modal #btn-submit").attr("el-event", "edit");
                   $("#warehouse-scanner-form").find("textarea[name=content]").summernote("code", dataJSON.content);
                   $("#warehouse-scanner-form").find("input[name='barcode_pabrik']").attr("readonly", true);
                   
                   bindToForm($("#warehouse-scanner-modal"), dataJSON);
                   $("#warehouse-scanner-form").find("input[name=jumlah_stok]").val('');

                   $('.preloader').hide();
                },
                error: function(datas, textStatus, xhr) {
                    alert('Data Belum ada');
                    $("#warehouse-scanner-form").find("input[name=id]").val(null);
                    $("#warehouse-scanner-modal #btn-submit").attr("el-event", "add");
                    $("#warehouse-scanner-form").find("textarea[name=content]").summernote("code", "");        
                    
                    resetForm("#warehouse-scanner-form");
                    $("#warehouse-scanner-form").find("input[name='barcode_pabrik']").val(ids);
                    $("#warehouse-scanner-form").find("input[name='barcode_pabrik']").attr("readonly", true);
                    $("#warehouse-scanner-form").find("input[name=scanner_form]").attr("disabled", true);
                    $("#form-scan").show();
                    $('.preloader').hide();
                }
              });
        }
  });

  $("#warehouse-scanner-modal #scanner").on('focusout', function(event) {
      $(this).val('');
  });

  $("#warehouse-scanner-form #sparepart_jenis").on('change', function(event) {
      var typeSparePart = $("#warehouse-scanner-form #sparepart_type").val();

      if($(this).val() == 'NOT_PURCHASE') {
          $("#warehouse-scanner-form .purchase_type").hide();
          
      } else {
          $("#warehouse-scanner-form .purchase_type").show();

      }

      if($(this).val() == 'PURCHASE' && typeSparePart == 'PAID_OFF') {
          $("#warehouse-scanner-form .no_rek").show();

      } else {
          $("#warehouse-scanner-form .no_rek").hide();
      }
  });

  $("#sparepart-jenis").on('change', function(event) {
      var typeSparePart = $("#sparepart-type").val();

      if($(this).val() == 'NOT_PURCHASE') {
          $("#warehouse-form .purchase_type").hide();
          
      } else {
          $("#warehouse-form .purchase_type").show();

      }

      if($(this).val() == 'PURCHASE' && typeSparePart == 'PAID_OFF') {
        $("#warehouse-form .no_rek").show();

      } else {
         $("#warehouse-form .no_rek").hide();
      }
  });

  $("#warehouse-scanner-form #sparepart_type").on('change', function(event) {
      var jenisSparePart = $("#warehouse-scanner-form #sparepart_jenis").val();

      if($(this).val() == 'PAID_OFF' && jenisSparePart == 'PURCHASE') {
          $("#warehouse-scanner-form .no_rek").show();

      } else {
          $("#warehouse-scanner-form .no_rek").hide();
      }
  });

  $("#sparepart-type").on('change', function(event) {
      var jenisSparePart = $("#sparepart-jenis").val();

      if($(this).val() == 'PAID_OFF' && jenisSparePart == 'PURCHASE') {
        $("#warehouse-form .no_rek").show();

      } else {
         $("#warehouse-form .no_rek").hide();
      }
  });

});
