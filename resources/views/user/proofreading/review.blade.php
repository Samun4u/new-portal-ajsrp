@extends('user.layouts.app')
@push('title')
    {{ __('Review Proof Version') }}
@endpush

@push('style')
    <style>
        .proof-review-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .proof-info-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .proof-viewer {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            min-height: 400px;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-approve {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
        }

        .btn-request-corrections {
            background: #ffc107;
            color: #212529;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
        }

        .corrections-note {
            margin-top: 1rem;
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="proof-review-container">
        <h2 class="mb-4">{{ __('Review Proof Version') }} {{ $proof->version }}</h2>

        <div class="proof-info-card">
            <h5>{{ __('Article Information') }}</h5>
            <p><strong>{{ __('Title') }}:</strong> {{ $submission->article_title ?? __('N/A') }}</p>
            <p><strong>{{ __('Journal') }}:</strong> {{ $submission->journal->title ?? __('N/A') }}</p>
            @if ($proof->notes)
                <div class="alert alert-info">
                    <strong>{{ __('Editor Notes') }}:</strong><br>
                    {{ $proof->notes }}
                </div>
            @endif
        </div>

        <div class="proof-info-card">
            <h5>{{ __('Proof File') }}</h5>
            @if($proof->file_id && $proof->file)
                <div class="proof-viewer">
                    <iframe src="{{ getFileUrl($proof->file_id) }}" width="100%" height="600px" style="border: none;"></iframe>
                </div>
                <div class="text-center">
                    <a href="{{ getFileUrl($proof->file_id) }}" target="_blank"
                        class="btn btn-primary">{{ __('Download Proof') }}</a>
                </div>
            @else
                <div class="alert alert-warning">
                    {{ __('Proof file not found. Please contact the editor.') }}
                </div>
            @endif
        </div>

        @if ($proof->status === 'pending')
            <div class="proof-info-card">
                <form id="approveProofForm" method="POST" action="{{ route('user.submission.proofreading.approve', encrypt($proof->id)) }}" style="display: none;">
                    @csrf
                </form>

                <form id="requestCorrectionsForm" method="POST" action="{{ route('user.submission.proofreading.request-corrections', encrypt($proof->id)) }}">
                    @csrf
                    <div class="corrections-note" id="correctionsNote">
                        <label class="form-label">{{ __('Corrections Needed') }} <span class="text-danger">*</span></label>
                        <textarea name="corrections_note" class="form-control" rows="5"
                            placeholder="{{ __('Please describe what corrections are needed...') }}" required></textarea>
                        @error('corrections_note')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="action-buttons">
                        <button type="button" class="btn-approve" id="approveBtn" onclick="approveProof()">
                            <span class="btn-text">{{ __('Approve Proof') }}</span>
                            <span class="btn-spinner d-none">
                                <i class="fa fa-spinner fa-spin me-2"></i>{{ __('Processing...') }}
                            </span>
                        </button>
                        <button type="button" class="btn-request-corrections" id="requestCorrectionsBtn" onclick="requestCorrections()">
                            <span class="btn-text">{{ __('Request Corrections') }}</span>
                            <span class="btn-spinner d-none">
                                <i class="fa fa-spinner fa-spin me-2"></i>{{ __('Processing...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="alert alert-info">
                @if ($proof->status === 'approved')
                    {{ __('This proof has been approved.') }}
                @elseif($proof->status === 'corrections_requested')
                    <strong>{{ __('Corrections Requested') }}:</strong><br>
                    {{ $proof->corrections_requested }}
                @endif
            </div>
        @endif
    </div>
@endsection

    @push('script')
        <script>
            function approveProof() {
                if (confirm('Are you sure you want to approve this proof?')) {
                    const btn = document.getElementById('approveBtn');
                    const btnText = btn.querySelector('.btn-text');
                    const btnSpinner = btn.querySelector('.btn-spinner');

                    btn.disabled = true;
                    btnText.classList.add('d-none');
                    btnSpinner.classList.remove('d-none');

                    document.getElementById('approveProofForm').submit();
                }
            }

            function requestCorrections() {
                const correctionsNote = document.getElementById('correctionsNote');
                const noteTextarea = correctionsNote.querySelector('textarea[name="corrections_note"]');

                if (correctionsNote.style.display === 'none') {
                    correctionsNote.style.display = 'block';
                    noteTextarea.focus();
                } else {
                    const note = noteTextarea.value.trim();
                    if (!note) {
                        alert('{{ __('Please provide corrections note.') }}');
                        return;
                    }
                    if (note.length < 10) {
                        alert('{{ __('Corrections note must be at least 10 characters.') }}');
                        return;
                    }
                    if (confirm('{{ __('Are you sure you want to request corrections?') }}')) {
                        const btn = document.getElementById('requestCorrectionsBtn');
                        const btnText = btn.querySelector('.btn-text');
                        const btnSpinner = btn.querySelector('.btn-spinner');

                        btn.disabled = true;
                        btnText.classList.add('d-none');
                        btnSpinner.classList.remove('d-none');

                        document.getElementById('requestCorrectionsForm').submit();
                    }
                }
            }
        </script>
    @endpush



