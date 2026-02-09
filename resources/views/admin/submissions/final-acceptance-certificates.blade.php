@extends('admin.layouts.app')
@push('title')
    {{ __('Final Acceptance Certificates') }}
@endpush

@push('style')
    <style>
        .certificates-container {
            padding: 2rem;
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-row:last-child {
            margin-bottom: 0;
        }

        .submission-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
        }

        .submission-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .submission-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .submission-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-item i {
            color: #007bff;
        }

        .certificate-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-download {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }

        .btn-download:hover {
            background: #218838;
            color: white;
        }

        .btn-review {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }

        .btn-review:hover {
            background: #0056b3;
            color: white;
        }

        .badge-certificate {
            background: #d4edda;
            color: #155724;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .no-certificate {
            color: #6c757d;
            font-style: italic;
        }

        .authors-list {
            margin-top: 0.5rem;
            color: #495057;
            font-size: 0.9rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .close-modal:hover {
            background: #f8f9fa;
            color: #000;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <div class="certificates-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ __('Final Acceptance Certificates') }}</h2>
                <span class="badge badge-primary" style="background: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600;">NEW</span>
            </div>
        </div>

        <div class="mb-3">
            @php
                $activeTab = $tab ?? ($filters['tab'] ?? 'pending');
                $pendingUrl = route('admin.submissions.final-acceptance-certificates.index', array_merge(request()->except('page'), ['tab' => 'pending']));
                $issuedUrl = route('admin.submissions.final-acceptance-certificates.index', array_merge(request()->except('page'), ['tab' => 'issued']));
            @endphp
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'pending' ? 'active' : '' }}" href="{{ $pendingUrl }}">
                        {{ __('Pending Issuance') }}
                        <span class="badge bg-secondary ms-2">{{ $pendingCount ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'issued' ? 'active' : '' }}" href="{{ $issuedUrl }}">
                        {{ __('Issued') }}
                        <span class="badge bg-secondary ms-2">{{ $issuedCount ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.submissions.final-acceptance-certificates.index') }}">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <div class="filter-row">
                    <div>
                        <label class="form-label">{{ __('Search') }}</label>
                        <input type="text" name="search" class="form-control"
                               value="{{ $filters['search'] ?? '' }}"
                               placeholder="{{ __('Title, Author name, or Email') }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('Journal') }}</label>
                        <select name="journal_id" class="form-control">
                            <option value="">{{ __('All Journals') }}</option>
                            @foreach($journals as $journal)
                                <option value="{{ $journal->id }}"
                                        {{ ($filters['journal_id'] ?? '') == $journal->id ? 'selected' : '' }}>
                                    {{ $journal->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('Date From') }}</label>
                        <input type="date" name="date_from" class="form-control"
                               value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('Date To') }}</label>
                        <input type="date" name="date_to" class="form-control"
                               value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.submissions.final-acceptance-certificates.index') }}"
                       class="btn btn-secondary">{{ __('Clear') }}</a>
                </div>
            </form>
        </div>

        <!-- Submissions List -->
        @if($submissions->count() > 0)
            @foreach($submissions as $submission)
                <div class="submission-card">
                    <div class="submission-header">
                        <div style="flex: 1;">
                            <div class="submission-title">
                                {{ $submission->article_title ?? __('Untitled') }}
                            </div>
                            <div class="submission-meta">
                                <div class="meta-item">
                                    <i class="fa fa-hashtag"></i>
                                    <strong>{{ __('Submission ID') }}:</strong> <span>{{ $submission->id }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa fa-book"></i>
                                    <span>{{ $submission->journal->title ?? __('N/A') }}</span>
                                </div>
                                @if($submission->issue)
                                    <div class="meta-item">
                                        <i class="fa fa-file-alt"></i>
                                        <span>{{ __('Issue') }}: {{ $submission->issue->title ?? 'Vol. ' . ($submission->issue->volume ?? '-') . ', No. ' . ($submission->issue->number ?? '-') }}</span>
                                    </div>
                                @endif
                                <div class="meta-item">
                                    <i class="fa fa-calendar"></i>
                                    <span>{{ __('Accepted') }}: {{ $submission->acceptance_date ? \Carbon\Carbon::parse($submission->acceptance_date)->format('M d, Y') : __('N/A') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa fa-credit-card"></i>
                                    <span class="badge bg-success">{{ __('Payment Completed') }}</span>
                                </div>
                                @if($submission->authors && $submission->authors->count() > 0)
                                    <div class="meta-item">
                                        <i class="fa fa-users"></i>
                                        <span>{{ $submission->authors->count() }} {{ __('Author(s)') }}</span>
                                    </div>
                                @endif
                                @if($submission->certificate_sent_at)
                                    <div class="meta-item">
                                        <span class="badge badge-primary">{{ __('Certificate Sent') }}</span>
                                    </div>
                                @endif
                            </div>
                            @if($submission->authors && $submission->authors->count() > 0)
                                <div class="authors-list">
                                    <strong>{{ __('Authors') }}:</strong>
                                    {{ $submission->authors->map(function($author) { return trim($author->first_name . ' ' . $author->last_name); })->implode(', ') }}
                                </div>
                            @endif
                        </div>
                        <div class="certificate-actions">
                            <div class="d-flex flex-column gap-2" style="align-items: flex-end;">
                                @if($submission->acceptance_certificate_file_id)
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge-certificate">
                                            <i class="fa fa-check-circle"></i> {{ __('Certificate Available') }}
                                        </span>
                                        <a href="{{ route('certificates.download', $submission->id) }}"
                                           class="btn-download">
                                            <i class="fa fa-download"></i> {{ __('Download') }}
                                        </a>
                                        <a href="{{ route('certificates.create', $submission->id) }}"
                                           class="btn-review" style="background: #ffc107; color: #212529;">
                                            <i class="fa fa-edit"></i> {{ __('Edit / Regenerate') }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.submissions.final-acceptance-certificate.resend', encrypt($submission->id)) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-review" style="background: #17a2b8;" onclick="return confirm('{{ __('Are you sure you want to resend the certificate?') }}');">
                                                <i class="fa fa-paper-plane"></i> {{ __('Send Again') }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="no-certificate">{{ __('Certificate not issued yet') }}</span>
                                        <button type="button" class="btn-review" style="background: #28a745;"
                                                onclick="openGenerateModal({{ $submission->id }}, '{{ encrypt($submission->id) }}', {{ $submission->journal_id }}, '{{ addslashes($submission->journal->title ?? '') }}')">
                                            <i class="fa fa-file-pdf"></i> {{ __('Generate') }}
                                        </button>
                                    </div>
                                @endif

                                @if($submission->metadata_status !== 'approved')
                                    <form method="POST" action="{{ route('admin.submissions.final-acceptance-certificate.send-reminder', encrypt($submission->id)) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-review" style="background: #6c757d;" onclick="return confirm('{{ __('Are you sure you want to send a reminder?') }}');">
                                            <i class="fa fa-bell"></i> {{ __('Send Reminder') }}
                                        </button>
                                    </form>
                                @endif

                                <div class="d-flex gap-2 align-items-center">
                                    <a href="{{ route('admin.submissions.final-metadata.review', encrypt($submission->id)) }}"
                                       class="btn-review">
                                        <i class="fa fa-eye"></i> {{ __('View Details') }}
                                    </a>
                                    @if($submission->issue)
                                        <a href="{{ route('admin.issues.show', $submission->issue->id) }}"
                                           class="btn-review" style="background: #6f42c1;">
                                            <i class="fa fa-file-alt"></i> {{ __('View Issue') }}
                                        </a>
                                    @endif
                                    @if($submission->galleyFiles && $submission->galleyFiles->where('status', 'approved')->count() > 0)
                                        <a href="{{ route('admin.ojs.quicksubmit-data', encrypt($submission->id)) }}"
                                           class="btn-review" style="background: #28a745;">
                                            <i class="fa fa-file-export"></i> {{ __('OJS QuickSubmit') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                {{ $activeTab === 'issued'
                    ? __('No issued certificates found.')
                    : __('No manuscripts pending certificate issuance found.') }}
            </div>
        @endif
    </div>

    <!-- Certificate Generation Modal -->
    <div id="certificateModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3 style="margin: 0;">{{ __('Generate Final Acceptance Certificate') }}</h3>
                <button type="button" class="close-modal" onclick="closeGenerateModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label style="font-weight: 600; display: block; margin-bottom: 0.5rem;">
                        {{ __('Journal') }}
                    </label>
                    <input type="text" id="modal_journal_name" class="form-control" readonly style="background: #f8f9fa;">
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label style="font-weight: 600; display: block; margin-bottom: 0.5rem;">
                        {{ __('Select Volume / Issue') }} <span style="color: red;">*</span>
                    </label>
                    <select id="modal_issue_id" class="form-control" required>
                        <option value="">{{ __('Loading...') }}</option>
                    </select>
                    <small class="text-muted" style="display: block; margin-top: 0.25rem;">
                        {{ __('Only scheduled and published issues are shown') }}
                    </small>
                </div>

                <div id="issue_details" style="display: none; margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 6px;">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.75rem; color: #495057;">{{ __('Issue Details') }}</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.9rem;">
                        <div>
                            <strong>{{ __('Status:') }}</strong> <span id="detail_status"></span>
                        </div>
                        <div>
                            <strong>{{ __('Articles:') }}</strong> <span id="detail_articles"></span>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <strong>{{ __('Publication Date:') }}</strong> <span id="detail_date"></span>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; padding: 1rem; background: #fff3cd; border-left: 3px solid #ffc107; border-radius: 4px;">
                    <p style="margin: 0; font-size: 0.9rem; color: #856404;">
                        <i class="fa fa-info-circle"></i> {{ __('The article will be linked to the selected issue and the certificate will be generated.') }}
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeGenerateModal()">
                    {{ __('Cancel') }}
                </button>
                <button type="button" id="btn_generate" class="btn btn-success" onclick="confirmGenerate()" disabled>
                    <i class="fa fa-check"></i> {{ __('Generate Certificate') }}
                </button>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    let currentSubmissionId = null;
    let currentSubmissionEncrypted = null;
    let journalIssues = [];

    function openGenerateModal(submissionId, encryptedId, journalId, journalName) {
        currentSubmissionId = submissionId;
        currentSubmissionEncrypted = encryptedId;

        document.getElementById('modal_journal_name').value = journalName;
        document.getElementById('certificateModal').style.display = 'flex';
        document.getElementById('btn_generate').disabled = true;
        document.getElementById('issue_details').style.display = 'none';

        // Fetch issues for this journal
        fetch(`/admin/submissions/journal/${journalId}/issues`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.issues) {
                    journalIssues = data.data.issues;
                    populateIssuesDropdown(data.data.issues);
                } else {
                    alert('{{ __('Failed to load issues') }}');
                    closeGenerateModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('An error occurred while loading issues') }}');
                closeGenerateModal();
            });
    }

    function populateIssuesDropdown(issues) {
        const select = document.getElementById('modal_issue_id');
        select.innerHTML = '<option value="">{{ __('-- Select Volume / Issue --') }}</option>';

        if (issues.length === 0) {
            select.innerHTML = '<option value="">{{ __('No issues available') }}</option>';
            select.disabled = true;
            return;
        }

        select.disabled = false;
        issues.forEach(issue => {
            const option = document.createElement('option');
            option.value = issue.id;

            let label = `{{ __('Vol') }} ${issue.volume || '?'}, {{ __('No') }} ${issue.number || '?'}`;
            if (issue.year) label += ` (${issue.year})`;
            if (issue.title) label += ` - ${issue.title}`;
            label += ` [${issue.status.charAt(0).toUpperCase() + issue.status.slice(1)}]`;

            option.textContent = label;
            option.dataset.status = issue.status;
            option.dataset.articles = issue.articles_count;
            option.dataset.date = issue.publication_date || '{{ __('Not set') }}';

            select.appendChild(option);
        });
    }

    document.getElementById('modal_issue_id').addEventListener('change', function() {
        const issueId = this.value;
        const btn = document.getElementById('btn_generate');
        const detailsDiv = document.getElementById('issue_details');

        if (issueId) {
            btn.disabled = false;
            const option = this.options[this.selectedIndex];

            document.getElementById('detail_status').textContent = option.dataset.status.charAt(0).toUpperCase() + option.dataset.status.slice(1);
            document.getElementById('detail_articles').textContent = option.dataset.articles + ' {{ __('article(s)') }}';
            document.getElementById('detail_date').textContent = option.dataset.date;
            detailsDiv.style.display = 'block';
        } else {
            btn.disabled = true;
            detailsDiv.style.display = 'none';
        }
    });

    function confirmGenerate() {
        const issueId = document.getElementById('modal_issue_id').value;
        if (!issueId) {
            alert('{{ __('Please select a volume/issue') }}');
            return;
        }

        if (!confirm('{{ __('Generate final acceptance certificate now? The article will be assigned to the selected issue.') }}')) {
            return;
        }

        document.getElementById('btn_generate').disabled = true;
        document.getElementById('btn_generate').innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('Generating...') }}';

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('issue_id', issueId);

        fetch(`/admin/submissions/final-acceptance-certificate/generate/${currentSubmissionEncrypted}`, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || '{{ __('Certificate generated successfully!') }}');
                location.reload();
            } else {
                alert(data.message || '{{ __('Failed to generate certificate') }}');
                document.getElementById('btn_generate').disabled = false;
                document.getElementById('btn_generate').innerHTML = '<i class="fa fa-check"></i> {{ __('Generate Certificate') }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('An error occurred while generating certificate') }}');
            document.getElementById('btn_generate').disabled = false;
            document.getElementById('btn_generate').innerHTML = '<i class="fa fa-check"></i> {{ __('Generate Certificate') }}';
        });
    }

    function closeGenerateModal() {
        document.getElementById('certificateModal').style.display = 'none';
        currentSubmissionId = null;
        currentSubmissionEncrypted = null;
        journalIssues = [];
    }
</script>
@endpush
