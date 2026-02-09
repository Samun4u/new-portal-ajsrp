(function ($) {
    "use strict";

    // service-route call
    commonAjax('GET', $('#service-data-route').val(), serviceResponse, serviceResponse);
    let selectDynamicOptions = '';

    function serviceResponse(response) {
        let allService = response.data;
        selectDynamicOptions = allService.map(item => {
            return `<option value="${item.id}" data-price="${item.price}">
                        ${item.service_name} (Price: ${item.price})
                    </option>`;
        });
    }

    // add more field start
    $(document).on('click', '.removeOtherField', function () {
        $(this).closest('tr').remove();
    });


    // Dynamically added row event listener
    $(document).ready(function() {
        $(document).on('change', '.singleService', function() {
            var selectedPriceContainer = $(this).closest('tr').find('.service-price');
            var selectedPrice = $(this).find(':selected').data('price');
            selectedPriceContainer.val(selectedPrice);
        });

        $('.quantity-input').val(1);
    });

    $('.addmoreservice').on('click', function (e) {
        e.preventDefault();
        let html = `
        <tr>
            <input type="hidden" name="types[]" value="1">
            <td>
                <select class="form-select singleService" name="service_id[]">
                    <option value="">Select Services</option>
                    ${selectDynamicOptions.join('')}
                </select>
                <div class="service_id"></div>
            </td>
            <td>
                <div class="min-w-100">
                    <input type="text" name="price[]" class="price form-control zForm-control zForm-control-table service-price "
                           id="" placeholder="Enter Price"/>
                </div>
            </td>
            <td>
                <div class="min-w-100">
                    <input type="text" name="discount[]" class="discount form-control zForm-control zForm-control-table"
                    value="0" placeholder="Enter Discount"/>
                </div>
            </td>
            <td>
                <div class="">
                    <input type="number" name="quantity[]" class="quantity form-control zForm-control zForm-control-table quantity-input"
                    value="1" placeholder="Enter Quantity"/>
                </div>
            </td>
            <td>
                <button class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30
                            d-flex justify-content-center align-items-center text-red removeOtherField" type="button"><i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>`;

        $('#inputTable tbody').append(html);

    });

    // get client order
    $(document).ready(function () {
        $('.clientSelectOption').on('change', function () {
            commonAjax('GET', $('#client-order-route').val(), orderResponse, orderResponse, {id: $(this).val()});
        });
    });

    $(".orderPayableAmountContainer").hide();
    $(".payableAmount").hide();

    function orderResponse(response) {
        $(".selectOrderList").html(response.responseText);
        if (response.responseText.length !== 0) {
            $(".orderPayableAmountContainer").show();
        } else {
            $(".orderPayableAmountContainer").hide();
            $(".payableAmount").hide();
            $(".invoiceCreateForm").show();
        }
    }


    $(document).ready(function () {
        $('.selectOrderList').on('change', function () {
            $(".payableAmount").show();
            $(".invoiceCreateForm").hide();
        });
    });

    $(document).ready(function () {
        dataTable('all');
    });

    $(document).on('click', '.orderStatusTab', function (e) {
        var status = $(this).data('status');
        dataTable(status);
    });

    var allOrderTable;
    $(document).on('input', '#datatableSearch', function () {
        allOrderTable.search($(this).val()).draw();
    });

    function dataTable(status) {
        allOrderTable = $("#orderTable-" + status).DataTable({
            pageLength: 10,
            ordering: true,
            serverSide: false,
            processing: true,
            responsive: true,
            searching: true,
            ajax: {
                url: $('#client-order-list-route').val(),
                data: function (data) {
                    data.status = status;
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>",
                },
                searchPlaceholder: "Search here...",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                {data: 'client_name', name: 'client_name', orderable: false},
                {data: 'service_name', name: 'service_name' ,orderable: false},
                {data: 'article_title', name: 'article_title', orderable: false},
                {data: 'order_id', name: 'order_id', orderable: false},
                {data: "total_price", name: "total_price", orderable: false},
                {data: "working_status", name: "working_status", orderable: false},
                {data: "payment_status", name: "payment_status", orderable: true},
                {data: "order_status", name: "order_status", orderable: true},
                {data: "created_at", name: "created_at", orderable: true},
                {data: "action", name: "action", orderable: false}
            ],
            order: [[6, 'desc']],
            stateSave: true,
            "bDestroy": true
        });
    }


    window.chatResponse = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            // toastr.success(response['message']);
            $(".conversation-text").val('');
            $("#files-names").html('');
            $("#files-names2").html('');
            $("#mAttachment").val('');
            dt = new DataTransfer();
            if (response.data.type == 1){
                $(".admin-team-chat").html(response.data.conversationTeamTypeData);
                $('.admin-team-chat').scrollTop($('.admin-team-chat')[0]?.scrollHeight);

            }else{
                $(".admin-client-chat").html(response.data.conversationClientTypeData);
                $('.admin-client-chat').scrollTop($('.admin-client-chat')[0]?.scrollHeight);
            }

        } else {
            commonHandler(response)
        }
    }

    $(window).on('load', function () {
        $('.admin-client-chat').scrollTop($('.admin-client-chat')[0]?.scrollHeight);
    });
    $(document).on('click', '.chat-team-tab', function (e) {
        $('.admin-team-chat').scrollTop($('.admin-team-chat')[0]?.scrollHeight);
    });

    $(document).on('click', '.assign-member', function (e) {
        var checkedStatus = 0;
        if ($(this).prop('checked') == true) {
            checkedStatus = 1;
        }
        commonAjax('GET', $('#assignMemberRoute').val(), assigneeResponse, assigneeResponse, {
            'member_id': $(this).val(),
            'checked_status': checkedStatus,
            'order_id': $(this).data('order'),
        });
    });

    function assigneeResponse(response) {
        if (response['status'] === true) {
            toastr.success(response['message']);
            location.reload();
        } else {
            commonHandler(response)
        }
    }

    $(document).on('click', '#noteAddModal', function (e) {
       $("#orderIdField").val($(this).data("order_id"));
    });
    $(document).on('click', '#noteEditModal', function (e) {
       $("#orderIdField").val($(this).data("order_id"));
       $("#noteDetails").val($(this).data("details"));
       $("#noteIdField").val($(this).data("id"));
    });

})(jQuery);
