$("document").ready(function(){
  var accessToken =  window.Laravel.api_token;

  $("#tgl_pinjaman").daterangepicker({
    locale: {
        format: 'DD-MM-YYYY'
    },
    singleDatePicker : true,
  });

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

  $(".btn-detail").click(function() {
      var invoker = $(this);
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);
      var block = '';
      
      for(var i = 0; i < dataJSON.money_detail_termin.length; i++) {
          block += `<div class="timeline-block">
                        <span class="timeline-step badge-success">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted font-weight-bold">`+dataJSON.money_detail_termin[i].created_at+`</small>
                                    <h5 class="text-muted font-weight-bold mt-3 mb-0">Termin : `+dataJSON.money_detail_termin[i].baris_termin+` <br>
                                                                                    Nominal : Rp `+convertToRupiah(dataJSON.money_detail_termin[i].nominal_termin)+`  <br>
                                                                                    Rekening : `+dataJSON.money_detail_termin[i].rek_name+`-`+dataJSON.money_detail_termin[i].rek_no+` <br>
                                                                                    Tanggal Pembayaran : `+dataJSON.money_detail_termin[i].date+` 
                                                                                    <br>
                                                                                    <br>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>`;
      }

      $("#blockHtml").html(block);

  })


  $("#btn-submits-detail").click(function(){
    var event = $("#moneyTransactionHeader-modal #btn-submits-detail").attr("el-event");
    var data = new FormData($("#moneyTransactionHeader-detail-form")[0]);
    data.append("_token", window.Laravel.csrfToken);

    $.ajax({
      url: window.Laravel.app_url + "/api/moneyTransactionHeader/paid",
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
            $("#moneyTransactionHeader-modal").modal("hide");
            $("#successModal").modal("show");
            $('.preloader').hide();
            location.reload();
            document.getElementById("search-data").click();

        },error: function(datas, textStatus, xhr) {
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

  $("#moneyTransactionHeader-modal-detail").on("show.bs.modal", function(e) {
    var invoker = $(e.relatedTarget);
    var tableRows = "";

    if(invoker.attr('el-event') == 'edit') {
      var dataJSON = invoker.attr("data-json");
      var dataJSON = JSON.parse(dataJSON);
      $('.termin-val').text('Pembayaran Ke - '+(parseInt(dataJSON.total_bayar) + 1));
      var responses = dataJSON.money_detail_termin;

      for(var i = 0; i < responses.length; i++) {
        id = responses[i].id;
        nominal_termin = responses[i].nominal_termin;
        baris_termin = responses[i].baris_termin ? '<a href="#" class="btn btn-success">Bayar</a>' : '<i class="fas fa-check text-success"></i>';
    
        tableRows += "<tr>" +
                       "<td>"+ (i+1) +"</td>"+
                       "<td>Rp "+ def(nominal_termin) +"</td>"+
                       "<td>"+ (i+1) +"</td>"+
                       "<td>"+ def(baris_termin) +"</td>"+
                     "</tr>";
      }
    
      if(!tableRows) {
        tableRows += "<tr>" +
                     "</tr>";
      }
    
      $("#table-moneyTransactionHeader-detail tbody").html(tableRows);
      $("#moneyTransactionHeader-detail-form").find("input[name=transaksi_header_id]").val(dataJSON.id);
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

  $(".rek_id").select2({
    placeholder:"Pilih Rekening"
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
  var responses = responses.result.data == undefined ? responses : responses.result;

  for(var i = 0; i < responses.data.length; i++) {
    id = responses.data[i].id;
    name_user = responses.data[i].name_user;
    pokok = responses.data[i].pokok;
    sisa_pokok = responses.data[i].sisa_pokok;
    status = responses.data[i].status;
    moneyTransactionHeader_date_join = responses.data[i].moneyTransactionHeader_date_join;
    rek_no = responses.data[i].rek_no;
    rek_name = responses.data[i].rek_name;
    total_bayar = responses.data[i].total_bayar;
    user_id = responses.data[i].user_id;
    data_json = responses.data[i].data_json;

    if(status == 'LUNAS') {
        var badge = 'badge-success';
        
    } else {
        var badge = 'badge-danger';

    }

    tableRows += "<tr>" +
                   "<td>"+ (i+1) +"</td>"+
                   "<td>"+ def(name_user) +"</td>"+
                   "<td>"+ convertToRupiah(pokok) +"</td>"+
                   "<td>"+ convertToRupiah(sisa_pokok) +"</td>"+
                   "<td> <span class='badge "+badge+"'>"+status+"</span></td>"+
                   "<td align='left'><a class='btn btn-warning btn-sm' href='"+window.Laravel.app_url+"/pinjaman-karyawan/detail/"+user_id+"'>Detail</a></td>"+
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
