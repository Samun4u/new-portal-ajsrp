(function($){
    "use strict";

    // Reviewer Application List Datatable
    $(document).ready(function () {
        dataTable('all');
    });

    $(document).on('click', '.applicationStatusTab', function (e) {
        var status = $(this).data('status');
        dataTable(status);
    });

    var allApplicationTable
    $(document).on('input', '#datatableSearch', function () {
        allApplicationTable.search($(this).val()).draw();
    });

    function dataTable(status) {

        allApplicationTable = $("#applicationTable-" + status).DataTable({
            pageLength: 10,
            ordering: true,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>",
                },
                searchPlaceholder: "Search applications",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            },
            ajax: {
                url: $('#reviewer-application-list-route').val(),
                data: function (data) {
                    data.status = status;
                }
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                { data: 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'full_name', searchable: true },
                { data: 'email', name: 'email', searchable: true },
                { data: 'institution_country', name: 'institution', searchable: true },
                { data: 'field', name: 'field_of_study', searchable: true },
                { data: 'experience', name: 'experience_years', orderable: true },
                { data: "status_badge", name: "status", orderable: false },
                { data: "submitted_at", name: "created_at" },
                { data: "action", name: "action", orderable: false, searchable: false }
            ],
            order: [[7, 'desc']],
            stateSave: true,
            "bDestroy": true
        });
    }

    // Approve button click
    let applicationId = null;
    $(document).on('click', '.approve-application-btn', function() {
        applicationId = $(this).data('id');
        $('#approveModal').modal('show');
    });

    // Reject button click
    $(document).on('click', '.reject-application-btn', function() {
        applicationId = $(this).data('id');
        $('#rejectModal').modal('show');
    });

    // Confirm approve
    $('#confirmApprove').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: $('#reviewer-application-list-route').val() + '/approve/' + applicationId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#approveModal').modal('hide');
                    allApplicationTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-user-check"></i> Approve & Create Account');
            }
        });
    });

    // Confirm reject
    $('#confirmReject').on('click', function() {
        const reason = $('#reject_reason').val();

        if (!reason.trim() || reason.trim().length < 10) {
            toastr.error('Please provide a detailed rejection reason (minimum 10 characters)');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: $('#reviewer-application-list-route').val() + '/reject/' + applicationId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                reason: reason
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#rejectModal').modal('hide');
                    allApplicationTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('Reject Application');
                $('#reject_reason').val('');
            }
        });
    });

})(jQuery);

