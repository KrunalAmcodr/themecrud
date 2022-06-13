@extends('layouts.dashboardapp')

@push('extrastyle')
    <!-- Date picker plugins css -->
    <link href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.30.4/filepond.min.css">
    <style>
        .filepond--drop-label label {
            font-size: 16px;
            font-family: 'Poppins';
        }

        .filepond--file {
            font-size: 18px !important;
            font-family: 'Poppins';
        }
    </style>
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
                                            <input type="text" class="form-control datepicker-taskdate" id="date" value="{{ old('date') }}"
                                                name="date" placeholder="mm/dd/yyyy"
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
                                        <div class="form-group @error('images') has-error @enderror">
                                            <label for="images" class="control-label">File upload</label>
                                            <input type="file" class="my-pond" name="images[]" id="image" multiple>
                                            @error('images')
                                                <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--/col-->
                                </div>
                                <!--/row-->
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">Add Task</button>
                                <button type="reset" class="btn btn-default">Cancel</button>
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
    <!-- include FilePond library -->
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <!-- include FilePond plugins -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    <!-- include FilePond jQuery adapter -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
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

            // First register any plugins
            // $.fn.filepond.registerPlugin(FilePondPluginImagePreview);

            FilePond.registerPlugin(
                FilePondPluginFileValidateType
            );

            FilePond.setOptions({
                server: {
                    url: "{{ route('task.store') }}",
                    process: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }
                }
            });

            // Turn input element into a pond
            $('.my-pond').filepond();

            // Set allowMultiple property to true
            $('.my-pond').filepond('allowMultiple', true);

            $('.my-pond').filepond('acceptedFileTypes', ['image/*']);
        })
    </script>
@endpush
