@extends('layouts.dashboardapp')

@push('extrastyle')
    <!-- Date picker plugins css -->
    <link href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!--alerts CSS -->
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-style.css') }}" type="text/css">
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">All Items</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="active">All Items</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="table-responsive">
                    <table id="items-datatable" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Descriptions</th>
                                <th>Manufacture Date</th>
                                <th>Images</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Descriptions</th>
                                <th>Manufacture Date</th>
                                <th>Images</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <!--  modal content -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modal-heading"
        aria-hidden="true" id="item-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal-heading">Item</h4>
                </div>
                <div class="modal-body">
                    <div class="itemform-container">
                        <form id="item-form" action="{{ route('item.create') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="itemid" id="itemid">
                            <div class="form-body">
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title" class="control-label">Title</label>
                                            <input type="text" id="title" name="title" class="form-control"
                                                placeholder="Item Title" value="{{ old('title') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="datepicker_container">
                                            <label for="manufacture_date" class="control-label">Manufacture Date</label>
                                            <input type="text" class="form-control datepicker-taskdate"
                                                id="manufacture_date" value="{{ old('manufacture_date') }}"
                                                name="manufacture_date" placeholder="mm/dd/yyyy"
                                                data-date-container='#datepicker_container'>
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <!--row-->
                                <div class="row">
                                    <!--col-->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="control-label">Description</label>
                                            <textarea id="description" name="description">{{ old('description') }}</textarea>
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                    <!--/col-->
                                </div>
                                <!--/row-->
                                <!--row-->
                                <div class="row">
                                    <!--col-->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="images" class="control-label">Images upload</label>
                                            <input type="file" class="form-control" name="images[]" id="images" multiple>
                                            <span class="help-block error-span"></span>
                                        </div>
                                        <div class="images-selected mb-5">
                                            <h5 class="text-capitalize font-weight-bold text-secondary">Selected Images:
                                            </h5>
                                            <div class="removeimage-boxes" id="imagelist"></div>
                                        </div>
                                    </div>
                                    <!--/col-->
                                </div>
                                <!--/row-->
                            </div>
                            <div class="form-actions">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                    <button type="button" class="btn btn-primary" id="update-item">Update Item</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!--  Show modal content -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modal-heading"
        aria-hidden="true" id="item-show-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-white" id="modal-heading">Item</h4>
                </div>
                <div class="modal-body">
                    <div class="itemview-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@push('extrascript')
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script> --}}
    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/main-custom.js') }}"></script>
@endpush
