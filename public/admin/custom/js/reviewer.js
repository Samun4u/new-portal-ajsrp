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
            { data: 'user_name', name: 'user_name', orderable: false, searchable: false, },
            { data: "user_email", name: "user_email" },
            { data: "company_name", name: "company_name" },
            { data: "status", name: "status" },
            { data: "action", name: "action" }
        ],
    });

    $("#clientOrderHistoryTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#client-order-history-route').val(),
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
            { data: "order_id", name: "order_id" },
            // { data: 'service_name', name: 'service_name' },
            { data: "total", name: "total" },
            { data: "transaction_amount", name: "transaction_amount" },
            { data: "working_status", name: "working_status" },
            { data: "payment_status", name: "payment_status" }
        ],
    });

    $("#clientActivityLogHistoryDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#client-activity-log-history-route').val(),
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
            { data: "action", name: "action" },
            { data: "source", name: "source" },
            { data: "ip_address", name: "ip_address" },
            { data: "location", name: "location" },
            { data: "created_at", name: "created_at" }
        ],
    });

    $("#clientInvoiceHistoryDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#client-invoice-history-route').val(),
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
            { data: "invoice_id", name: "invoice_id" },
            { data: "order_name", name: "order_name" },
            { data: "gateway_name", name: "gateway_name" },
            { data: "total", name: "total" },
            { data: "created_at", name: "created_at" },
            { data: "status", name: "status" },
        ],
        columnDefs: [
            {
                targets: 4, // Assuming "created_at" is the first column (index 0)
                render: function(data, type, row) {
                    // Format date using moment.js, adjust format as needed
                    return moment(data).format('YYYY-MM-DD HH:mm:ss');
                }
            }
        ]
    });
})(jQuery);
