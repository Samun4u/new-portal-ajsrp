<div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-20 mb-20 bd-b-one bd-c-stroke">
    <h5 class="fs-18 fw-500 lh-24 text-title-black">{{ __('Section Update') }}</h5>
    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent" data-bs-dismiss="modal"
            aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="ajax reset" action="{{route('super-admin.setting.testimonial.update',$testimonial->id)}}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <input name="id" type="hidden" value="{{ $testimonial->id }}">

    <div class="row rg-20">
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Name') }} <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="name" value="{{ $testimonial->name }}"
                   required placeholder="{{ __('Type Name') }}">
        </div>
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Designation') }} <span
                    class="text-danger">*</span></label>
            <textarea class="form-control zForm-control" name="designation" rows="6"
                      placeholder="Description">{{ $testimonial->designation }}</textarea>
        </div>
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Comment') }} <span
                    class="text-danger">*</span></label>
            <textarea class="form-control zForm-control" name="comment" rows="6"
                      placeholder="Type Comment">{{ $testimonial->comment }}</textarea>
        </div>

        <div class="col-12">
            <label for="name" class="zForm-label">{{ __('Company Name') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="company_name" id="company_name" placeholder="{{ __('Company Name') }}"
                   class="form-control zForm-control" value="{{$testimonial->company_name}}">
        </div>

        <div class="col-12">
            <label for="name" class="zForm-label">{{ __('Rating') }} <span class="text-danger">*</span></label>
            <input type="number" name="rating" id="name" placeholder="1" class="form-control zForm-control" min="1"
                   max="5" value="{{$testimonial->rating}}">
        </div>


        <div class="col-12">
            <label for="iso_code" class="zForm-label">{{ __('Status') }} <span
                    class="text-danger">*</span></label>
            <select name="status" class="sf-select-without-search primary-form-control">
                <option {{ $testimonial->status == 1 ? 'selected' : '' }} value="1">{{ __('Active') }}
                <option {{ $testimonial->status == 0 ? 'selected' : '' }} value="0">{{ __('Deactivate') }}
            </select>
        </div>
        <div class="col-12">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap mt-4 zImage-upload-details mw-100">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                       alt=""/></div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                    </div>
                    <label for="zImageUpload" class="zForm-label">{{ __('Image') }} <span
                            class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($testimonial->image) }}"/>
                        <input type="file" name="image" id="flag" accept="image/*" onchange="previewFile(this)"/>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <button type="submit"
            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white mt-25">{{
            __('Update') }}</button>
</form>
