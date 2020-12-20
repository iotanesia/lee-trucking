@extends('layouts/default')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{$title}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item pt-1"><a href="index"><i class="fa fa-fw fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#">Master</a>
        </li>
        <li class="breadcrumb-item active">
            {{$title}}
        </li>
    </ol>

</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            
        </div>
        <div class="col-lg-12">
            <div class="card card-primary" id="group-sparepart">
                <div class="card-header text-white bg-primary">
                    <h3 class="card-title d-inline">
                        <i class="fa fa-fw fa-table"></i> Table {{$title}}
                    </h3>
                    <span class="pull-right">
                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                    </span>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control has-primary" name="search_value" data-model="group-sparepart" placeholder="Search Key">
                        <div class="input-group-append">
                            <a type="button" class="input-group-text btn btn-primary btn-border btn-flat" id="search-data" el-event="search-data" data-model="group-sparepart"><i class="fa fa-search"></i></a>
                            <a type="button" class="input-group-text btn btn-primary btn-border btn-flat bg-primary text-white"  data-toggle="modal" data-target="#group-sparepart-modal"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped"  id="table-group-sparepart" api-route="get-list-pagination" data-model="group-sparepart" request-url="{{ route('api-group-sparepart') }}" on-success-load="successLoadgroupsparepart">
                            <thead>
                            <tr>
                                <th>Nama Group SparePart</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="row col-sm-6 pull-left">
                    <span class="page-into" style="white-space: nowrap;"></span>
                    </div>
                    <ul class="pagination pagination-sm no-margin pull-right"></ul>
                </div>
            </div>
        </div>
    </div>
    <!--row end-->
@include('layouts.right_sidebar')
</section>
<div class="modal fade" id="group-sparepart-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add group-sparepart</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form" id="group-sparepart-form">
                <input type="hidden" name="id" id="id">
                <div class="card-body">
                <div class="form-group">
                    <label for="group_name">Group Name</label>
                    <input type="text" name="group_name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="group_status">Status</label>
                    <select name="group_status" id="group_status" class="form-control">
                        <option value="">Select Status</option>
                        @foreach($status as $row)
                        <option value="{{$row->id}}">{{$row->param_name}}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" el-event="add" id="btn-submit">Save changes</button>
        </div>
    </div>
</div>
<script src="{{asset('js/event.js')}}"></script>
<script src="{{asset('js/group-sparepart.js')}}"></script>
@endsection

