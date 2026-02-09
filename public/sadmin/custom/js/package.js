(function ($) {
    ("use strict");
    $(document).on('click', '#add', function () {
        var selector = $('#addModal');
        selector.find('.otherFields').html('');
        selector.modal('show');
    });

    $(document).on('change', 'select[name=customer_limit_type],select[name=product_limit_type],select[name=subscription_limit_type]', function () {
        var selector = $(this).closest('div');
        if ($(this).val() == 1) {
            selector.find('input').prop('disabled', false);
        } else {
            selector.find('input').val(0);
            selector.find('input').prop('disabled', true);
        }
    });

    $(document).on('click', '.edit', function () {
        commonAjax('GET', $('#packageInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
    });

    function getDataEditRes(response) {
        var selector = $('#editModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();

        selector.find('input[name=id]').val(response.data.id);
        selector.find('.upload-img-box img').attr('src', response.data.icon);
        selector.find('input[name=name]').val(response.data.name);
        if (response.data.number_of_order == -1) {
            selector.find('input[name=order_limit]').val(0);
            selector.find('select[name=number_of_order]').val(2);
        } else {
            selector.find('input[name=order_limit]').val(response.data.number_of_order);
        }
        if (response.data.number_of_client == -1) {
            selector.find('input[name=client_limit]').val(0);
            selector.find('select[name=number_of_client]').val(2);
        } else {
            selector.find('input[name=client_limit]').val(response.data.number_of_client);
        }

        // others
        var otherHtmlFields = '';
        var otherFields = JSON.parse(response.data.others);
        if (otherFields) {
            otherFields.forEach((val) => {
                otherHtmlFields += otherFiledTemplate(val)
            });
        }
        selector.find('.otherFields').html(otherHtmlFields);


        selector.find('input[name=monthly_price]').val(response.data.monthly_price)
        selector.find('input[name=yearly_price]').val(response.data.yearly_price)
        if (response.data.status == 1) {
            selector.find('input[name=status]').prop('checked', true);
        } else {
            selector.find('input[name=status]').prop('checked', false);
        }
        if (response.data.is_trail == 1) {
            selector.find('input[name=is_trail]').prop('checked', true);
        } else {
            selector.find('input[name=is_trail]').prop('checked', false);
        }
        if (response.data.is_default == 1) {
            selector.find('input[name=is_default]').prop('checked', true);
        } else {
            selector.find('input[name=is_default]').prop('checked', false);
        }
        selector.modal('show')
    }

    $('.addOtherField').on('click', function () {
        var selector = $(this).closest('.modal')
        selector.find('.otherFields').append(otherFiledTemplate());
    });

    $(document).on('click','.removeOtherField', function () {
            $(this).parent().remove();
    });

    function otherFiledTemplate(val = null) {
        return `<div class="input-group mb-3 flex-nowrap mt-3">
                    <input type="text" name="others[]" class="form-control zForm-control" value="${val ?? ''}">
                    <button type="button"
                        class="bg-danger input-group-text text-white removeOtherField"
                        id="basic-addon1"><i class="fa-solid fa-trash"></i></button>
                </div>`;
    }

    $("#packageDataTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive:true,
        // responsive: {
        //     breakpoints: [
        //         { name: "desktop", width: Infinity },
        //         { name: "tablet", width: 1400 },
        //         { name: "fablet", width: 768 },
        //         { name: "phone", width: 480 },
        //     ],
        // },
        searching: false,
        ajax: $('#packageIndexRoute').val(),
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
            { data: 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false, },

            { data: "name", name: "name" },
            { data: "monthly_price", name: "monthly_price" },
            { data: "yearly_price", name: "yearly_price" },
            { data: "status", name: "status" },
            { data: "trail", name: "trail" },
            { data: "action", name: "action" },
        ],
    });

    $('#assignPackage').on('click', function () {
        var selector = $('#assignPackageModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.find('form').trigger('reset');
        selector.modal('show')
    })

    $("#packageUserDataTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: {
            breakpoints: [
                { name: "desktop", width: Infinity },
                { name: "tablet", width: 1400 },
                { name: "fablet", width: 768 },
                { name: "phone", width: 480 },
            ],
        },
        searching: false,
        ajax: $('#packagesUserRoute').val(),
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
            { data: "user_name", name: "users.name" },
            { data: "package_name", name: "packages.name" },
            { data: "start_date", name: "owner_packages.start_date" },
            { data: "end_date", name: "owner_packages.end_date" },
            { data: "payment_status", name: "subscription_orders.payment_status" },
            { data: "status", name: "user_packages.status" }
        ],
    });

})(jQuery);
