$("document").ready(function(){
    var accessToken =  window.Laravel.api_token;
  
    $.ajax({
      url: window.Laravel.app_url + "/api/user/get-list",
      type: "GET",
      dataType: "json",
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoaduser(data);
      },
    });
  
    $("#btn-submit").click(function(){
      var event = $("#user-modal #btn-submit").attr("el-event");
      var data = new FormData($("#user-form")[0]);
      data.append("_token", window.Laravel.csrfToken);

      if(event == "edit") {
          var url = window.Laravel.app_url + "/api/user/" + event + "";
          
      } else {
            var url = window.Laravel.app_url + "/api/register";
      }
  
      $.ajax({
        url: url,
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
          $("#user-modal").modal("hide");
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
    });

    $("#group_id").select2({
        placeholder:"Pilih Group"
    });

    $("#cabang_id").select2({
        placeholder:"Pilih Cabang"
    });

    $("#is_active").select2({
        placeholder:"Pilih Parent"
    });

    $("#user_category").select2({
        placeholder:"Pilih Kategori"
    });

    $("#tgl_lahir").daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker : true,
    });

    $("#agama").select2({
        placeholder:"Pilih Agama"
    });

    $("#jenis_kelamin").select2({
        placeholder:"Pilih Jenis Kelamin"
    });

    $("#user-modal").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);
  
      if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataJSON = JSON.parse(dataJSON);
  
        $("#user-form").find("input[name=id]").val(dataJSON.id);
        $("#user-form").find("input[name=no_rek]").val(dataJSON.no_rek);
        $("#user-form").find("input[name=nama_bank]").val(dataJSON.nama_bank);
        $("#user-form").find("input[name=nama_rekening]").val(dataJSON.nama_rekening);
        $("#user-modal #btn-submit").attr("el-event", "edit");
        $("#user-form").find("textarea[name=content]").summernote("code", dataJSON.content);
        
        bindToForm($("#user-modal"), dataJSON);
        $("#user-form").find("select[name=cabang_id]").val(dataJSON.cabang_id).trigger('change');
        
    } else {
        $("#user-form").find("select[name=cabang_id]").val(null).trigger('change');
        $("#user-form").find("input[name=id]").val(null);
        $("#user-modal #btn-submit").attr("el-event", "add");
        $("#user-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#user-form");
      }
    });
  });
  
  var successLoaduser = (function(responses, dataModel) {
      
    var tableRows = "";
    var responses = responses.result.data == undefined ? responses : responses.result;
  
    for(var i = 0; i < responses.data.length; i++) {
      id = responses.data[i].id;
      user_name = responses.data[i].name;
      email = responses.data[i].email;
      group_name = responses.data[i].group_name;
      data_json = responses.data[i].data_json;

      tableRows += "<tr>" +
                     "<td>"+ (i + 1) +"</td>"+
                     "<td>"+ user_name +"</td>"+
                     "<td>"+ def(email) +"</td>"+
                     "<td>"+ def(group_name) +"</td>"+
                     "<td align='center'>"+
                       "<div class='btn-group'>"+
                         "<a class='btn btn-slack btn-icon-only btn-sm' href='#' el-event='edit' data-json='"+ data_json +"' data-toggle='modal' data-target='#user-modal'><i class='fas fa-edit'></i></a>"+
                         "<a class='btn btn-danger btn-icon-only btn-sm btn-delete' href='#' el-event='edit' data-id='"+ id +"'><i class='fa fa-trash'></i></a>"+
                       "</div>"+
                     "</td>"+
                   "</tr>";
    }
  
    if(!tableRows) {
      tableRows += "<tr>" +
                   "</tr>";
    }
  
    $("#table-user tbody").html(tableRows);
    paginate(responses, 'user');
  
    $(".preloader").hide();
  
    $(".btn-delete").click(function(){
      var id = $(this).attr("data-id");
      var confirms =  confirm("Are You sure want to delete this file!");
      var accessToken =  window.Laravel.api_token;
  
      if(confirms) {
        $.ajax({
          url: window.Laravel.app_url + "/api/user/delete",
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
  