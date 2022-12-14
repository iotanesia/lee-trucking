$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/spareparts/get-list-unpaid",
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

  $.ajax({
    url: window.Laravel.app_url + "/api/drop-down/get-list-rekening",
    type: "GET",
    dataType: "json",
    crossDomain: true,
    beforeSend: function( xhr ) { 
        $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        dataDropDown = data.data;
    },
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
        $("#successModal").modal("show");
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

    $("#purchase_date").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });

    $("#due_date").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });

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
        $("#successModal").modal("show");
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

      console.log(dataDropDown);

      if(dataJSON.stk_history_stok !== undefined) {  
          var tableRows = "";

          for(var i = 0; i < dataJSON.stk_history_stok.length; i++) {
              id = dataJSON.stk_history_stok[i].id;
              amount = dataJSON.stk_history_stok[i].amount;
              sparepart_type = dataJSON.stk_history_stok[i].sparepart_type;
              jumlah_stok = dataJSON.stk_history_stok[i].jumlah_stok != null ? dataJSON.stk_history_stok[i].jumlah_stok : 0;

              tableRows += "<tr>" +
                                "<td>"+ (i+1) +" <input type='hidden' value='"+id+"'> </td>"+
                                "<td>"+ sparepart_type +"</td>"+
                                "<td>"+ convertToRupiah(amount) +"</td>"+
                                "<td>"+ jumlah_stok +"</td>"+
                                "<td>"+ convertToRupiah(parseInt(amount) * parseInt(jumlah_stok)) +"</td>"+
                                "<td> <select name='no_rek' id='no_rek_"+id+"' class='form-control no_rek'>"+optionList(dataDropDown)+"</select></td>"+
                                "<td align='center'>"+
                                    "<div class='btn-group'>"+
                                    "<button type='button' onClick='paid("+id+")' class='btn btn-success btn-sm' href='#'>PAID</button>"+
                                    "</div>"+
                                "</td>"+
                            "</tr>";

          }

        var select = $(".no_rek");

        select.val(null).trigger('change');

            $("#table-spareparts-detail tbody").html(tableRows);
        }

      bindToForm($("#spareparts-modal"), dataJSON);
      $("#spareparts-form").find("input[name=jumlah_stok]").val('');
      $("#spareparts-form").find("input[name=id]").val(dataJSON.id);
      $("#spareparts-modal #btn-submits").attr("el-event", "edit");
      $("#spareparts-form").find("textarea[name=content]").summernote("code", dataJSON.content);
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
                    "<td>"+ jumlah_stok +"</td>"+
                    "<td align='center'>"+
                        "<div class='btn-group'>"+
                        "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#spareparts-modal'><i class='fas fa-edit'></i></a>"+
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
                    $("#form-scan").hide();
                    $('.preloader').hide();
                }
            });
        }
    });

    $("#spareparts-scanner-modal #scanner").on('focusout', function(event) {
        $(this).val('');
    });

    $("#spareparts-scanner-form #sparepart_jenis").on('change', function(event) {
        var typeSparePart = $("#spareparts-scanner-form #sparepart_type").val();

        if($(this).val() == 'NOT_PURCHASE') {
            $("#spareparts-scanner-form .purchase_type").hide();
            
        } else {
            $("#spareparts-scanner-form .purchase_type").show();

        }

        if($(this).val() == 'PURCHASE' && typeSparePart == 'PAID_OFF') {
            $("#spareparts-scanner-form .no_rek").show();

        } else {
            $("#spareparts-scanner-form .no_rek").hide();
        }
    });

    $("#sparepart-jenis").on('change', function(event) {
        var typeSparePart = $("#sparepart-type").val();

        if($(this).val() == 'NOT_PURCHASE') {
            $("#spareparts-form .purchase_type").hide();
            
        } else {
            $("#spareparts-form .purchase_type").show();

        }

        if($(this).val() == 'PURCHASE' && typeSparePart == 'PAID_OFF') {
        $("#spareparts-form .no_rek").show();

        } else {
        $("#spareparts-form .no_rek").hide();
        }
    });

    $("#spareparts-scanner-form #sparepart_type").on('change', function(event) {
        var jenisSparePart = $("#spareparts-scanner-form #sparepart_jenis").val();

        if($(this).val() == 'PAID_OFF' && jenisSparePart == 'PURCHASE') {
            $("#spareparts-scanner-form .no_rek").show();

        } else {
            $("#spareparts-scanner-form .no_rek").hide();
        }
    });

    $("#sparepart-type").on('change', function(event) {
        var jenisSparePart = $("#sparepart-jenis").val();

        if($(this).val() == 'PAID_OFF' && jenisSparePart == 'PURCHASE') {
        $("#spareparts-form .no_rek").show();

        } else {
        $("#spareparts-form .no_rek").hide();
        }
    });

});

function optionList(arrOption, selectedValue) {
    var options = "<option value=''>select</option>";
  
    for(var i = 0; i < arrOption.length; i++) {
      options += "<option value='" + arrOption[i].id + "' " + (arrOption[i].id == selectedValue ? "selected" : "") + ">" + arrOption[i].rek_no + " - " + arrOption[i].bank_name + " - " + arrOption[i].rek_name +"</option>";
    }
  
    return options;
}

function paid(id) {
    var accessToken =  window.Laravel.api_token;
    var no_rek = $('#no_rek_'+id).val();
    alert(no_rek);
    var data = new FormData;
    data.append("id", id);
    data.append("no_rek", no_rek);
    
    $.ajax({
        url: window.Laravel.app_url + "/api/spareparts/paid",
        type: "POST",
        dataType: "json",
        data:data,
        processData: false,
        contentType: false,
        headers: {"Authorization": "Bearer " + accessToken},
        crossDomain: true,
        beforeSend: function( xhr ) {
          $('.preloader').show();
      },
      success: function(datas, textStatus, xhr) {
          $("#successModal").modal("show");
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

}
