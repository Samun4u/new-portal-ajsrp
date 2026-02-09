(function ($) {
    "use strict";
    $("#recentOpenTicketHistoryList").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#recent-open-history-route').val(),
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
            {data: 'userInfo', name: 'userInfo', orderable: false, searchable: false,},
            {data: "ticket_id", name: "ticket_id"},
            {data: "order_id", name: "order_id"},
            {data: "priorityStatus", name: "priorityStatus"}
        ],
    });

    $(document).ready(function () {
        $("#recentOrderHistoryDashboard").DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: false,
            processing: true,
            responsive: true,
            searching: false,
            retrieve: true,
            destroy: true,
            ajax: $('#recent-open-order-route').val(),
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
                {data: "order_id", name: "order_id"},
                {data: "userInfo", name: "userInfo"},
                {data: "paymentInfo", name: "paymentInfo"}
            ],
        });

    });


    $(document).ready(function () {
        commonAjax('GET', $('#revenue-overview-chart-data-route').val(), revenueOverviewChartDataRes, revenueOverviewChartDataRes);
        commonAjax('GET', $('#client-overview-chart-data-route').val(), clientOverviewChartDataRes, clientOverviewChartDataRes);
    });

    function revenueOverviewChartDataRes(response) {
        if (response.status == true) {
            var monthList = [];
            var dataList = [];
            $.each(response.data, function (index, item) {
                monthList.push(index);
                dataList.push(item);
            });

            console.log();

            //revenue overview
            var options = {
                chart: {
                    height: 350,
                    type: "area",
                    toolbar: {
                        show: false,
                    },
                },
                stroke: {
                    width: 2.5,
                    curve: "straight",
                },
                tooltip: {
                    enabled: false,
                },
                colors: ["#007AFF"],
                dataLabels: {
                    enabled: false,
                },
                series: [
                    {
                        name: "Series 1",
                        data: dataList,
                    },
                ],
                fill: {
                    type: "gradient",
                    gradient: {
                        gradientToColors: ["#007AFF"],
                        shadeIntensity: 1,
                        type: "vertical",
                        opacityFrom: 1,
                        opacityTo: 0.5,
                        stops: [0, 100],
                    },
                },
                xaxis: {
                    categories: monthList,
                    tickPlacement: "on",
                    min: undefined,
                    max: undefined,
                    axisTicks: {
                        show: true,
                        borderType: "solid",
                        color: "#F0F0F0",
                        height: 13,
                    },
                    labels: {
                        style: {
                            cssClass: "revenueOverviewChart-xaxis-label",
                        },
                    },
                },
                yaxis: {
                    tickAmount: 5,
                    decimalsInFloat: 1,
                    min: 0,
                    labels: {
                        style: {
                            cssClass: "revenueOverviewChart-yaxis-label",
                        },
                    },
                },
            };
            var z_revenueOverviewChart = new ApexCharts(document.querySelector("#revenueOverviewChart"), options);
            z_revenueOverviewChart.render();
        }

    }

    function clientOverviewChartDataRes(response) {
        if (response.status == true) {
            var monthList = [];
            var dataList = [];
            $.each(response.data, function (index, item) {
                monthList.push(index);
                dataList.push(item);
            });


            //client overview
            const ctx = document.getElementById("myChart");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: monthList,
                    datasets: [
                        {
                            backgroundColor: "#7A5AF820",
                            borderRadius: 50,
                            hoverBackgroundColor: "#7a5af8",
                            data: dataList,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: 0,
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            stacked: true,
                            grid: {
                                display: true,
                                color: "#F2F2F290",
                            },
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        }
    }

})(jQuery);
