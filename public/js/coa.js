$("document").ready(function(){
    var accessToken =  window.Laravel.api_token;
  
    $.ajax({
      url: window.Laravel.app_url + "/api/coa/get-list",
      type: "GET",
      dataType: "json",
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoadcoa(data);
      },
    });
  
    $("#btn-submit").click(function(){
      var event = $("#coa-modal #btn-submit").attr("el-event");
      var data = new FormData($("#coa-form")[0]);
      data.append("_token", window.Laravel.csrfToken);
  
      $.ajax({
        url: window.Laravel.app_url + "/api/coa/" + event + "",
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
          $("#coa-modal").modal("hide");
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

    $("#coa_status").select2({
        placeholder:"Select Status"
    });

    $("#coa_parent").select2({
        placeholder:"Select Parent"
    });

    $("#coa_category").select2({
        placeholder:"Select Category"
    });
  
    $("#coa-modal").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);
  
      if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataJSON = JSON.parse(dataJSON);
  
        $("#coa-form").find("input[name=id]").val(dataJSON.id);
        $("#coa-modal #btn-submit").attr("el-event", "edit");
        $("#coa-form").find("textarea[name=content]").summernote("code", dataJSON.content);
        
        bindToForm($("#coa-modal"), dataJSON);
        
      } else {
        $("#coa-form").find("input[name=id]").val(null);
        $("#coa-modal #btn-submit").attr("el-event", "add");
        $("#coa-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#coa-form");
      }
    });
  });
  
  var successLoadcoa = (function(responses, dataModel) {
      
    var tableRows = "";
    var responses = responses.result.data == undefined ? responses : responses.result;
  
    for(var i = 0; i < responses.data.length; i++) {
      id = responses.data[i].id;
      coa_name = responses.data[i].coa_name;
      coa_code = responses.data[i].coa_code;
      coa_status_name = responses.data[i].coa_status_name;
      coa_category_name = responses.data[i].coa_category_name;
      parent_coa_name = responses.data[i].parent_coa_name;
      data_json = responses.data[i].data_json;

      tableRows += "<tr>" +
                     "<td>"+ (i + 1) +"</td>"+
                     "<td>"+ coa_name +"</td>"+
                     "<td>"+ def(coa_code) +"</td>"+
                     "<td>"+ coa_status_name +"</td>"+
                     "<td>"+ coa_category_name +"</td>"+
                     "<td>"+ def(parent_coa_name) +"</td>"+
                   "</tr>";
    }
  
    if(!tableRows) {
      tableRows += "<tr>" +
                   "</tr>";
    }
  
    $("#table-coa tbody").html(tableRows);
    paginate(responses, 'coa');
  
    $(".preloader").hide();
  
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
  