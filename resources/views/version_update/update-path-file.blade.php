@extends('admin.layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="max-w-894 m-auto">
            <!-- Page Title -->
            <h4 class="fs-18 fw-600 lh-20 text-title-black pb-17">{{ $pageTitle }}</h4>

            <!-- Form for Uploading Script File -->
            <form method="POST" action="{{ route('admin.store-script-file') }}" enctype="multipart/form-data">
                @csrf
                <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                    <div class="max-w-713 m-auto py-sm-52 py-15">
                        <div class="mb-36">
                            <div class="row rg-20">
                                <!-- File Input -->
                                <div class="col-12">
                                    <label for="file" class="zForm-label">{{ __('File') }}</label>
                                    <input name="file" type="file" class="form-control zForm-control" id="file" placeholder="{{ __('Choose File') }}" />
                                    @if ($errors->has('file'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('file') }}</span>
                                    @endif
                                </div>

                                <!-- Path Input -->
                                <div class="col-12">
                                    <label for="path" class="zForm-label">{{ __('Path') }}</label>
                                    <input name="path" type="text" class="form-control zForm-control" id="path" placeholder="{{ __('Enter Path') }}" />
                                    @if ($errors->has('path'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('path') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Form for Downloading Script File -->
            <form method="POST" action="{{ route('admin.load-script-file') }}">
                @csrf
                <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                    <div class="max-w-713 m-auto py-sm-52 py-15">
                        <div class="mb-36">
                            <div class="row rg-20">
                                <!-- Path Input for Download -->
                                <div class="col-12">
                                    <label for="downloadPath" class="zForm-label">{{ __('Path') }}</label>
                                    <input name="path" type="text" class="form-control zForm-control" id="downloadPath" placeholder="{{ __('Enter Path') }}" />
                                    @if ($errors->has('path'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('path') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">{{ __('Download') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Content End -->
@endsection
