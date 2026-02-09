@extends(auth()->user()->role == USER_ROLE_SUPER_ADMIN? 'sadmin.layouts.app':'admin.layouts.app');
{{--@push('style')--}}
    {{--    <style>--}}
    {{--        /* The total progress gets shown by event listeners */--}}
    {{--        #previews #total-progress {--}}
    {{--            opacity: 0;--}}
    {{--            height: 0;--}}
    {{--            transition: opacity 0.3s linear;--}}
    {{--        }--}}

    {{--        #previews .upload-completed {--}}
    {{--            height: 0;--}}
    {{--            opacity: 0;--}}
    {{--        }--}}

    {{--        #previews .dz-processing #total-progress {--}}
    {{--            opacity: 100;--}}
    {{--            height: 15px;--}}
    {{--            transition: opacity 0.3s linear;--}}
    {{--        }--}}

    {{--        #previews .file-upload.dz-success #total-progress {--}}
    {{--            opacity: 0;--}}
    {{--            height: 0;--}}
    {{--            transition: opacity 0.3s linear;--}}
    {{--        }--}}

    {{--        #previews .file-upload.dz-success .upload-completed {--}}
    {{--            opacity: 100;--}}
    {{--            height: 20px;--}}
    {{--        }--}}

    {{--        #previews .progress {--}}
    {{--            background-color: var(--bs-progress-bg) !important;--}}
    {{--        }--}}

    {{--        /* Hide the progress bar when finished */--}}
    {{--        #previews .file-upload.dz-success .progress {--}}
    {{--            opacity: 0;--}}
    {{--            height: 0;--}}
    {{--            transition: opacity 0.3s linear;--}}
    {{--        }--}}

    {{--        #previews .file-upload.dz-error .progress {--}}
    {{--            display: none;--}}
    {{--        }--}}


    {{--        /* Hide the delete button initially */--}}
    {{--        #previews .file-upload .delete-btn {--}}
    {{--            display: none;--}}
    {{--        }--}}

    {{--        /* Hide the start and cancel buttons and show the delete button */--}}

    {{--        #previews .file-upload.dz-success .start,--}}
    {{--        #previews .file-upload.dz-success .cancel {--}}
    {{--            display: none;--}}
    {{--        }--}}

    {{--        #previews .file-upload.dz-success .delete-btn {--}}
    {{--            display: block;--}}
    {{--        }--}}

    {{--        #previews .file-upload.dz-error .start-btn {--}}
    {{--            display: none;--}}
    {{--        }--}}

    {{--        .fs-7 {--}}
    {{--            font-size: 0.8rem;--}}
    {{--        }--}}
    {{--    </style>--}}
{{--@endpush--}}
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="customers__area">
            <div class="billing-center-area bg-white bd-ra-8 p-sm-25 p-15">
                @if (getCustomerCurrentBuildVersion() == $latestBuildVersion)
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-sm-8">
                            <div class="alert alert-info" type="info" icon="info-circle">
                                <i class="fa fa-info-circle"></i>
                                {{ __('You have the latest version of this app.') }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-8">
                        <div class="pb-15 bg-white bd-ra-8 overflow-hidden">
                            <table class="table zTable zTable-last-item-right">
                                <thead>
                                <tr>
                                    <th colspan="2">
                                        <div class="rounded-0 text-start">{{ __('System Details') }}</div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ __('Current Verasion') }}</td>
                                    <td>
                                        @if (getCustomerCurrentBuildVersion() == $latestBuildVersion)
                                            {{ getOption('current_version') }} <i
                                                class="fa  fa-check-circle text-success"></i>
                                        @else
                                            {{ getOption('current_version') }} <i data-bs-toggle="tooltip"
                                                                                  data-bs-placement="top"
                                                                                  title="download latest from codecanyon"
                                                                                  class="fa fa-warning text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                                @if (getCustomerCurrentBuildVersion() < $latestBuildVersion)
                                    <tr>
                                        <td>
                                            {{ __('Latest Version') }}
                                            <a class="text-link" target="_blank"
                                               href="{{$codecanyon_url}}">{{ __('Download Latest') }}</a>
                                        </td>
                                        <td>{{ $latestVersion }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Laravel Version') }}</td>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('PHP Version') }}</td>
                                    <td>{{ phpversion() }}</td>
                                </tr>
                                @if (!is_null($mysql_version))
                                    <tr>
                                        <td>{{ $databaseType }}</td>
                                        <td>{{ $mysql_version }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if (getCustomerCurrentBuildVersion() < $latestBuildVersion)
                    <div class="row justify-content-center pt-20">
                        <div class="col-md-8">
                            <div class="p-sm-25 p-15 bg-white bd-one bd-c-stroke bd-ra-8 mb-20" type="danger">
                                <ol class="mb-0">
                                    <li class="fs-16 fw-500 text-para-text">{{ __('Do not click update button if the application is customised. Your changes will be lost') }}
                                        .
                                    </li>
                                    <li class="fs-16 fw-500 text-para-text">{{ __('Take backup all the files and database before updating.') }}</li>
                                </ol>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody class="align-baseline">
                                    <tr>
                                        <td colspan="2">
                                            <div class="d-flex justify-content-center align-items-center cg-10">
                                                <div
                                                    class="sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-green py-10 px-26 fs-15 fw-600 lh-25 text-white"
                                                    id="dz-clickable">
                                                    <i class="fa fa-upload"></i>
                                                    <span>{{ __('Upload File') }}</span>
                                                </div>
                                                <div class="files" id="previews">

                                                    <div id="template" class="file-upload">
                                                        <table class="table table-borderless align-middle">
                                                            <tr>
                                                                <td>
                                                                    <div class="preview text-para-text"><i
                                                                            class="fa fa-file-archive h1"></i></div>
                                                                </td>
                                                                <td>
                                                                    <p class="name fs-14 fw-400 text-para-text"
                                                                       data-dz-name></p>
                                                                    <strong
                                                                        class="error fs-14 fw-400 text-danger error-message"
                                                                        data-dz-errormessage></strong>
                                                                </td>
                                                                <td>
                                                                    <p class="d-flex size fs-14 fw-400 text-para-text"
                                                                       data-dz-size></p>
                                                                </td>
                                                                <td>
                                                                    <div id="actions" class="d-flex cg-5">
                                                                        <button
                                                                            class="border-0 sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-green py-5 px-15 fs-15 fw-600 lh-25 text-white start start-btn">
                                                                            <i class="fa fa-upload"></i>
                                                                            <span>{{ __('Start') }}</span>
                                                                        </button>
                                                                        <button id="cancel-btn"
                                                                                class="border-0 sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-red py-5 px-15 fs-15 fw-600 lh-25 text-white cancel">
                                                                            <i class="fa fa-cancel"></i>
                                                                            <span>{{ __('Cancel') }}</span>
                                                                        </button>
                                                                        <a data-url="{{ route(auth()->user()->role == USER_ROLE_SUPER_ADMIN?'super-admin.file-version-update-execute':'admin.file-version-update-execute') }}"
                                                                           class="update-execute-btn sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-green py-5 px-15 fs-15 fw-600 lh-25 text-white delete-btn">
                                                                            <i
                                                                                class="fa fa-download mr-1"></i>
                                                                            {{ __('Update') }}
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                        <div class="progress progress-striped active col-md-12 p-0"
                                                             id="total-progress" role="progressbar"
                                                             aria-valuemin="0" aria-valuemax="100"
                                                             aria-valuenow="0">
                                                            <div class="progress-bar progress-bar-success"
                                                                 style="width:0%;" data-dz-uploadprogress></div>
                                                        </div>
                                                        <div
                                                            class="text-center fs-18 fw-500 lh-22 text-green pt-20 upload-completed">
                                                            <span>{{ __('Upload Completed') }}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                                @if ($errors->has('update_file'))
                                                    <span class="fs-15 fw-600 lh-25 text-red"><i
                                                            class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('update_file') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @if ($uploadedFile != '')
                                        <tr>
                                            <td>
                                                {{ $uploadedFile }}
                                                <a data-url="{{ route(auth()->user()->role == USER_ROLE_SUPER_ADMIN?'super-admin.file-version-delete':'admin.file-version-delete') }}"
                                                   data-reload="true"
                                                   class="sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-danger py-5 px-15 fs-15 fw-600 lh-25 text-white delete">
                                                    <i class="fa fa-trash mr-1"></i>
                                                    {{ __('Delete') }}
                                                </a>
                                            </td>
                                            <td>
                                                <a data-url="{{ route(auth()->user()->role == USER_ROLE_SUPER_ADMIN?'super-admin.file-version-update-execute':'admin.file-version-update-execute') }}"
                                                   class="update-execute-btn sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-green py-5 px-15 fs-15 fw-600 lh-25 text-white">
                                                    <i class="fa fa-download mr-1"></i>
                                                    {{ __('Update') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="billing-center-area bg-white bd-ra-8 p-sm-25 p-15 mt-20">
                <h4 class="fs-18 fw-600 lh-20 text-title-black pb-20">{{ __('Addons') }}</h4>

                @forelse($addons as $addon)

                    <div class="bd-one bd-c-stroke bd-ra-8 p-sm-25 p-15">
                        <div class="row align-items-center row-gap-2">
                            <div class="col-sm-6 col-md-2 col-lg-1">
                                <a href="{{ $addon->details->codecanyon_url }}" target="_blank">
                                    <img
                                        src="https://support.zainikthemes.com/uploaded_files/images/app_image/{{ $addon->logo }}"
                                        class="img-responsive img-thumbnail" alt="">
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-8 col-lg-5">
                                <div class="pb-10 d-flex align-items-center cg-10 flex-wrap">
                                    <a href="{{ $addon->details->codecanyon_url }}" target="_blank"
                                       class="fs-16 fw-500 lh-20 text-title-black">
                                        {{ $addon->title }}
                                    </a>
                                    @if (isAddonInstalled($addon->code) > 0)
                                        <span class="zBadge zBadge-active">{{ __('Installed') }}
                                            {{ getOption($addon->code . '_current_version') }}</span>
                                    @endif
                                </div>
                                <p class="fs-14 fw-500 lh-20 text-para-text">
                                    {!! $addon->details?->description !!}
                                </p>
                            </div>
                            <div class="col-md-2 col-lg-6 text-end rtl-versionUpdate-link">
                                <a href="{{ route(auth()->user()->role == USER_ROLE_SUPER_ADMIN?'super-admin.addon.details':'admin.addon.details', $addon->code) }}"
                                   class="sf-cursor-pointer d-inline-flex align-items-center cg-10 bd-ra-8 bg-main-color py-5 px-15 fs-15 fw-600 lh-25 text-white">
                                    <i class="fa-solid fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                @empty
                    <p class="alert alert-warning">No data found!</p>
                @endforelse

            </div>
        </div>
    </div>


    <!-- Page content area end -->
@endsection
@push('script')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>
    <script>
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "{{ route(auth()->user()->role == USER_ROLE_SUPER_ADMIN?'super-admin.file-version-update-store':'admin.file-version-update-store') }}", // Set the url
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            paramName: 'update_file',
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 1,
            acceptedFiles: '.zip',
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: "#dz-clickable" // Define the element that should be used as click trigger to select files.
        });


        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function () {
                myDropzone.enqueueFile(file);
            };
            $('#dz-clickable').addClass('d-none');
        });

        myDropzone.on("totaluploadprogress", function (progress) {
            var progressbar = document.querySelector("#total-progress .progress-bar");
            if (typeof progressbar != 'undefined' && progressbar != null) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
            }
        });

        myDropzone.on("error", function (file, response) {
            if (typeof response.errors != 'undefined') {
                $('#previews .error-message').text(response.errors?.update_file[0]);
            } else {
                $('#previews .error-message').text(response.message);
            }
        });

        $(document).on('click', '#cancel-btn', function () {
            myDropzone.removeAllFiles(true);
            $('#dz-clickable').removeClass('d-none');
        });

        $(document).on('click', '.update-execute-btn', function () {
            Swal.fire({
                title: "{{ __('Version Update Execute') }}",
                html: `<div class="alert alert-danger fs-7 px-0 text-start" type="danger">
                        <ol class="mb-0">
                            <li>Do not click update now button if the application is customised. Your changes will be lost.</li>
                            <li>Take backup all the files and database before updating.</li>
                        </ol>
                    </div>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Update Now'
            }).then((result) => {
                if (result.value) {
                    location.replace($(this).data('url'));
                }
            })
        })
    </script>
@endpush
