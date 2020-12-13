$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/kenek/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadkenek(data);
    },
  });

  $("#btn-submit").click(function(){
    var event = $("#kenek-modal #btn-submit").attr("el-event");
    var data = new FormData($("#kenek-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/kenek/" + event + "",
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
        $("#kenek-modal").modal("hide");
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

  $("#kenek-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);

      $("#kenek-form").find("input[name=id]").val(dataJSON.id);
      $("#kenek-modal #btn-submit").attr("el-event", "edit");
      $("#kenek-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      
      bindToForm($("#kenek-modal"), dataJSON);
      
    } else {
      $("#kenek-form").find("input[name=id]").val(null);
      $("#kenek-modal #btn-submit").attr("el-event", "add");
      $("#kenek-form").find("textarea[name=content]").summernote("code", "");
      resetForm("#kenek-form");
    }
  });
});

var successLoadkenek = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.data.data == undefined ? responses : responses.data;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    kenek_name = responses.data[i].kenek_name;
    kenek_status = responses.data[i].status_name;
    kenek_join_date = responses.data[i].kenek_join_date;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ kenek_name +"</td>"+
                   "<td>"+ kenek_status +"</td>"+
                   "<td>"+ kenek_join_date +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-xs' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#kenek-modal'><i class='fa fa-pencil'></i></a>"+
                       "<a class='btn btn-danger btn-xs btn-delete' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-kenek tbody").html(tableRows);
  paginate(responses, 'kenek');
  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/kenek/delete",
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
