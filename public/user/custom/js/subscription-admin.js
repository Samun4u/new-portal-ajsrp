
$(document).on('click', '#chooseAPlan', function () {
    commonAjax('GET', $('#chooseAPlanRoute').val(), setPlanModalData, setPlanModalData);
});

function setPlanModalData(response) {
    var selector = $('#choosePackageModal')
    selector.modal('show');
    selector.find('#planListBlock').html(response.responseText);
}

var requestCurrentPlan = $('#requestCurrentPlan').val();
if (requestCurrentPlan == 'no') {
    $('#chooseAPlan').trigger('click');
}

$(document).on('change', '#zPrice-plan-switch', function () {
    if ($(this).is(':checked') == true) {
        $(document).find('.plan_type').val(2);
    } else {
        $(document).find('.plan_type').val(1);
    }
});

window.addEventListener('load', function () {
    if ($('#requestPlanId').val()) {
        let response = { 'responseText': $('#gatewayResponse').val() };
        setPaymentModal(response)
    }
})

function setPaymentModal(response) {
    var selector = $('#paymentMethodModal')
    selector.modal('show');
    $('#choosePackageModal').modal('hide');
    selector.find('#gatewayListBlock').html(response.responseText);
}

$(document).on('click', '.paymentGateway', function (e) {
    var selectGatewaySlug = $(this).data('gateway').replace(/\s+/g, '');
    $('#selectGateway').val(selectGatewaySlug)
    $('#selectCurrency').val('');
    $('#package_id').val($(this).data('package_id'));
    $('#duration_type').val($(this).data('duration_type'));
    commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, { 'id': $(this).data('id') });
    if ($('.modal').length) {
        $('.payment-item').removeClass('active');
        $(this).closest('.payment-item').addClass('active');
    } else {
        $('html, body').animate({
            scrollTop: $("#currencyAppend").offset().top
        }, 200);
    }
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
    var html = '';
    var planAmount = parseFloat($('#planAmount').val()).toFixed(2);
    Object.entries(response.data).forEach((currency) => {
        let currencyAmount = currency[1].conversion_rate * planAmount;
        html += `<li class="d-flex justify-content-between align-items-center">
                    <div class="zForm-wrap-radio gatewayCurrencyAmount">
                        <input class="form-check-input" type="radio" name="currencyAmount" value="${gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol)}" id="${currency[1].id}" />
                        <label class="form-check-label" for="${currency[1].id}">${currency[1].currency}</label>
                    </div>
                    <p><span>${gatewayCurrencyPrice(Number(planAmount))}</span> * <span>${currency[1].conversion_rate}</span> = <span>${gatewayCurrencyPrice(Number(currencyAmount), currency[1].symbol)}</span></p>
                </li>`;
    });
    $('#currencyAppend').html(html);
}

$(document).on('click', '.gatewayCurrencyAmount', function () {
    var getCurrencyAmount = '(' + $(this).find('input').val() + ')';
    $('#gatewayCurrencyAmount').text(getCurrencyAmount)
    $('#selectCurrency').val($(this).text().replace(/\s+/g, ''));
});

$(document).on('change', '#bank_id', function () {
    $('#bankDetails').removeClass('d-none');
    $('#bankDetails p').html($(this).find(':selected').data('details'));
});

$('#payBtn').on('click', function () {
    var gateway = $('#selectGateway').val()
    var currency = $('#selectCurrency').val();
    if (gateway == '') {
        toastr.error('Select Gateway');
        $('#payBtn').attr('type', 'button');
    } else {
        if (currency == '') {
            toastr.error('Select Currency');
            $('#payBtn').attr('type', 'button');
        } else {
            $('#payBtn').attr('type', 'submit');
        }
    }
});
