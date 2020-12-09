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
                <div class="card card-primary">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Salary</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
        @include('layouts.right_sidebar')
    </section>
    <script src="{{asset('js/event.js')}}"></script>
@endsection

