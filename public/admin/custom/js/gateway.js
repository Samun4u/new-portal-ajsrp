var getCurrencySymbol = $('#getCurrencySymbol').val();
var allCurrency = JSON.parse($('#allCurrency').val());
var supportedCurrency = JSON.parse($('#supportedCurrency').val());
var gatewaySlug = $('#gatewaySlugFind').val();

(function ($) {
    "use strict";
    $('.addCurrencyBtn').on('click', function (e) {
        var html = '';
        var options = '';
        Object.entries(allCurrency).forEach((currency) => {
            options += '<option value="' + currency[0] + '">' + currency[1] + '</option>';
        });
        html += `<li class="paymentConversionRate">
                    <div class="d-flex justify-content-between align-items-center g-10">
                    <div class="flex-grow-1 d-flex flex-wrap flex-sm-nowrap left">
                        <select class="sf-select currency" name="currency[]">${options}</select>
                        <p class="p-13 fs-14 fw-400 lh-22 text-title-black bg-body-bg text-nowrap">1${getCurrencySymbol} = </p>
                        <input type="text" name="conversion_rate[]" class="form-control zForm-control" id="" placeholder="1.00" />
                        <p class="p-13 fs-14 fw-400 lh-22 text-title-black bg-body-bg text-nowrap append_currency"></p>
                    </div>
                    <button type="submit" class="flex-shrink-0 bd-one bd-c-stroke rounded-circle w-25 h-25 d-flex justify-content-center align-items-center bg-transparent text-danger removedCurrencyBtn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    </div>
                </li>`;
        $('#currencyConversionRateSection').append(html);
        $('.currency').trigger("change");
        $(".sf-select").select2({
            dropdownCssClass: "sf-select-dropdown",
            selectionCssClass: "sf-select-section",
        });
    })

    $(document).on('click', '.removedCurrencyBtn', function () {
        $(this).closest('li').remove();
    });

    $(document).on('change', '.currency', function () {
        let selectedCurrency = $(this).val();

        $(this).closest('li').find('.append_currency').text(selectedCurrency);

        if (gatewaySlug !== 'bank' && gatewaySlug !== 'cash') {
            if (supportedCurrency[gatewaySlug] && supportedCurrency[gatewaySlug].includes(selectedCurrency)) {
                $(this).closest('li').find('.currency-warning').remove();
            } else {
                let warningNote = '<div class="fs-14 currency-warning text-danger pt-10">Currency not supported, please check and add carefully.</div>';
                if ($(this).closest('li').find('.currency-warning').length === 0) {
                    $(this).closest('li').append(warningNote);
                }
            }
        } else {
            $(this).closest('li').find('.currency-warning').remove();
        }
    });

    // Bank
    $('.addBankBtn').on('click', function () {
        $('.bankItemLists').append(addBank());
    });

    $(document).on('click', '.removedBankBtn', function () {
        $(this).closest('li').remove()
    });

    window.addBank = function () {
        return `<li class="d-flex justify-content-between align-items-center g-10">
                    <div class="flex-grow-1 d-flex flex-wrap flex-sm-nowrap g-10 left">
                        <div class="flex-grow-1">
                            <input type="text" class="form-control zForm-control" name="bank[name][]" placeholder="Name">
                        </div>
                        <div class="flex-grow-1">
                            <textarea name="bank[details][]" class="form-control zForm-control" placeholder="Details"></textarea>
                        </div>
                    </div>
                    <button type="button"
                        class="flex-shrink-0 bd-one bd-c-stroke rounded-circle w-25 h-25 d-flex justify-content-center align-items-center bg-transparent text-danger removedBankBtn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </li>`;
    }


})(jQuery);
