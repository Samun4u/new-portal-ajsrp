<div class="d-flex justify-content-between align-items-center flex-wrap g-10 pb-20 mb-20 bd-b-one bd-c-stroke">
    <h5 class="fs-18 fw-500 lh-24 text-title-black">{{ __('Update Service Setting') }}</h5>
    <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent" data-bs-dismiss="modal"
            aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="ajax reset" action="{{ route('super-admin.setting.service.store') }}" method="post"
      data-handler="commonResponseForModal">
    @csrf

    <div class="row rg-20">
        <input type="hidden" value="{{ $service->id }}" name="id">
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Name') }} <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="name" value="{{ $service->name }}"
                   placeholder="{{ __('name') }}">
        </div>

        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="title" placeholder="{{ __('title') }}"
                   value="{{ $service->title }}">
        </div>
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Sub Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="sub_title"
                   placeholder="{{ __('sub title') }}" value="{{ $service->sub_title }}">
        </div>
        <div class="col-12">
            <label for="currentPassword" class="zForm-label">{{ __('Description') }} <span
                    class="text-danger">*</span></label>
            <textarea class="form-control zForm-control" name="description" id="" cols="30" rows="6"
                      placeholder="{{ __('description') }}">{{ $service->description }}</textarea>
        </div>
        <div class="col-12">
            <label for="rtl" class="zForm-label">{{ __('Status') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search" name="status">
                <option {{ $service->status == ACTIVE ? 'selected' : '' }} value="1">
                    {{ __('Active') }}
                </option>
                <option {{ $service->status == DEACTIVATE ? 'selected' : '' }} value="0">
                    {{ __('Deactivate') }}
                </option>
            </select>
        </div>
        <div class="col-12">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                       alt=""/>
                        </div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                        </p>
                    </div>
                    <label for="zImageUpload" class="zForm-label">{{ __('Image') }} <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($service->image) }}"/>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewFile(this)"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit"
            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white mt-25">{{
            __('Update') }}</button>
</form>
