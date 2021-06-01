$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;
  
  $(".sparepart").select2({
      placeholder:"Select Sparepart"
  });

  $.ajax({
    url: window.Laravel.app_url + "/api/stkRepairBanHeader/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadstkRepairBanHeader(data);
    },
  });

  $.ajax({
      url: window.Laravel.app_url + "/api/spareparts/get-list-all-ban",
      type: "GET",
      dataType: "json",
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) {
      },
      success: function(data, textStatus, xhr) {
          dataSparepart = data.result
      },
  });

  $("#btn-submit").click(function(){
    var event = $("#stkRepairBanHeader-modal #btn-submit").attr("el-event");
    var data = new FormData($("#stkRepairBanHeader-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/stkRepairBanHeader/" + event + "",
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
        $("#stkRepairBanHeader-modal").modal("hide");
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

  $(".btn-detail").click(function() {
        var invoker = $(this);
        var dataJSON = invoker.attr("history-json");
        var dataJSON = JSON.parse(dataJSON);
        console.log(dataJSON);
        var block = '';
    
        for(var i = 0; i < dataJSON.length; i++) {
            block += `<div class="timeline-block">
                        <span class="timeline-step badge-success">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted font-weight-bold">`+dataJSON[i].created_at+`</small>
                                    <h5 class="text-muted font-weight-bold mt-3 mb-0">Batas Ritasi : `+dataJSON[i].batas_ritasi+` <br>
                                                                                    Total Ritasi : `+dataJSON[i].total_ritasi+`  <br>
                                                                                    Nama Ban : `+dataJSON[i].name_ban+`  <br>
                                                                                    Desc : `+dataJSON[i].desc+`  <br>
                                                                                    <br>
                                                                                    <br>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>`;
        }

        $("#blockHtml").html(block);

    })

  $("#stkRepairBanHeader-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);
      var tblBlock = '';
      var tblBlockSelect = '';

      $("#stkRepairBanHeader-form").find("input[name=id]").val(dataJSON.id);
      $("#stkRepairBanHeader-modal #btn-submit").attr("el-event", "edit");
      $("#stkRepairBanHeader-form").find("input[name=truck_id]").attr('readonly', true);
      $("#stkRepairBanHeader-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      
      bindToForm($("#stkRepairBanHeader-modal"), dataJSON);

      for (var i = 0; i < dataJSON.stk_history_stok.length; i++) {
          var responses = dataJSON.stk_history_stok[i];
          var selectBlock = '';

          $.each(dataSparepart, function( k, v) {
            if(v.id == responses.sparepart_id) {
              var selected = 'selected';
            
            } else {
              var selected = '';
            }
            selectBlock += '<option value="'+v.id+'" '+selected+'> '+v.sparepart_name+' </option>'
          });

          tblBlock += '<tr id="tr-'+i+'">'+
                           '<td>'+(i + 1)+'</td>'+
                           '<td>'+
                               '<select name="sparepart_detail[sparepart_id][]" class="form-control sparepart-select-opt" id="sparepart'+i+'">'+ selectBlock +'</select>'+
                           '</td>'+
                           '<td><input type="text" name="sparepart_detail[jumlah_stock][]" value="'+dataJSON.stk_history_stok[i].jumlah_stok+'" class="form-control"><input type="hidden" name="sparepart_detail[id][]" value="'+dataJSON.stk_history_stok[i].id+'" class="form-control"></td>'+
                           '<td><a class="btn btn-danger btn-icon-only btn-sm btn-delete" data-id="'+i+'" href="#"><i class="fa fa-trash"></i></a> <input type="hidden" id="isDelete'+i+'" value="0" name="sparepart_detail[is_deleted][]"> </td>'+
                       '</tr>';
      }

      $("#tblBlock tbody").html(tblBlock);
      
    } else {
      $("#stkRepairBanHeader-form").find("input[name=id]").val(null);
      $("#stkRepairBanHeader-modal #btn-submit").attr("el-event", "add");
      $("#stkRepairBanHeader-form").find("textarea[name=content]").summernote("code", "");
      $("#stkRepairBanHeader-form").find("input").attr('readonly', false);
      resetForm("#stkRepairBanHeader-form");

      $("#tblBlock tbody").html('');
    }

    $(".btn-delete").click(function() {
        var idTr = $(this).attr("data-id");
        $("#tr-"+idTr).hide();
        $("#isDelete"+idTr).val(1);
    })
  });

    function optData(idSelect, res) {
        var opt = '';
            opt += '<option value="0">--Select SparePart--</option>';

        $.each(res, function( k, v) {
            opt += '<option value="'+v.id+'"> '+name+' </option>';
        });

        console.log(opt);

        $(idSelect).html(opt);
    }
});

var successLoadstkRepairBanHeader = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    truck_name = responses.data[i].truck_name;
    truck_plat = responses.data[i].truck_plat;
    driver_name = responses.data[i].driver_name;
    total_rit = responses.data[i].total_rit;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ (i + 1) +"</td>"+
                   "<td>"+ truck_name +"</td>"+
                   "<td>"+ truck_plat +"</td>"+
                   "<td>"+ driver_name +"</td>"+
                   "<td>"+ total_rit +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-slack btn-icon-only btn-sm' href='"+window.Laravel.app_url+"/truck/ban-detail/"+id+"'><i class='fas fa-tools'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-stkRepairBanHeader tbody").html(tableRows);
  paginate(responses, 'stkRepairBanHeader');

  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/stkRepairBanHeader/delete",
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

  $("#select-provinsi").on("click", function() {
      var id = $(this).val();

      $.ajax({
        url: window.Laravel.app_url + "/api/daerah/get-kabupaten-by-idProv",
        type: "GET",
        dataType: "json",
        data:"idProvinsi"+"="+id,
        crossDomain: true,
        beforeSend: function( xhr ) { 
          $('.preloader').show();
        },
        success: function(data, textStatus, xhr) {
            $("#select-kabupaten").html("");
            $("#select-kecamatan").html("");
            optData('#select-kabupaten', data, 'kabupaten');
            $('.preloader').hide();
        },
      });
  })

  $("#select-kabupaten").on("click", function() {
    var id = $(this).val();

    $.ajax({
      url: window.Laravel.app_url + "/api/daerah/get-kecamatan-by-idKab",
      type: "GET",
      dataType: "json",
      data:"idKabupaten"+"="+id,
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
          $("#select-kecamatan").html("");
          optData('#select-kecamatan', data, 'kecamatan');
          $('.preloader').hide();
      },
    });
})
$("#btn-submits-detail").click(function(){
    var event = $("#moneyTransactionHeader-modal #btn-submits-detail").attr("el-event");
    var data = new FormData($("#moneyTransactionHeader-detail-form")[0]);
    data.append("_token", window.Laravel.csrfToken);
    
    $.ajax({
      url: window.Laravel.app_url + "/api/moneyTransactionHeader/paid",
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
            $("#moneyTransactionHeader-modal").modal("hide");
            $("#successModal").modal("show");
            $('.preloader').hide();
            location.reload();
            document.getElementById("search-data").click();
            
          },error: function(datas, textStatus, xhr) {
              $('.preloader').hide();
            msgError = "";
            for(var item in datas.responseJSON.errors) {
                msgError += datas.responseJSON.errors[item][0] + "*";
              }
            alert(msgError);
          }
      });
  })
  
  $("#moneyTransactionHeader-modal").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);

      if(invoker.attr('el-event') == 'edit') {
          var dataJSON = invoker.attr("data-json");
          var dataJSON = JSON.parse(dataJSON);
          
          $("#moneyTransactionHeader-form").find("input[name=id]").val(dataJSON.id);
          $("#moneyTransactionHeader-modal #btn-submit").attr("el-event", "edit");
          $("#moneyTransactionHeader-form").find("textarea[name=content]").summernote("code", dataJSON.content);
          
          bindToForm($("#moneyTransactionHeader-modal"), dataJSON);
          
      } else {
          $("#moneyTransactionHeader-form").find("input[name=id]").val(null);
          $("#moneyTransactionHeader-modal #btn-submit").attr("el-event", "add");
          $("#moneyTransactionHeader-form").find("textarea[name=content]").summernote("code", "");
          resetForm("#moneyTransactionHeader-form");
      }
  });

  $("#moneyTransactionHeader-modal-detail").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);
      var tableRows = "";

      if(invoker.attr('el-event') == 'edit') {
          var dataJSON = invoker.attr("data-json");
          var dataJSON = JSON.parse(dataJSON);
          console.log(dataJSON);
          $('.termin-val').text('Pembayaran Ke - '+(parseInt(dataJSON.total_bayar) + 1));
          var responses = dataJSON.money_detail_termin;

          
          $("#table-moneyTransactionHeader-detail tbody").html(tableRows);
          $("#moneyTransactionHeader-detail-form").find("input[name=id]").val(dataJSON.id);
          $("#moneyTransactionHeader-detail-form").find("input[name=total_ritasi]").val(dataJSON.total_ritasi);
          $("#moneyTransactionHeader-detail-form").find("input[name=batas_ritasi]").val(dataJSON.batas_ritasi);
          $("#moneyTransactionHeader-detail-form").find("input[name=transaksi_header_id]").val(dataJSON.id);
          $("#moneyTransactionHeader-modal #btn-submit").attr("el-event", "edit");
          $("#moneyTransactionHeader-form").find("textarea[name=content]").summernote("code", dataJSON.content);
          
          bindToForm($("#moneyTransactionHeader-modal"), dataJSON);
          
      } else {
          $("#moneyTransactionHeader-form").find("input[name=id]").val(null);
          $("#moneyTransactionHeader-modal #btn-submit").attr("el-event", "add");
          $("#moneyTransactionHeader-form").find("textarea[name=content]").summernote("code", "");
          resetForm("#moneyTransactionHeader-form");
      }
  });

  $("#btn-submit-detail").click(function(){
      var accessToken =  window.Laravel.api_token;
      var event = $("#moneyTransactionHeader-modal-detail #btn-submit-detail").attr("el-event");
      var data = new FormData($("#moneyTransactionHeader-detail-form")[0]);
      data.append("_token", window.Laravel.csrfToken);
      
      $.ajax({
          url: window.Laravel.app_url + "/api/ban/add-repair",
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
            $("#moneyTransactionHeader-modal").modal("hide");
            $("#successModal").modal("show");
            $('.preloader').hide();
            location.reload();
            document.getElementById("search-data").click();
            
          },error: function(datas, textStatus, xhr) {
              $('.preloader').hide();
            msgError = "";
            for(var item in datas.responseJSON.errors) {
                msgError += datas.responseJSON.errors[item][0] + "*";
              }
            alert(msgError);
          }
      });
  })
});
