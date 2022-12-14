$("document").ready(function() {
    $("#driver_id").select2({
        placeholder:"Select Driver"
    });
    
    $("#otv_payment_method").select2({
        placeholder:"Select Payment"
    });

    $("#penagihan_id").select2({
        placeholder:"Select Penagihan"
    });

    $("#truck_id").select2({
        placeholder:"Select Truck"
    });

    $("#kenek_id").select2({
        placeholder:"Select Kenek"
    });

    $("#tgl_inv").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });

    $("#tgl_po").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });

    $("#jenis_surat_jalan").select2({
        placeholder:"Select Jenis Surat Jalan"
    });

    var accessToken =  window.Laravel.api_token;
  
    $.ajax({
      url: window.Laravel.app_url + "/api/expedition/get-list-approval-otv",
      type: "GET",
      dataType: "json",
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoadexpedition(data);
      },
    });
  
    $("#btn-submit").click(function(){
      var event = $("#expedition-modal #btn-submit").attr("el-event");
      $("#expedition-form #status_approval").val("APPROVED");
      var data = new FormData($("#expedition-form")[0]);
      data.append("_token", window.Laravel.csrfToken);
  
      $.ajax({
        url: window.Laravel.app_url + "/api/expedition/" + event + "",
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
          $("#expedition-modal").modal("hide");
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
  
    $("#btn-reject").click(function(){
      var event = $("#expedition-modal #btn-reject").attr("el-event");
      $("#expedition-form #status_activity").val("DRIVER_SELESAI_EKSPEDISI");
      $("#expedition-form #status_approval").val("REJECTED");
      var data = new FormData($("#expedition-form")[0]);
      data.append("_token", window.Laravel.csrfToken);
  
      $.ajax({
        url: window.Laravel.app_url + "/api/expedition/" + event + "",
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
          $("#expedition-modal").modal("hide");
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
  
    $("#btn-revision").click(function(){
      var event = $("#expedition-modal #btn-revision").attr("el-event");
      $("#expedition-form #status_activity").val("DRIVER_SELESAI_EKSPEDISI");
      $("#expedition-form #status_approval").val("REVISION");
      var data = new FormData($("#expedition-form")[0]);
      data.append("_token", window.Laravel.csrfToken);
  
      $.ajax({
        url: window.Laravel.app_url + "/api/expedition/" + event + "",
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
          $("#expedition-modal").modal("hide");
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
  
    $("#expedition-modal").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);
  
      if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataJSON = JSON.parse(dataJSON);
        var group_id = window.Laravel.group_id;
  
        $("#expedition-form").find("input[name=id]").val(dataJSON.id);
        $("#expedition-form").find("input[name=ex_id]").val(dataJSON.id);
        $("#expedition-modal #btn-submit").attr("el-event", "edit");
        $("#expedition-form").find("textarea[name=content]").summernote("code", dataJSON.content);
        $("#expedition-form #img").attr("src", dataJSON.otv_image);
        // $("#expedition-form #flash-img").attr("href", dataJSON.otv_image);
        
        bindToForm($("#expedition-modal"), dataJSON);
        $("#expedition-form #kenek_id").val(dataJSON.kenek_id).trigger("change");
        $("#expedition-form #driver_id").val(dataJSON.driver_id).trigger("change");

        if((group_id == 8 && dataJSON.status_activity == 'DRIVER_SELESAI_EKSPEDISI') || (group_id == 8 && dataJSON.status_activity == 'WAITING_OWNER')) {
            $("#expedition-form").find("input[name=status_activity]").val("CLOSED_EXPEDITION");
            
        } else if(group_id == 10 && dataJSON.status_activity == "DRIVER_SELESAI_EKSPEDISI") {
            $("#expedition-form").find("input[name=status_activity]").val("WAITING_OWNER");
        }

        if(dataJSON.otv_payment_method == 'NON_TUNAI') {
            $('#penagihanBlok').show();

        } else {
            $('#penagihanBlok').hide();
        }

        $("#expedition-modal #tujuan").html("<option value='"+dataJSON.ojk_id+"'>"+kabupaten +" - "+ kecamatan +" - "+ cabang_name +"</option>").trigger("change");
        $("#expedition-modal #ojk").val(dataJSON.harga_ojk);
        $("#expedition-modal #otv").val(dataJSON.harga_otv);
        
    } else {
        $("#expedition-modal #tujuan").html("");
        $("#expedition-form").find("input[name=id]").val(null);
        $("#expedition-modal #btn-submit").attr("el-event", "add");
        $("#expedition-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#expedition-form");
      }
    });
  });
  
  var successLoadexpedition = (function(responses, dataModel) {
      
    var tableRows = "";
    var responses = responses.result.data == undefined ? responses : responses.result;
  
    for(var i = 0; i < responses.data.length; i++) {
      id = responses.data[i].id;
      nomor_inv = responses.data[i].nomor_inv;
      pabrik_pesanan = responses.data[i].pabrik_pesanan;
      nomor_surat_jalan = responses.data[i].nomor_surat_jalan;
      nama_barang = responses.data[i].nama_barang;
      truck_name = responses.data[i].truck_name;
      truck_plat = responses.data[i].truck_plat;
      driver_name = responses.data[i].driver_name;
      tgl_inv = responses.data[i].tgl_inv;
      tgl_po = responses.data[i].tgl_po;
      kecamatan = responses.data[i].kecamatan;
      kabupaten = responses.data[i].kabupaten;
      cabang_name = responses.data[i].cabang_name;
      status_name = responses.data[i].status_name;
      approval_name = responses.data[i].approval_name;
      data_json = responses.data[i].data_json;

      if(responses.data[i].status_activity == 'SUBMIT') {
          classColor = 'badge-success';
          
      } else if(responses.data[i].status_activity == 'APPROVAL_OJK_DRIVER') {
          classColor = 'badge-warning';

      } else if(responses.data[i].status_activity == 'DRIVER_MENUJU_TUJUAN') {
          classColor = 'badge-info';

      } else if(responses.data[i].status_activity == 'DRIVER_SAMPAI_TUJUAN') {
          classColor = 'badge-gradient-warning';
      
      } else {
          classColor = 'badge-danger';

      }

      if(responses.data[i].approval_code == 'APPROVED') {
          classColors = 'badge-success';

      } else if(responses.data[i].approval_code == 'REVISION') {
          classColors = 'badge-warning';

      } else if(responses.data[i].approval_code == 'WAITING_OWNER') {
          classColors = 'badge-info';
      
      } else if(responses.data[i].approval_code == null){
          approval_name = '-';
          classColors = '';

      } else {
          classColors = 'badge-danger';
      }

  
      tableRows += "<tr>" +
                     "<td>"+ (i+1) +"</td>"+
                     "<td>"+ nomor_inv +"</td>"+
                     "<td>"+ truck_plat +"</td>"+
                     "<td>"+ driver_name +"</td>"+
                     "<td>"+ dateFormat(tgl_inv) +"</td>"+
                     "<td>"+ dateFormat(tgl_po) +"</td>"+
                     "<td>"+ kecamatan +" - "+ cabang_name +"</td>"+
                     "<td> <span class='badge "+classColors+"'>"+ approval_name +"</span></td>"+
                     "<td align='center'>"+
                       "<div class='btn-group'>"+
                         "<a class='btn btn-warning btn-xs btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#expedition-modal'><i class='fas fa-eye'></i></a>"+
                       "</div>"+
                     "</td>"+
                   "</tr>";
    }
  
    if(!tableRows) {
      tableRows += "<tr>" +
                   "</tr>";
    }
  
    $("#table-expedition tbody").html(tableRows);
    paginate(responses, 'expedition');
    $(".preloader").hide();
  
    $(".btn-delete").click(function(){
      var id = $(this).attr("data-id");
      var confirms =  confirm("Are You sure want to delete this file!");
      var accessToken =  window.Laravel.api_token;
  
      if(confirms) {
        $.ajax({
          url: window.Laravel.app_url + "/api/expedition/delete",
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
  