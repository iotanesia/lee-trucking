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
        $('.loader').show();
      },
      success: function(datas, textStatus, xhr) {
        alert('Data berhasil di simpan');
        $("table[data-model='truck']").closest("div[id='truck']").find("a[el-event='search-data']").click();
        $("#truck-modal").modal("hide");
      },
      error: function(datas, textStatus, xhr) {
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

      $("#truck-form").find("input[name=id]").val(dataJSON.id);
      $("#truck-modal #btn-submit").attr("el-event", "edit");
      $("#truck-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      
      bindToForm($("#truck-modal"), dataJSON);
      
    } else {
      $("#truck-form").find("input[name=id]").val(null);
      $("#truck-modal #btn-submit").attr("el-event", "add");
      $("#truck-form").find("textarea[name=content]").summernote("code", "");
      resetForm("#truck-form");
    }
  });
});

var successLoadtruck = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.responses == undefined ? responses : responses.responses;
    console.log(responses);
  for(var i = 0; i < responses.data.data.length; i++) {
    id = responses.data.data[i].id;
    truck_plat = responses.data.data[i].truck_plat;
    truck_status = responses.data.data[i].truck_status;
    truck_corporate_asal = responses.data.data[i].truck_corporate_asal;
    truck_date_join = responses.data.data[i].truck_date_join;
    cabang_id = responses.data.data[i].cabang_id;
    data_json = responses.data.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ truck_plat +"</td>"+
                   "<td>"+ truck_status +"</td>"+
                   "<td>"+ truck_corporate_asal +"</td>"+
                   "<td>"+ truck_date_join +"</td>"+
                   "<td>"+ cabang_id +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-xs' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#truck-modal'><i class='fa fa-pencil'></i></a>"+
                       "<a class='btn btn-danger btn-xs btn-delete' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
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
        url: window.Laravel.app_url + "/api/truck/delete",
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
          $("table[data-model='truck']").closest("div[id='truck']").find("a[el-event='search-data']").click();
          $('.preloader').hide();
        },
      });
    }
  })
});
