@extends('layouts.dashboardapp')

@push('extrastyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <style>
        h2.jq-toast-heading {
            font-family: 'Poppins';
            margin: 0;
            margin-left: 10px;
        }

        .jq-toast-wrap {
            width: 300px;
        }

        span.close-jq-toast-single {
            top: 50%;
            transform: translateY(-50%);
            font-size: 22px;
        }
    </style>
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
                        <div class="form-group">
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
    <script type="text/javascript">
        $(document).ready(function() {
            getPagination('#items-table');

            function getPagination(table) {
                var lastPage = 1;

                $('#maxRows')
                    .on('change', function(evt) {
                        //$('.paginationprev').html('');						// reset pagination

                        lastPage = 1;
                        $('.pagination')
                            .find('li')
                            .slice(1, -1)
                            .remove();
                        var trnum = 0; // reset tr counter
                        var maxRows = parseInt($(this).val()); // get Max Rows from select option
                        var totalRows = $(table + ' tbody tr').length; // numbers of rows

                        if(totalRows < 5){
                            $('.pageshow-count div').hide();
                        }

                        if (maxRows == 5000 || maxRows >= totalRows) {
                            $('.pagination').hide();
                        } else {
                            $('.pagination').show();
                        }

                        $(table + ' tr:gt(0)').each(function() {
                            // each TR in  table and not the header
                            trnum++; // Start Counter
                            if (trnum > maxRows) {
                                // if tr number gt maxRows

                                $(this).hide(); // fade it out
                            }
                            if (trnum <= maxRows) {
                                $(this).show();
                            } // else fade in Important in case if it ..
                        }); //  was fade out to fade it in
                        if (totalRows > maxRows) {
                            // if tr total rows gt max rows option
                            var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
                            //	numbers of pages
                            for (var i = 1; i <= pagenum;) {
                                // for each page append pagination li
                                $('.pagination #prev')
                                    .before(
                                        '<li data-page="' +
                                        i +
                                        '">\
                                                								  <span>' +
                                        i++ +
                                        '<span class="sr-only">(current)</span></span>\
                                                								</li>'
                                    )
                                    .show();
                            } // end for i
                        } // end if row count > max rows
                        $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
                        $('.pagination li').on('click', function(evt) {
                            // on click each page
                            evt.stopImmediatePropagation();
                            evt.preventDefault();
                            var pageNum = $(this).attr('data-page'); // get it's number

                            var maxRows = parseInt($('#maxRows')
                                .val()); // get Max Rows from select option

                            if (pageNum == 'prev') {
                                if (lastPage == 1) {
                                    return;
                                }
                                pageNum = --lastPage;
                            }
                            if (pageNum == 'next') {
                                if (lastPage == $('.pagination li').length - 2) {
                                    return;
                                }
                                pageNum = ++lastPage;
                            }

                            lastPage = pageNum;
                            var trIndex = 0; // reset tr counter
                            $('.pagination li').removeClass(
                                'active'); // remove active class from all li
                            $('.pagination [data-page="' + lastPage + '"]').addClass(
                                'active'); // add active class to the clicked
                            // $(this).addClass('active');					// add active class to the clicked
                            limitPagging();
                            $(table + ' tr:gt(0)').each(function() {
                                // each tr in table not the header
                                trIndex++; // tr index counter
                                // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
                                if (
                                    trIndex > maxRows * pageNum ||
                                    trIndex <= maxRows * pageNum - maxRows
                                ) {
                                    $(this).hide();
                                } else {
                                    $(this).show();
                                } //else fade in
                            }); // end of for each tr in table
                        }); // end of on click pagination list
                        limitPagging();
                    })
                    .val(5)
                    .change();

                // end of on select change

                // END OF PAGINATION
            }

            function limitPagging() {
                // alert($('.pagination li').length)

                if ($('.pagination li').length > 7) {
                    if ($('.pagination li.active').attr('data-page') <= 3) {
                        $('.pagination li:gt(5)').hide();
                        $('.pagination li:lt(5)').show();
                        $('.pagination [data-page="next"]').show();
                    }
                    if ($('.pagination li.active').attr('data-page') > 3) {
                        $('.pagination li:gt(0)').hide();
                        $('.pagination [data-page="next"]').show();
                        for (let i = (parseInt($('.pagination li.active').attr('data-page')) - 2); i <= (parseInt($(
                                '.pagination li.active').attr('data-page')) + 2); i++) {
                            $('.pagination [data-page="' + i + '"]').show();

                        }

                    }
                }
            }
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
                            url: "task/" + id,
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
                            }
                        });
                    });
            })
        });
    </script>
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
