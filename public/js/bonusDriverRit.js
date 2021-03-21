$("document").ready(function(){
    var accessToken =  window.Laravel.api_token;
  
    $.ajax({
      url: window.Laravel.app_url + "/api/bonusDriverRit/get-list",
      type: "GET",
      dataType: "json",
      headers: {"Authorization": "Bearer " + accessToken},
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoadbonusDriverRit(data);
      },
    });
  
    $("#btn-submit").click(function(){
      var event = $("#bonusDriverRit-modal #btn-submit").attr("el-event");
      var data = new FormData($("#bonusDriverRit-form")[0]);
      data.append("_token", window.Laravel.csrfToken);
  
      $.ajax({
        url: window.Laravel.app_url + "/api/bonusDriverRit/" + event + "",
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
          $("#bonusDriverRit-modal").modal("hide");
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

    $("#bonusDriverRit_status").select2({
        placeholder:"Select Status"
    });

    $("#bonusDriverRit_parent").select2({
        placeholder:"Select Parent"
    });

    $("#bonusDriverRit_category").select2({
        placeholder:"Select Category"
    });

    $("#tahun-select").select2({
        placeholder:"Select Tahun"

    }).on('select2:select', function (e) {
        var data = e.params.data;
        var tahun = $("#tahun-select").val();
        var bulan = $("#bulan-select").val();
        
        if(tahun != '' && bulan != '') {
            var accessToken =  window.Laravel.api_token;
  
            $.ajax({
                url: window.Laravel.app_url + "/api/bonusDriverRit/get-list?tahun="+tahun+"&bulan="+bulan+"",
                type: "GET",
                dataType: "json",
                headers: {"Authorization": "Bearer " + accessToken},
                crossDomain: true,
                beforeSend: function( xhr ) { 
                    $('.preloader').show();
                },
                success: function(data, textStatus, xhr) {
                    $('.preloader').hide();
                    successLoadbonusDriverRit(data);
                },
            });
        }
    });

    $("#bulan-select").select2({
        placeholder:"Select Bulan"
    }).on('select2:select', function (e) {
        var data = e.params.data;
        var tahun = $("#tahun-select").val();
        var bulan = $("#bulan-select").val();
        
        if(tahun != '' && bulan != '') {
            var accessToken =  window.Laravel.api_token;
  
            $.ajax({
                url: window.Laravel.app_url + "/api/bonusDriverRit/get-list?year="+tahun+"&month="+bulan+"",
                type: "GET",
                dataType: "json",
                headers: {"Authorization": "Bearer " + accessToken},
                crossDomain: true,
                beforeSend: function( xhr ) { 
                    $('.preloader').show();
                },
                success: function(data, textStatus, xhr) {
                    $('.preloader').hide();
                    successLoadbonusDriverRit(data);
                },
            });
        }
    });

    $('#date-picker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: '06/01/2013',
        maxDate: '06/30/2021',      
        format: 'DD/MM/YYYY'
      }).on('hide.daterangepicker', function (ev, picker) {
        $('.table-condensed tbody tr:nth-child(2) td').click();
        alert(picker.startDate.format('MM/YYYY'));
      });
  
    $("#bonusDriverRit-modal").on("show.bs.modal", function(e) {
      var invoker = $(e.relatedTarget);
  
      if(invoker.attr('el-event') == 'edit') {
        var dataJSON = invoker.attr("data-json");
        var dataJSON = JSON.parse(dataJSON);
  
        $("#bonusDriverRit-form").find("input[name=id]").val(dataJSON.id);
        $("#bonusDriverRit-modal #btn-submit").attr("el-event", "edit");
        $("#bonusDriverRit-form").find("textarea[name=content]").summernote("code", dataJSON.content);
        
        bindToForm($("#bonusDriverRit-modal"), dataJSON);
        
      } else {
        $("#bonusDriverRit-form").find("input[name=id]").val(null);
        $("#bonusDriverRit-modal #btn-submit").attr("el-event", "add");
        $("#bonusDriverRit-form").find("textarea[name=content]").summernote("code", "");
        resetForm("#bonusDriverRit-form");
      }
    });
  });
  
  var successLoadbonusDriverRit = (function(responses, dataModel) {
      
    var tableRows = "";
    var responses = responses.result.data == undefined ? responses : responses.result;
  
    for(var i = 0; i < responses.data.length; i++) {
      id = responses.data[i].id;
      driver_name = responses.data[i].driver_name;
      rit_truck = responses.data[i].rit_truck;
      truck = responses.data[i].truck;
      total_rit = responses.data[i].total_rit;
      bonus = responses.data[i].bonus;
      reward_jenis = responses.data[i].reward_jenis;
      data_json = responses.data[i].data_json;

      tableRows += "<tr>" +
                     "<td>"+ (i + 1) +"</td>"+
                     "<td>"+ driver_name +"</td>"+
                     "<td>"+ truck +"</td>"+
                     "<td>"+ def(total_rit) +"</td>"+
                     "<td>"+ def(rit_truck) +"</td>"+
                     "<td>"+ def(reward_jenis) +"</td>"+
                     "<td>"+ convertToRupiah(parseInt(total_rit) * 10000) +"</td>"+
                     "<td>"+  convertToRupiah(bonus)  +"</td>"+
                     "<td>"+  convertToRupiah(parseInt(bonus) + (parseInt(total_rit) * 10000))  +"</td>"+
                   "</tr>";
    }
  
    if(!tableRows) {
      tableRows += "<tr>" +
                   "</tr>";
    }
  
    $("#table-bonusDriverRit tbody").html(tableRows);
    paginate(responses, 'bonusDriverRit');
  
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
  