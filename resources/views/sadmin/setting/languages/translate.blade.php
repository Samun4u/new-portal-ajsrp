@extends('sadmin.layouts.app')
@push('admin-style')
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-title-black pb-16">{{ __($title) }}</h4>
            <div class="row bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                <input type="hidden" id="language-route" value="{{ route('super-admin.setting.languages.index') }}">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-2 item-title justify-content-between mb-20">
                        <button type="button"
                                class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white"
                                data-bs-toggle="modal" data-bs-target="#importModal" title="Import Keywords">
                            {{ __('Import Keywords') }}
                        </button>
                        <button  type="button"
                                class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white addmore">

                            {{ __('+ Add More') }}
                        </button>
                    </div>
                    <div class="table-responsive zTable-responsive">
                        <table class="table zTable zTable-last-item-right">
                            <thead>
                            <tr>
                            <tr>
                                <th class="min-w-160">
                                    <div>{{ __('Key') }}</div>
                                </th>
                                <th class="min-w-160">
                                    <div>{{ __('Value') }}</div>
                                </th>
                                <th class="w-28">
                                    <div>{{ __('Action') }}</div>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="append">
                            @foreach ($translators as $key => $value)
                                <tr>
                                    <td>
                                    <textarea type="text" class="key form-control zForm-control" readonly
                                              required>{!! $key !!}</textarea>
                                    </td>
                                    <td>
                                        <input type="hidden" value="0" class="is_new">
                                        <textarea type="text" class="val form-control zForm-control"
                                                  required>{!! $value !!}</textarea>
                                    </td>
                                    <td class="text-end">
                                        <button type="button"
                                                class="updateLangItem border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                                        __('Update') }}</button>
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
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <form class="ajax" action="{{ route('super-admin.setting.languages.import') }}" method="POST"
                      data-handler="languageHandler">
                    @csrf
                    <input type="hidden" name="current" value="{{ $language->iso_code }}">

                    <div
                        class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                        <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Import Language') }}</h2>
                        <div class="mClose">
                            <button type="button"
                                    class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                    data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row rg-20">
                        <div class="">
                                <span class="text-danger text-center">{{ __('Note: If you import keywords, your current
                                    keywords will be deleted and replaced by the imported keywords.') }}</span>
                        </div>
                        <div class="col-md-12">
                            <label for="status" class="zForm-label">
                                {{ __('Language') }} </label>
                            <select name="import" class="sf-select flex-shrink-0 export" id="inputGroupSelect02">
                                <option value=""> {{ __('Select Option') }} </option>
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang->iso_code }}">{{ __($lang->language) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start g-10 mt-20">
                        <button type="button"
                                class="border-0 bg-body-bg py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-title-black"
                                data-bs-dismiss="modal" title="Back">{{ __('Back') }}</button>
                        <button type="submit"
                                class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white"
                                title="Submit">{{ __('Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="updateLangItemRoute"
           value="{{ route('super-admin.setting.languages.update.translate', [$language->id]) }}">
@endsection

@push('script')
    <script src="{{asset('assets/js/languages.js')}}"></script>
@endpush
