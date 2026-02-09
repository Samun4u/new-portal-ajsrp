(function ($) {
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

    //  service price show
    let selectArray = [];
    $(document).ready(function () {
        $(document).on('change', '.singleService', function () {
            var value = $(this).val();
            var selectedPriceContainer = $(this).closest('tr').find('.service-price');
            var selectedPrice = $(this).find(':selected').data('price');
            selectedPriceContainer.val(selectedPrice);

            if ($(this).val() == 'new_service') {
                $(this).closest('td').find('.services_input').removeClass("d-none");
                // $(this).closest('td').find('.services_select').hide();
            }else{
                $(this).closest('td').find('.services_input').addClass("d-none")
            }


            var total = 0
            var price =  $(this).closest("tr").find(".service-price").val();
            var quantity = $(this).closest("tr").find(".service-quantity").val();


            if(price.length > 0){
                total = parseFloat(price) * parseFloat(quantity);
                $(this).closest("tr").find(".service-total").text(total.toFixed(2));
            }else{
                $(this).closest("tr").find(".service-total").text(total.toFixed(2));
            }

            calculateSubTotal();
            calculateTotal();

        });
        $('.quantity-input').val(1);

    });

    // addmore field start
    $(document).on('click', '.removeOtherField', function () {
        $(this).closest('#otherFields tr').remove();
        calculateSubTotal();
        calculateTotal();
    });

    $(document).on('keyup', '.service-price', function () {
        $(".sub-total-old").hide();
        var total = 0
        var price = $(this).val();
        var quantity = $(this).closest("tr").find(".service-quantity").val();

        if(price.length > 0){
            total = parseFloat(price) * parseFloat(quantity);
            console.log(total);
            $(this).closest("tr").find(".service-total").text(total.toFixed(2));
        }else{
            $(this).closest("tr").find(".service-total").text(total.toFixed(2));
        }

        calculateSubTotal();
        calculateTotal();

    });

    $(document).on('keyup', '.service-quantity', function () {
        $(".sub-total-old").hide();
        var total = 0
        var price = $(this).closest("tr").find(".service-price").val();
        var quantity = $(this).val();
        console.log(price);
        if(quantity.length > 0){
            total = parseFloat(price) * parseFloat(quantity);
            $(this).closest("tr").find(".service-total").text(total.toFixed(2));
        }else{
            $(this).closest("tr").find(".service-total").text(total.toFixed(2));
        }

        calculateSubTotal();
        calculateTotal();
    });

    $(document).on('keyup', '#quotationDiscount', function () {
        calculateTotal();
    });

    function calculateSubTotal(){
        var subtotal = 0;
        $(".service-total").each(function() {
            subtotal += parseFloat(Number($(this).text().replace(/[^0-9\.-]+/g,"")));
        });

        $(".sub-total").text(subtotal.toFixed(2));
    }

    function calculateTotal(){
        var total = 0;
        var discount =  parseFloat($("#quotationDiscount").val());
        $(".service-total").each(function() {
            // console.log($(this).text());
            total += parseFloat(Number($(this).text().replace(/[^0-9\.-]+/g,"")));
            // console.log(Number($(this).text().replace(/[^0-9\.-]+/g,"")));
        });

        if(!isNaN(discount)) {
            total = total - discount;
        } else {
            total = total
        }

        $(".total").text(total.toFixed(2));

    }


    $('.addmore').on('click', function (e) {
        e.preventDefault();
        var currency = $("#current-currency").val();
        let html = `
        <tr class="select-price-wrap">
            <td class="">
                <div class="select_service">
                    <select class="form-select singleService" name="service_id[]">
                        <option value="">Select Services</option>
                        <option value="new_service">Create new service</option>
                        ${selectDynamicOptions.join('')}
                    </select>
                    <div class="service_id"></div>
                </div>
                <div class="min-w-100 services_input d-none">
                    <input type="text"
                        class="form-control zForm-control zForm-control-table"
                        name="service_name[]" placeholder="Services name">
                </div>
            </td>
            <td>
                <div class="min-w-100">
                    <input type="number"
                        class="form-control zForm-control zForm-control-table selectedValueContainer service-price price"
                        name="price[]" placeholder="Enter Price">
                </div>
            </td>
            <td>
                <div class="min-w-100">
                <input type="number" name="quantity[]"
                        class="form-control zForm-control zForm-control-table service-quantity quantity" id=""
                        placeholder="Enter Quantity" value="1"></div>
            </td>
            <td>
                <div class="min-w-100">
                <input type="number" name="duration[]"
                        class="form-control zForm-control zForm-control-table duration" id=""
                        placeholder="Duration Dealy" value="1"></div>
            </td>
            <td>
                <p class="fs-14 fw-400 lh-17 text-para-text">${currency}<span class="service-total">0.00</span></p>
            </td>
            <td>
                <button
                    class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center removeOtherField align-items-center text-red"><i
                        class="fa-solid fa-trash"></i></button>
            </td>
        </tr> `;
        $('#otherFields').append(html);

    });

    function quotationResponse(response) {
        if (response['status'] === true) {
            toastr.success(response['message']);
            window.location = $('#quotationListRoute').val()
        } else {
            commonHandler(response)
        }
    }

    window.quotationResponse = quotationResponse;

    // copy url
    $(document).ready(function () {
        $(document).on('click', '.copyUrlBtn', function () {
            var selectedPriceContainer = $(this).closest('tr').find('.copyUrl');
            var textToCopy = selectedPriceContainer.val();
            var tempTextarea = $('<textarea>').val(textToCopy).appendTo('body');
            tempTextarea.select();
            document.execCommand("copy");
            tempTextarea.remove();
            toastr.success("URL copied successfully");
        });
    });

    // quotation data list
    $("#quotationDataList").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: false,
        ajax: $('#quotationListRoute').val(),
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
            {data: 'quotation_id', name: 'quotation_id', orderable: false, searchable: false,},
            {data: "price", name: "price"},
            {data: "customer", name: "customer"},
            {data: "url", name: "url"},
            {data: "date", name: "date"},
            {data: "status", name: "status"},
            {data: "action", name: "action"}
        ],
        stateSave: true,
        "bDestroy": true
    });

})(jQuery);
