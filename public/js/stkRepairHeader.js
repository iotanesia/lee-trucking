$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;
  
  $(".sparepart").select2({
      placeholder:"Select Sparepart"
  });

  $.ajax({
    url: window.Laravel.app_url + "/api/stkRepairHeader/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadstkRepairHeader(data);
    },
  });

  $.ajax({
      url: window.Laravel.app_url + "/api/spareparts/get-list-all",
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
    var event = $("#stkRepairHeader-modal #btn-submit").attr("el-event");
    var data = new FormData($("#stkRepairHeader-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/stkRepairHeader/" + event + "",
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
        $("#stkRepairHeader-modal").modal("hide");
        $('.preloader').hide();
        document.getElementById("search-data").click();
      },
      error: function(datas, textStatus, xhr) {
        $('.preloader').hide();
        msgError = "";
        for(var item in datas.responseJSON.errors) {
          msgError += datas.responseJSON.errors[item][0] + "*";
        }
        msgError = datas.responseJSON.code_message;
        alert(msgError);
      }
    });
  })

  $("#stkRepairHeader-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);
      var tblBlock = '';
      var tblBlockSelect = '';

      $("#stkRepairHeader-form").find("input[name=id]").val(dataJSON.id);
      $("#stkRepairHeader-modal #btn-submit").attr("el-event", "edit");
      $("#stkRepairHeader-form").find("input[name=truck_id]").attr('readonly', true);
      $("#stkRepairHeader-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      bindToForm($("#stkRepairHeader-modal"), dataJSON);

      for (var i = 0; i < dataJSON.stk_history_stok.length; i++) {
          var responses = dataJSON.stk_history_stok[i];
          var selectBlock = '';

          $.each(dataSparepart, function( k, v) {
            console.log(v.id+' - '+v.sparepart_name)
            if(v.id == responses.sparepart_id) {
              var selected = 'selected';
            
            } else {
              var selected = '';
            }
            selectBlock += '<option value="'+v.id+'" '+selected+'> '+v.sparepart_name+' </option>'
          });

          tblBlock += '<tr id="tr-'+i+'">'+
                           '<td>'+
                               '<select name="sparepart_detail[sparepart_id][]" class="form-control sparepart-select-opt" id="sparepart'+i+'">'+ selectBlock +'</select>'+
                           '</td>'+
                           '<td><input type="text" name="sparepart_detail[jumlah_stock][]" value="'+dataJSON.stk_history_stok[i].jumlah_stok+'" class="form-control"><input type="hidden" name="sparepart_detail[id][]" value="'+dataJSON.stk_history_stok[i].id+'" class="form-control"></td>'+
                           '<td><a class="btn btn-danger btn-icon-only btn-sm btn-delete" data-id="'+i+'" href="#"><i class="fa fa-trash"></i></a> <input type="hidden" id="isDelete'+i+'" value="0" name="sparepart_detail[is_deleted][]"> </td>'+
                       '</tr>';
      }

      $("#tblBlock tbody").html(tblBlock);
      
    } else {
      $("#stkRepairHeader-form").find("input[name=id]").val(null);
      $("#stkRepairHeader-modal #btn-submit").attr("el-event", "add");
      $("#stkRepairHeader-form").find("textarea[name=content]").summernote("code", "");
      $("#stkRepairHeader-form").find("input").attr('readonly', false);
      resetForm("#stkRepairHeader-form");

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

var successLoadstkRepairHeader = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    truck_name = responses.data[i].truck_name;
    kode_repair = responses.data[i].kode_repair;
    truck_plat = responses.data[i].truck_plat;
    driver_name = responses.data[i].driver_name;
    created_at = responses.data[i].created_at;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ (i + 1) +"</td>"+
                   "<td>"+ def(kode_repair) +"</td>"+
                   "<td>"+ truck_name +" - "+truck_plat+"</td>"+
                   "<td>"+ driver_name +"</td>"+
                   "<td>"+ created_at +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-slack btn-icon-only btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#stkRepairHeader-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-danger btn-icon-only btn-sm' href='#' el-event='edit' data-id='"+ id +"' data-url='/api/stkRepairHeader/delete' data-toggle='modal' data-target='#deletedModal'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-stkRepairHeader tbody").html(tableRows);
  paginate(responses, 'stkRepairHeader');

  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/stkRepairHeader/delete",
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
});
