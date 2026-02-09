(function ($) {
    ("use strict");
    var currencyRes;

    var invoiceType = $('#checkoutType').val();
    var afterDiscountTotalAmount;
    var totalAmount = parseFloat($('#totalAmount').val()).toFixed(2);
    var discountAmount = parseFloat($('#discountAmount').val()).toFixed(2)

    $(document).on('click', '.payNowBtn', function () {
        console.log('in');
        var selector = $('#gatewayModal')
        gatewayCurrencyPrice(visualNumberFormat(parseFloat(totalAmount)), currencySymbol);
        var getData = $(this).data('data');
        if (invoiceType == 2) {
            totalAmount = getData.total;
            var invoiceId = getData.id;
            var showInvoiceId = getData.invoice_id;
            $('#invoiceId').val(invoiceId);
            $('#showInvoiceId').text(showInvoiceId);
            $('#showInvoiceAmount').text(gatewayCurrencyPrice(visualNumberFormat(parseFloat(totalAmount)), currencySymbol));

        } else if (invoiceType == 3) {
        }
        selector.modal('show');
    });

    $(document).on('click', '.paymentGateway', function (e) {
        $('#selectGateway').val('')
        $('#selectCurrency').val('');
        $('#couponCode').val('')
        if (invoiceType == 1) {
            discountAmount = 0.00;
        }
        $('#discountShowAmount').text(gatewayCurrencyPrice(visualNumberFormat(parseFloat(discountAmount)), currencySymbol));
        $('#totalShowAmount').text(gatewayCurrencyPrice(visualNumberFormat(parseFloat(totalAmount)), currencySymbol));
        var selectGatewaySlug = $(this).data('gateway');
        var selectGatewayId = $(this).data('id');
        $('#selectGateway').val(selectGatewaySlug)
        commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, { 'id': selectGatewayId });
        if ($('.modal').length) {
            $('.payment-item').removeClass('active');
            $(this).closest('.payment-item').addClass('active');
        } else {
            $('html, body').animate({
                scrollTop: $("#gatewayCurrencyAppend").offset().top
            }, 200);
        }
        console.log(selectGatewaySlug);
        if (selectGatewaySlug == 'bank') {
            $('#bankSection').removeClass('d-none');
            $('#bank_slip').attr('required', true);
            $('#bank_id').attr('required', true);
            $('#bank_id').trigger('change');
        } else {
            $('#bank_slip').attr('required', false);
            $('#bank_id').attr('required', false);
            $('#bankSection').addClass('d-none');
        }
    });

    function getCurrencyRes(response) {
        currencyRes = response.data;
        currencyHtmlTemplate(currencyRes)
    }

    function currencyHtmlTemplate(currencyRes) {
        console.log(currencyRes);
        var html = '';
        if (invoiceType == 4) {
            afterDiscountTotalAmount = parseFloat(totalAmount);
        } else {
            afterDiscountTotalAmount = parseFloat(totalAmount) - parseFloat(discountAmount);
        }
        Object.entries(currencyRes).forEach((currency) => {
            let currencyAmount = currency[1].conversion_rate * Number(afterDiscountTotalAmount);
            if ($('.modal')) {
                html += `<li class="d-flex justify-content-between align-items-center d-none">
                            <div class="zForm-wrap-radio gatewayCurrencyAmount">
                                <input class="form-check-input" type="radio" name="currencyAmount" value="${gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol)}" id="${currency[1].id}" />
                                <label class="form-check-label" for="${currency[1].id}">${currency[1].currency}</label>
                            </div>
                            <p><span>${gatewayCurrencyPrice(Number(afterDiscountTotalAmount))}</span> * <span>${currency[1].conversion_rate}</span> = <span>${gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol)}</span></p>
                        </li>`;
            } else {
                html += `<li class="d-none">
                        <div class="position-relative w-100">
                            <div class="zForm-wrap-radio gatewayCurrencyAmount">
                                <input class="form-check-input" type="radio" name="currencyAmount" value="${gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol)}" id="${currency[1].id}" />
                                <label class="form-check-label" for="${currency[1].id}">${currency[1].currency}</label>
                            </div>
                            <div class="position-absolute top-50 end-0 translate-middle-y">
                                <p><span>${gatewayCurrencyPrice(Number(afterDiscountTotalAmount))}</span> * <span>${currency[1].conversion_rate}</span> = <span>${gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol)}</span></p>
                            </div>
                        </div>
                    </li>`;
            }

            var getCurrencyAmount = '(' + gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol) + ')'; //getCurrencyAmount
            $('#gatewayCurrencyAmount').text(getCurrencyAmount)
            var getCurrency = currency[1].currency;
            $('#selectCurrency').val(getCurrency.replace(/\s+/g, ''));
        });
        $('#gatewayCurrencyAppend').html(html);
    }

    $(document).on('click', '.gatewayCurrencyAmount', function () {
        var getCurrencyAmount = '(' + $(this).find('input').val() + ')';
        $('#gatewayCurrencyAmount').text(getCurrencyAmount)
        $('#selectCurrency').val($(this).text().replace(/\s+/g, ''));
    });

    $(document).on('change', '#bank_id', function () {
        $('#bankDetails').removeClass('d-none');
        $('#bankDetails').html($(this).find(':selected').data('details'));
    });

    $('#paymentNowBtn').on('click', function () {
        var gateway = $('#selectGateway').val()
        var currency = $('#selectCurrency').val();
        if (gateway == '') {
            toastr.error('Select Gateway');
            $('#paymentNowBtn').attr('type', 'button');
        } else {
            if (currency == '') {
                toastr.error('Select Currency');
                $('#paymentNowBtn').attr('type', 'button');
                $('html, body').animate({
                    scrollTop: $("#gatewayCurrencyAppend").offset().top
                }, 200);
            } else {
                $('#paymentNowBtn').attr('type', 'submit');
            }
        }
    });

    function checkoutOrderResponse(data) {
        var output = '';
        var type = 'error';
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (data['status'] == true) {
            if (data.data.gateway == 'bank') {
                output = output + data['message'];
                type = 'success';
                alertAjaxMessage(type, output);
                if (data.data.checkout_type == 1) {
                    window.location.href = $('#waitingRoute').val();
                } else {
                    window.location.href = $('#gotoRoute').val();
                }
            } else if (data.data.gateway == 'cash') {
                output = output + data['message'];
                type = 'success';
                alertAjaxMessage(type, output);
                if (data.data.checkout_type == 1) {
                    window.location.href = $('#waitingRoute').val();
                } else {
                    window.location.href = $('#gotoRoute').val();
                }
            } else {
                window.location.href = data.data.redirect_url;
            }

        } else {
            commonHandler(data)
        }
    }
    window.checkoutOrderResponse = checkoutOrderResponse;

    $('#couponCodeApplyBtn').on('click', function () {
        var coupon_code = $('#couponCode').val();
        commonAjax('GET', $('#getCouponInfoRoute').val(), getCouponResponse, getCouponResponse, {
            'order_form_id': $('#formOrderId').val(),
            'code': coupon_code,
            'user_id': $('#userId').val()
        });
    });

    function getCouponResponse(data) {
        if (data['status'] == true) {
            $('#gatewayCurrencyAppend').html();
            $('#selectCurrency').val()
            var get_discount_type = data.data.coupon.discount_type;
            var get_discount_amount = data.data.coupon.discount_amount;
            var show_discount_amount = 0;
            var calculate_discount_amount = 0;

            var orderFormServicesHtml = '';
            if (orderFormServices) {
                $.each(orderFormServices, function (index, orderFormService) {
                    orderFormServicesHtml +=
                        `<tr>
                            <td>${orderFormService.service_name}</td>
                            <td>${orderFormService.quantity}</td>`;
                    if (jQuery.inArray(orderFormService.service_id.toString(), JSON.parse(data.data.coupon.service_ids)) !== -1) {
                        if (get_discount_type == 1) {
                            show_discount_amount += Number(get_discount_amount);
                        } else {
                            calculate_discount_amount = parseFloat(get_discount_amount) * 0.01 * parseFloat(orderFormService.price);
                        }
                        show_discount_amount += Number(calculate_discount_amount)
                        orderFormServicesHtml += `<td>${gatewayCurrencyPrice(visualNumberFormat(calculate_discount_amount), currencySymbol)}</td>`;
                    } else {
                        orderFormServicesHtml += `<td>${gatewayCurrencyPrice(0)}</td>`;
                    }
                    orderFormServicesHtml += `<td>${gatewayCurrencyPrice(visualNumberFormat(orderFormService.price), currencySymbol)}</td>
                        </tr>`
                });
                $('#orderFormServiceItems').html(orderFormServicesHtml);
            }

            if (get_discount_type == 1) {
                discountAmount = parseFloat(Number(show_discount_amount)).toFixed(2);
                afterDiscountTotalAmount = parseFloat(totalAmount) - show_discount_amount;
                $('#discountAmount').val(discountAmount);
                $('#discountShowAmount').text(gatewayCurrencyPrice(visualNumberFormat(discountAmount), currencySymbol))
                $('#totalShowAmount').text(gatewayCurrencyPrice(visualNumberFormat(afterDiscountTotalAmount), currencySymbol))
            } else {
                discountAmount = show_discount_amount;
                afterDiscountTotalAmount = parseFloat(totalAmount) - discountAmount;
                $('#discountAmount').val(discountAmount);
                $('#discountShowAmount').text(gatewayCurrencyPrice(visualNumberFormat(discountAmount), currencySymbol))
                $('#totalShowAmount').text(gatewayCurrencyPrice(visualNumberFormat(afterDiscountTotalAmount), currencySymbol))
            }
            if (currencyRes != null) {
                currencyHtmlTemplate(currencyRes)
            }

            toastr.success(data['message']);
        } else {
            commonHandler(data)
        }
    }
    function setPaymentModal(response) {
        console.log(response);
        var selector = $('#buyNowModal')
        selector.modal('show');
        selector.find('#gatewayListBlock').html(response.responseText);
    }

    window.setPaymentModal = setPaymentModal;

    $(document).on('click', '.payment-item-btn', function () {
        $('.payment-item').removeClass('active');
        $(this).parent('.payment-item').addClass('active');

        var selectGateway = $(this).data('gateway').replace(/\s+/g, '');
        $('#selectGateway').val(selectGateway)
        $('#selectedGatewayId').val($(this).data('id'));
        $('#itemId').val($(this).data('item_id'));

        commonAjax('GET', $('#getCurrencyByGatewayIdRoute').val(), getCurrencyListRes, getCurrencyListRes, { 'id': $(this).data('id'), 'amount': $(this).data('item_amount') });
        if (selectGateway == 'bank') {
            $('#bankSection').removeClass('d-none');
            $('#bank_slip').attr('required', true);
            $('#bank_id').attr('required', true);
        } else {
            $('#bank_slip').attr('required', false);
            $('#bank_id').attr('required', false);
            $('#bankSection').addClass('d-none');
        }
    });

    function getCurrencyListRes(response) {
        $('#currencyListBlock').html(response.responseText);
    }

    $(document).on('change', '#bank_id', function () {
        $('#bankDetails').removeClass('d-none');
        $('#bankDetails p').html($(this).find(':selected').data('details'));
    });

    $(document).on('click', '.currency-option', function () {
        $('#currencyId').val($(this).data('id'));
        $('#orderPlaceSubmitBtnAmountBlock').removeClass('d-none');
        $('#orderPlaceSubmitBtnAmount').text($(".amount").text());
    });

    $(document).on('click', '#couponApplybtn', function () {
        // var afterAmount = 0;
        // var mainAmount = $(this).data('main_amount').val();
        // $('.payment-item-btn').data('item_amount',afterAmount);

        commonAjax('GET', $('#getApplyCouponRoute').val(), applyCouponRes, applyCouponRes, { 'item_id': $(this).data('item_id'), 'main_amount': $(this).data('main_amount'), 'coupon_code': $("#couponCode").val(), 'selected_gateway': $('#selectedGatewayId').val() });

    });

    function applyCouponRes(response) {
        if (response['status'] === true) {
            toastr.success(response['message']);
            $(".amount").text(response.data.discountPrice);
            $('.payment-item-btn').data('item_amount', response.data.discountPrice);
            $('#currencyListBlock').html(response.data.currencyList);
            $('#coupon').html(response.data.coupon_code);
        } else {
            commonHandler(response)
        }
    }


})(jQuery);

