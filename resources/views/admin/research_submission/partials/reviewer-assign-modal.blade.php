<style>
    .assign-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5ff;
        border-radius: 30px;
        padding: 4px 10px;
        font-size: 12px;
        color: #3b82f6;
    }

    .assign-status-badge .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #10b981;
    }

    .reviewer-search-row {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .reviewer-search-input-wrap {
        flex: 1;
        position: relative;
    }

    .reviewer-search-input-wrap input {
        width: 100%;
        padding: 9px 12px 9px 30px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        outline: none;
        transition: border 0.08s ease, box-shadow 0.08s ease;
    }

    .reviewer-search-input-wrap input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .reviewer-search-icon {
        position: absolute;
        left: 9px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: #9ca3af;
    }

    .reviewer-pill-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .reviewer-pill {
        border-radius: 999px;
        font-size: 11px;
        padding: 6px 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        cursor: pointer;
    }

    .reviewer-pill.active {
        background: #e0edff;
        border-color: #3b82f6;
        color: #1d4ed8;
        font-weight: 500;
    }

    .reviewer-suggestions-list {
        margin-top: 6px;
        display: grid;
        gap: 10px;
    }

    .reviewer-suggestion-card {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        transition: box-shadow 0.08s ease, transform 0.08s ease, border-color 0.08s ease;
    }

    .reviewer-suggestion-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        border-color: #d1d5db;
    }

    .reviewer-main {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .reviewer-avatar {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        background: linear-gradient(135deg, #2563eb, #10b981);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .reviewer-info {
        font-size: 13px;
    }

    .reviewer-name {
        font-weight: 600;
        margin-bottom: 2px;
    }

    .reviewer-meta {
        font-size: 11px;
        color: #6b7280;
    }

    .reviewer-meta span + span::before {
        content: "•";
        margin: 0 6px;
        color: #d1d5db;
    }

    .reviewer-expertise {
        margin-top: 2px;
        font-size: 11px;
        color: #4b5563;
    }

    .reviewer-tags {
        margin-top: 4px;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .reviewer-tag {
        font-size: 10px;
        background: #f3f4f6;
        border-radius: 999px;
        padding: 3px 7px;
        color: #4b5563;
    }

    .reviewer-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        font-size: 11px;
        color: #9ca3af;
    }

    .btn-assign-reviewer {
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        cursor: pointer;
        background: #10b981;
        color: #ffffff;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-assign-reviewer:hover {
        background: #059669;
    }
</style>

<div class="modal fade" id="assignReviewersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">{{ __('Suggested Reviewers') }}</h5>
                    <p class="modal-subtitle text-muted mb-0">
                        <span id="assignReviewersPaperTitle" class="fw-semibold"></span>
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <div class="reviewer-search-row">
                    <div class="reviewer-search-input-wrap">
                        <span class="reviewer-search-icon">&#128269;</span>
                        <input type="text" id="reviewerSearchInput"
                               placeholder="{{ __('Search by name, email, or expertise…') }}">
                    </div>
                    <div class="reviewer-pill-filters">
                        <button type="button" class="reviewer-pill active" data-filter="best">
                            {{ __('Best match') }}
                        </button>
                        <button type="button" class="reviewer-pill" data-filter="no_conflict">
                            {{ __('No conflicts') }}
                        </button>
                    </div>
                </div>

                <div id="reviewerSuggestionsList" class="reviewer-suggestions-list">
                    <p class="text-muted mb-0">{{ __('Loading suggestions…') }}</p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    {{ __('Showing smart suggestions based on journal, keywords, and reviewer history.') }}
                </small>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>





