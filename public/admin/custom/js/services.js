(function( $ ){
    ("use strict");

    $(document).on('click', '#addMoreFaqBtn', function () {
        // Find the first tr inside the #faqItems table
        let tr = $('#clone-faq').find('tr');

        // Clone the tr
        let clonedTr = tr.clone();

        // Clear input fields and textarea in the cloned tr
        clonedTr.find('input').val('');
        clonedTr.find('textarea').val('');

        // Append the cloned tr to the #faqItems table
        $(this).closest('form').find('#faqItems').append(clonedTr);
    });

    $(document).on('click', '.removeFaq', function () {
        let tr = $(this).closest('tr').remove();
    });

    $(document).on('click', '#addMoreFeatureBtn', function () {
        // Find the first tr inside the #faqItems table
        let tr = $('#clone-custom-features').find('tr');

        // Clone the tr
        let clonedTr = tr.clone();

        // Clear input fields and textarea in the cloned tr
        clonedTr.find('input').val('');

        // Append the cloned tr to the #faqItems table
        $(this).closest('form').find('#featureItems').append(clonedTr);
    });

    $(document).on('click', '.removeFeature', function () {
        let tr = $(this).closest('tr').remove();
    });

    $(document).on("click",".payment-type",function() {
        $("#payment_type").val($(this).data('payment_type'));
    });

    $(document).on("click",".delete-service",function() {
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
                commonAjax('GET', $('#serviceDeleteRoute').val(), serviceDeleteRes, serviceDeleteRes, { 'id': $(this).data('id') });
            }
        })
    });

    function serviceDeleteRes(response){
            $('.error-message').remove();
            $('.is-invalid').removeClass('is-invalid');
            if (response['status'] === true) {
                toastr.success(response['message']);
                setTimeout(() => {
                    window.location.href = $('#serviceListRoute').val();
                }, 1000);
            } else {
                commonHandler(response)
            }
    }

    $(document).on("change",".service-search",function() {
        commonAjax('GET', $('#serviceSearchRoute').val(), serviceSearchRes, serviceSearchRes, { 'keyword': $(this).val() });
    });

    function serviceSearchRes(response){
        console.log(response);
        $("#searchresult").html(response.data);
    }

    function serviceUpdateResponse(response){
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message']);
            }else {
            commonHandler(response)
        }
    }
    window.serviceUpdateResponse = serviceUpdateResponse;


})(jQuery);
