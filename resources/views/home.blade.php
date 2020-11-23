@extends('layouts.appv2')
@section('content')   
  <div class="app-main__outer">
      <div class="app-main__inner">
          <div class="app-page-title border-bottom">
              <div class="page-title-wrapper">
                  <div class="page-title-heading">
                      <div class="page-title-icon">
                          <i class="pe-7s-search icon-gradient bg-mean-fruit">
                          </i>
                      </div>
                      <div id="blockTitle">MOFING-X-SYS
                          <div class="page-title-subheading">Monitoring & Financial Recording Expedition 
                          </div>
                      </div>
                  </div>
              </div>
          </div>            
          <!-- start Content  -->
          <div class="row">
              <div class="col-md-12">
                  <div class="main-card mb-3 card" id="blockContent">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Provinsi </label>
                              <div class="col-sm-8">
                                <select class="form-control select2" name="id_provinsi" style="width: 100%;">
                                  <option value="">Pilih Provinsi</option>  
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Kecamatan </label>
                              <div class="col-sm-8">
                                <select class="form-control select2" name="id_kecamatan" style="width: 100%;">
                                  <option value="">Pilih Kecamatan</option>  
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> kabupaten </label>
                              <div class="col-sm-8">
                                <select class="form-control select2" name="id_kabupaten" style="width: 100%;">
                                  <option value="">Pilih Kabupaten</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Desa / Kelurahan </label>
                              <div class="col-sm-8">
                                <select class="form-control select2 editable" name="id_kelurahan" style="width: 100%;">
                                  <option value="">Pilih Kelurahan</option>  
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> NIK </label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Nama Lengkap </label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Tempat Lahir </label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Status Hub. Keluarga </label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control">
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> No. KK </label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Jenis Kelamin </label>
                              <div class="col-sm-8">
                                <select name="" id="" class="form-control">
                                  <option value="">Pilih Jenis Kelamin</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Tanggal Lahir </label>
                              <div class="col-sm-8">
                                <input type="date" name="" id="" class="form-control">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 control-label"> Tanggal Input </label>
                              <div class="col-sm-8">
                                <input type="date" name="" id="" class="form-control">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="main-card mb-3 card" id="TableblockContent">
                      <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-bordered bg-primary text-white">
                              <thead>
                                <th>NO</th>
                                <th>NO KK</th>
                                <th>NIK</th>
                                <th>NAMA LENGKAP</th>
                                <th>TTL</th>
                                <th>ALAMAT</th>
                                <th>PHOTO</th>
                                <th>OPERASI</th>
                              </thead>
                            </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- end content -->
      </div>
      <div class="app-wrapper-footer">
          <div class="app-footer">
              <div class="app-footer__inner">
                  <div class="app-footer-left">
                        copyright {{date('Y')}} &nbsp;&nbsp; <a href="#">iotanesia.co.id</a>
                  </div>
              </div>
          </div>
      </div>    
  </div>
  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
</div>
<script>
  window.Laravel = {!! json_encode([
    "csrfToken" => csrf_token(),
    "app_url" => url('/'),
  ]) !!};

  function menuDetail(id) {
    $.ajax({
      url: window.Laravel.app_url + "/menu/get-list-detail",
      type: "GET",
      data: 'id'+'='+id,
      dataType: "json",
      crossDomain: true,
      beforeSend: function( xhr ) { 
        $('.preloader').show();
      },
      success: function(data, textStatus, xhr) {
        $('.preloader').hide();
        successLoadMenuDetail(data);
      },
    });
  }
  
  $("#search").keyup(function(e){
    var data = $(this).val();
    if(e.keyCode == 13) {
      $.ajax({
        url: window.Laravel.app_url + "/menu/get-list-search",
        type: "GET",
        data: 'where_value'+'='+data,
        dataType: "json",
        crossDomain: true,
        beforeSend: function( xhr ) { 
          $('.preloader').show();
        },
        success: function(data, textStatus, xhr) {
          $('.preloader').hide();
          successLoadMenu(data);
        },
      });
    }
  });

  var successLoadMenuDetail = (function(responses, dataModel) {
    var blockContent = "";
    var blockTitle = "";
    var responses = responses.responses;
    
    menu_code = responses.menu_code;
    menu = responses.menu;
    content_title = responses.content_title;
    content = responses.content;
    id = responses.id;
    
    
    blockTitle += menu +
                  "<div class='page-title-subheading'>Pertamina</div>";

    blockContent += "<div class='card-body'>"+
                      content+
                    "</div>";

    $("#blockContent").html(blockContent);
    $("#blockTitle").html(blockTitle);
  });

  var successLoadMenu = (function(responses, dataModel) {
    var blockTitle = "";
    var blockContent = "";
    var htmllinkRows = "";
    var menuFirst = "";
    var content_titleFirst = "";
    var contentFirst = "";
    var responses = responses.responses;
    
    for(var i = 0; i < responses.length; i++) {
      menu_code = responses[i].menu_code;
      menu = responses[i].menu;
      content_title = responses[i].content_title;
      content = responses[i].content;
      id = responses[i].id;

      menuFirst = responses[0].menu;
      content_titleFirst = responses[0].content_title;
      contentFirst = responses[0].content;

      htmllinkRows += "<li>"+
                        "<a href='#"+menu_code+"' onClick='menuDetail("+id+")' class=''>"+
                        "<i class='metismenu-icon pe-7s-monitor'></i>"+
                            menu+
                        "</a>"+
                      "</li>";
    }

    blockTitle += menuFirst +
                  "<div class='page-title-subheading'>Pertamina</div>";

    blockContent += "<div class='card-body slide-text'>"+
                      contentFirst+
                    "</div>";

    $("#blockContent").html(blockContent);
    $("#blockTitle").html(blockTitle);
    $("#blockLinkHtml").html(htmllinkRows);
  });

</script>
@endsection
