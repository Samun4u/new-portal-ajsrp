(function($){
    "use strict";
    var clientDataTableSerch
    $(document).on('input', '#ClientDatatableSearch', function () {
        clientDataTableSerch.search($(this).val()).draw();
    });
    clientDataTableSerch =  $("#clientListDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
        processing: true,
        responsive: true,
        searching: true,
        ajax: $('#client-list-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { data: 'sl_no', name: 'sl_no', orderable: false, searchable: false, },
            { data: "title", name: "title" },
            { data: "slug", name: "slug" },
            { data: "role_id", name: "role_id" },
            { data: "status", name: "status" },
            { data: "action", name: "action" }
        ],
    });


    // $('select[name="client_order_id"]').on('change', function () {
    //     var orderId = $(this).val();
    //     var requestType = $("#finalCertificateType").val();
    //     if (orderId !== '') {
    //         $.ajax({
    //             url: $('#orderDetailsUrl').val(),
    //             type: 'GET',
    //             data: { order_id: orderId, type: requestType },
    //             success: function (response) {
    //                 console.log(response);
    //                 $('#addAuthorName').val(response.author_names);
    //                 $('#addAuthorAffiliations').val(response.author_affiliations);
    //                 $('#addPaperTitle').val(response.paper_title);
    //                 $('#addJournalName').val(response.journal_name);
    //                 $('#addVolume').val('');
    //                 $('#addIssue').val('');
    //                 $('#addDate').val('');
    //             },
    //             error: function () {
    //                 alert('Something went wrong. Please try again.');
    //             }
    //         });
    //     } else {
    //         // clear fields if no order selected
    //         $('#addAuthorName').val('');
    //         $('#addAuthorAffiliations').val('');
    //         $('#addPaperTitle').val('');
    //         $('#addJournalName').val('');
    //         $('#addVolume').val('');
    //         $('#addIssue').val('');
    //         $('#addDate').val('');
    //     }
    // });

})(jQuery);
