<div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-20 mb-20 bd-b-one bd-c-stroke">
    <h5 class="fs-18 fw-500 lh-24 text-title-black">{{ __('Section Update') }}</h5>
    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent" data-bs-dismiss="modal"
            aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="ajax reset" action="{{ route('super-admin.setting.frontend-setting.section.update') }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <input type="hidden" name="slug" value="{{ $section->slug }}">
    <input name="id" type="hidden" value="{{ $section->id }}">

    <div class="row rg-20">
        @if ($section->has_page_title == STATUS_ACTIVE)
            <div class="col-12">
                <label for="currentPassword" class="zForm-label">{{ __('Page Title') }} <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control zForm-control" name="page_title"
                       value="{{ $section->page_title }}" required placeholder="{{ __('Page Title') }}">
            </div>
        @endif
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="title" value="{{ $section->title }}"
                   required placeholder="{{ __('Title') }}">
        </div>
        @if ($section->has_description == STATUS_ACTIVE)
            <div class="col-12">
                <label for="currentPassword" class="zForm-label">{{ __('Description') }} <span
                        class="text-danger">*</span></label>
                <textarea class="form-control zForm-control" name="description" rows="6"
                          placeholder="Description">{{ $section->description }}</textarea>
            </div>
        @endif
        <div class="col-12">
            <label for="iso_code" class="zForm-label">{{ __('Status') }} <span
                    class="text-danger">*</span></label>
            <select name="status" class="sf-select-without-search primary-form-control">
                <option {{ $section->status == 1 ? 'selected' : '' }} value="1">{{ __('Active') }}
                <option {{ $section->status == 0 ? 'selected' : '' }} value="0">
                {{ __('Deactivate') }}
            </select>
        </div>
        {{-- @if ($section->has_image == STATUS_ACTIVE)
        <div class="col-12">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                alt="" /></div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                    </div>
                    <label for="zImageUpload" class="zForm-label">{{ __('Image') }} <span class="text-mime-type">{{
                            __('(jpeg,png,jpg,svg,webp)') }}</span> <span class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($section->image) }}" />
                        <input type="file" name="image" id="flag" accept="image/*" onchange="previewFile(this)" />
                    </div>
                </div>
            </div>
        </div> --}}
        @if ($section->has_banner_image == STATUS_ACTIVE)
            <div class="col-12">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap zImage-upload-details mw-100">
                        <div class="zImage-inside">
                            <div class="d-flex pb-12"><img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                           alt=""/>
                            </div>
                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                        </div>
                        <label for="zImageUpload" class="zForm-label">{{ __('Banner Image') }} <span
                                class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span> <span
                                class="text-danger">*</span></label>
                        <div class="upload-img-box">
                            <img src="{{ getFileUrl($section->banner_image) }}"/>
                            <input type="file" name="banner_image" id="flag" accept="banner_image/*"
                                   onchange="previewFile(this)"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- @endif --}}
    </div>

    <button type="submit"
            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white mt-25">{{
            __('Update') }}</button>
</form>
