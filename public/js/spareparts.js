$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/spareparts/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadspareparts(data);
    },
  });

  $(".sparepart_jenis").select2({
      placeholder:"Select Jenis"
  });

  $("#btn-submits").click(function(){
    var event = $("#spareparts-modal #btn-submits").attr("el-event");
    var data = new FormData($("#spareparts-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/spareparts/" + event + "",
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
        alert('Data berhasil di simpan');
        $("#spareparts-modal").modal("hide");
        $("#spareparts-scanner-modal").modal("hide");
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

  $("#btn-submit").click(function(){
    var event = $("#spareparts-scanner-modal #btn-submit").attr("el-event");
    var data = new FormData($("#spareparts-scanner-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/spareparts/" + event + "",
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
        alert('Data berhasil di simpan');
        $("#spareparts-modal").modal("hide");
        $("#spareparts-scanner-modal").modal("hide");
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

  $("#spareparts-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);
    var src = '{{asset("assets/img/add-photo.png")}}';

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);

      bindToForm($("#spareparts-modal"), dataJSON);

      $("#spareparts-form").find("input[name=id]").val(dataJSON.id);
      $("#spareparts-modal #btn-submits").attr("el-event", "edit");
      $("#spareparts-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      $("#spareparts-form").find("input[name=jumlah_stok]").attr("disabled", true);
      $("#spareparts-form").find("input[name=barcode_pabrik]").attr("disabled", true);
      $("#spareparts-form").find("input[name=due_date]").attr("disabled", true);
      $("#spareparts-form").find("select[name=sparepart_jenis]").attr("disabled", true).trigger("change");
      $("#spareparts-form").find("input[name=purchase_date]").attr("disabled", true);
      $("#spareparts-form").find("select[name=sparepart_type]").attr("disabled", true);
      $("#spareparts-form").find("select[name=satuan_type]").attr("disabled", true);
      $("#spareparts-form").find("input[name=amount]").attr("disabled", true);
      $("#spareparts-form #imgScreen").attr("src", dataJSON.img_sparepart);
      
      
    } else {
      $("#spareparts-form").find("input[name=id]").val(null);
      $("#spareparts-modal #btn-submits").attr("el-event", "add");
      $("#spareparts-form").find("textarea[name=content]").summernote("code", "");
      $("#spareparts-form").find("input[name=jumlah_stok]").attr("disabled", false);
      $("#spareparts-form").find("input[name=barcode_pabrik]").attr("disabled", false);
      $("#spareparts-form").find("input[name=due_date]").attr("disabled", false);
      $("#spareparts-form").find("select[name=sparepart_jenis]").attr("disabled", false).trigger("change");
      $("#spareparts-form").find("input[name=purchase_date]").attr("disabled", false);
      $("#spareparts-form").find("select[name=sparepart_type]").attr("disabled", false);
      $("#spareparts-form").find("select[name=satuan_type]").attr("disabled", false);
      $("#spareparts-form").find("input[name=amount]").attr("disabled", false);
      resetForm("#spareparts-form");
    }
  });

  $("#spareparts-scanner-modal").on("shown.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);
    $("#scanner").val("");
    $("#scanner").focus();
    $("#form-scan").hide();
  });
});

var successLoadspareparts = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    sparepart_name = responses.data[i].sparepart_name;
    sparepart_jenis = responses.data[i].sparepart_jenis;
    merk_part = responses.data[i].merk_part;
    barcode_pabrik = responses.data[i].barcode_pabrik;
    barcode_gudang = responses.data[i].barcode_gudang;
    group_name = responses.data[i].group_name;
    jumlah_stok = responses.data[i].jumlah_stok;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ barcode_gudang +"</td>"+
                   "<td>"+ barcode_pabrik +"</td>"+
                   "<td>"+ sparepart_name +"</td>"+
                   "<td>"+ sparepart_jenis +"</td>"+
                   "<td>"+ group_name +"</td>"+
                   "<td>"+ merk_part +"</td>"+
                   "<td>"+ jumlah_stok +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#spareparts-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-danger btn-sm btn-delete' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-spareparts tbody").html(tableRows);
  paginate(responses, 'spareparts');
  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/spareparts/delete",
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

  $("#spareparts-scanner-modal #scanner").on('change', function(event) {
        $(this).select();
        ids = $(this).val();
        var accessToken =  window.Laravel.api_token;

        if(ids.length > 4) {
            $.ajax({
                url: window.Laravel.app_url + "/api/spareparts/get-sparepart-detail",
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
 
                   $("#spareparts-scanner-form").find("input[name=id]").val(dataJSON.id);
                   $("#spareparts-scanner-form").find("input[name=scanner_form]").attr("disabled", false);
                   $("#spareparts-scanner-modal #btn-submit").attr("el-event", "edit");
                   $("#spareparts-scanner-form").find("textarea[name=content]").summernote("code", dataJSON.content);
                   $("#spareparts-scanner-form").find("input[name='barcode_pabrik']").attr("readonly", true);
                   
                   bindToForm($("#spareparts-scanner-modal"), dataJSON);
                   $("#spareparts-scanner-form").find("input[name=jumlah_stok]").val('');

                   $('.preloader').hide();
                },
                error: function(datas, textStatus, xhr) {
                    alert('Data Belum ada');
                    $("#spareparts-scanner-form").find("input[name=id]").val(null);
                    $("#spareparts-scanner-modal #btn-submit").attr("el-event", "add");
                    $("#spareparts-scanner-form").find("textarea[name=content]").summernote("code", "");        
                    
                    resetForm("#spareparts-scanner-form");
                    $("#spareparts-scanner-form").find("input[name='barcode_pabrik']").val(ids);
                    $("#spareparts-scanner-form").find("input[name='barcode_pabrik']").attr("readonly", true);
                    $("#spareparts-scanner-form").find("input[name=scanner_form]").attr("disabled", true);
                    $("#form-scan").show();
                    $('.preloader').hide();
                }
              });
        }
  });

  $("#spareparts-scanner-modal #scanner").on('focusout', function(event) {
      $(this).val('');
  });

  $("#spareparts-scanner-form #sparepart_jenis").on('change', function(event) {
      if($(this).val() == 'NOT_PURCHASE') {
          $("#spareparts-scanner-form .purchase_type").hide();
          
      } else {
          $("#spareparts-scanner-form .purchase_type").show();

      }
  });

  $("#spareparts-form #sparepart_jenis").on('change', function(event) {
      if($(this).val() == 'NOT_PURCHASE') {
          $("#spareparts-form .purchase_type").hide();
          
      } else {
          $("#spareparts-form .purchase_type").show();

      }
  });

});
