jQuery(document).ready(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    if ($("#removeimage").length > 0 || $("#items-datatable").length > 0) {
        $(document).on('click', '#removeimage', function () {
            $(this).parent().remove();
            if ($.trim($(".removeimage-boxes").html()) == '') {
                $('.images-selected').hide();
            }
        });
    }

    if ($("#delete-taskfromshow-btn").length > 0) {
        $(document).on('click', '#delete-taskfromshow-btn', function (e) {
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
                function () {
                    trbutton.closest('tr').remove();
                    $.ajax({
                        type: "DELETE",
                        url: "/admin/task/" + id,
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function (data) {
                            swal("Deleted!", data.success,
                                "success");
                            if ($('#items-table tbody tr').length == false) {
                                $('#items-table').remove();
                                $('.table-container-ctm').append(
                                    '<div class="alert alert-warning">Task not found.</div>'
                                )
                            }
                            window.location.href = "/admin/task";
                        }
                    });
                });
        });
    }

    if ($("#items-table").length > 0) {
        getPagination('#items-table');

        function getPagination(table) {
            var lastPage = 1;

            $('#maxRows')
                .on('change', function (evt) {
                    //$('.paginationprev').html('');						// reset pagination

                    lastPage = 1;
                    $('.pagination')
                        .find('li')
                        .slice(1, -1)
                        .remove();
                    var trnum = 0; // reset tr counter
                    var maxRows = parseInt($(this).val()); // get Max Rows from select option
                    var totalRows = $(table + ' tbody tr').length; // numbers of rows

                    if (totalRows < 6) {
                        $('.pageshow-count div').hide();
                    } else {
                        $('.pageshow-count div').show();
                    }

                    if (maxRows == 5000 || maxRows >= totalRows) {
                        $('.pagination').hide();
                    } else {
                        $('.pagination').show();
                    }

                    $(table + ' tr:gt(0)').each(function () {
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
                    $('.pagination li').on('click', function (evt) {
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
                        $(table + ' tr:gt(0)').each(function () {
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
    }

    if ($("#delete-task-btn").length > 0) {
        $(document).on('click', '#delete-task-btn', function (e) {
            e.preventDefault();
            var trbutton = $(this);
            let id = $(this).data('id');

            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this task data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function () {
                    trbutton.closest('tr').remove();
                    $.ajax({
                        type: "DELETE",
                        url: "task/" + id,
                        data: {
                            "id": id
                        },
                        success: function (data) {
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
        });
    }

    if ($("#items-datatable").length > 0) {

        // fot export buttons
        // $('#items-datatable').DataTable({
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel', 'pdf', 'print'
            //     ]
            // });

        var modal = $('#item-modal'),
            modalShowItem = $('#item-show-modal'),
            modalHeading = $('#modal-heading'),
            itemForm = $('#item-form'),
            errorSpan = $('.error-span'),
            itemIdInput = $('#itemid'),
            addBtn = $('#add-item'),
            updateBtn = $('#update-item'),
            divImages = $('.images-selected'),
            divViewItem = $('.itemview-container'),
            imagePath = "/image/",
            table = $('#items-datatable').DataTable({
                processing: true,
                serverSide: true,
                dom: 'lBfrtip',
                buttons: [{
                    text: '<span>Add New</span> <i class="fa fa-plus-circle m-l-5"></i>',
                    className: 'btn btn-info waves-effect waves-light',
                    action: function (e, dt, node, config) {
                        itemIdInput.val('');
                        errorSpan.text('');
                        modal.modal('show');
                        itemForm.find('input').parent().removeClass('has-error');
                        itemForm.find('textarea').parent().removeClass('has-error');
                        itemForm[0].reset();
                        modalHeading.text('Add New Item');
                        updateBtn.hide();
                        divImages.hide();
                        addBtn.show();
                    }
                }],
                ajax: "/admin/item",
                columns: [{
                        data: 'id',
                        name: 'id',
                        'visible': false,
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        width: '50px',
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        width: '150px',
                        "render": function (data, type, full, meta) {
                            return '<a href="javascript:void(0)" class="font-weight-bold text-dark" id="item-show" data-itemid="' +
                                full.id + '">' + data + '</a>';
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'manufacture_date',
                        name: 'manufacture_date',
                        "render": function (data, type, full, meta) {
                            var date = new Date(data);
                            return (('0' + (date.getMonth() + 1)).slice(-2) + "/" + ('0' + date
                                .getDate()).slice(-2) + "/" + +date.getFullYear());
                        },
                        width: '100px',
                    },
                    {
                        data: 'images',
                        name: 'images',
                        render: function (data, type, full, meta) {
                            var dataClean = $.parseJSON(data.replaceAll("&quot;", "\""));
                            var imageHtml = '';
                            $.each(dataClean, function (key, value) {
                                imageHtml += '<img src="' + imagePath + '/' + value
                                    .replaceAll(
                                        "\"", "") +
                                    '" width="60" height="auto" class="ml-2 mb-2" />';
                            });
                            return imageHtml;
                        },
                        orderable: false,
                        width: '150px',
                        searchable: false
                    },
                    {
                        data: null,
                        name: 'action',
                        render: function (data, type, full, meta) {
                            var html =
                                '<h4><a href="javascript:void(0)" class="text-info m-1 mt-0" id="edit-item" data-itemid="' +
                                data.id +
                                '"><i class="ti-pencil-alt"></i></a><a href="javascript:void(0)" class="text-danger m-1 mt-0" data-itemid="' +
                                data.id + '" id="delete-item"><i class="ti-trash"></i></a></h4>';
                            return html;
                        },
                        // className: "dt-center editor-edit",
                        // defaultContent: '<i class="bi bi-pencil-square"></i>',
                        width: '140px',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

        addBtn.click(function (e) {
            e.preventDefault();
            var formData = new FormData(itemForm[0]);
            formData.append('description', tinymce.get("description").getContent());
            errorSpan.text('');
            itemForm.find('input').parent().removeClass('has-error');
            itemForm.find('textarea').parent().removeClass('has-error');
            $.ajax({
                url: "/admin/item/create",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    itemForm[0].reset();
                    table.draw();
                    modal.modal('hide');
                    $.toast({
                        heading: response.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                },
                error: function (response) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        if (key.indexOf('images.') != -1) {
                            $('#' + key.split('.')[0]).parent().find('.error-span')
                                .text(value.join(' and ').replaceAll(key,
                                    "images"));
                            $('#' + key.split('.')[0]).parent().addClass(
                                'has-error');
                        } else {
                            $('#' + key).parent().find('.error-span').text(value);
                            $('#' + key).parent().addClass('has-error');
                        }
                    });
                }
            })
        })

        $(document).on('click', '#edit-item', function (e) {
            e.preventDefault();
            updateBtn.show();
            addBtn.hide();
            modal.modal();
            modalHeading.text('Edit Item');
            divImages.show();
            itemIdInput.val($(this).data('itemid'))
            errorSpan.text('');
            itemForm.find('input').parent().removeClass('has-error');
            itemForm.find('textarea').parent().removeClass('has-error');
            var itemData = table.row($(this).parents('tr')).data();
            $.each(itemData, function (key, value) {
                if (key == 'images') {
                    var dataCleanImages = $.parseJSON(value);
                    var imageHtml = '';
                    $.each(dataCleanImages, function (key, value) {
                        imageHtml +=
                            "<div class='removeimage-container d-inline-flex'><input type='hidden' class='selectedimageinput' name='selectedimageinput[]' value='" +
                            value + "'>";
                        imageHtml += '<img src="' + imagePath + '/' + value.replaceAll(
                                "\"",
                                "") +
                            '" width="100" height="auto" class="m-1"><i class="fa fa-times-circle text-danger" id="removeimage"></i></div>';
                    });
                    $('#imagelist').empty().append(imageHtml);
                } else if (key == 'description') {
                    tinyMCE.get('description').setContent(value)
                } else if (key == 'manufacture_date') {
                    var date = new Date(value);
                    var dateFormated = ('0' + (date.getMonth() + 1)).slice(-2) + "/" + ('0' +
                        date.getDate()).slice(-2) + "/" + +date.getFullYear();
                    itemForm.find('#' + key).val(dateFormated);
                } else {
                    itemForm.find('#' + key).val(value);
                }
            })
        });

        updateBtn.click(function (e) {
            e.preventDefault();
            var formData = new FormData(itemForm[0]);
            formData.append('description', tinymce.get("description").getContent());
            errorSpan.text('');
            itemForm.find('input').parent().removeClass('has-error');
            itemForm.find('textarea').parent().removeClass('has-error');

            $.ajax({
                url: "/admin/item/update",
                data: formData,
                method: 'POST',
                contentType: false,
                processData: false,
                success: function (response) {
                    itemForm[0].reset();
                    table.draw();
                    modal.modal('hide');
                    $.toast({
                        heading: response.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                },
                error: function (response) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        if (key.indexOf('images.') != -1) {
                            $('#' + key.split('.')[0]).parent().find('.error-span')
                                .text(value.join(' and ').replaceAll(key,
                                    "images"));
                            $('#' + key.split('.')[0]).parent().addClass(
                                'has-error');
                        } else {
                            $('#' + key).parent().find('.error-span').text(value);
                            $('#' + key).parent().addClass('has-error');
                        }
                    });
                }
            })
        })

        $(document).on('click', '#item-show', function (e) {
            e.preventDefault();
            modalShowItem.modal();
            var itemData = table.row($(this).parents('tr')).data();
            modalShowItem.find('.modal-title').text('Item : ' + itemData.title);
            divViewItem.empty();
            var date = new Date(itemData.manufacture_date),
                yr = date.getFullYear(),
                month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
                day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
                newDate = month + '/' + day + '/' + yr;
            divViewItem.append('<p><i class="ti-calendar"></i> ' + newDate + '</p>');
            divViewItem.append('<h3>Description:</h3> ' + itemData.description);
            var dataCleanImages = $.parseJSON(itemData.images);
            var imageHtml = '';
            $.each(dataCleanImages, function (key, value) {
                imageHtml += '<img src="' + imagePath + '/' + value.replaceAll(
                        "\"", "") +
                    '" width="100" height="auto" class="m-1" />';
            });
            divViewItem.append('<h3>Images:</h3> ' + imageHtml);
        })

        $(document).on('click', '#delete-item', function (e) {
            e.preventDefault();
            let itemId = $(this).data('itemid');
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this task data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: "DELETE",
                        url: "/admin/item/delete",
                        data: {
                            "id": itemId
                        },
                        success: function (data) {
                            swal("Deleted!", data.success,
                                "success");
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            );
        });
    }
});
