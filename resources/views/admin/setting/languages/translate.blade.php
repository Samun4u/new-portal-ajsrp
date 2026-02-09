@extends('admin.layouts.app')
@push('admin-style')
@endpush
@section('content')
<div class="p-30">
    <div class="">
        <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __($pageTitle) }}</h4>
        <div class="row bd-c-ebedf0 bd-half bd-ra-25 bg-white h-100 p-30">
            <input type="hidden" id="language-route" value="{{ route('admin.setting.languages.index') }}">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap gap-2 item-title justify-content-between mb-20">
                    <button type="button"
                        class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white"
                        data-bs-toggle="modal" data-bs-target="#importModal" title="Import Keywords">
                        {{__('Import Keywords')}}
                    </button>
                    <button type="button"
                        class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white addmore">
                        <i class="fa fa-plus"></i>
                        {{__('Add More')}}
                    </button>
                </div>
                <div class="table-responsive zTable-responsive">
                    <table class="table zTable">
                        <thead>
                            <tr>
                            <tr>
                                <th class="min-w-160"><div>{{ __('Key') }}</div></th>
                                <th class="min-w-160"><div>{{ __('Value') }}</div></th>
                                <th class="text-center w-28"><div>{{ __('Action') }}</div></th>
                            </tr>
                        </thead>
                        <tbody id="append">
                            @foreach ($translators as $key => $value)
                            <tr>
                                <td>
                                    <textarea type="text" class="key form-control" readonly required>{!! $key
                                        !!}</textarea>
                                </td>
                                <td>
                                    <input type="hidden" value="0" class="is_new">
                                    <textarea type="text" class="val form-control" required>{!! $value
                                        !!}</textarea>
                                </td>
                                <td class="text-end">
                                    <button type="button"
                                        class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                                        __('Update')
                                        }}</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal section start -->
<div class="modal fade" id="importModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="ajax" action="{{ route('admin.setting.languages.import') }}" method="POST"
                data-handler="languageHandler">
                @csrf
                <input type="hidden" name="current" value="{{ $language->iso_code }}">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Import Language') }}</h5>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                            data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-inner-form-box">
                        <div class="row">
                            <div class="mb-30">
                                <span class="text-danger text-center">{{ __('Note: If you import keywords, your current
                                    keywords will be deleted and replaced by the imported keywords.') }}</span>
                            </div>
                            <div class="col-md-6 mb-25">
                                <label for="status" class="label-text-title color-heading font-medium mb-2">
                                    {{ __('Language') }} </label>
                                <select name="import" class="sf-select flex-shrink-0 export" id="inputGroupSelect02">
                                    <option value=""> {{ __('Select Option') }} </option>
                                    @foreach ($languages as $lang)
                                    <option value="{{ $lang->iso_code }}">{{ __($lang->language) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <button type="button" class="border-0 theme-btn-back me-3" data-bs-dismiss="modal" title="Back">{{
                        __('Back')
                        }}</button>
                    <button type="submit" class="theme-btn me-3" title="Submit">{{ __('Import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="updateLangItemRoute"
    value="{{ route('admin.setting.languages.update.translate', [$language->id]) }}">
@endsection

@push('script')
<script src="{{asset('assets/js/languages.js')}}"></script>
@endpush
