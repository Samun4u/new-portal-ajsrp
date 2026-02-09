<div class="modal-header p-20 border-0 pb-0">
    <h5 class="modal-title fs-18 fw-600 lh-24 text-1b1c17">{{ __('Update Packages') }}</h5>
    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent" data-bs-dismiss="modal"
        aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>

<form class="ajax reset" action="{{ route('super-admin.packages.store') }}" method="post"
    data-handler="commonResponseForModal">
    @csrf
    <input type="hidden" value="{{$package->id}}" name="id">
    <div class="modal-body mt-4">
        <div class="row rg-25">
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" value="{{$package->name}}" name="name"
                            placeholder="{{ __('Name') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Number Of Client') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" class="primary-form-control" name="number_of_client" placeholder="0"
                            value="{{$package->number_of_client}}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Number Of Order') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" class="primary-form-control" name="number_of_order" placeholder="0"
                            value="{{$package->number_of_order}}">
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="input__group">
                    <div class="primary-form-group">
                        <div class="primary-form-group-wrap">
                            <label class="form-label" for="rtl">{{ __('Custom Domain Setup') }} <span
                                    class="text-danger">*</span></label>
                            <select name="custom_domain_setup" class="sf-select-without-search">
                                <option {{$package->custom_domain_setup == CUSTOM_DOMAIN_SETUP_NO ? 'selected' : ''}}
                                    value="{{CUSTOM_DOMAIN_SETUP_NO}}">{{ __('No') }}</option>
                                <option {{$package->custom_domain_setup == CUSTOM_DOMAIN_SETUP_YES ? 'selected' : ''}}
                                    value="{{CUSTOM_DOMAIN_SETUP_YES}}">{{ __('Yes') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Access Community') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" value="{{$package->access_community}}" class="primary-form-control"
                            name="access_community" placeholder="{{ __('Access Community') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Support') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" value="{{$package->support}}" class="primary-form-control" name="support"
                            placeholder="Support">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input__group">
                    <div class="primary-form-group">
                        <div class="primary-form-group-wrap">
                            <label class="form-label" for="rtl">{{ __('Status') }} <span
                                    class="text-danger">*</span></label>
                            <select name="status" class="sf-select-without-search">
                                <option {{$package->status == ACTIVE ? 'selected' : ''}} value="{{ACTIVE}}">{{
                                    __('Active') }}
                                </option>
                                <option {{$package->status == DEACTIVATE ? 'selected' : ''}} value="{{DEACTIVATE}}">{{
                                    __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="input__group">
                    <div class="primary-form-group">
                        <div class="primary-form-group-wrap">
                            <label class="form-label" for="rtl">{{ __('Is Trail') }} <span
                                    class="text-danger">*</span></label>
                            <select name="is_trail" class="sf-select-without-search">
                                <option {{$package->is_trail == ACTIVE ? 'selected' : ''}} value="{{ACTIVE}}">{{
                                    __('Active') }}
                                </option>
                                <option {{$package->is_trail == DEACTIVATE ? 'selected' : ''}} value="{{DEACTIVATE}}">{{
                                    __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Monthly Price') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" value="{{$package->monthly_price}}" class="primary-form-control"
                            name="monthly_price" placeholder="0">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Yearly Price') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" value="{{$package->yearly_price}}" class="primary-form-control"
                            name="yearly_price" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Device Limit') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" value="{{$package->device_limit}}" class="primary-form-control"
                            name="device_limit" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="d-flex form-check ps-0">
                    <div class="zCheck form-check form-switch">
                        <input class="form-check-input" value="{{$package->is_popular}}" {{$package->is_popular ==
                        ACTIVE ? 'checked' : ''}} type="checkbox" name="is_popular" role="switch" id="flexCheckChecked"
                        />
                    </div>
                    <label class="form-check-label ps-3" for="flexCheckChecked">
                        {{ __('Is Popular') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        <button type="submit" class="m-0 fs-15 border-0 fw-500 lh-25 py-10 px-26 bg-cdef84 hover-bg-one bd-ra-12">{{
            __('Update') }}</button>
    </div>
</form>