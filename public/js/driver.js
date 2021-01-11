$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/driver/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoaddriver(data);
    },
  });

   $("#user_id").select2({
       placeholder:"Select User"
   }).on('select2:select', function (e) {
      $("#driver_name").val($('#user_id :selected').text());
   });

  $("#btn-submit").click(function(){
    var event = $("#driver-modal #btn-submit").attr("el-event");
    var data = new FormData($("#driver-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/driver/" + event + "",
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
        $("#driver-modal").modal("hide");
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

  $("#driver-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);

      $("#driver-form").find("input[name=id]").val(dataJSON.id);
      $("#driver-modal #btn-submit").attr("el-event", "edit");
      $("#driver-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      
      bindToForm($("#driver-modal"), dataJSON);
      
    } else {
      $("#driver-form").find("input[name=id]").val(null);
      $("#driver-modal #btn-submit").attr("el-event", "add");
      $("#driver-form").find("textarea[name=content]").summernote("code", "");
      resetForm("#driver-form");
    }
  });
});

var successLoaddriver = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.data.data == undefined ? responses : responses.data;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    driver_name = responses.data[i].driver_name;
    driver_status = responses.data[i].status_name;
    kenek_name = responses.data[i].kenek_name;
    driver_join_date = responses.data[i].driver_join_date;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ driver_name +"</td>"+
                   "<td>"+ driver_status +"</td>"+
                   "<td>"+ kenek_name +"</td>"+
                   "<td>"+ driver_join_date +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-xs btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#driver-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-danger btn-xs btn-sm' href='#' el-event='edit' data-id='"+ id +"' data-url='/api/driver/delete' data-toggle='modal' data-target='#deletedModal'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-driver tbody").html(tableRows);
  paginate(responses, 'driver');
  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/driver/delete",
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
