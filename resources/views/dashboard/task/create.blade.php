@extends('layouts.dashboardapp')

@push('extrastyle')
    <!-- Date picker plugins css -->
    <link href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Add New Task</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Dashboard</a></li>
                <li><a href="{{ route('task.index') }}">Tasks</a></li>
                <li class="active">Add New</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!--row -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group @error('name') has-error @enderror">
                                            <label for="name" class="control-label">Name</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Task Name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-4">
                                        <div class="form-group @error('date') has-error @enderror"
                                            id="datepicker_container">
                                            <label for="date" class="control-label">Date</label>
                                            <input type="text" class="form-control datepicker-taskdate" id="date"
                                                value="{{ old('date') }}" name="date" placeholder="mm/dd/yyyy"
                                                data-date-container='#datepicker_container'>
                                            @error('date')
                                                <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <!--row-->
                                <div class="row">
                                    <!--col-->
                                    <div class="col-md-12">
                                        <div class="form-group @error('description') has-error @enderror">
                                            <label for="description" class="control-label">Description</label>
                                            <textarea id="description" name="description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--/col-->
                                </div>
                                <!--/row-->
                                <!--row-->
                                <div class="row">
                                    <!--col-->
                                    <div class="col-md-12">
                                        <div
                                            class="form-group @error('images') has-error @enderror @error('images.*') has-error @enderror">
                                            <label for="images" class="control-label">Images upload</label>
                                            <input type="file" class="form-control" name="images[]" id="images" multiple>
                                            @error('images')
                                                <span class="help-block">{{ $message }}</span>
                                            @enderror
                                            @error('images.*')
                                                @php
                                                    $message_mod = preg_replace('/[a-z]+\.+[0-9]/i', 'image', $message);
                                                @endphp
                                                <span class="help-block">{{ $message_mod }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--/col-->
                                </div>
                                <!--/row-->
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">Add Task</button>
                                <a href="{{ route('task.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('extrascript')
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/jasny-bootstrap.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            if ($(".datepicker-taskdate").length > 0) {
                $('.datepicker-taskdate').datepicker({
                    autoclose: true,
                    todayHighlight: true
                });
            }
            if ($("#description").length > 0) {
                tinymce.init({
                    selector: "textarea#description",
                    theme: "modern",
                    height: 300,
                    plugins: [
                        "advlist autolink link lists hr anchor",
                        "searchreplace wordcount fullscreen insertdatetime",
                        "save directionality paste"
                    ],
                    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
                });
            }
        })
    </script>
@endpush
