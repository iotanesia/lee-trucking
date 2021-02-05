$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/moneyTransactionHeader/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadmoneyTransactionHeader(data);
    },
  });

  $("#btn-submit").click(function(){
    var event = $("#moneyTransactionHeader-modal #btn-submit").attr("el-event");
    var data = new FormData($("#moneyTransactionHeader-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/moneyTransactionHeader/" + event + "",
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
        $("#moneyTransactionHeader-modal").modal("hide");
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

  $("#moneyTransactionHeader_status").select2({
    placeholder:"Select Status"
  });

  $("#user_id").select2({
    placeholder:"Pilih Karyawan"
  });

  $("#rek_id").select2({
    placeholder:"Pilih Rekening"
  });

  $("#moneyTransactionHeader_date_join").daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    singleDatePicker : true,
  });


});

var successLoadmoneyTransactionHeader = (function(responses, dataModel) {
    
  var tableRows = "";
  var responses = responses.data.data == undefined ? responses : responses.data;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    name_user = responses.data[i].name_user;
    pokok = responses.data[i].pokok;
    sisa_pokok = responses.data[i].sisa_pokok;
    status = responses.data[i].status;
    moneyTransactionHeader_date_join = responses.data[i].moneyTransactionHeader_date_join;
    rek_no = responses.data[i].rek_no;
    rek_name = responses.data[i].rek_name;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ def(name_user) +"</td>"+
                   "<td>"+ def(pokok) +"</td>"+
                   "<td>"+ def(sisa_pokok) +"</td>"+
                   "<td>"+ def(rek_no) +" - "+ def(rek_name) +"</td>"+
                   "<td>"+ def(status) +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-animate-modal='rotateInDownLeft' data-toggle='modal' data-target='#moneyTransactionHeader-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-danger btn-sm' href='#' el-event='edit' data-id='"+ id +"' data-url='/api/moneyTransactionHeader/delete' data-toggle='modal' data-target='#deletedModal'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-moneyTransactionHeader tbody").html(tableRows);
  paginate(responses, 'moneyTransactionHeader');
  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/moneyTransactionHeader/delete",
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