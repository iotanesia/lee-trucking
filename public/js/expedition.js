$("document").ready(function() {
    $("#driver_id").select2({
        placeholder:"Select Driver"
    });

    $("#otv_payment_method").select2({
        placeholder:"Select Payment"
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

    $(".upperCaseText").on('keyup', function(){
        this.value = this.value.toUpperCase();
    });

    $("#driver_id").on("change", function() {
        id = $(this).val();

        $.ajax({
            url: window.Laravel.app_url + "/api/driver/get-detail-driver/",
            type: "GET",
            dataType: "json",
            data: 'id='+id,
            headers: {"Authorization": "Bearer " + accessToken},
            crossDomain: true,
            beforeSend: function( xhr ) { 
              $('.preloader').show();
            },
            success: function(res, textStatus, xhr) {
              $('.preloader').hide();
            //   $("#kenek_id").val(res.data.id).trigger("change");
              $('#nama_penerima').val(res.data.nama_rekening);
              $('#no_rek').val(res.data.no_rek);
              $('#bank_name').val(res.data.bank_name);
              $('#nomor_hp_penerima').val(res.data.nomor_hp);
            },
          });
    });

    var accessToken =  window.Laravel.api_token;
  
    $.ajax({
      url: window.Laravel.app_url + "/api/expedition/get-list",
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
          console.log(datas);
          msgError = "";
          for(var item in datas.responseJSON.code_message) {
            msgError += datas.responseJSON.code_message[item];
          }
          alert(msgError);
          location.reload();
        }
      });
    })
  
    $("#expedition-modal").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);
      if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataJSON = JSON.parse(dataJSON);

        console.log(dataJSON);
  
        $("#expedition-form").find("input[name=id]").val(dataJSON.id);
        $("#expedition-modal #btn-submit").attr("el-event", "edit");
        $("#expedition-form").find("textarea[name=content]").summernote("code", dataJSON.content);
        
        bindToForm($("#expedition-modal"), dataJSON);

        $("#expedition-modal #tujuan").html("<option value='"+dataJSON.ojk_id+"'>"+dataJSON.kabupaten +" - "+ dataJSON.kecamatan +" - "+ dataJSON.cabang_name +"</option>").trigger("change");
        $("#expedition-modal #ojk").val(dataJSON.harga_ojk);
        $("#expedition-modal #otv").val(dataJSON.harga_otv);
        
    } else {
        $("#jenis_surat_jalan").val("").trigger("change");
        $("#otv_payment_method").val("").trigger("change");
        $("#truck_id").val("").trigger("change");
        $("#driver_id").val("").trigger("change");
        $("#kenek_id").val("").trigger("change");
        $("#expedition-modal #tujuan").html("").trigger("change");
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
      data_json = responses.data[i].data_json;
  
      tableRows += "<tr>" +
                     "<td>"+ (i+1) +"</td>"+
                     "<td>"+ nomor_inv +"</td>"+
                     "<td>"+ truck_plat +"</td>"+
                     "<td>"+ driver_name +"</td>"+
                     "<td>"+ dateFormat(tgl_inv) +"</td>"+
                     "<td>"+ dateFormat(tgl_po) +"</td>"+
                     "<td>"+ kabupaten +" - "+ kecamatan +" - "+ cabang_name +"</td>"+
                     "<td align='center'>"+
                       "<div class='btn-group'>";
                       if(responses.data[i].status_activity == "SUBMIT") {

                        tableRows += "<a class='btn btn-success btn-xs btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#expedition-modal'><i class='fas fa-edit'></i></a>"+
                                     "<a class='btn btn-danger btn-xs btn-delete btn-sm' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>";
                       }
      tableRows +=     "</div>"+
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
  