(function () {
    const root = document.getElementById('reviewer-assignment-root');
    if (!root) {
        return;
    }

    const autosaveUrl = root.dataset.autosaveUrl;
    const submitUrl = root.dataset.submitUrl;
    const invitationStatus = root.dataset.invitationStatus || 'pending';
    const form = document.getElementById('review-workspace-form');
    const progressBar = document.getElementById('workspace-progress');
    const progressLabel = document.getElementById('workspace-progress-label');
    const saveBtn = document.getElementById('save-progress');
    const submitBtn = document.getElementById('submit-review');
    const previewBtn = document.getElementById('preview-review');
    const toast = document.getElementById('reviewer-toast');
    const toastMessage = document.getElementById('reviewer-toast-message');
    const toastIcon = document.getElementById('reviewer-toast-icon');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    let autosaveTimer = null;

    const canEdit = invitationStatus === 'accepted';

    const updateProgressUi = (value) => {
        if (progressBar) {
            progressBar.style.width = `${value}%`;
        }
        if (progressLabel) {
            progressLabel.textContent = `${value}%`;
        }
    };

    const showToast = (message, success = true) => {
        if (!toast || !toastMessage || !toastIcon) {
            return;
        }
        toastMessage.textContent = message;
        toastIcon.textContent = success ? '✓' : '⚠️';
        toast.style.background = success ? '#28a745' : '#dc3545';
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2500);
    };

    const serializeForm = () => {
        if (!form) {
            return null;
        }
        const formData = new FormData(form);
        form.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
            if (!checkbox.checked) {
                formData.append(checkbox.name, '0');
            }
        });
        return formData;
    };

    const performAutosave = async () => {
        if (!canEdit || !autosaveUrl || !csrfToken) {
            return;
        }

        const payload = serializeForm();
        if (!payload) {
            return;
        }

        try {
            const response = await fetch(autosaveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: payload,
            });

            if (!response.ok) {
                throw new Error('Autosave failed');
            }

            const data = await response.json();
            if (data?.data?.progress !== undefined) {
                updateProgressUi(data.data.progress);
            }
            showToast(window.reviewerWorkspace?.translations?.autosaveSuccess || 'Progress saved');
        } catch (error) {
            showToast(error.message, false);
        }
    };

    const debouncedAutosave = () => {
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(performAutosave, window.reviewerWorkspace?.autosaveDelay || 1500);
    };

    const submitReview = async () => {
        if (!canEdit || !submitUrl || !csrfToken) {
            return;
        }

        if (!window.confirm(window.reviewerWorkspace?.translations?.submitConfirm || 'Submit review? This action cannot be undone.')) {
            return;
        }

        const payload = serializeForm();
        if (!payload) {
            return;
        }

        try {
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: payload,
            });

            if (!response.ok) {
                const errorData = await response.json();
                const message = errorData?.message || 'Submission failed';
                throw new Error(message);
            }

            const data = await response.json();
            if (data?.data?.progress !== undefined) {
                updateProgressUi(data.data.progress);
            }
            showToast(window.reviewerWorkspace?.translations?.submitSuccess || 'Review submitted');
            setTimeout(() => window.location.reload(), 1500);
        } catch (error) {
            showToast(error.message, false);
        }
    };

    if (form && canEdit) {
        form.addEventListener('change', debouncedAutosave);
        form.addEventListener('input', (event) => {
            const { target } = event;
            if (target.matches('input[type="range"]')) {
                const label = form.querySelector(`.rating-value[data-target="${target.getAttribute('name')}"]`);
                if (label) {
                    label.textContent = `${target.value}/5`;
                }
                debouncedAutosave();
            }
        });
    }

    if (saveBtn && canEdit) {
        saveBtn.addEventListener('click', (event) => {
            event.preventDefault();
            performAutosave();
        });
    }

    if (submitBtn && canEdit) {
        submitBtn.addEventListener('click', (event) => {
            event.preventDefault();
            submitReview();
        });
    }

    if (previewBtn && canEdit) {
        previewBtn.addEventListener('click', (event) => {
            event.preventDefault();
            window.alert('Preview your review before final submission.\n\nA dedicated preview screen will be available in a future update.');
        });
    }
})();

