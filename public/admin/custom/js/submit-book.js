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
        // clientInvoiceTable
        //     .row($(this).closest('tr'))
        //     .remove().draw();
        $(this).closest('#otherFields tr').remove();
    });

     //  service price show
    $(document).ready(function() {
        $(document).on('change', '.singleService', function() {
            var selectedPriceContainer = $(this).closest('tr').find('.service-price');
            var selectedPrice = $(this).find(':selected').data('price');
            selectedPriceContainer.val(selectedPrice);
        });

        $('.quantity-input').val(1);
    });

    $('.addmore').on('click', function (e) {
        e.preventDefault();
        let html = `
                <tr class="select-price-wrap">
                    <input type="hidden" name="types[]" value="1">
                    <td>
                        <select class="form-select h-48 py-5 singleService" name="service_id[]">
                            <option value="">Select Services</option>
                            ${selectDynamicOptions.join('')}
                        </select>
                        <div class="service_id"></div>
                    </td>
                    <td>
                        <div class="min-w-100">
                            <input type="number" name="price[]" class="form-control zForm-control zForm-control-table service-price price"
                                   id="" placeholder="Enter Price"/>
                        </div>
                    </td>
                    <td>
                        <div class="min-w-100">
                            <input type="number" name="discount[]" class="form-control zForm-control zForm-control-table discount"
                            value="0" placeholder="Enter Discount"/>
                        </div>
                    </td>
                    <td>
                        <div class="">
                            <input type="number" name="quantity[]" class="form-control zForm-control zForm-control-table quantity-input quantity"
                            value="1" placeholder="Enter Quantity"/>
                        </div>
                    </td>
                    <td>
                        <button class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30
                                    d-flex justify-content-center align-items-center text-red removeOtherField" type="button"><i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>`;

        $('#otherFields').append(html);

    });

    // get client order
    $(document).ready(function () {
        $('.clientSelectOption').on('change', function () {
            commonAjax('GET', $('#client-order-route').val(), orderResponse, orderResponse, { id: $(this).val() });
        });
    });

    $(".orderPayableAmountContainer").hide();
    $(".payableAmount").hide();

    function orderResponse(response) {
        $(".selectOrderList").html(response.responseText);

        if (response.responseText.length !== 0) {
            $(".orderPayableAmountContainer").show();
        }else{
            $(".orderPayableAmountContainer").hide();
            $(".payableAmount").hide();
            $(".invoiceCreateForm").show();
        }
    }

    $(document).ready(function () {
        $('.selectOrderList').on('change', function () {
            if ($(this).find(':selected').val() != '') {
                $(".payableAmount").show();
                $(".invoiceCreateForm").hide();
            }else{
                $(".payableAmount").hide();
                 $(".invoiceCreateForm").show();
            }
        });
    });

    function invoiceResponse(response) {
        if (response['status'] === true) {
            toastr.success(response['message']);
            window.location = $('#client-invoice-list-route').val()
        } else {
            commonHandler(response)
        }
    }
    window.invoiceResponse=invoiceResponse;

    // Invoice List Datatable
    $(document).ready(function () {
        dataTable('all');
    });

    $(document).on('click', '.invoiceStatusTab', function (e) {
        var status = $(this).data('status');
        dataTable(status);
    });

    var allInvoiceTable
    $(document).on('input', '#datatableSearch', function () {
        allInvoiceTable.search($(this).val()).draw();
    });

    function dataTable(status) {

        allInvoiceTable = $("#invoiceTable-" + status).DataTable({
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
            ajax: {
                url: $('#client-submitted-book-list-route').val(),
                data: function (data) {
                    data.status = status;
                }
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                { data: 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: true },
                {data: 'client_name', name: 'client_name'},
                { data: 'title', name: 'title'},
                { data: 'author', name: 'author' },
                { data: 'email', name: 'email'},
                { data: "publication_year", name: "publication_year" },
                { data: "allow_public", name: "allow_public" },
                { data: "status", name: "status" },
                { data: "action", name: "action" }
            ],
            stateSave: true,
            "bDestroy": true
        });
    }
    // let clientInvoiceTable ;
    // $(document).ready(function () {
    //     ("use strict");
    //     clientInvoiceTable = $("#clientInvoiceTable").DataTable({
    //         ordering: false,
    //         serverSide: false,
    //         processing: true,
    //         responsive: true,
    //         searching: false,
    //         dom: "",
    //     });
    // });

})(jQuery);
