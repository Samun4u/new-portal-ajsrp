(function ($) {
    ("use strict");

    $(document).ready(function () {
        dataTable('all');
    });

    $(document).on('click', '.ticketStatusTab', function (e) {
        var status = $(this).data('status');
        dataTable(status);
    });

    var allTeamTable
    $(document).on('input', '#datatableSearch', function () {
        allTeamTable.search($(this).val()).draw();
    });

    function dataTable(status) {

        allTeamTable = $("#ticketTable-" + status).DataTable({
            pageLength: 10,
            ordering: true,
            serverSide: false,
            processing: true,
            responsive: true,
            searching: true,
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>",
                },
                searchPlaceholder: "Search event",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            },
            ajax: {
                url: $('#ticketListRoute').val(),
                data: function (data) {
                    data.status = status;
                }
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                {"data": "ticket_id", "name": "ticket.ticket_id", orderable: false},
                {"data": "order_id", "name": "ticket.order_id", orderable: false},
                {"data": "client", orderable: false},
                {"data": "priority" ,orderable: false},
                {"data": "assignee", orderable: false},
                {"data": "status", orderable: true},
                {
                    "data": "updated_at", 
                    "orderable": true,
                    "render": function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return $(data).data('timestamp');
                        }
                        return data;
                    }
                },
                {"data": "action", orderable: false}
            ],
            order: [[6, 'desc']],
            stateSave: true,
            "bDestroy": true
        });
    }

    $(document).on('click', '.assign-member', function (e) {
        var checkedStatus = 0;
        if ($(this).prop('checked') == true) {
            checkedStatus = 1;
        }
        commonAjax('GET', $('#assignMemberRoute').val(), assigneeResponse, assigneeResponse, {
            'member_id': $(this).val(),
            'checked_status': checkedStatus,
            'ticket_id': $(this).data('ticket'),
            'data_table': $(this).data('table')
        });

    });

    function assigneeResponse(response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message']);
            $('#ticketTable-' + response.data.datatable).DataTable().ajax.reload();
        } else {
            commonHandler(response)
        }
    }

    $(document).on('click', '.file-delete', function (e) {
        $(this).parent().remove();
    });

    $(document).on('click', '.ticket-status-change', function (e) {
        Swal.fire({
            title: 'Sure! You want to change status?',
            text: "You Are Going To Change Ticket Status!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change It!'
        }).then((result) => {
            if (result.value) {
                commonAjax('GET', $('#statusChangeRoute').val(), statusChangeResponse, statusChangeResponse, {
                    'status': $(this).val(),
                    'ticket_id': $(this).data('ticket'),
                });
            }else{
                $(".status"+$(this).data('status')).prop("checked", true);
            }
        })


    });

    function statusChangeResponse(response){
        if (response['status'] === true) {
            toastr.success(response['message']);
        } else {
            commonHandler(response)
        }
    }


})(jQuery);
