<div class="customers__area">
    <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
        <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Cookie Configuration') }}</h2>
        <div class="mClose">
            <button type="button" class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    <form class="ajax" action="{{ route('super-admin.setting.settings_env.update') }}" method="post"
        class="form-horizontal" data-handler="commonResponseForModal">
        @csrf
        <div class="row">
            <div class="col-12 mb-4">
                <label class="zForm-label">{{ __('Cookie Consent Text') }} </label>
                <textarea class="form-control zForm-control min-h-157" name="cookie_consent_text">{{ getOption('cookie_consent_text') }}</textarea>
            </div>
        </div>
        <div class="d-flex g-12 flex-wrap mt-25">
            <button class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white"
                type="submit">{{ __('Update') }}</button>
        </div>
    </form>
</div>
