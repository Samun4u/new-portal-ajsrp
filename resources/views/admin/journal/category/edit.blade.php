@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__("Edit Journal Category")}}</h4>
                    <!--  -->
                </div>
                <form class="ajax reset" action="{{route('admin.journals.category.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.journals.category.list')}}">
                    @csrf

                    <!--  -->
                    <div class="py-sm-30 px-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 px-sm-25">
                            <!--  -->
                            <input type="hidden" name="id" value="{{$journalCategory->id}}">
                            <div class="row rg-20 pb-20">
                                <div class="col-12">
                                        <label for="createCategoryName" class="zForm-label">{{__("Category Name (English)")}}
                                            <span class="text-red">*</span></label>
                                        <input type="text" class="form-control zForm-control" id="createCategoryName"
                                            placeholder="{{__('Category Name')}}" name="name" value="{{$journalCategory->name}}"/>
                                </div>
                                <div class="col-12">
                                        <label for="createCategoryNameInAr" class="zForm-label">{{__("Category Name (Arabic)")}}
                                            <span class="text-red">*</span></label>
                                        <input type="text" class="form-control zForm-control" id="createCategoryName"
                                            placeholder="{{__('Category Name')}}" name="name_ar" value="{{$journalCategory->arabic_name}}"/>
                                </div>
                                <div class="col-12">
                                    <label for="createServiceEvery"
                                            class="zForm-label">{{__("Status")}} <span class="text-red">*</span></label>
                                    <select class="sf-select-two" name="status">
                                        <option value="{{JOURNAL_CATEGORY_STATUS_ACTIVE}}" {{ $journalCategory->status == JOURNAL_CATEGORY_STATUS_ACTIVE ? 'selected' : '' }}>{{__("Active")}}</option>
                                        <option value="{{JOURNAL_CATEGORY_STATUS_INACTIVE}}" {{ $journalCategory->status == JOURNAL_CATEGORY_STATUS_INACTIVE ? 'selected' : '' }}>{{__("Inactive")}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__("Update")}}</button>
                        <a href="{{ route('admin.journals.category.list') }}"
                           class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/journal_category.js') }}"></script>
@endpush

