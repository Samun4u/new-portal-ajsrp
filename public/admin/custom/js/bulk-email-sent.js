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

        let activeStatus = $('#myTab .nav-link.active').data('status');

        // on page load, check if there's an active tab in localStorage
        let activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            let tabTriggerEl = $("#" + activeTab);
            if (tabTriggerEl.length) {
                
                let tab = new bootstrap.Tab(tabTriggerEl[0]);
                tab.show();

                activeStatus = tabTriggerEl.data('status');

                if(activeStatus === 'sent_email_history') {
                    historyDataTable(activeStatus);
                }
                if(activeStatus === 'all') {
                    dataTable(activeStatus);
                }
            }
        }

        // when tab change, save it to localStorage
        $('#myTab button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('id'));
        });
         dataTable('all');
    });

    $(document).on('click', '.orderStatusTab', function (e) {
        var status = $(this).data('status');
        if(status === 'all'){
            dataTable(status);
        }

        if(status === 'sent_email_history'){
            historyDataTable(status);
        }
       
    });

    function dataTable(status) {
        $("#orderTable-" + status).DataTable({
            pageLength: 10,
            ordering: true,
            serverSide: false,
            processing: true,
            responsive: true,
            searching: false,
            ajax: {
                url: $('#bulk-email-template-list-route').val(),
                data: function (data) {
                    data.status = 'all';
                }
            },
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
                {data: 'name', name: 'name', orderable: true},
                {data: 'subject', name: 'subject' ,orderable: false},                
                {data: "created_at", name: "created_at", orderable: false},
                {data: "action", name: "action", orderable: false}
            ],
            order: [[0, 'desc']],
            stateSave: true,
            "bDestroy": true
        });
    }

    function historyDataTable(status) {
        $("#orderTable-" + status).DataTable({
            pageLength: 10,
            ordering: true,
            serverSide: false,
            processing: true,
            responsive: true,
            searching: false,
            ajax: {
                url: $('#bulk-email-send-history-route').val(),
                data: function (data) {
                    data.status = 'history';
                }
            },
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
                {data: 'to', name: 'to', orderable: true},
                {data: 'subject', name: 'subject' ,orderable: false},  
                {data: "status", name: "status", orderable: false},
                {data: "sent_by", name: "sent_by", orderable: false},              
                {data: "created_at", name: "created_at", orderable: false},
            ],
            order: [[0, 'desc']],
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
