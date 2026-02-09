(function ($) {
    "use strict";

    $("#userTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: false,
        responsive: true,
        ajax: $('#userTable-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search pending event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "name", "name": "name",responsivePriority:1},
            {"data": "email", "name": "email"},
            {"data": "packagesName", "name": "package_name"},
            {"data": "created_at", "name": "created_at"},
            {"data": "country", "name": "country"},
            {"data": "status", "name": "status"},
            {"data": "action", "name": "action"},
        ],
    });

    // activityDataTable js
    $('#activityDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searing: true,
        responsive:true,
        ajax: $('#user-activity-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search pending event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "action"},
            {"data": "source"},
            {"data": "ip_address"},
            {"data": "location"},
            {"data": "created_at"}
        ]
    });


  // packages history DataTable js
    $('#packagesHistoryDatatable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searing: true,
        responsive:true,
        ajax: $('#user-packages-history-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search pending event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "name", "name":"name"},
            {"data": "start_date", "name" : "start_date"},
            {"data": "end_date" , "name" : "end_date"},
            {"data": "payment_status", "name" : "payment_status"},
            {"data": "status" ,"name" : "status"}
        ]
    });

})(jQuery)
