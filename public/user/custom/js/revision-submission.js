(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var manuscriptInput = document.getElementById('manuscript_file');
        var responseInput = document.getElementById('response_file');
        var attachmentsInput = document.getElementById('attachments');

        function updateLabel(input) {
            if (!input) {
                return;
            }
            input.addEventListener('change', function (event) {
                var files = event.target.files;
                if (!files || files.length === 0) {
                    return;
                }

                var label = input.closest('.file-upload-card');
                if (!label) {
                    return;
                }

                var info = label.querySelector('.text-para-text');
                if (info) {
                    if (files.length === 1) {
                        info.textContent = files[0].name;
                    } else {
                        info.textContent = files.length + ' ' + translatedFilesLabel();
                    }
                }
            });
        }

        function translatedFilesLabel() {
            return window.revisionTranslations?.filesSelected || 'files selected';
        }

        updateLabel(manuscriptInput);
        updateLabel(responseInput);

        if (attachmentsInput) {
            attachmentsInput.addEventListener('change', function (event) {
                var files = event.target.files;
                var info = attachmentsInput.closest('.file-upload-card')?.querySelector('.text-para-text');
                if (info && files && files.length > 0) {
                    info.textContent = files.length === 1
                        ? files[0].name
                        : files.length + ' ' + translatedFilesLabel();
                }
            });
        }
    });
})();

