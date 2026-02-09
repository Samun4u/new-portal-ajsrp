(function ($) {
    "use strict";

    window.dt = new DataTransfer();

    var Medi = {
        init: function () {
            this.Basic.init();
        },

        Basic: {
            init: function () {
                this.Preloader();
                this.Tools();
                this.PopupGallery();
                this.BackgroundImage();
                this.MobileMenu();
                this.TabTable();
                this.Select();
                this.Editor();
                // this.Z_Chart();
                // this.Z_Chart_2();
                this.DateRangePicker();
                this.Message();
                this.FilUpLoad();
                this.Animation();
                this.SortForm();
                this.PassShowHide();
                this.LandingPriceToggle();
                this.LandingTestimonial();
                this.DateCalendar();
                // this.TaskProgress();
            },
            TaskProgress: function () {
                const progress = document.querySelector(".taskProgress");
                const progressNumber =
                    document.getElementById("taskProgress-txt");

                progress.addEventListener("input", function () {
                    const value = this.value;
                    // this.style.background = `linear-gradient(to right, #82CFD0 0%, #82CFD0 ${value}%, #fff ${value}%, white 100%)`;
                    progressNumber.textContent = `${value}%`;
                });
            },
            DateCalendar: function () {
                $(".datepicker").datepicker({
                    format: "mm/dd/yyyy",
                    todayHighlight: true,
                    defaultViewDate: "today",
                });
            },
            Preloader: function () {
                $("#preloader-status").fadeOut();
                $("#preloader").delay(350).fadeOut("slow");
                $("body").delay(350);
            },
            Tools: function () {
                // Calendar icon
                $("input.date-time-picker").each(function () {
                    $(this)
                        .closest(".primary-form-group-wrap")
                        .addClass("calendarIcon"); // Add your custom class here
                });

                // Sidebar menu background
                $(document).ready(function () {
                    function checkScreenSize() {
                        var $sidebar = $(".zSidebar");
                        if ($(window).width() < 1024) {
                            // $sidebar.removeAttr("data-aos data-aos-duration");
                            $sidebar.attr({
                                "data-aos": "",
                                "data-aos-duration": "",
                            });
                        } else {
                            $sidebar.attr({
                                "data-aos": "fade-right",
                                "data-aos-duration": "1000",
                            });
                        }
                    }

                    // Initial check on page load
                    checkScreenSize();

                    // Listen for window resize events
                    $(window).resize(function () {
                        checkScreenSize();
                    });
                });

                // Checkout page payment
                $(document).ready(function () {
                    $(".checkoutPaymentItem li button").click(function () {
                        $(".checkoutPaymentItem li button").removeClass(
                            "active"
                        );
                        $(this).addClass("active");
                    });
                });

                // Landing page Header
                jQuery(window).on("scroll", function () {
                    if (jQuery(window).scrollTop() > 250) {
                        jQuery(".landing-header").addClass("sticky-on");
                    } else {
                        jQuery(".landing-header").removeClass("sticky-on");
                    }
                });
            },
            PopupGallery: function () {
                $(".sf-popup-gallery").each(function () {
                    $(this).magnificPopup({
                        delegate: "a",
                        type: "image",
                        showCloseBtn: false,
                        preloader: false,
                        gallery: {
                            enabled: true,
                        },
                        // callbacks: {
                        //   elementParse: function (item) {
                        //     if (item.el[0].className == "video") {
                        //       item.type = "iframe";
                        //     } else {
                        //       item.type = "image";
                        //     }
                        //   },
                        // },
                    });
                });
            },
            BackgroundImage: function () {
                $("[data-background]").each(function () {
                    $(this).css(
                        "background-image",
                        "url(" + $(this).attr("data-background") + ")"
                    );
                });
            },
            MobileMenu: function () {
                $(".mobileMenu").on("click", function () {
                    $(".zSidebar").addClass("menuClose");
                });
                $(".zSidebar-overlay").on("click", function () {
                    $(".zSidebar").removeClass("menuClose");
                });
                // Menu arrow
                $(".zSidebar-menu li a").each(function () {
                    if (
                        $(this).next("div").find("ul.zSidebar-submenu li")
                            .length > 0
                    ) {
                        $(this).addClass("has-subMenu-arrow");
                    }
                });
            },
            TabTable: function () {
                $(document).on(
                    "shown.bs.tab",
                    'button[data-bs-toggle="tab"]',
                    function (event) {
                        $($.fn.dataTable.tables(true))
                            .DataTable()
                            .responsive.recalc();
                        $($.fn.dataTable.tables(true)).css("width", "100%");
                        $($.fn.dataTable.tables(true))
                            .DataTable()
                            .columns.adjust()
                            .draw();
                    }
                );
            },
            EventPayMent: function () {
                $(".paymentItem").on("click", function () {
                    $(".paymentItem-input").prop("checked", false);
                    $(this).find(".paymentItem-input").prop("checked", true);
                });
            },
            Select: function () {
                // when need select with search field
                $(".sf-select").select2({
                    dropdownCssClass: "sf-select-dropdown",
                    selectionCssClass: "sf-select-section",
                });
                // when don't need search field but can't use in modal
                $(".sf-select-two").select2({
                    dropdownCssClass: "sf-select-dropdown",
                    selectionCssClass: "sf-select-section",
                    minimumResultsForSearch: -1,
                    placeholder: {
                        text: "Select an option",
                    },
                });
                // when don't need search field and can use in modal
                $(".sf-select-without-search").niceSelect();
                // when need search in modal
                $(".sf-select-modal").select2({
                    dropdownCssClass: "sf-select-dropdown",
                    selectionCssClass: "sf-select-section",
                    dropdownParent: $(".modal"),
                });
            },
            Editor: function () {
                $(".summernoteOne").summernote({
                    placeholder: "Write description...",
                    tabsize: 2,
                    minHeight: 183,
                    toolbar: [
                        // ["style", ["style"]],
                        // ["view", ["undo", "redo"]],
                        // ["fontname", ["fontname"]],
                        // ["fontsize", ["fontsize"]],
                        // ["font", ["bold", "italic", "underline"]],
                        // ["para", ["ul", "ol", "paragraph"]],
                        // ["color", ["color"]],
                        ["font", ["bold", "italic", "underline"]],
                        ["para", ["ul", "ol", "paragraph"]],
                    ],
                });
            },
            Z_Chart: function () {
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
                            data: [0.4, 0.55, 0.1, 0.35, 0.2, 0.9, 0.2],
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
                        categories: [
                            " ",
                            "2019",
                            "2020",
                            "2021",
                            "2022",
                            "2023",
                            " ",
                        ],
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
                        max: 1.0,
                        labels: {
                            style: {
                                cssClass: "revenueOverviewChart-yaxis-label",
                            },
                        },
                    },
                };

                var z_revenueOverviewChart = new ApexCharts(
                    document.querySelector("#revenueOverviewChart"),
                    options
                );
                z_revenueOverviewChart.render();
            },
            Z_Chart_2: function () {
                const ctx = document.getElementById("myChart");

                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                        datasets: [
                            {
                                backgroundColor: "#7A5AF820",
                                borderRadius: 50,
                                hoverBackgroundColor: "#7a5af8",
                                data: [
                                    [66, 49],
                                    [55, 12],
                                    [47, 44],
                                    [50, 31],
                                    [93, 33],
                                    [12, 9],
                                    [58, 1],
                                    [66, 49],
                                    [55, 12],
                                    [47, 44],
                                    [50, 31],
                                    [93, 33],
                                ],
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
            },
            PostPhotoCount: function () {
                $("ul.postPhotoItems").each(function () {
                    var $ul = $(this);
                    var $li = $ul.find("li");
                    var liCount = $li.length;

                    if (liCount > 3) {
                        $li.eq(2)
                            .find("a")
                            .append(
                                "<div class='morePhotos'>+" +
                                    (liCount - 1) +
                                    "</div>"
                            );
                    }
                });

                $("ul.postPhotoItems").each(function () {
                    var liCount = $(this).find("li").length;

                    if (liCount === 1) {
                        $(this).addClass("postPhotoItems-one");
                    } else if (liCount === 2) {
                        $(this).addClass("postPhotoItems-two");
                    } else if (liCount === 3) {
                        $(this).addClass("postPhotoItems-three");
                    } else if (liCount > 3) {
                        $(this).addClass("postPhotoItems-multi");
                    }
                });
            },
            DateRangePicker: function () {
                $(".date-time-picker").daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    autoUpdateInput: false,
                    locale: {
                        format: "D-M-Y",
                    },
                });
                $(".date-time-picker").on(
                    "apply.daterangepicker",
                    function (ev, picker) {
                        $(this).val(picker.startDate.format("DD/MM/YYYY"));
                    }
                );
            },
            Message: function () {
                // For Message
                const userChats = document.querySelectorAll(".user-chat");
                const chatMessages = document.querySelectorAll(
                    ".content-chat-message-user"
                );

                userChats.forEach((userChat) => {
                    userChat.addEventListener("click", () => {
                        const selectedUsername =
                            userChat.getAttribute("data-username");

                        chatMessages.forEach((chatMessage) => {
                            const messageUsername =
                                chatMessage.getAttribute("data-username");

                            if (messageUsername === selectedUsername) {
                                chatMessage.classList.add("active");
                            } else {
                                chatMessage.classList.remove("active");
                            }
                        });

                        userChats.forEach((chat) => {
                            chat.classList.remove("active");
                        });
                        userChat.classList.add("active");
                    });

                    // Activate the first user-chat element initially
                    userChats[0].classList.add("active");
                    chatMessages[0].classList.add("active");
                });
            },
            FilUpLoad: function () {
                // File attachment

                $("#mAttachment,#mAttachment1").on("change", function (e) {
                    for (var i = 0; i < this.files.length; i++) {
                        let fileBloc = $("<span/>", { class: "file-block" }),
                            fileName = $("<p/>", {
                                class: "name",
                                text: this.files.item(i).name,
                            });
                        fileBloc
                            .append(
                                '<span class="file-icon"><i class="fa-solid fa-file"></i></span>'
                            )
                            .append(fileName)
                            .append(
                                '<span class="file-delete"><i class="fa-solid fa-xmark"></i></span>'
                            );
                        $("#filesList > #files-names").append(fileBloc);
                    }

                    for (let file of this.files) {
                        dt.items.add(file);
                    }

                    this.files = dt.files;

                    $("span.file-delete").click(function () {
                        let name = $(this).next("span.name").text();

                        $(this).parent().remove();
                        for (let i = 0; i < dt.items.length; i++) {
                            if (name === dt.items[i].getAsFile().name) {
                                dt.items.remove(i);
                                continue;
                            }
                        }
                    });
                });
            },
            Animation: function () {
                AOS.init();
            },
            SortForm: function () {
                var fbTemplate = document.getElementById("fb-editor");
                var options = {
                    controlPosition: "left",
                    disabledActionButtons: ["data"],
                    // onCloseFieldEdit: false,
                    showActionButtons: false,
                    replaceFields: [
                        {
                            type: "checkbox-group",
                            label: "Checkbox",
                            icon: '<i class="fa-solid fa-check"></i>',
                        },
                        {
                            type: "radio-group",
                            label: "Radio",
                            icon: '<i class="fa-solid fa-circle"></i>',
                        },
                        {
                            type: "select",
                            label: "Select",
                            icon: '<i class="fa-solid fa-list-ul"></i>',
                        },
                    ],
                    disableFields: [
                        "autocomplete",
                        "file",
                        "hidden",
                        "header",
                        "date",
                        "paragraph",
                        "button",
                    ],
                    controlOrder: [
                        "text",
                        "textarea",
                        "number",
                        "select",
                        "checkbox-group",
                        "radio-group",
                    ],
                };
                // $(fbTemplate).formBuilder(options).actions.closeAllFieldEdit();
            },
            PassShowHide: function () {
                $(".toggle-password").click(function () {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $($(this).attr("toggle"));
                    if (input.attr("type") == "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
            },
            LandingPriceToggle: function () {
                $("#zPrice-plan-switch").on("click", function () {
                    $(".zPrice-plan-monthly").toggleClass("d-none");
                    $(".zPrice-plan-yearly").toggleClass("d-block");
                });
            },
            LandingTestimonial: function () {
                var swiper = new Swiper(".ldTestiItems", {
                    slidesPerView: 1.1,
                    spaceBetween: 15,
                    centeredSlides: true,
                    roundLengths: true,
                    loop: true,
                    // loopAdditionalSlides: 30,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    breakpoints: {
                        992: {
                            slidesPerView: 1.5,
                            spaceBetween: 25,
                        },
                        // 768: {
                        //   slidesPerView: 4,
                        //   spaceBetween: 40,
                        // },
                        // 1024: {
                        //   slidesPerView: 5,
                        //   spaceBetween: 50,
                        // },
                    },
                });
            },
        },
    };
    jQuery(document).ready(function () {
        Medi.init();
    });
})(jQuery);
