(function ($) {
    ("use strict");
    var allTeamTable
    $(document).on('input', '#teamMemberSearch', function () {
        allTeamTable.search($(this).val()).draw();
    });

    allTeamTable = $("#allTeamTable").DataTable({
        pageLength: 10,
        ordering: false,
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
        ajax: $('#teamMemberIndexRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "name", "name": "users.name" },
            { "data": "email", "name": "users.email" },
            { "data": "role", "name": "users.role" },
            { "data": "designation" },
            { "data": "action" }
        ]
    });
})(jQuery);
