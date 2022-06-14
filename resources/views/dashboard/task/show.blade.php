@extends('layouts.dashboardapp')

@push('extrastyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">View Task</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Dashboard</a></li>
                <li><a href="{{ route('task.index') }}">All Task</a></li>
                <li class="active">View Task</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-12">
            <div class="panel panel-primary block6">
                <div class="panel-heading">
                    Task : {{ $task->name }}
                    <div class="pull-right">
                        <a href="{{ route('task.edit', $task->id) }}" class="text-white m-1 mt-0"><i
                                class="ti-pencil-alt"></i></a>
                        <a href="javascript:void(0)" id="delete-taskfromshow-btn" class="text-white m-1 mt-0"
                            data-id="{{ $task->id }}"><i class="ti-trash"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <p><i class="ti-calendar"></i> {{ date('m/d/Y', strtotime($task->date)) }}</p>
                        <h3>Description:</h3>
                        {!! $task->description !!}
                        <h3>Images:</h3>
                        @foreach (json_decode($task->images) as $image)
                            <img src="{{ asset('image/' . $image) }}" width="200" height="auto" class="ml-2 mb-2" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extrascript')
    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/main-custom.js') }}"></script>
@endpush
