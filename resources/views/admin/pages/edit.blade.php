@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__($pageTitle)}}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <form class="ajax reset" action="{{route('admin.pages.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseRedirect"
                      data-redirect-url="{{route('admin.pages.list')}}">
                    @csrf
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <!--  -->
                            <div class="bd-b-one bd-c-stroke pb-40 mb-36">
                                <div class="row rg-20">
                                    <input type="hidden" name="id" value="{{$page->id}}">
                                    <div class="col-12">
                                        <label for="addTitle" class="zForm-label">{{__('Title')}}</label>
                                        <input name="title" type="text" class="form-control zForm-control"
                                               id="addTitle" placeholder="{{__('Enter Title')}}" value="{{ old('title', $page->title) }}" required/>
                                    </div>

                                    <div class="col-12">
                                        <label class="zForm-label">{{__('Content')}}</label>
                                        <textarea name="content" id="tinymce-content" class="form-control zForm-control" rows="10"> {{ old('content', $page->content) }}</textarea>
                                    </div>

                                    <div class="col-12">
                                        <label for="role" class="zForm-label">{{ __('Who can see') }}</label>
                                        <select class="sf-select-without-search" name="role_id" required>
                                            <option value="">{{ __('Select') }}</option>
                                            <option value="4" {{ $page->role_id == 4 ? 'selected' : '' }}>{{ __('Clients') }}</option>
                                            <option value="5" {{ $page->role_id == 5 ? 'selected' : '' }}>{{ __('Reviewers') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="status" class="zForm-label">{{ __('Status') }}</label>
                                        <select class="sf-select-without-search" name="status" required>
                                            <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                    
                                </div>

                            </div>

                        </div>
                    </div>
                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__('Save')}}</button>
                        <a href="{{ URL::previous() }}"
                           class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__('Cancel')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/pages.js') }}"></script>
    <script src="https://cdn.tiny.cloud/1/xji72nbks88sevgjk86fzuvy24rzgfv22qfpe5h65tw4tj70/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#tinymce-content',
            height: 400,
            menubar: false,
            plugins: 'link image code table lists',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image table code',
             readonly: false,
        });
    </script>
@endpush
