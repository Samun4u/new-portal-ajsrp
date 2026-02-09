@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <form class="ajax" action="{{ route('admin.order-form.store') }}" method="POST"
            data-handler="commonResponseWithPageLoad">
            @csrf
            <div class="max-w-894 m-auto">
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ $pageTitle }}</h4>
                </div>
                <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-30">
                    <div class="max-w-713 m-auto py-sm-52 py-15">
                        <div class="row rg-20">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-8">
                                    <label for="addOrderFormFormName" class="zForm-label mb-0">{{ __('Form Name') }}
                                        <span class="text-red">*</span>
                                    </label>
                                </div>
                                <input type="text" name="service_name" class="form-control zForm-control"
                                    id="addOrderFormFormName" placeholder="{{ __('Form Name') }}" />
                            </div>
                            <div class="col-12">
                                <label for="addOrderFormInformation"
                                    class="zForm-label">{{ __('Form Information') }}</label>
                                <textarea name="service_details" id="addOrderFormInformation" class="form-control zForm-control min-h-175"
                                    placeholder="{{ __('Form Information') }}"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-8">
                                    <label for="addOrderFormPolicyLink"
                                        class="zForm-label mb-0">{{ __('Terms & Conditions Link') }}</label>
                                </div>
                                <input type="text" name="policy_link" class="form-control zForm-control"
                                    id="addOrderFormPolicyLink" placeholder="{{ __('Policy Link') }}" />
                            </div>
                            <div class="col-12 table-responsive">
                                <table class="table zTable zTable-last-item-right" id="inputTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="text-nowrap">{{ __('Service Name') }}</div>
                                            </th>
                                            <th>
                                                <div>{{ __('Quantity') }}</div>
                                            </th>
                                            <th>
                                                <div>{{ __('Price') }}</div>
                                            </th>
                                            <th>
                                                <div>{{ __('Total') }}</div>
                                            </th>
                                            <th>
                                                <div>{{ __('Action') }}</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="serviceItems">
                                        <tr>
                                            <td>
                                                <select class="form-select service_id" name="service_ids[]">
                                                    <option value="">{{ __('Select Option') }}</option>
                                                    @foreach ($services as $key => $service)
                                                        <option value="{{ $service->id }}"
                                                            data-price="{{ $service->price }}">
                                                            {{ $service->service_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="min-w-100">
                                                    <input type="number" name="quantity[]" step="any" min="0"
                                                        value="1"
                                                        class="form-control zForm-control zForm-control-table quantity"
                                                        placeholder="{{ __('Quantity') }}" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="min-w-100">
                                                    <input type="number" name="price[]" step="any" min="0"
                                                        value="0"
                                                        class="form-control zForm-control zForm-control-table price"
                                                        placeholder="{{ __('Price') }}" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="min-w-100">
                                                    <input type="number" name="total[]" step="any" min="0"
                                                        value="0"
                                                        class="form-control zForm-control zForm-control-table total"
                                                        placeholder="{{ __('Total') }}" />
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="bd-one bd-c-stroke rounded-circle bg-transparent ms-auto w-30 h-30 d-flex justify-content-center align-items-center text-red serviceItemRemoveBtn">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button"
                                    class="mt-12 border-0 p-0 bg-transparent fs-14 fw-500 lh-22 text-main-color text-decoration-underline addServiceBtn">{{ __('+Add More Service') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="formAddFields" class="sf-sortable-form"></div>
                <input type="hidden" name="service_fields">
                <div class="pt-25 d-flex align-items-center flex-wrap g-10 order-form-buttons">
                    <button
                        class="bd-one bd-c-main-color bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white saveService">{{ __('Save') }}</button>
                    <a href="{{ route('admin.order-form.index') }}"
                        class="bd-one bd-c-para-text bd-ra-8 bg-white py-10 px-26 fs-15 fw-600 lh-25 text-para-text">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('script')
    <script>
        var services = {!! $services !!};


        var formAddFieldSelector = document.getElementById("formAddFields");
        var options = {
            disableHTMLLabels: false,
            controlPosition: "left",
            disabledActionButtons: ["data"],
            onCloseFieldEdit: false,
            disableFields: ["autocomplete", "file", "hidden", "header", "date", "paragraph", "button"],
            controlOrder: ["text", "number", "select", "checkbox-group", "radio-group"],
            showActionButtons: false,
            i18n: {
                locale: $('#lang_code').val(),
                location: '/assets/lang/',
                extension: '.lang'
            }
        };
        var formBuilder = $(formAddFieldSelector).formBuilder(options);
        $(document).on('click', '.saveService', function() {
            $(this).closest('form').find('input[name=service_fields]').val(formBuilder.actions.getData('json'))
        });
    </script>
    <script src="{{ asset('admin/custom/js/order-form.js') }}"></script>
@endpush
