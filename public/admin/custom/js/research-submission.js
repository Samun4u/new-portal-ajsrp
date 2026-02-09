(function($){
    "use strict";

    // Research Submission List Datatable
    $(document).ready(function () {
        dataTable('all');
    });

    $(document).on('click', '.researchStatusTab', function (e) {
        var status = $(this).data('status');
        dataTable(status);
    });

    var allResearchTable
    $(document).on('input', '#datatableSearch', function () {
        allResearchTable.search($(this).val()).draw();
    });

    function dataTable(status) {

        allResearchTable = $("#researchTable-" + status).DataTable({
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
                searchPlaceholder: "Search submissions",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            },
            ajax: {
                url: $('#research-submission-list-route').val() + '/data',
                data: function (data) {
                    data.status = status;
                }
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                { data: 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'author_name', name: 'author_name', orderable: false, searchable: true },
                { data: 'title', name: 'title', searchable: true },
                { data: 'user', name: 'user', orderable: false, searchable: true },
                { data: 'language', name: 'language', orderable: false, searchable: false },
                { data: "status", name: "status", orderable: false, searchable: false },
                { data: "certificate_status", name: "certificate_status", orderable: false, searchable: false },
                { data: "created_at", name: "created_at" },
                { data: "action", name: "action", orderable: false, searchable: false }
            ],
            order: [[7, 'desc']],
            stateSave: true,
            "bDestroy": true
        });
    }

    // Open Assign Reviewers modal
    $(document).on('click', '.assign-reviewers-btn', function () {
        const orderId = $(this).data('order-id');
        const title = $(this).data('title') || '';

        $('#assignReviewersPaperTitle').text(title);
        $('#assignReviewersModal').data('order-id', orderId);

        loadReviewerSuggestions(orderId);
        const modal = new bootstrap.Modal(document.getElementById('assignReviewersModal'));
        modal.show();
    });

    function loadReviewerSuggestions(orderId) {
        const route = $('#reviewer-matching-route').val();
        const list = $('#reviewerSuggestionsList');

        list.html('<p class="text-muted mb-0">' + window.translations?.loading_reviewer_suggestions || 'Loading suggestions…' + '</p>');

        $.ajax({
            url: route,
            method: 'GET',
            data: { order_id: orderId },
            success: function (response) {
                if (!response.success || !response.reviewers || response.reviewers.length === 0) {
                    list.html('<p class="text-muted mb-0">No reviewer suggestions found.</p>');
                    return;
                }

                let html = '';
                response.reviewers.forEach(function (r) {
                    const initials = (r.name || '').split(' ').map(p => p[0]).join('').substring(0, 2).toUpperCase();
                    const notes = (r.match_notes || []).join(' • ');
                    const tags = (r.match_notes || []).map(n => '<span class="reviewer-tag">' + n + '</span>').join('');

                    html += '' +
                        '<div class="reviewer-suggestion-card">' +
                        '  <div class="reviewer-main">' +
                        '    <div class="reviewer-avatar">' + initials + '</div>' +
                        '    <div class="reviewer-info">' +
                        '      <div class="reviewer-name">' + r.name + '</div>' +
                        '      <div class="reviewer-meta">' +
                        '        <span>' + (r.email || '') + '</span>' +
                        '        <span>' + (r.institution || '') + '</span>' +
                        '      </div>' +
                        '      <div class="reviewer-expertise">' + (r.field_of_study || '') + '</div>' +
                        '      <div class="reviewer-tags">' + tags + '</div>' +
                        '    </div>' +
                        '  </div>' +
                        '  <div class="reviewer-actions">' +
                        '    <button type="button" class="btn-assign-reviewer" data-reviewer-id="' + r.id + '">' +
                        '      <i class="fas fa-user-plus"></i> Assign' +
                        '    </button>' +
                        '    <span>' + (r.match_score || 0) + '% match</span>' +
                        '  </div>' +
                        '</div>';
                });

                list.html(html);
            },
            error: function () {
                list.html('<p class="text-danger mb-0">Failed to load reviewer suggestions.</p>');
            }
        });
    }

    // Approve button click
    let researchId = null;
    $(document).on('click', '.approve-btn', function() {
        researchId = $(this).data('id');
        $('#approveModal').modal('show');
    });

    // Reject button click
    $(document).on('click', '.reject-btn', function() {
        researchId = $(this).data('id');
        $('#rejectModal').modal('show');
    });

    // Confirm approve
    $('#confirmApprove').on('click', function() {
        const notes = $('#approve_notes').val();
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: $('#research-submission-list-route').val() + '/approve/' + researchId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                notes: notes
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#approveModal').modal('hide');
                    allResearchTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('Approve & Send Certificate');
                $('#approve_notes').val('');
            }
        });
    });

    // Confirm reject
    $('#confirmReject').on('click', function() {
        const notes = $('#reject_notes').val();

        if (!notes.trim()) {
            toastr.error('Please provide a rejection reason');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: $('#research-submission-list-route').val() + '/reject/' + researchId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                notes: notes
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#rejectModal').modal('hide');
                    allResearchTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('Reject');
                $('#reject_notes').val('');
            }
        });
    });

})(jQuery);

