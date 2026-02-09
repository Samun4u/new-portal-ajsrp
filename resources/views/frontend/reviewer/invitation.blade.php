@extends('frontend.layouts.app')

@push('title')
    {{ __('Review Invitation') }}
@endpush

@push('style')
    <style>
        .invitation-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.12);
            padding: 32px;
            margin-bottom: 24px;
        }
        .invitation-meta {
            display: grid;
            gap: 16px;
        }
        .meta-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
        }
        .meta-box h4 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #475569;
            margin-bottom: 8px;
        }
        .meta-box p {
            margin: 0;
            font-size: 15px;
            color: #0f172a;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-accepted {
            background: #dcfce7;
            color: #166534;
        }
        .status-declined {
            background: #fee2e2;
            color: #991b1b;
        }
        .decision-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 28px;
        }
        .decision-card legend {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 16px;
            color: #0f172a;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #4338ca);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
        }
        .btn-outline-danger {
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
        }
        .alert-custom {
            border-radius: 12px;
            padding: 16px 20px;
        }
    </style>
@endpush

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if(session('success'))
                        <div class="alert alert-success alert-custom">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    @if(session('status'))
                        <div class="alert alert-warning alert-custom">
                            <i class="fa-solid fa-circle-info me-2"></i>{{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ __('Please address the following:') }}
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="invitation-card">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                            <div>
                                <h1 class="h4 mb-2">{{ __('Review Invitation') }}</h1>
                                <p class="text-muted mb-0">
                                    {{ __('You have been invited to review the manuscript listed below. Please confirm whether you can assist with this review and declare any conflicts of interest.') }}
                                </p>
                            </div>
                            <span class="status-pill status-{{ $review->invitation_status }}">
                                <i class="fa-solid fa-envelope-open-text"></i>
                                {{ __('Status: :status', ['status' => ucwords(str_replace('_', ' ', $review->invitation_status))]) }}
                            </span>
                        </div>

                        <div class="invitation-meta">
                            <div class="meta-box">
                                <h4>{{ __('Manuscript Title') }}</h4>
                                <p>{{ $submission->article_title ?? __('Not provided') }}</p>
                            </div>

                            <div class="meta-box">
                                <h4>{{ __('Journal') }}</h4>
                                <p>{{ optional($submission->journal)->title ?? __('Not assigned') }}</p>
                            </div>

                            <div class="meta-box">
                                <h4>{{ __('Abstract') }}</h4>
                                <p style="white-space: pre-line;">{{ $submission->article_abstract ?? __('No abstract available.') }}</p>
                            </div>

                            <div class="meta-box">
                                <h4>{{ __('Keywords') }}</h4>
                                <p>{{ $submission->article_keywords ?? __('Not provided') }}</p>
                            </div>

                            <div class="meta-box">
                                <h4>{{ __('Due Date') }}</h4>
                                <p>
                                    @if($assignment && $assignment->due_at)
                                        {{ $assignment->due_at->format('M d, Y') }}
                                    @else
                                        {{ __('The editorial office will confirm the deadline after your response.') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($canRespond)
                        <div class="decision-card">
                            <form method="POST" action="{{ route('reviewer.invitation.respond', $token) }}">
                                @csrf
                                <fieldset class="mb-4">
                                    <legend>{{ __('Will you accept this review invitation?') }}</legend>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="decision" id="decision-accept" value="accept" {{ old('decision', 'accept') === 'accept' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="decision-accept">
                                            {{ __('Yes, I am available to complete this review.') }}
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="decision" id="decision-decline" value="decline" {{ old('decision') === 'decline' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="decision-decline">
                                            {{ __('No, I am unable to accept this invitation.') }}
                                        </label>
                                    </div>
                                </fieldset>

                                <fieldset class="mb-4">
                                    <legend>{{ __('Conflict of Interest Declaration') }}</legend>
                                    <p class="text-muted mb-2">{{ __('Please indicate whether you have any personal, financial, or professional conflicts with this manuscript or its authors.') }}</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="conflict_declared" id="conflict-no" value="0" {{ old('conflict_declared', '0') === '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="conflict-no">
                                            {{ __('No, I am not aware of any conflicts of interest.') }}
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="conflict_declared" id="conflict-yes" value="1" {{ old('conflict_declared') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="conflict-yes">
                                            {{ __('Yes, I may have a potential conflict (please describe below).') }}
                                        </label>
                                    </div>
                                    <textarea name="conflict_details" id="conflict_details" rows="4" class="form-control mt-3" placeholder="{{ __('Describe the conflict (required if you selected Yes).') }}">{{ old('conflict_details') }}</textarea>
                                </fieldset>

                                <div class="d-flex align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-regular fa-paper-plane me-2"></i>{{ __('Submit Response') }}
                                    </button>
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                        {{ __('Go to reviewer dashboard') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="invitation-card">
                            <h2 class="h5 mb-3">{{ __('Thank you for your response!') }}</h2>
                            <p class="mb-3 text-muted">
                                {{ __('Your decision has been recorded on :date.', ['date' => optional($review->responded_at)->format('M d, Y H:i')]) }}
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                {{ __('Go to reviewer dashboard') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const conflictRadios = document.querySelectorAll('input[name="conflict_declared"]');
            const conflictDetailsField = document.getElementById('conflict_details');

            function toggleConflictDetails() {
                if (!conflictDetailsField) {
                    return;
                }
                const selected = document.querySelector('input[name="conflict_declared"]:checked');
                if (selected && selected.value === '1') {
                    conflictDetailsField.removeAttribute('disabled');
                } else {
                    conflictDetailsField.value = '';
                    conflictDetailsField.setAttribute('disabled', 'disabled');
                }
            }

            if (conflictRadios.length > 0) {
                conflictRadios.forEach(radio => radio.addEventListener('change', toggleConflictDetails));
                toggleConflictDetails();
            }
        });
    </script>
@endpush

