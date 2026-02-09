(function ($) {

    var serviceItemHtml;
    if (services) {
        $.each(services, function (index, service) {
            serviceItemHtml += `<option value="${service.id}" data-price="${service.price}">${service.service_name}</option>`;
        });
    }

    $(document).on('change', '.service_id', function () {
        var itemSelector = $(this).closest('tr');
        var price = $(this).find(":selected").data("price");
        itemSelector.find('.price').val(price);
        itemSelector.find('.price').trigger('input');
        itemSelector.find('.price').val(price);
    });

    $(document).on('click', '.addServiceBtn', function () {
        $('#serviceItems').append(serviceItem());
        // $('#serviceItems').find(".sf-select-without-search").niceSelect();
    });

    function serviceItem() {
        return `<tr>
                    <td>
                        <select class="form-select service_id" name="service_ids[]">
                            ${serviceItemHtml}
                        </select>
                    </td>
                    <td>
                        <div class="min-w-100">
                            <input type="number" name="quantity[]" step="any" min="0"
                                value="1"
                                class="form-control zForm-control zForm-control-table quantity"
                                placeholder="Quantity" />
                        </div>
                    </td>
                    <td>
                        <div class="min-w-100">
                            <input type="number" name="price[]" step="any" min="0"
                                value="0"
                                class="form-control zForm-control zForm-control-table price"
                                placeholder="Price" />
                        </div>
                    </td>
                     <td>
                        <div class="min-w-100">
                            <input type="number" name="total[]" step="any" min="0"
                                value="0"
                                class="form-control zForm-control zForm-control-table total"
                                placeholder="Total" />
                        </div>
                    </td>
                    <td>
                        <button type="button"
                            class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red serviceItemRemoveBtn">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
    }

    $(document).on('click', '.serviceItemRemoveBtn', function () {
        $(this).parent().parent().remove();
    });

    $(document).on('change', '#serviceItems .price', function () {
        if ($(this).val() < 0) {
            $(this).val(0);
        }
    });

    $(document).on('change', '#serviceItems .discount', function () {
        if ($(this).val() < 0) {
            $(this).val(0);
        }
    });

    $(document).on('change', '#serviceItems .quantity', function () {
        if ($(this).val() < 1) {
            $(this).val(1);
        }
    });

    var orderFormsList;
    orderFormsList = $("#orderFormsList").DataTable({
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
        ajax: $('#orderFormIndexRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "name" },
            { "data": "form_id" },
            { "data": "link" },
            { "data": "total" },
            { "data": "action" }
        ]
    });

    $(document).on('input', '.price,.quantity', function () {
        var total = 0
        var price = $(this).closest("tr").find(".price").val();
        var quantity = $(this).closest("tr").find(".quantity").val();
        total = parseFloat(price) * parseFloat(quantity);
        total = !isNaN(total) ? total : 0.0;
        $(this).closest("tr").find(".total").val(total.toFixed(2));
    });
})(jQuery);
