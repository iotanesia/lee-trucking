$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $.ajax({
    url: window.Laravel.app_url + "/api/group-sparepart/get-list-pagination",
    type: "GET",
    dataType: "json",
    headers: {"Authorization": "Bearer " + accessToken},
    crossDomain: true,
    beforeSend: function( xhr ) { 
      $('.preloader').show();
    },
    success: function(data, textStatus, xhr) {
      $('.preloader').hide();
      successLoadgroupsparepart(data);
    },
  });

  $("#btn-submit").click(function(){
    var event = $("#group-sparepart-modal #btn-submit").attr("el-event");
    var data = new FormData($("#group-sparepart-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/group-sparepart/" + event + "",
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
        $("#group-sparepart-modal").modal("hide");
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

  $("#group-sparepart-modal").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);

      $("#group-sparepart-form").find("input[name=id]").val(dataJSON.id);
      $("#group-sparepart-modal #btn-submit").attr("el-event", "edit");
      $("#group-sparepart-form").find("textarea[name=content]").summernote("code", dataJSON.content);
      
      bindToForm($("#group-sparepart-modal"), dataJSON);
      
    } else {
      $("#group-sparepart-form").find("input[name=id]").val(null);
      $("#group-sparepart-modal #btn-submit").attr("el-event", "add");
      $("#group-sparepart-form").find("textarea[name=content]").summernote("code", "");
      resetForm("#group-sparepart-form");
    }
  });
  
  $("#group_status").select2({
    placeholder:"Select Status"
  });

});

var successLoadgroupsparepart = (function(responses, dataModel) {
    
  var tableRows = "";
  console.log(responses);
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    group_name = responses.data[i].group_name;
    group_status_name = responses.data[i].group_status_name;
    data_json = responses.data[i].data_json;

    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ group_name +"</td>"+
                   "<td>"+ group_status_name +"</td>"+
                   "<td align='center'>"+
                     "<div class='btn-group'>"+
                       "<a class='btn btn-success btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#group-sparepart-modal'><i class='fas fa-edit'></i></a>"+
                       "<a class='btn btn-danger btn-sm btn-delete' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
                     "</div>"+
                   "</td>"+
                 "</tr>";
  }

  if(!tableRows) {
    tableRows += "<tr>" +
                 "</tr>";
  }

  $("#table-group-sparepart tbody").html(tableRows);
  paginate(responses, 'group-sparepart');

  $(".preloader").hide();

  $(".btn-delete").click(function(){
    var id = $(this).attr("data-id");
    var confirms =  confirm("Are You sure want to delete this file!");
    var accessToken =  window.Laravel.api_token;

    if(confirms) {
      $.ajax({
        url: window.Laravel.app_url + "/api/group-sparepart/delete",
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

  function optData(idSelect, res, title) {

    var opt = '';
        opt += '<option value="0">--Select '+title+'--</option>';

    $.each(res.data, function( k, v) {
        console.log(v.kabupaten);

        if(title == 'kabupaten') {
            name = v.kabupaten;
        
        } else if(title == 'kecamatan') {
            name = v.kecamatan;
        }
        
        opt += '<option value="'+v.id+'"> '+name+' </option>';
    });

    $(idSelect).html(opt);
  }
});
