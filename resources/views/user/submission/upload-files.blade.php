@extends('user.submission.main')
@push('style')
<style>

    /* required file name style */
    .show-required-file-name {
        display: flex;
        align-items: center; /* Align items vertically in the center */
        gap: 10px; /* Add some space between the button and the file name */
    }

    /* cover letter file name style */
    .cover-letter-show-required-file-name {
        display: flex;
        align-items: center; /* Align items vertically in the center */
        gap: 10px; /* Add some space between the button and the file name */
    }

    /* cover letter file name style */
    .supplimental-show-required-file-name {
        display: flex;
        align-items: center; /* Align items vertically in the center */
        gap: 10px; /* Add some space between the button and the file name */
    }

    .supplementary-files-container {
    margin-left: 150px;
    width: 100%;
    max-width: 400px;
}

.supplementary-file-display-item,
.new-supplementary-file-display-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    /* border-bottom: 1px solid #eee; */
}

.new-supplementary-files {
    margin-top: 10px;
}

/* Mobile responsive */
@media (max-width: 768px) {
  /* General mobile adjustments */
  .step-three-file-upload-card-white {
    flex-direction: column;
  }

  .step-three-file-upload-left,
  .step-three-file-upload-right {
    width: 100% !important;
    padding: 0;
  }

  /* Required file section */
  .show-required-file-name {
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
  }

  .show-required-file-name .upload-file {
    width: 100%;
  }

  .file-name-display {
    margin-left: 0 !important;
    width: 100% !important;
  }

  /* Cover letter section */
  .cover-letter-show-required-file-name {
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
  }

  .cover-letter-file-name-display {
    margin-left: 0 !important;
    width: 100% !important;
  }

  /* Supplementary files section */
  .supplimental-show-required-file-name {
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
  }

  .supplementary-files-container {
    margin-left: 0 !important;
    width: 100% !important;
  }

  .supplementary-file-display-item,
  .new-supplementary-file-display-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 0;
  }

  /* Button adjustments */
  .upload-file {
    width: 100%;
    padding: 12px;
  }

  /* File name text adjustments */
  .file-name span,
  .cover-letter-file-name-display span,
  .supplementary-file-display-item span {
    display: inline-block;
    max-width: 80%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* Delete button adjustments */
  .delete-cover-letter,
  .delete-supplementary {
    padding: 0;
    width: 100%;
    justify-content: flex-start;
  }
}





</style>
@endpush
@section('submission-content')
<!-- step 3 -->
<div class="tab-pane fade {{ $step === 'stepThree' ? 'show active' : '' }}" id="v-pills-stepThree" role="tabpanel"
                    aria-labelledby="v-pills-stepThree-tab" tabindex="0">

                    <div class="step-three">
                        <form action="{{ route('user.submission.upload.files.save') }}" method="POST" data-handler="commonResponse" class="ajax"
                        enctype="multipart/form-data"
                        >
                        @csrf
                        <input type="hidden" class="step-three-client-order-id"  name="id" value="{{ $clientOrderId }}" >
                            <div class="header-title">
                                <h2>{{ __('Step 3') }}: {{ __('Upload Files') }}</h2>
                            </div>

                            <div class="step-three-file-upload">

                                <h3>{{ __('Required Files') }} <span>*</span></h3>

                                <div class="step-three-file-upload-card">

                                    <div class="step-three-file-upload-card-white">

                                        <!-- left -->
                                        <div class="step-three-file-upload-left">

                                            <h4>{{ __('Full Article File') }} <span>*</span></h4>

                                            <ul>
                                                <li>
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ __('Required') }}
                                                </li>

                                                <li>
                                                    <i class="fas fa-check"></i>
                                                    {{ __('Allow .doc, .docx, .tex format') }}
                                                </li>

                                                <li>
                                                    <i class="fas fa-folder-open"></i>
                                                    {{ __('Maximum of 1 main manuscript') }}
                                                </li>
                                            </ul>

                                        </div>

                                        <!-- right -->
                                        <div class="step-three-file-upload-right">

                                            <!-- Hidden field to track existing file -->
                                            <input type="hidden" name="existing_file"
                                                value="{{ $clientOrderSubmission->full_article_file ? '1' : '0' }}">

                                            <div class="show-required-file-name">
                                                <button class="upload-file required-upload-file-validation" type="button">
                                                    <i class="fas fa-folder-plus"></i>
                                                    {{ __('Upload File') }}
                                                    <input type="file" name="full_article_file">
                                                </button>
                                                @if($clientOrderSubmission->full_article_file ?? false)
                                                <div class="file-name-display" style="margin-left: 150px; width: 100%; max-width: 400px;">
                                                    <div class="file-name" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                                                        <span style="color: #2e4453; font-size: 14px;"> {{ getFileData($clientOrderSubmission->full_article_file,'original_name') }}</span>
                                                    </div>
                                                </div>

                                                @endif
                                            </div>

                                            <!-- alert -->
                                            <div class="after-upload">
                                                    <h6 class="alert"></h6>
                                            </div>



                                        </div>


                                    </div>

                                </div>

                                <h3>{{ __('Optional Files') }}</h3>

                                <div class="step-three-file-upload-card">

                                    <!-- item -->
                                    <div class="step-three-file-upload-card-white">

                                        <!-- left -->
                                        <div class="step-three-file-upload-left">

                                            <h4>{{ __('Cover Letter') }}</h4>

                                            <ul>
                                                <li>
                                                    <i class="fas fa-star"></i>
                                                    {{ __('Optional') }}
                                                </li>

                                                <li>
                                                    <i class="fas fa-check"></i>
                                                    {{ __('Allow .doc, .docx, .pdf format') }}
                                                </li>

                                                <li>
                                                    <i class="fas fa-folder-open"></i>
                                                    {{ __('Maximum of 1 file') }}
                                                </li>
                                            </ul>

                                        </div>

                                        <!-- right -->
                                        <div class="step-three-file-upload-right">
                                        <div class="cover-letter-show-required-file-name">
                                            <button class="upload-file" type="button">
                                                <i class="fas fa-folder-plus"></i>
                                                {{ __('Upload File') }}
                                                <input type="file" name="cover_letter_file">

                                            </button>
                                            @if($clientOrderSubmission->covert_letter_file ?? false)
                                            <div class="cover-letter-file-name-display" style="margin-left: 150px; width: 100%; max-width: 400px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                                                    <span style="color: #2e4453; font-size: 14px;">
                                                        {{ getFileData($clientOrderSubmission->covert_letter_file,'original_name') }}
                                                    </span>
                                                    <button class="delete-cover-letter" data-file-id="{{ $clientOrderSubmission->covert_letter_file }}" style="background: none; border: none; color: #0085ba; font-size: 14px; cursor: pointer; display: flex; align-items: center;">
                                                        <i class="fas fa-trash-alt" style="margin-right: 5px;"></i>
                                                        {{ __('Delete') }}
                                                    </button>
                                                </div>
                                            </div>
                                            @endif

                                        </div>


                                        </div>


                                    </div>

                                    <!-- item -->
                                    <div class="step-three-file-upload-card-white">

                                        <!-- left -->
                                        <div class="step-three-file-upload-left">

                                            <h4>{{ __('Supplementary Materials') }}</h4>

                                            <ul>
                                                <li>
                                                    <i class="fas fa-star"></i>
                                                    {{ __('Optional') }}
                                                </li>

                                                <li>
                                                    <i class="fas fa-check"></i>
                                                    {{ __('Allow most document files') }}
                                                </li>

                                                <li>
                                                    <i class="fas fa-folder-open"></i>
                                                    {{ __('Maximum of 5 files') }}
                                                </li>
                                            </ul>

                                        </div>

                                        <!-- right -->
                                        <div class="step-three-file-upload-right">

                                        <div class="supplimental-show-required-file-name">
                                            <button class="upload-file" type="button">
                                                <i class="fas fa-folder-plus"></i>
                                                {{ __('Add Files') }}
                                                <input type="file" name="supplementary_files[]" multiple>

                                            </button>
                                            <div class="supplementary-files-container" style="margin-left: 150px; width: 100%; max-width: 400px;">
                                                @if(count($clientOrderSubmission->supplyment_material_files) > 0)
                                                <div class="supplementary-file-display">
                                                    @foreach ($clientOrderSubmission->supplyment_material_files as $file)
                                                    <div class="supplementary-file-display-item">
                                                        <span style="color: #2e4453; font-size: 14px;">
                                                            {{ getFileData($file->file_id,'original_name') }}
                                                        </span>
                                                        <button class="delete-supplementary" data-file-id="{{ $file->file_id }}" style="background: none; border: none; color: #0085ba; font-size: 14px; cursor: pointer; display: flex; align-items: center;">
                                                            <i class="fas fa-trash-alt" style="margin-right: 5px;"></i>
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                                <div class="new-supplementary-files"></div>
                                            </div>
                                        </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- continue -->
                            <div class="continue-button">

                                <!-- right -->
                                <div class="right">
                                    <a href="{{ route('user.submission.article.information',['action' => 'update','id' => $clientOrderId]) }}">
                                        <button class="previous" type="button">{{ __('Previous Step') }}</button>
                                    </a>
                                </div>

                                <!-- left -->
                                <div class="left">
                                    <button type="submit" id="save_button" value="save" class="previous">{{ __('Save') }}</button>
                                    <button type="submit" id="save_and_continue_button" value="save_and_continue" class="continue"> {{ __('Save and Continue') }}</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
@endsection
@push('script')
<script>
  (function($) {
    "use strict";

    $(document).ready(function() {
        // Cover Letter File Upload
        $('input[name="cover_letter_file"]').change(function() {
            const file = this.files[0];
            const container = $(this).closest('.cover-letter-show-required-file-name');

            // Clear previous displays
            container.find('.cover-letter-file-name-display').remove();

            if (file) {
                // Create display element
                const fileDisplay = $(`
                    <div class="cover-letter-file-name-display" style="margin-left: 150px; width: 100%; max-width: 400px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                            <span style="color: #2e4453; font-size: 14px;">${file.name}</span>
                            <button class="delete-new-file" type="button" style="background: none; border: none; color: #0085ba; font-size: 14px; cursor: pointer; display: flex; align-items: center;">
                                <i class="fas fa-trash-alt" style="margin-right: 5px;"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                `);

                // Add delete handler for new files
                fileDisplay.find('.delete-new-file').click(() => {
                    $(this).val(''); // Clear file input
                    fileDisplay.remove();
                });

                container.append(fileDisplay);
            }
        });

        let supplementaryFiles = [];

// Function to render supplementary files
function renderSupplementaryFiles() {
    const container = $('.supplimental-show-required-file-name');
    const newFilesContainer = container.find('.new-supplementary-files');
    newFilesContainer.empty(); // Clear current display

    supplementaryFiles.forEach((file, index) => {
        const fileEntry = $(`
            <div class="new-supplementary-file-display-item">
                <span style="color: #2e4453; font-size: 14px;">
                    ${file.name}
                </span>
                <button class="delete-new-file" type="button" data-file-index="${index}" style="background: none; border: none; color: #0085ba; font-size: 14px; cursor: pointer; display: flex; align-items: center;">
                    <i class="fas fa-trash-alt" style="margin-right: 5px;"></i>
                    Delete
                </button>
            </div>
        `);

        // Delete handler for new files
        fileEntry.find('.delete-new-file').click(() => {
            supplementaryFiles.splice(index, 1); // Remove file from array
            const dt = new DataTransfer();
            supplementaryFiles.forEach(f => dt.items.add(f));
            $('input[name="supplementary_files[]"]')[0].files = dt.files;
            renderSupplementaryFiles(); // Re-render display
        });

        newFilesContainer.append(fileEntry);
    });
}

$('input[name="supplementary_files[]"]').change(function() {
    const newFiles = Array.from(this.files);
    const container = $(this).closest('.supplimental-show-required-file-name');
    const existingCount = {{ $clientOrderSubmission->supplyment_material_files->count() ?? 0 }};

    // Clear previous errors
    container.find('.error-message').remove();

    // Validate total files
    if (existingCount + supplementaryFiles.length + newFiles.length > 5) {
        container.append(`
            <div class="error-message" style="color: red; margin-top: 10px;">
                <i class="fas fa-exclamation-triangle"></i>
                Maximum 5 files allowed (${existingCount} already uploaded)
            </div>
        `);
        this.value = '';
        return;
    }

    // Add new files avoiding duplicates
    newFiles.forEach(newFile => {
        if (!supplementaryFiles.some(f => f.name === newFile.name)) {
            supplementaryFiles.push(newFile);
        }
    });

    // Update input files
    const dt = new DataTransfer();
    supplementaryFiles.forEach(f => dt.items.add(f));
    this.files = dt.files;

    // Render files
    renderSupplementaryFiles();
});




        // Delete Cover Letter
        $('.cover-letter-show-required-file-name').on('click', '.delete-cover-letter', function(e) {
            e.preventDefault();
            const fileId = $(this).data('file-id');

            if (confirm('Are you sure you want to delete this file?')) {
                $.ajax({
                    url: "{{ route('user.submission.deleteCoverLetter') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        file_id: fileId
                    },
                    success: (response) => {
                        if (response.success) {
                            $(this).closest('.cover-letter-file-name-display').remove();
                        }
                    }
                });
            }
        });

        // Existing Supplementary File Delete
        $('.supplimental-show-required-file-name').on('click', '.delete-supplementary', function(e) {
            e.preventDefault();
            const fileId = $(this).data('file-id');

            if (confirm('Are you sure you want to delete this file?')) {
                $.ajax({
                    url: "{{ route('user.submission.deleteSupplementary') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        file_id: fileId
                    },
                    success: (response) => {
                        if (response.success) {
                            $(this).closest('.supplementary-file-display-item').remove();
                        }
                    }
                });
            }
        });



        // When either save button is clicked
        $('#save_button, #save_and_continue_button').click(function(e) {
            e.preventDefault();

            // Clear any existing custom error messages
            $('.custom-error-message').remove();
            $('.additional-help-message').remove();

            // Check if file is uploaded
            let fileInput = $('.required-upload-file-validation input[type="file"]');
            const hasExistingFile = document.querySelector('input[name="existing_file"]').value === '1';
            let hasErrors = false;


            // Check if a file is uploaded
            if (!hasExistingFile && !fileInput.files) {

                if (fileInput[0].files.length === 0) {
                    // No file selected
                    showFileErrorMessage("{{ __('Full Article File is required.') }}");
                    hasErrors = true;
                } else {
                    // Check file extension
                    let fileName = fileInput[0].files[0].name;
                    let fileExt = fileName.split('.').pop().toLowerCase();

                    if (!['doc', 'docx', 'tex'].includes(fileExt)) {
                        showFileErrorMessage("{{ __('Only .doc, .docx, and .tex files are allowed.') }}");

                        // Show additional help message for file format errors
                        showAdditionalHelpMessage();

                        hasErrors = true;
                    }

                    // Check file size (20MB limit)
                    if (fileInput[0].files[0].size > 20 * 1024 * 1024) {
                        showFileErrorMessage("{{ __('File size exceeds 20MB limit.') }}");
                        hasErrors = true;
                    }
                }
            }




            // If no errors, submit the form
            if (!hasErrors) {
                // Your form submission code here
                $(this).closest('form').submit();
            }
        });

        // Function to display file error message
        function showFileErrorMessage(message) {
            // Add error message after the alert area
            $('.after-upload h6.alert').after(
                '<h6 class="alert custom-error-message" style="color: red; margin-top: 10px;">' +
                '<i class="fas fa-exclamation-triangle"></i> ' +
                message + '</h6>'
            );

            // Also highlight the upload button with a red border
            $('.required-upload-file-validation').css('border', '1px solid red');
        }

        // Function to show additional help message
        function showAdditionalHelpMessage() {
            // Add the additional help message
            $('.after-upload').append(
                '<p class="additional-help-message" style="margin-top: 10px; font-style: italic;">' +
                'If you fail to submit your manuscript, kindly forward it to ' +
                '<a href="mailto:submit@ajsrp.com">submit@ajsrp.com</a> ' +
                'along with your username and the chosen journal information.' +
                '</p>'
            );
        }

        // File input change handler
        $('.required-upload-file-validation input[type="file"]').change(function() {
            $('.custom-error-message').remove();
            $('.additional-help-message').remove();
            $('.upload-file').css('border', '');

            $('.file-name-display').remove();

            // Clear any existing custom error messages
            $('.custom-error-message').remove();
            $('.additional-help-message').remove();

            // Check if file is uploaded
            let fileInput = $('.required-upload-file-validation input[type="file"]');
            let hasErrors = false;

            if (fileInput[0].files.length === 0) {
                // No file selected
                showFileErrorMessage("{{ __('Full Article File is required.') }}");
                hasErrors = true;
            } else {
                // Check file extension
                let fileName = fileInput[0].files[0].name;
                let fileExt = fileName.split('.').pop().toLowerCase();

                if (!['doc', 'docx', 'tex'].includes(fileExt)) {
                    showFileErrorMessage("{{ __('Only .doc, .docx, and .tex files are allowed.') }}");

                    // Show additional help message for file format errors
                    showAdditionalHelpMessage();

                    hasErrors = true;
                }

                // Check file size (20MB limit)
                if (fileInput[0].files[0].size > 20 * 1024 * 1024) {
                    showFileErrorMessage("{{ __('File size exceeds 20MB limit.') }}");
                    hasErrors = true;
                }
            }

            if (!hasErrors) {
                document.querySelector('input[name="existing_file"]').value = '0';
                let fileName = '';
                if (this.files && this.files.length > 0) {
                    fileName = this.files[0].name;

                    // Update or create file name display
                    if ($('.file-name-display').length === 0) {
                        $('.show-required-file-name').append(`
                            <div class="file-name-display" style="margin-left: 150px; width: 100%; max-width: 400px;">
                                <div class="file-name" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                                    <span style="color: #2e4453; font-size: 14px;">${fileName}</span>
                                </div>
                            </div>
                        `);
                    } else {
                        $('.file-name-display .file-name').html(`&nbsp;
                            ${fileName}
                            <span class="text-muted">(new upload)</span>
                        `);
                    }
                }
            }
        });
    });
})(jQuery);
</script>
@endpush
