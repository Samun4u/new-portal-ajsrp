(function($){
    "use strict";
    $("#clientListDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
        processing: true,
        responsive: true,
        searching: false,
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
            { data: "action", name: "action" }
        ],
    });

    $("#clientOrderHistoryTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
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
            { data: 'product_name', name: 'products.name', orderable: false, searchable: false, },
            { data: "order_id", name: "order_id" },
            { data: "gateway_name", name: "gateways.name" },
            { data: "payment_price", name: "payment_price" },
            { data: "status", name: "status" }
        ],
    });

    $("#clientInvoiceHistoryDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
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
            { data: "subscription_id", name: "subscription_id" },
            { data: 'product_name', name: 'product.name', orderable: false, searchable: false, },
            { data: "amount", name: "amount" },
            { data: "status", name: "status" },
        ],
    });
})(jQuery);
