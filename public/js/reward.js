$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/reward/get-list",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadreward(data);
    },
  });

  $("#btn-submit").click(function(){
    var event = $("#reward-modal #btn-submit").attr("el-event");
    var data = new FormData($("#reward-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/reward/" + event + "",
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
        $("#reward-modal").modal("hide");
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

  $("#reward-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);

      $("#reward-form").find("input[name=id]").val(dataJSON.id);
      $("#reward-modal #btn-submit").attr("el-event", "edit");
      $("#reward-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      
      bindToForm($("#reward-modal"), dataJSON);
      
    } else {
      $("#reward-form").find("input[name=id]").val(null);
      $("#reward-modal #btn-submit").attr("el-event", "add");
      $("#reward-form").find("textarea[name=content]").summernote("code", "");
      resetForm("#reward-form");
    }
  });

  $("#reward_status").select2({
    placeholder:"Select Status"
  });

  $("#cabang_id").select2({
    placeholder:"Select Cabang"
  });

  $("#reward_date_join").daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    singleDatePicker : true,
  });


});

var successLoadreward = (function(responses, dataModel) {
    
    var tableRows = "";
    var responses = responses.result.data == undefined ? responses : responses.result;
  
    for(var i = 0; i < responses.data.length; i++) {
      id = responses.data[i].id;
      min = responses.data[i].min;
      max = responses.data[i].max;
      bonus = responses.data[i].bonus;
      reward_jenis = responses.data[i].reward_jenis;
      data_json = responses.data[i].data_json;
  
      tableRows += "<tr>" +
                     "<td>"+ (i+1) +"</td>"+
                     "<td>"+ reward_jenis +"</td>"+
                     "<td>"+ min +"</td>"+
                     "<td>"+ max +"</td>"+
                     "<td>Rp "+ bonus +"</td>"+
                     "<td align='center'>"+
                       "<div class='btn-group'>"+
                         "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#reward-modal'><i class='fas fa-edit'></i></a>"+
                         "<a class='btn btn-danger btn-icon-only btn-sm' href='#' el-event='edit' data-id='"+ id +"' data-url='/api/reward/delete' data-toggle='modal' data-target='#deletedModal'><i class='fa fa-trash'></i></a>"+
                       "</div>"+
                     "</td>"+
                   "</tr>";
    }

    if(!tableRows) {
      tableRows += "<tr>" +
                   "</tr>";
    }
  
    $("#table-reward tbody").html(tableRows);
    
    paginate(responses, 'reward');

    $(".preloader").hide();
    $(".btn-delete").click(function(){
        var id = $(this).attr("data-id");
        var confirms =  confirm("Are You sure want to delete this file!");
        var accessToken =  window.Laravel.api_token;

        if(confirms) {
            $.ajax({
                url: window.Laravel.app_url + "/api/reward/delete",
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
