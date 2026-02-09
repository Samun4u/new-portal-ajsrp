(function ($) {

    $(document).on('click', '.service', function () {
        var state = $(this);
        var serviceId = state.val();
        var name = state.data('name');
        var price = state.data('price');
        if (state.is(':checked') == true) {
            $('#serviceItems').append(serviceItem(serviceId, name, price));
        } else {
            $('#serviceItem' + serviceId).remove();
        }
    });

    function serviceItem(id, name, price) {
        return `<div class="max-w-178 w-100 flex-grow-1 flex-shrink-0 bd-one bd-c-stroke bd-ra-8 py-sm-20 px-sm-14 p-10 d-flex justify-content-between align-items-center g-10" id="serviceItem${id}">
                    <div>
                        <h4 class="fs-12 fw-500 lh-15 text-title-black pb-5">${name}</h4>
                        <p class="fs-13 fw-500 lh-15 text-para-text">${price}</p>
                        <input type="hidden" name="service_ids[]" value="${id}">
                    </div>
                    <button class="rounded-circle bd-one bd-c-stroke p-0 bg-transparent w-25 h-25 d-flex justify-content-center align-items-center text-danger serviceItemRemoveBtn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>`;
    }

    $(document).on('click', '.serviceItemRemoveBtn', function () {
        $(this).parent().remove();
    });

    var orderFormsList;
    orderFormsList = $("#orderFormsList").DataTable({
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
        ajax: $('#orderFormIndexRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "name" },
            { "data": "form_id" },
            { "data": "link" },
            { "data": "action" }
        ]
    });
})(jQuery);
