@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Edit Journal') }}</h4>
                    <!--  -->
                </div>
                <form class="ajax reset" action="{{ route('admin.journals.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="commonResponseRedirect"
                    data-redirect-url="{{ route('admin.journals.list') }}">
                    @csrf

                    <!--  -->
                    <div class="py-sm-30 px-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 px-sm-25">
                            <!--  -->
                            <input type="hidden" name="id" value="{{ $journal->id }}">
                            <div class="row rg-20 pb-20">
                                <div class="col-12">
                                    <label for="createJournalTitle" class="zForm-label">{{ __('Journal Title (English)') }}
                                        <span class="text-red">*</span></label>
                                    <input type="text" class="form-control zForm-control" id="createJournalTitle"
                                        placeholder="{{ __('Journal Title (English)') }}" name="title"
                                        value="{{ $journal->title }}" />
                                </div>
                                <div class="col-12">
                                    <label for="createJournalTitleInAr"
                                        class="zForm-label">{{ __('Journal Title (Arabic)') }}
                                        <span class="text-red">*</span></label>
                                    <input type="text" class="form-control zForm-control" id="createJournalTitleInAr"
                                        placeholder="{{ __('Journal Title (Arabic)') }}" name="title_ar"
                                        value="{{ $journal->arabic_title }}" />
                                </div>
                                <div class="col-12">
                                    <label for="createJournalWebsite" class="zForm-label">{{ __('Website') }}
                                        <span class="text-red">*</span></label>
                                    <input type="text" class="form-control zForm-control" id="createJournalTitle"
                                        placeholder="{{ __('Website') }}" name="website"
                                        value="{{ $journal->website }}" />
                                </div>
                                {{-- <div class="col-12">
                                    <label for="createJournalPrice"
                                            class="zForm-label">{{__("Charge")}} <span class="text-red">*</span></label>
                                    <div class="sf-input-wrap">
                                        <div class="flex-grow-1">
                                            <input type="text" class="form-control zForm-control"
                                                    id="createJournalPrice" placeholder="0.00"
                                                    name="charge" value="{{$journal->charges}}" />
                                        </div>
                                        <p class="currency">{{currentCurrencyType()}}</p>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <label for="createJournalCategory" class="zForm-label">{{ __('Category') }} <span
                                            class="text-red">*</span></label>
                                    <select class="sf-select-two" name="journal_subject_id[]" multiple>
                                        @foreach ($journalCategoryList as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $journal->subjects->contains($category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="createJournalService" class="zForm-label">{{ __('Service') }} <span
                                            class="text-red">*</span></label>
                                    <select class="sf-select-two" name="journal_service_id">
                                        <option value="">Select</option>
                                        @foreach ($serviceList as $service)
                                            <option value="{{ $service->id }}"
                                                {{ $journal->service_id == $service->id ? 'selected' : '' }}>
                                                {{ $service->service_name }}
                                                ({{ currentCurrency('symbol') }}{{ $service->price }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="createJournalStatus" class="zForm-label">{{ __('Status') }} <span
                                            class="text-red">*</span></label>
                                    <select class="sf-select-two" name="status">
                                        <option value="{{ JOURNAL_STATUS_ACTIVE }}"
                                            {{ $journal->status == JOURNAL_STATUS_ACTIVE ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="{{ JOURNAL_STATUS_INACTIVE }}"
                                            {{ $journal->status == JOURNAL_STATUS_INACTIVE ? 'selected' : '' }}>
                                            {{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <h6 class="mt-3 mb-2">{{ __('OJS Integration Fields (Optional)') }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <label for="createJournalShortName" class="zForm-label">{{ __('Short Name') }}</label>
                                    <input type="text" class="form-control zForm-control" id="createJournalShortName"
                                        placeholder="{{ __('Short Name') }}" name="short_name"
                                        value="{{ $journal->short_name ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="createJournalOjsContext"
                                        class="zForm-label">{{ __('OJS Context') }}</label>
                                    <input type="text" class="form-control zForm-control" id="createJournalOjsContext"
                                        placeholder="{{ __('OJS journal path/slug') }}" name="ojs_context"
                                        value="{{ $journal->ojs_context ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="createJournalIssnPrint" class="zForm-label">{{ __('ISSN Print') }}</label>
                                    <input type="text" class="form-control zForm-control" id="createJournalIssnPrint"
                                        placeholder="{{ __('ISSN Print') }}" name="issn_print"
                                        value="{{ $journal->issn_print ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="createJournalIssnOnline"
                                        class="zForm-label">{{ __('ISSN Online') }}</label>
                                    <input type="text" class="form-control zForm-control" id="createJournalIssnOnline"
                                        placeholder="{{ __('ISSN Online') }}" name="issn_online"
                                        value="{{ $journal->issn_online ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="createJournalImpactFactor"
                                        class="zForm-label">{{ __('Impact Factor (IF)') }}</label>
                                    <input type="text" class="form-control zForm-control" id="createJournalImpactFactor"
                                        placeholder="{{ __('e.g. 1.23') }}" name="impact_factor"
                                        value="{{ $journal->impact_factor ?? '' }}" />
                                </div>
                                <div class="col-12">
                                    <h6 class="mt-3 mb-2">{{ __('Certificate Information (Automated)') }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <label for="editor_in_chief_en" class="zForm-label">{{ __('Editor-in-Chief Name (English)') }}</label>
                                    <input type="text" class="form-control zForm-control" id="editor_in_chief_en"
                                        placeholder="{{ __('Editor name in English') }}" name="editor_in_chief"
                                        value="{{ $journal->editor_in_chief ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="editor_in_chief_ar" class="zForm-label">{{ __('Editor-in-Chief Name (Arabic)') }}</label>
                                    <input type="text" class="form-control zForm-control" id="editor_in_chief_ar"
                                        placeholder="{{ __('Editor name in Arabic') }}" name="chief_editor_name_ar"
                                        value="{{ $journal->chief_editor_name_ar ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="managing_editor_en" class="zForm-label">{{ __('Managing Editor Name (English)') }}</label>
                                    <input type="text" class="form-control zForm-control" id="managing_editor_en"
                                        placeholder="{{ __('Managing editor name in English') }}" name="managing_editor_name_en"
                                        value="{{ $journal->managing_editor_name_en ?? '' }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="managing_editor_ar" class="zForm-label">{{ __('Managing Editor Name (Arabic)') }}</label>
                                    <input type="text" class="form-control zForm-control" id="managing_editor_ar"
                                        placeholder="{{ __('Managing editor name in Arabic') }}" name="managing_editor_name_ar"
                                        value="{{ $journal->managing_editor_name_ar ?? '' }}" />
                                </div>
                                <div class="col-md-4">
                                    <label for="signature_file" class="zForm-label">{{ __('Editor-in-Chief Signature') }}</label>
                                    <input type="file" class="form-control zForm-control" id="signature_file" name="signature_file" accept="image/*" />
                                    @if($journal->signature_path)
                                        <div class="mt-2 preview-container">
                                            <img src="{{ Storage::url($journal->signature_path) }}" alt="Signature" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="managing_signature_file" class="zForm-label">{{ __('Managing Editor Signature') }}</label>
                                    <input type="file" class="form-control zForm-control" id="managing_signature_file" name="managing_signature_file" accept="image/*" />
                                    @if($journal->managing_editor_signature_path)
                                        <div class="mt-2 preview-container">
                                            <img src="{{ Storage::url($journal->managing_editor_signature_path) }}" alt="Managing Signature" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="stamp_file" class="zForm-label">{{ __('Journal Seal / Stamp') }}</label>
                                    <input type="file" class="form-control zForm-control" id="stamp_file" name="stamp_file" accept="image/*" />
                                    @if($journal->stamp_path)
                                        <div class="mt-2 preview-container">
                                            <img src="{{ Storage::url($journal->stamp_path) }}" alt="Stamp" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{ __('Update') }}</button>
                        <a href="{{ route('admin.journals.list') }}"
                            class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/journal.js') }}"></script>
@endpush
