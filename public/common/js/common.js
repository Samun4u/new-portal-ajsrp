
(function ($) {
    "use strict";

    $(document).on('submit', "form.ajax", function (event) {
        
        event.preventDefault();
        var enctype = $(this).prop("enctype");
        if (!enctype) {
            enctype = "application/x-www-form-urlencoded";
        }

        var form = $(this);
        var formData = new FormData(form[0]);
        // Find the clicked submit button and get its value
        var buttonValue = $(this).find('button[type="submit"]:focus').val(); // Get the value of the clicked button

        // If a button was clicked, append its value to FormData
        if (buttonValue) {
            console.log("Button Value:", buttonValue); // Debugging: Log the clicked button's value
            formData.append("action", buttonValue); // Append the button value to FormData
        }

        commonAjax($(this).prop('method'), $(this).prop('action'), window[$(this).data("handler")], window[$(this).data("handler")], formData);
    });


    $(document).on("click", "a.delete", function () {
        const selector = $(this);
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: $(this).data("url"),
                    success: function (data) {
                        if (data.status == true) {
                            selector.closest('.removable-item').fadeOut('fast');
                            Swal.fire({
                                title: 'Deleted',
                                html: ' <span style="color:red">' + data.message + '</span> ',
                                timer: 2000,
                                icon: 'success'
                            })
                            location.reload()
                        } else {
                            Swal.fire({
                                title: 'Error',
                                html: ' <span style="color:red">' + data.message + '</span> ',
                                timer: 2000,
                                icon: 'error'
                            })
                        }
                    },
                    error: function (data) {
                        if (data.responseJSON.status == false) {
                            Swal.fire({
                                title: 'Error',
                                html: ' <span style="color:red">' + data.responseJSON.message + '</span> ',
                                timer: 2000,
                                icon: 'error'
                            })

                        }
                    }
                })
            }
        })
    });

    $(document).on("click", ".deleteItem", function () {
        let form_id = this.dataset.formid;
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $("#" + form_id).submit();
            } else if (result.dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Your imaginary file is safe :)",
                    "error"
                )
            }
        })
    });

    $(document).ready(function () {
        $(".multiple-basic-single").select2({
            placeholder: "Select Option",
        });

        $(".multiple-select-input").select2({
            tags: true,
            tokenSeparators: [','],
        })
    });




    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    window.getLanguage = function () {
        return {
            "sEmptyTable": "No Data Available In Table",
            "sInfo": "Showing START to END of TOTAL entries",
            "sInfoEmpty": "Showing 0 to 0 of 0 entries",
            "sInfoFiltered": "(filtered from MAX total entries)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Show MENU entries",
            "sLoadingRecords": "Loading...",
            "sProcessing": "Processing...",
            "sSearch": "Search:",
            "sZeroRecords": "No matching records found",
            "oPaginate": {
                "sFirst": "First",
                "sLast": "Last",
                "sNext": "Next",
                "sPrevious": "Previous"
            },
            "oAria": {
                "sSortAscending": ": activate to sort column ascending",
                "sSortDescending": ": activate to sort column descending"
            }
        };
    }


    window.currencyPrice = function ($price) {
        if (currencyPlacement == 'after')
            return $price + ' ' + currencySymbol;
        else {
            return currencySymbol + $price;
        }
    }

    window.dateFormat = function (date, format = 'MM-DD-YYYY') {
        return moment(date).format(format);
    }

    window.deleteItem = function (url, id, redirect_url = null) {
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        Swal.fire({
                            title: 'Deleted',
                            html: ' <span style="color:red">Item has been deleted</span> ',
                            timer: 2000,
                            icon: 'success'
                        })
                        toastr.success(data.message);
                        $('#' + id).DataTable().ajax.reload();
                        if (redirect_url) {
                            window.location.href = redirect_url;
                        }
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.message)
                    }
                })
            }
        })
    }

    window.commonAjax = function (type, url, successHandler, errorHandler, data) {
        if (typeof url == 'undefined') {
            return false;
        }
        var ajaxData = {
            type: type,
            url: url,
            dataType: 'json',
            success: successHandler,
            error: errorHandler
        }
        if (typeof (data) != 'undefined') {
            ajaxData.data = data;
        }
        if (type == 'POST' || type == 'post') {
            ajaxData.encType = 'enctype';
            ajaxData.contentType = false;
            ajaxData.processData = false;
        }
        $.ajax(ajaxData);
    }

    window.showMessage = function (response) {
        if (response.status == true) {
            alertAjaxMessage('success', response.message)
            location.reload()
        } else {
            alertAjaxMessage('error', response.message)
        }
    }

    window.commonHandler = function (data) {
        var output = '';
        var type = 'error';
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (data['status'] == false) {
            output = output + data['message'];
        } else if (data['status'] === 422) {
            var errors = data['responseJSON']['errors'];
            output = getValidationError(errors);
        } else if (data['status'] === 500) {
            output = data['responseJSON']['message'];
        } else if (typeof data['responseJSON']['error'] !== 'undefined') {
            output = data['responseJSON']['error'];
        } else {
            output = data['responseJSON']['message'];
        }
        alertAjaxMessage(type, output);
    }

    window.alertAjaxMessage = function (type, message) {
        if (type === 'success') {
            toastr.success(message);
        } else if (type === 'error') {
            toastr.error(message);
        } else if (type === 'warning') {
            toastr.error(message);
        } else {
            return false;
        }
    }

    window.getValidationError = function (errors) {
        var output = 'Validation Errors';
        $.each(errors, function (index, items) {
            if (index.indexOf('.') != -1) {
                var name = index.split('.');
                var getName = name.slice(0, -1).join('-');
                var i = name.slice(-1);
                var message = items[0];
                var itemSelect = $(document).find('.' + getName + ':eq(' + i + ')')
                itemSelect.addClass('is-invalid');
                itemSelect.closest('div').append('<span class="text-danger p-2 fs-12 z-index-10 position-relative error-message">' + message + '</span>')
            } else {
                var itemSelect = $(document).find("[name='" + index + "']");
                if (!itemSelect.length) {
                    itemSelect = $(document).find("[name^='" + index + "']");
                }
                itemSelect.addClass('is-invalid');
                itemSelect.closest('div').append('<span class="text-danger p-2 fs-12 z-index-10 position-relative error-message">' + items[0] + '</span>')
            }
        });
        return output;
    }

    window.settingCommonHandler = function (data) {
        var output = '';
        var type = 'error';
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');

        if (data['status'] == true) {
            output = output + data['message'];
            type = 'success';
            if ($('.modal.show').length) {
                $('.modal.show').modal('toggle')
            }
            if ($('.dataTable ').length) {
                $('.dataTable').DataTable().ajax.reload();
            }
            alertAjaxMessage(type, output);
            $('.reset-form')[0].reset();
            if ($(document).find('form.reset').length) {
                $(document).find('form.reset')[0].reset();
                if ($('.summernoteOne')) {
                    $('.summernoteOne').summernote('reset');
                }
                if ($('.upload-img-box').find('img')) {
                    $('.upload-img-box').find('img').attr('src', '');
                }
                if ($('.select2-hidden-accessible')) {
                    $('.select2-hidden-accessible').val(null).trigger('change');
                }
                if ($('.sf-select-without-search')) {
                    $('.sf-select-without-search').niceSelect('update');
                }
            }
        } else {
            commonHandler(data)
        }
    }

    window.getEditModal = function (url, modalId, callbackFunc){
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $(document).find(modalId).find('.modal-content').html(data);

                if ($(document).find(modalId).find('.sf-select-edit-modal').length) {
                    $(document).find(modalId).find('.sf-select-edit-modal').select2({
                        dropdownCssClass: "sf-select-dropdown",
                        selectionCssClass: "sf-select-section",
                        dropdownParent: $(modalId),
                    });
                }

                if ($(document).find(modalId).find('.sf-select-without-search').length) {
                    $(document).find(modalId).find('.sf-select-without-search').niceSelect();
                }

                if ($(document).find(modalId).find('.date-time-picker').length) {
                    $(document).find(modalId).find('.date-time-picker').each(function () {
                        $(this).closest(".primary-form-group-wrap").addClass("calendarIcon"); // Add your custom class here
                    });
                }

                if ($(document).find(modalId).find('.date-time-picker').length) {
                    $(document).find(modalId).find('.date-time-picker').daterangepicker({
                        singleDatePicker: true,
                        timePicker: true,
                        locale: {
                            format: "Y-M-D h:mm",
                        },
                    });
                }

                if ($(document).find(modalId).find('.sf-select-two').length) {
                    $(document).find(modalId).find('.sf-select-two').select2({
                        dropdownCssClass: "sf-select-dropdown",
                        selectionCssClass: "sf-select-section",
                        minimumResultsForSearch: -1,
                    });
                }

                if ($(document).find(modalId).find('.summernoteOne').length) {
                    $(document).find(modalId).find('.summernoteOne').summernote({
                        placeholder: "Write description...",
                        tabsize: 2,
                        minHeight: 183,
                        toolbar: [
                            // ["style", ["style"]],
                            // ["view", ["undo", "redo"]],
                            // ["fontname", ["fontname"]],
                            // ["fontsize", ["fontsize"]],
                            // ["font", ["bold", "italic", "underline"]],
                            // ["para", ["ul", "ol", "paragraph"]],
                            // ["color", ["color"]],
                            ["font", ["bold", "italic", "underline"]],
                            ["para", ["ul", "ol", "paragraph"]],
                        ],
                    });
                }

                $(modalId).modal('toggle');

                // Execute callback after modal is fully loaded and toggled
                if (typeof callbackFunc !== 'undefined'  && typeof window[callbackFunc] === 'function') {
                    window[callbackFunc]();
                }
            },
            error: function (error) {
                toastr.error(error.responseJSON.message)
            }
        })
    }

    window.commonResponseForModal = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message'])

            if ($('.modal.show').length) {
                $('.modal.show').modal('toggle');
            }

            if ($('.dataTable').length) {
                $('.dataTable').DataTable().ajax.reload();
            }
            else {
                //redirect to the specified URL
                if (response['data'] && response['data']['redirectUrl']) {
                    setTimeout(function () {
                        location.href = response['data']['redirectUrl'];
                    }, 1000);
                }else{
                    setTimeout(() => {
                        location.reload()
                    }, 1000);
                }
                
            }

            if ($(document).find('form.reset').length) {
                $(document).find('form.reset')[0].reset();
                if ($('.summernoteOne')) {
                    $('.summernoteOne').summernote('reset');
                }
                if ($('.upload-img-box').find('img')) {
                    $('.upload-img-box').find('img').attr('src', '');
                }
                if ($('.select2-hidden-accessible')) {
                    $('.select2-hidden-accessible').val(null).trigger('change');
                }
                if ($('.sf-select-without-search')) {
                    $('.sf-select-without-search').niceSelect('update');
                }
            }

        } else {
            commonHandler(response)
        }
    }

    window.commonResponseWithPageLoad = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message'])

            //redirect to the specified URL
            if (response['data'] && response['data']['redirectUrl']) {
                setTimeout(function () {
                    location.href = response['data']['redirectUrl'];
                }, 1000);
            }else{
                setTimeout(() => {
                    location.reload()
                }, 1000);
            }


        } else {
            commonHandler(response)
        }
    }

    window.commonResponse = function (response) {
        console.log('response===',response['data']);
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {

            if(response['data']['redirect_url']){
                setTimeout(function () {
                    location.href = response['data']['redirect_url'];
                }, 700);
            }else{
                toastr.success(response['message']);

                // if(response['data']['reload']){
                //     setTimeout(function () {
                //         location.reload();
                //     }, 700);
                // }

                const clientOrderId = response['data']['client_order_id'];

                if(clientOrderId){

                    if (typeof clientOrderId === "undefined" || clientOrderId === null || clientOrderId === "") {
                        console.error("clientOrderId is not set!");
                    } else {
                        let baseUrl = "/user/submission/select-a-journal/by-subject";
                        let stepOneRoute = baseUrl + "/update/" + clientOrderId;

                        let baseUrlStepTwo = "/user/submission/article-information";
                        let stepTwoRoute = baseUrlStepTwo + "/update/" + clientOrderId;
                    
                        if((response['data']['action'] === "step_one_save") || (response['data']['action'] === "step_two_save") ){ 
                            $(".step-one-route").attr("href", stepOneRoute);
                            $(".step-two-route").attr("href", stepTwoRoute);
                    
                            // Set value
                            $('.step-tow-client-order-id').val(clientOrderId);
                            
                            // Set name attribute dynamically
                            $('.step-tow-client-order-id').attr('name', 'id');
                        }
                    }

                }

                $(".reset").trigger("reset");
                $("#files-names").html(" ");
                if ($('#serviceImage ').length) {
                    $("#serviceImage").src('');
                }
                if ($('.dataTable ').length) {
                    $('.dataTable').DataTable().ajax.reload();
                }
                if ($('.modal.show').length) {
                    $('.modal.show').modal('toggle');
                }
                if ($(document).find('form.reset').length) {
                    $(document).find('form.reset')[0].reset();
                    if ($('.summernoteOne')) {
                        $('.summernoteOne').summernote('reset');
                    }
                    if ($('.upload-img-box').find('img')) {
                        $('.upload-img-box').find('img').attr('src', '');
                    }
                    if ($('.select2-hidden-accessible')) {
                        $('.select2-hidden-accessible').val(null).trigger('change');
                    }
                    if ($('.sf-select-without-search')) {
                        $('.sf-select-without-search').niceSelect('update');
                    }
                }
            }
        } else {
            commonHandler(response)
        }
    }

    window.getShowMessage = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message']);
        } else {
            commonHandler(response)
        }
    }

    window.commonResponseRedirect = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message']);
            if ($('.dataTable ').length) {
                $('.dataTable').DataTable().ajax.reload();
            }

            if ($(document).find('form.reset').length) {
                $(document).find('form.reset')[0].reset();
                if ($('.summernoteOne')) {
                    $('.summernoteOne').summernote('reset');
                }
                if ($('.upload-img-box').find('img')) {
                    $('.upload-img-box').find('img').attr('src', '');
                }
                if ($('.select2-hidden-accessible')) {
                    $('.select2-hidden-accessible').val(null).trigger('change');
                }
                if ($('.sf-select-without-search')) {
                    $('.sf-select-without-search').niceSelect('update');
                }
            }

            if ($('form').find(`[data-redirect-url]`)) {
                setTimeout(function () {
                    location.href = $(document).find(`[data-redirect-url]`).data('redirect-url');
                }, 700);
            }

        } else {
            commonHandler(response)
        }
    }

    window.gatewayCurrencyPrice = function ($price, $currency = '$') {
        if (currencyPlacement == 'after')
            return $price + ' ' + $currency;
        else {
            return $currency + ' ' + $price;
        }
    }

    function visualNumberFormat(value) {
        try {
            if (value == null || value == undefined || isNaN(value) || value == '') {
                return '0.00';
            }
            value = parseFloat(value);
            if (Number.isInteger(value)) {
                return value.toFixed(2);
            }
            const temp = value.toFixed(8);
            const number = temp.split('.');
            let floatValue = number[1];
            floatValue = floatValue.toString();
            const result = floatValue.replace(/[0]+$/, '');
            if (result.length < 2) {
                return value.toFixed(2);
            }

            return `${number[0]}.${result}`;
        } catch (e) {
            return '';
        }
    }
    window.visualNumberFormat = visualNumberFormat;

    $(document).on("click", ".subscriptionCancel", function () {
        let stateSelect = $(this);
        Swal.fire({
            title: 'Sure! You want to cancel?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Cancel It!'
        }).then((result) => {
            if (result.value) {
                stateSelect.closest('form').submit();
            } else if (result.dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Your imaginary file is safe :)",
                    "error"
                )
            }
        })
    });


})(jQuery)
