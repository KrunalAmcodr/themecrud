@extends('layouts.dashboardapp')

@push('extrastyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/css/custom-style.css')}}" type="text/css">    
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">All Tasks</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="active">All Tasks</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /row -->
    <div class="row">
        <div class="col-12">
            <div class="white-box">
                <div class="d-flex align-items-center">
                    <div class="col-md-4 pageshow-count">
                        <div class="form-group" style="display: none;">
                            <label>Select Number Of Rows</label>
                            <select class="form-control" name="state" id="maxRows">
                                <option value="5000">Show ALL Rows</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="70">70</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <a href="{{ route('task.create') }}"
                            class="btn btn-info waves-effect waves-light float-right mb-3"><span>Add New</span> <i
                                class="fa fa-plus-circle m-l-5"></i></a>
                    </div>
                </div>
                <div class="table-responsive table-container-ctm">
                    @if (isset($tasks) && !empty($tasks) && count($tasks) > 0)
                        <table class="table table-hover color-table dark-table" id="items-table">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th style="width:150px;">Name</th>
                                    <th style="width:100px;">Date</th>
                                    <th>Description</th>
                                    <th style="width:150px;">Images</th>
                                    <th style="width:80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><a href="{{ route('task.show', $task->id) }}" class="font-weight-bold text-dark">{{ $task->name }}</a></td>
                                        <td>{{ date('m/d/Y', strtotime($task->date)) }}</td>
                                        <td>{!! $task->description !!}</td>
                                        <td>
                                            @foreach (json_decode($task->images) as $image)
                                                <img src="{{ asset('image/' . $image) }}" width="60" height="auto"
                                                    class="ml-2 mb-2" />
                                            @endforeach
                                        </td>
                                        <td>
                                            <h4><a href="{{ route('task.edit', $task->id) }}"
                                                    class="text-info m-1 mt-0"><i class="ti-pencil-alt"></i></a>
                                                <a href="javascript:void(0)" id="delete-task-btn"
                                                    class="text-danger m-1 mt-0" data-id="{{ $task->id }}"><i
                                                        class="ti-trash"></i></a>
                                            </h4>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--		Start Pagination -->
                        <div class='pagination-container'>
                            <nav>
                                <ul class="pagination">

                                    <li data-page="prev">
                                        <span>
                                            <i class="ti-angle-left"></i> <span class="sr-only">(current)
                                            </span></span>
                                    </li>
                                    <!--	Here the JS Function Will Add the Rows -->
                                    <li data-page="next" id="prev">
                                        <span> <i class="ti-angle-right"></i> <span
                                                class="sr-only">(current)</span></span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @else
                        <div class="alert alert-warning">{{ __('Task not found.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('extrascript')
    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/main-custom.js') }}"></script>
    @if (session('success'))
        <script type="text/javascript">
            $(document).ready(function() {
                $.toast({
                    heading: "{{ session('success') }}",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });
            });
        </script>
    @endif
@endpush
