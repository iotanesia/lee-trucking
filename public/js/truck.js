$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/truck/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadtruck(data);
    },
  });

  $("#btn-submit").click(function(){
    var event = $("#truck-modal #btn-submit").attr("el-event");
    var data = new FormData($("#truck-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/truck/" + event + "",
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
        $("#truck-modal").modal("hide");
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

  $("#btn-submit-ban").click(function(){
    var event = $("#ban-modal #btn-submit").attr("el-event");
    var data = new FormData($("#ban-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/ban/add",
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
        $("#ban-modal").modal("hide");
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

  $("#truck-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataJSON = JSON.parse(dataJSON);
        var tipe = dataJSON.truck_type ? dataJSON.truck_type : null;
        var jumlahBan = dataJSON.jumlah_ban ? dataJSON.jumlah_ban : null;

        $("#truck-form").find("input[name=id]").val(dataJSON.id);
        $("#truck-modal #btn-submit").attr("el-event", "edit");
        $("#truck-form").find("textarea[name=content]").summernote("code", dataJSON.content);

        bindToForm($("#truck-modal"), dataJSON);
        $("#truck-form").find("select[name=truck_type]").val(tipe).trigger('change');
        $("#truck-form").find("input[name=jumlah_ban]").val(jumlahBan).trigger('change');

    } else {
        $("#truck-form").find("input[name=id]").val(null);
        $("#truck-modal #btn-submit").attr("el-event", "add");
        $("#truck-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#truck-form");
        $("#truck-form").find("select[name=truck_type]").val(null).trigger('change');
    }
  });

  $("#ban-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);
    var tableRows = '';

    if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataBanJSON = invoker.attr("data-ban-json");
        var dataJSON = JSON.parse(dataJSON);
        var dataBanJSON = JSON.parse(dataBanJSON);
        var tipe = dataJSON.truck_type ? dataJSON.truck_type : null;
        var jumlahBan = dataJSON.jumlah_ban ? dataJSON.jumlah_ban : null;

        console.log(dataBanJSON);

        for (let index = 0; index < dataBanJSON.length; index++) {
            
        }


        for(var i = 0; i < dataBanJSON.length; i++) {
            id = dataBanJSON[i].id;
            name_ban = dataBanJSON[i].name_ban;
            code_ban = dataBanJSON[i].code_ban;
            desc = dataBanJSON[i].desc;
            total_ritasi = dataBanJSON[i].total_ritasi;

            tableRows += "<tr>" +
                           "<td>"+ def(name_ban) +"</td>"+
                           "<td>"+ def(code_ban) +"</td>"+
                           "<td>"+ def(desc) +"</td>"+
                           "<td align='center'>"+
                             "<div class='btn-group'>"+
                               "<a class='btn btn-danger btn-sm' href='#' el-event='edit' data-id='"+ id +"' data-url='/api/truck/ban-delete' data-toggle='modal' data-target='#deletedModal'><i class='fa fa-trash'></i></a>"+
                             "</div>"+
                           "</td>"+
                         "</tr>";
          }

          if(!tableRows) {
            tableRows += "<tr>" +
                         "</tr>";
          }

          $("#tblBlockBan tbody").html(tableRows);
          $("#ban-form").find("input[name=id]").val(dataJSON.id);
          $("#truck-modal #btn-submit").attr("el-event", "edit");
          $("#ban-form").find("textarea[name=content]").summernote("code", dataJSON.content);

          bindToForm($("#truck-modal"), dataJSON);
          $("#ban-form").find("select[name=truck_type]").val(tipe).trigger('change');
          $("#ban-form").find("input[name=jumlah_ban]").val(jumlahBan).trigger('change');

    } else {
        $("#ban-form").find("input[name=id]").val(null);
        $("#truck-modal #btn-submit").attr("el-event", "add");
        $("#ban-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#ban-form");
        $("#ban-form").find("select[name=truck_type]").val(null).trigger('change');
    }
  });

  $("#truck_status").select2({
    placeholder:"Select Status"
  });

  $("#cabang_id").select2({
    placeholder:"Select Cabang"
  });

  $("#driver_id").select2({
    placeholder:"Select Supir"
  });

  $("#truck_type").select2({
    placeholder:"Select Tipe"
  });

  $("#truck_date_join").daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    singleDatePicker : true,
  });


});

var successLoadtruck = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.data.data == undefined ? responses : responses.data;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    truck_plat = responses.data[i].truck_plat;
    truck_name = responses.data[i].truck_name;
    truck_status = responses.data[i].status_name;
    truck_corporate_asal = responses.data[i].truck_corporate_asal;
    truck_date_join = responses.data[i].truck_date_join;
    cabang_id = responses.data[i].cabang_name;
    data_json = responses.data[i].data_json;
    data_ban_json = responses.data[i].ban_json;

    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ def(truck_name) +"</td>"+
                   "<td>"+ def(truck_plat) +"</td>"+
                   "<td>"+ def(truck_status) +"</td>"+
                   "<td>"+ def(truck_corporate_asal) +"</td>"+
                   "<td>"+ dateFormat(truck_date_join) +"</td>"+
                   "<td>"+ def(cabang_id) +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-animate-modal='rotateInDownLeft' data-toggle='modal' data-target='#truck-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-warning btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-ban-json='"+data_ban_json+"' data-animate-modal='rotateInDownLeft' data-toggle='modal' data-target='#ban-modal'><i class='far fa-life-ring'></i></a>"+
                       "<a class='btn btn-danger btn-sm' href='#' el-event='edit' data-id='"+ id +"' data-url='/api/truck/delete' data-toggle='modal' data-target='#deletedModal'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
    }

    if(!tableRows) {
        tableRows += "<tr>" +
                    "</tr>";
    }

    $("#table-truck tbody").html(tableRows);
    paginate(responses, 'truck');
    $(".preloader").hide();

    $(".btn-delete").click(function(){
        var id = $(this).attr("data-id");
        var confirms =  confirm("Are You sure want to delete this file!");
        var accessToken =  window.Laravel.api_token;

        if(confirms) {
            $.ajax({
                url: window.Laravel.app_url + "/api/ban-delete",
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
            });
        }
    })
});
