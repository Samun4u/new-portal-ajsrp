(function () {
    const root = document.getElementById('reviewer-dashboard');
    if (!root) {
        return;
    }

    const workspace = document.querySelector('#reviewer-workspace .workspace');
    const form = document.getElementById('review-workspace-form');
    const progressBar = document.getElementById('workspace-progress');
    const saveDraftBtn = document.getElementById('save-progress');
    const submitBtn = document.getElementById('submit-review');
    const assignmentButtons = document.querySelectorAll('.assignment-card');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let autosaveTimer = null;
    const invitationStatus = workspace?.dataset.invitationStatus || 'pending';
    const invitationAccepted = invitationStatus === 'accepted';

    const buildUrl = (template) => {
        if (!workspace) {
            return null;
        }
        const reviewId = workspace.dataset.reviewId;
        if (!reviewId) {
            return null;
        }
        return template.replace('__REVIEW__', reviewId);
    };

    const showToast = (message) => {
        if (window.notyf) {
            window.notyf.success(message);
        } else {
            console.log(message);
        }
    };

    const showError = (message) => {
        if (window.notyf) {
            window.notyf.error(message);
        } else {
            console.error(message);
        }
    };

    const serializeForm = () => {
        if (!form) {
            return null;
        }
        const formData = new FormData(form);
        // Ensure unchecked checkboxes are captured as off
        form.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
            if (!checkbox.checked) {
                formData.append(checkbox.name, '0');
            }
        });
        return formData;
    };

    const updateProgress = (value) => {
        if (progressBar) {
            progressBar.style.width = `${value}%`;
        }
    };

    const performAutosave = async () => {
        const url = buildUrl(root.dataset.autosaveTemplate);
        if (!url || !csrfToken) {
            return;
        }

        const payload = serializeForm();
        if (!payload) {
            return;
        }

        try {
            const response = await fetch(url, {
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
                updateProgress(data.data.progress);
            }
            showToast(window.reviewerWorkspace?.translations?.autosaveSuccess || 'Saved');
        } catch (error) {
            showError(error.message);
        }
    };

    const debouncedAutosave = () => {
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(performAutosave, window.reviewerWorkspace?.autosaveDelay || 1500);
    };

    const submitReview = async () => {
        const url = buildUrl(root.dataset.submitTemplate);
        if (!url || !csrfToken) {
            return;
        }

        if (!window.confirm(window.reviewerWorkspace?.translations?.submitConfirm || 'Submit review?')) {
            return;
        }

        const payload = serializeForm();
        if (!payload) {
            return;
        }

        try {
            const response = await fetch(url, {
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
                updateProgress(data.data.progress);
            }
            showToast(window.reviewerWorkspace?.translations?.submitSuccess || 'Review submitted');
            window.location.reload();
        } catch (error) {
            showError(error.message);
        }
    };

    const disableForm = () => {
        if (!form) {
            return;
        }
        form.querySelectorAll('input, textarea, select').forEach((element) => {
            element.setAttribute('disabled', 'disabled');
        });
        if (saveDraftBtn) {
            saveDraftBtn.setAttribute('disabled', 'disabled');
        }
        if (submitBtn) {
            submitBtn.setAttribute('disabled', 'disabled');
        }
    };

    if (form && invitationAccepted) {
        form.addEventListener('change', debouncedAutosave);
        form.addEventListener('input', (event) => {
            if (event.target.matches('input[type="range"]')) {
                const target = event.target.getAttribute('name');
                const label = document.querySelector(`.rating-value[data-target="${target}"]`);
                if (label) {
                    label.textContent = `${event.target.value}/5`;
                }
                debouncedAutosave();
            }
        });
    } else if (!invitationAccepted) {
        disableForm();
    }

    if (saveDraftBtn && invitationAccepted) {
        saveDraftBtn.addEventListener('click', performAutosave);
    }

    if (submitBtn && invitationAccepted) {
        submitBtn.addEventListener('click', submitReview);
    }

    assignmentButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const targetUrl = button.dataset.reviewUrl;
            if (targetUrl) {
                window.location.href = targetUrl;
                return;
            }
            const reviewId = button.dataset.reviewId;
            if (!reviewId) {
                return;
            }
            const url = new URL(window.location.href);
            url.searchParams.set('review', reviewId);
            window.location.href = url.toString();
        });

        const status = button.dataset.invitationStatus;
        const pendingLink = button.dataset.invitationLink;
        if (status && status !== 'accepted' && pendingLink) {
            button.addEventListener('dblclick', () => {
                window.open(pendingLink, '_blank');
            });
        }
    });

    const extensionBtn = document.getElementById('request-extension');
    const reportBtn = document.getElementById('report-concern');

    if (extensionBtn) {
        extensionBtn.addEventListener('click', () => {
            showToast(window.reviewerWorkspace?.translations?.extensionRequested || __('Request sent to editor for extension.'));
        });
    }

    if (reportBtn) {
        reportBtn.addEventListener('click', () => {
            showToast(window.reviewerWorkspace?.translations?.concernReported || __('Editorial team notified about your concern.'));
        });
    }

    function __(text) {
        return text;
    }
})();

