(function ($) {
    ("use strict");
    var allTeamTable
    allTeamTable = $("#activityLogDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#activity-log-history-route').val(),
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
            { data: "source", name: "source" , className: "text-center" },
            { data: "ip_address", name: "ip_address", className: "text-center" },
            { data: "location", name: "location" },
            { data: "created_at", name: "created_at" }
        ]
    });
})(jQuery);
