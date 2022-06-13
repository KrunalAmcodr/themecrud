@extends('layouts.dashboardapp')

@push('extrastyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">View Post</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="active">View Post</li>
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
                        <a href="javascript:void(0)" id="delete-task-btn" class="text-white m-1 mt-0"
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
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#delete-task-btn', function(e) {
                e.preventDefault();
                var trbutton = $(this);
                let id = $(this).data('id');
                var token = $("meta[name='csrf-token']").attr("content");

                swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this task data!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function() {
                        trbutton.closest('tr').remove();
                        $.ajax({
                            type: "DELETE",
                            url: "/admin/task/" + id,
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            success: function(data) {
                                swal("Deleted!", data.success,
                                    "success");
                                if ($('#items-table tbody tr').length == false) {
                                    $('#items-table').remove();
                                    $('.table-container-ctm').append(
                                        '<div class="alert alert-warning">Task not found.</div>'
                                        )
                                }
                                window.location.href = "{{ route('task.index') }}";
                            }
                        });
                    });
            })
        });
    </script>
@endpush
