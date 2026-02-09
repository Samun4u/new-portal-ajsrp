(function( $ ){
    "use strict";
    $("#ticketSummery").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#ticket-summery-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder:  window.translations.searchPlaceholder || "Search event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            emptyTable: window.translations.emptyTable || "No Data Available In Table",
            info: window.translations.info || "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: window.translations.info || "Showing 0 to 0 of 0 entries"
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {data: "ticket_id", name: "ticket_id"},
            {data: "order_id", name: "order_id"},
            {data: "status", name: "status"}
        ],
    });

    $(document).ready(function () {
        $("#orderSummery").DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: false,
            processing: true,
            responsive: true,
            searching: false,
            retrieve: true,
            destroy: true,
            ajax: $('#order-summery-route').val(),
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>",
                },
                searchPlaceholder:  window.translations.searchPlaceholder || "Search event",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
                emptyTable: window.translations.emptyTable || "No Data Available In Table",
                info: window.translations.info || "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: window.translations.info || "Showing 0 to 0 of 0 entries"
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                {data: "order_id", name: "order_id"},
                {data: "workingStatus"},
                {data: "paymentStatus"}
            ],
        });

    });
})(jQuery);
