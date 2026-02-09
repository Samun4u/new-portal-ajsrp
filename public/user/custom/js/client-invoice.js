(function($){
    "use strict";

    // service-route call
    commonAjax('GET', $('#service-route').val(), serviceResponse, serviceResponse);
    let selectDynamicOptions = '';

    function serviceResponse(response) {
        let allService = response.data;
        selectDynamicOptions = allService.map(item => {
            return `<option value="${item.id}" data-price="${item.price}">
                        ${item.service_name} (Price: ${item.price})
                    </option>`;
        });
    }

    // addmore field start
    $(document).on('click', '.removeOtherField', function () {
        $(this).closest('.remove-all').remove();
    });

    $('.addmore').on('click', function (e) {
        e.preventDefault()
        let html = `
                    <div class="row rg-20 remove-all select-price-wrap">
                        <input type="hidden" name="types[]" value="1">
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="zForm-wrap w-100">
                                    <label class="zForm-label">Service Name</label>
                                    <select class="sf-select-without-search py-5 singleService" name="service_id[]">
                                        <option>Select service</option>
                                        ${selectDynamicOptions.join('')}
                                    </select>
                                </div>
                                <button type="button" class="ms-2 mt-30 input-group-text text-white removeOtherField"><i
                                class="text-danger fa fa-trash-can"></i></button>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <label for="addInvoicePrice" class="zForm-label">Price</label>
                            <input type="text" class="form-control zForm-control selectedValueContainer" step="0.01"
                                name="price[]" placeholder="Enter Price">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="addInvoiceDiscount" class="zForm-label">Discount</label>
                            <input type="number" class="form-control zForm-control" id="addInvoiceDiscount"
                                placeholder="Enter Discount" name="discount[]">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="addInvoiceQuantity" class="zForm-label">Quantity</label>
                            <input type="number" class="form-control zForm-control" id="addInvoiceQuantity"
                                placeholder="Enter Quantity" name="quantity[]">
                        </div>
                        <hr class="my-2 border-primary">
                    </div>
                    `;
        $('#otherFields').prepend(html);
    })

    //  service price show
    $(document).ready(function () {
        $('.singleService').on('change', function () {
            var selectedValueContainer = $(this).closest('.select-price-wrap').find('.selectedValueContainer');
            var selectedPrice = $(this).find(':selected').data('price');
            selectedValueContainer.val(selectedPrice);
        });
    });

    // get client order
    let orderPayableAmountHtml = `
        <span id="orderPayableAmount">
            <div class="col-12">
                <div
                    class="d-flex justify-content-between align-items-center flex-wrap g-8 pb-8">
                    <label
                        class="zForm-label mb-0">Order</label>
                </div>
                <select class="sf-select-without-search selectOrderList" name="order_id">

                </select>
            </div>
            <div class="col-12 mt-15">
                <label for="addInvoicePrice" class="zForm-label">Payable
                    Amount</label>
                <input type="text" class="form-control zForm-control" step="0.01"
                    name="payable_amount" placeholder="Payable Amount">
            </div>
        </span>`;

    $(document).ready(function () {
        $('.clientSelectOption').on('change', function () {
            commonAjax('GET', $('#client-order-route').val(), orderResponse, orderResponse, { id: $(this).val() });
        });
    });

    function orderResponse(response) {
        if (response.data.length !== 0) {
            $('#orderPayableAmount').remove();
            $(".invoiceCreateForm").hide();
            $('.payableAmountContainer').prepend(orderPayableAmountHtml);
            let allOrder = response.data;
            let allOrdersGet = allOrder.map(item => {
                return `<option value="${item.id}">
                    ${item.id}
                </option>`
            })
            $(".selectOrderList").prepend(allOrdersGet);
        }else{
            $(".invoiceCreateForm").show();
            $('#orderPayableAmount').remove();
        }
    }

    var invoiceDataTable
    $(document).on('input', '#teamMemberSearch', function () {
        invoiceDataTable.search($(this).val()).draw();
    });

    invoiceDataTable = $("#clientInvoiceListDatatable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#client-invoice-list-route').val(),
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
            { data: 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'client_name', name: 'client_name', orderable: false, searchable: false, },
            { data: 'service_name', name: 'service_name'},
            { data: "due_date", name: "due_date" },
            { data: "total_price", name: "total_price" },
            { data: "status", name: "status" },
            { data: "action", name: "action" }
        ],
    });
})(jQuery);
