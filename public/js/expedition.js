$("document").ready(function() {
    $("#driver_id").select2({
        placeholder:"Select Driver"
    });

    $("#jenis_surat_jalan").select2({
        placeholder:"Select Jenis Surat Jalan"
    });

    $("#driver_id").on("change", function() {
        id = $(this).val();

        $.ajax({
            url: window.Laravel.app_url + "/api/expedition/get-kenek",
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
              $("#kenek_id").val(res.data.kenek_name)
              $("#kenek_id").attr("disabled", true)
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
          alert('Data berhasil di simpan');
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
  
        $("#expedition-form").find("input[name=id]").val(dataJSON.id);
        $("#expedition-modal #btn-submit").attr("el-event", "edit");
        $("#expedition-form").find("textarea[name=content]").summernote("code", dataJSON.content);
        
        bindToForm($("#expedition-modal"), dataJSON);
        
      } else {
        $("#expedition-form").find("input[name=id]").val(null);
        $("#expedition-modal #btn-submit").attr("el-event", "add");
        $("#expedition-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#expedition-form");
      }
    });
  });
  
  var successLoadexpedition = (function(responses, dataModel) {
      
    var tableRows = "";
    var responses = responses.data.data == undefined ? responses : responses.data;
  
    for(var i = 0; i < responses.data.length; i++) {
      id = responses.data[i].id;
      nomor_inv = responses.data[i].nomor_inv;
      pabrik_pesanan = responses.data[i].pabrik_pesanan;
      nomor_surat_jalan = responses.data[i].nomor_surat_jalan;
      nama_barang = responses.data[i].nama_barang;
      truck_name = responses.data[i].truck_name;
      driver_name = responses.data[i].driver_name;
      tgl_inv = responses.data[i].tgl_inv;
      tgl_po = responses.data[i].tgl_po;
      data_json = responses.data[i].data_json;
  
      tableRows += "<tr>" +
                     "<td>"+ nomor_inv +"</td>"+
                     "<td>"+ pabrik_pesanan +"</td>"+
                     "<td>"+ nomor_surat_jalan +"</td>"+
                     "<td>"+ nama_barang +"</td>"+
                     "<td>"+ truck_name +"</td>"+
                     "<td>"+ driver_name +"</td>"+
                     "<td>"+ tgl_inv +"</td>"+
                     "<td>"+ tgl_po +"</td>"+
                     "<td align='center'>"+
                       "<div class='btn-group'>"+
                         "<a class='btn btn-success btn-xs btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#expedition-modal'><i class='fas fa-edit'></i></a>"+
                         "<a class='btn btn-danger btn-xs btn-delete btn-sm' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
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
  