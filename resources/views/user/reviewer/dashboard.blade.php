@extends('user.layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <style>
        .reviewer-dashboard-wrap {
            padding: 20px;
        }
        .reviewer-dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .reviewer-dashboard-header h2 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .reviewer-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(140px,1fr));
            gap: 12px;
            margin-bottom: 24px;
        }
        .reviewer-stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        }
        .reviewer-stat-card h4 {
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: #64748b;
        }
        .reviewer-stat-card span {
            font-size: 22px;
            font-weight: 600;
            color: #0f172a;
        }
        .reviewer-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }
        .assignment-list {
            background: #fff;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.07);
            height: calc(100vh - 220px);
            overflow-y: auto;
        }
        .assignment-card {
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            transition: all 0.2s ease-in-out;
            background: #f8fafc;
            text-align: left;
            width: 100%;
        }
        .assignment-card:hover {
            border-color: #2563eb;
            background: #eef2ff;
        }
        .assignment-card h5 {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .assignment-card.pending {
            border-color: #f59e0b;
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.16), rgba(253, 186, 116, 0.12));
        }
        .assignment-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
        }
        .assignment-status.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .assignment-status.declined {
            background: #fee2e2;
            color: #991b1b;
        }
        .assignment-status.accepted {
            background: #dcfce7;
            color: #166534;
        }
        .assignment-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12px;
            color: #475569;
        }
        .assignment-progress {
            margin-top: 10px;
            height: 6px;
            border-radius: 999px;
            background: #cbd5f5;
            overflow: hidden;
        }
        .assignment-progress span {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #38bdf8);
        }
        .assignment-actions {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .assignment-actions .btn-open-workspace {
            background: linear-gradient(135deg,#2563eb,#4f46e5);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .assignment-actions .btn-open-workspace:hover {
            opacity: 0.9;
        }
        .assignment-empty {
            text-align: center;
            color: #475569;
            font-size: 15px;
            padding: 40px 20px;
        }
        .workspace-info {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.09);
        }
        .workspace-info h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .workspace-info ol {
            margin-left: 18px;
            color: #475569;
            line-height: 1.6;
        }
        .workspace-info .info-callout {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            font-size: 14px;
        }
        @media (max-width: 1200px) {
            .reviewer-layout {
                grid-template-columns: 1fr;
            }
            .assignment-list {
                height: auto;
            }
        }
        @media (max-width: 768px) {
            .workspace-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .workspace-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            .workspace-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="reviewer-dashboard-wrap">
        <div class="reviewer-dashboard-header">
            <div>
                <h2>{{ __('Reviewer Workspace') }}</h2>
                <p class="text-muted">{{ __('Track assignments, complete reviews, and stay aligned with editorial expectations.') }}</p>
            </div>
            <div class="deadline-badge">
                <i class="fa-regular fa-clock"></i>
                <span>{{ __('Real-time autosave enabled') }}</span>
            </div>
        </div>

        <div class="reviewer-stats">
            <div class="reviewer-stat-card">
                <h4>{{ __('Total Reviews') }}</h4>
                <span>{{ $statistics['total_reviews'] }}</span>
            </div>
            <div class="reviewer-stat-card">
                <h4>{{ __('Active Reviews') }}</h4>
                <span>{{ $statistics['active_reviews'] }}</span>
            </div>
            <div class="reviewer-stat-card">
                <h4>{{ __('Completed') }}</h4>
                <span>{{ $statistics['completed_reviews'] }}</span>
            </div>
            <div class="reviewer-stat-card">
                <h4>{{ __('Avg Completion Time') }}</h4>
                <span>{{ $statistics['average_completion_time'] }}</span>
            </div>
            <div class="reviewer-stat-card">
                <h4>{{ __('Quality Rating') }}</h4>
                <span>{{ $statistics['quality_rating'] }}</span>
            </div>
            <div class="reviewer-stat-card">
                <h4>{{ __('Pending Invitations') }}</h4>
                <span>{{ $statistics['pending_invitations'] }}</span>
                <span class="small text-muted mt-2">{{ __('Awaiting your response') }}</span>
            </div>
        </div>

        <div class="reviewer-layout">
            <div>
                <div class="assignment-list">
                    @forelse($assignments as $item)
                        @php
                            $submission = $item['submission'];
                            $review = $item['review'];
                            $progress = $item['progress'];
                            $keywords = $submission && $submission->article_keywords ? explode(',', $submission->article_keywords) : [];
                            $invitationStatus = $item['invitation_status'] ?? 'pending';
                            $invitationToken = $item['invitation_token'] ?? null;
                        @endphp
                        <div class="assignment-card {{ $invitationStatus !== 'accepted' ? 'pending' : '' }}">
                            <h5>{{ \Illuminate\Support\Str::limit($submission?->article_title, 80) }}</h5>
                            <div class="assignment-meta">
                                <span><i class="fa-solid fa-hashtag"></i> {{ $item['order']?->order_id }}</span>
                                @if (!empty($item['assignment']->due_at))
                                    <span><i class="fa-regular fa-hourglass-half"></i> {{ __('Due') }}: {{ $item['assignment']->due_at->format('M d') }}</span>
                                @endif
                                @if ($submission?->article_type?->name)
                                    <span>{{ $submission->article_type->name }}</span>
                                @endif
                            </div>
                            <div class="mt-2">
                                <span class="assignment-status {{ $invitationStatus }}">
                                    <i class="fa-regular fa-envelope-open"></i>
                                    {{ ucwords(str_replace('_', ' ', $invitationStatus)) }}
                                </span>
                                @if($invitationStatus !== 'accepted' && $invitationToken)
                                    <a href="{{ route('join.application.reviewer.invitation.show', $invitationToken) }}" class="ms-2 text-decoration-none text-primary fw-semibold" target="_blank">
                                        {{ __('Respond') }}
                                    </a>
                                @endif
                            </div>
                            <div class="assignment-progress">
                                <span style="width: {{ $progress }}%"></span>
                            </div>
                            <div class="assignment-actions">
                                @if($review)
                                    <a class="btn-open-workspace" href="{{ route('user.reviewer.reviews.show', $review) }}">
                                        <i class="fa-regular fa-paper-plane"></i>{{ __('Open Review Workspace') }}
                                    </a>
                                @endif
                                <span class="text-muted small">
                                    {{ __('Progress:') }} {{ $progress }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="assignment-empty">
                            <i class="fa-regular fa-face-smile"></i>
                            <p>{{ __('No review assignments at the moment. We will notify you when a manuscript needs your expertise.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="workspace-info">
                <h3>{{ __('How to work on an assignment') }}</h3>
                <ol>
                    <li>{{ __('Select a manuscript in the left column to see its details and deadline.') }}</li>
                    <li>{{ __('Click “Open Review Workspace” to launch the full review form for that assignment.') }}</li>
                    <li>{{ __('Complete your evaluation, upload files if needed, and submit from the dedicated workspace page.') }}</li>
                </ol>
                <div class="info-callout mt-3">
                    <strong>{{ __('Need help?') }}</strong>
                    <p class="mb-1">{{ __('Invitation links, full manuscript files, autosave, and submission controls now live inside the dedicated workspace.') }}</p>
                    <p class="mb-0">{{ __('If a card shows “Respond”, complete the invitation first to unlock the workspace.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

