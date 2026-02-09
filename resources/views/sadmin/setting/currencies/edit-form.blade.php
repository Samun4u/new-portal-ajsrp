<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Update Currency') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('super-admin.setting.currencies.update', $currency->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    @method('PATCH')

    <div class="row rg-20">
        <div class="col-12">
            <label for="currency_code" class="zForm-label">{{ __('Currency ISO Code') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-edit-modal primary-form-control" id="currency_code"
                    name="currency_code">
                @foreach (getCurrency() as $code => $currencyItem)
                    <option value="{{ $code }}" {{ $code==$currency->currency_code ? 'selected' : '' }}>{{
                                $currencyItem }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label for="symbol" class="zForm-label">{{ __('Symbol') }}<span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="symbol" placeholder="Type symbol"
                   value="{{ $currency->symbol }}" required>
        </div>
        <div class="col-12">
            <label for="currency_placement" class="zForm-label">{{ __('Currency Placement') }}<span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search primary-form-control" name="currency_placement">
                <option value="">--{{ __('Select Option') }}--</option>
                <option {{ $currency->currency_placement == 'before' ? 'selected' : '' }} value="before">
                    {{ __('Before Amount') }}</option>
                <option {{ $currency->currency_placement == 'after' ? 'selected' : '' }} value="after">
                    {{ __('After Amount') }}</option>
            </select>
        </div>
        <div class="col-12 mt-4">
            <div class="d-flex form-check ps-0">
                <div class="zCheck form-switch">
                    <input class="form-check-input mt-0" value="1" name="current_currency" {{
                            $currency->current_currency == STATUS_ACTIVE ? 'checked' : '' }} type="checkbox"
                           id="flexCheckChecked--{{ $currency->id }}">
                </div>
                <label class="form-check-label ps-3 d-flex" for="flexCheckChecked-{{ $currency->id }}">
                    {{ __('Current Currency') }}
                </label>
            </div>
        </div>

    </div>

    <div class="d-flex g-12 flex-wrap mt-25">
        <button class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white"
                type="submit">{{
                    __('Update') }}</button>
    </div>
</form>
