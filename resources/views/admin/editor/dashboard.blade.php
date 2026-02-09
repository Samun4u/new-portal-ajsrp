@extends('admin.layouts.app')

@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <style>
        .editor-dashboard-grid {
            display: grid;
            gap: 18px;
        }
        .editor-stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
        }
        .editor-stat-card h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #334155;
            text-transform: uppercase;
        }
        .editor-stat-card .value {
            font-size: 34px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }
        .editor-stat-card span {
            color: #64748b;
            font-size: 13px;
            margin-top: 12px;
        }
        .editor-alert {
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }
        .editor-alert i {
            font-size: 16px;
        }
        .pipeline-card,
        .workflow-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
        }
        .pipeline-item {
            display: grid;
            grid-template-columns: minmax(220px, 2fr) minmax(140px, 1fr) minmax(100px, 1fr) minmax(120px, 1fr);
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .pipeline-item:last-child {
            border-bottom: none;
        }
        .pipeline-status,
        .review-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .pipeline-progress,
        .review-progress {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .pipeline-progress .bar,
        .review-progress .bar {
            height: 8px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
            position: relative;
        }
        .pipeline-progress .bar span,
        .review-progress .bar span {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #4f46e5, #38bdf8);
            transition: width 0.4s ease-in-out;
        }
        .mini-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 18px;
        }
        .status-breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .status-breakdown-item:last-child {
            border-bottom: none;
        }
        .status-breakdown-item span {
            font-size: 13px;
            color: #475569;
        }
        .status-breakdown-item strong {
            font-size: 15px;
            color: #0f172a;
        }
        .revision-row,
        .publication-row {
            display: grid;
            grid-template-columns: minmax(220px, 2fr) minmax(140px, 1fr) minmax(120px, 1fr) minmax(100px, 1fr);
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .revision-row:last-child,
        .publication-row:last-child {
            border-bottom: none;
        }
        .review-progress .badge {
            width: fit-content;
        }
        .quick-actions {
            display: grid;
            gap: 12px;
        }
        .quick-actions a {
            border-radius: 12px;
            border: 1px solid #cbd5f5;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            color: #0f172a;
            font-weight: 600;
        }
        .quick-actions a:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }
        .invite-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
        }
        .invite-pill.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .invite-pill.declined {
            background: #fee2e2;
            color: #991b1b;
        }
        .invite-pill.accepted {
            background: #dcfce7;
            color: #166534;
        }
        @media (max-width: 1200px) {
            .pipeline-item,
            .revision-row,
            .publication-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="home-section">
            <div class="d-flex align-items-center justify-content-between pb-26 flex-wrap gap-3">
                <div>
                    <h4 class="fs-24 fw-600 lh-29 text-title-black">
                        {{ __('Hello') }}, {{ auth()->user()->name }}
                    </h4>
                    <p class="fs-14 text-para-text mb-0">
                        {{ __('Monitor the editorial pipeline, reviewer workload, and publication queues in real time.') }}
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2 text-para-text">
                    <i class="fa-regular fa-clock me-1"></i>
                    <span>{{ __('Average decision time') }}: <strong>{{ $averageDecisionTime }}</strong></span>
                </div>
            </div>

            <div class="editor-dashboard-grid mb-30">
                <div class="row rg-20">
                    @foreach ($stats as $stat)
                        <div class="col-lg-4 col-md-6">
                            <div class="editor-stat-card">
                                <h4>{{ $stat['label'] }}</h4>
                                <div class="value">{{ number_format($stat['value']) }}</div>
                                <span>{{ $stat['subtext'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($alerts->isNotEmpty())
                    <div class="row rg-12">
                        @foreach ($alerts as $alert)
                            <div class="col-lg-4 col-md-6">
                                <div class="editor-alert bg-{{ $alert['type'] }} bg-opacity-10 text-{{ $alert['type'] }}">
                                    <i class="fa-solid {{ $alert['icon'] }}"></i>
                                    <span>{{ $alert['message'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row rg-20">
                    <div class="col-xl-8">
                        <div class="pipeline-card h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mini-title mb-0">{{ __('Editorial pipeline') }}</h3>
                                <span class="badge bg-light text-dark">
                                    {{ __('Sorted by most recent updates') }}
                                </span>
                            </div>
                            @forelse($pipelineRecords as $record)
                                <div class="pipeline-item">
                                    <div>
                                        <strong class="text-title-black d-block mb-1">{{ $record['title'] }}</strong>
                                        <div class="text-para-text fs-12">
                                            <span class="me-2"><i class="fa-solid fa-hashtag me-1"></i>{{ $record['order_id'] }}</span>
                                            <span><i class="fa-solid fa-book me-1"></i>{{ $record['journal'] ?? __('No journal') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="pipeline-status badge {{ $record['status_badge'] }}">
                                            <i class="fa-solid fa-circle"></i>
                                            {{ $record['status'] }}
                                        </span>
                                    </div>
                                    <div class="pipeline-progress" data-progress="{{ $record['progress'] }}">
                                        <div class="bar">
                                            <span class="progress-fill"></span>
                                        </div>
                                        <small class="text-para-text fs-12">{{ $record['progress'] }}%</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fs-12 text-para-text">{{ __('Updated') }} {{ $record['updated_at'] }}</div>
                                        <div class="fs-12 text-para-text">{{ $record['article_type'] ?? '' }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-para-text mb-0">{{ __('No submissions in the pipeline yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="workflow-card mb-20">
                            <h3 class="mini-title">{{ __('Workflow breakdown') }}</h3>
                            @forelse($stageBreakdown as $stage)
                                <div class="status-breakdown-item">
                                    <div>
                                        <span class="badge {{ $stage['badge'] }} me-2">{{ $stage['label'] }}</span>
                                        <span>{{ $stage['percent'] }}%</span>
                                    </div>
                                    <strong>{{ number_format($stage['value']) }}</strong>
                                </div>
                            @empty
                                <p class="text-para-text mb-0">{{ __('No submissions recorded yet.') }}</p>
                            @endforelse
                        </div>
                        <div class="workflow-card">
                            <h3 class="mini-title">{{ __('Quick actions') }}</h3>
                            <div class="quick-actions">
                                <a href="{{ route('admin.research-submission.index') }}">
                                    <i class="fa-regular fa-folder-open text-primary"></i>
                                    {{ __('Manage research submissions') }}
                                </a>
                                <a href="{{ route('admin.client-orders.list') }}">
                                    <i class="fa-solid fa-clipboard-list text-success"></i>
                                    {{ __('Review client orders') }}
                                </a>
                                <a href="{{ route('admin.reviewer-application.index') }}">
                                    <i class="fa-regular fa-user-check text-info"></i>
                                    {{ __('Process reviewer applications') }}
                                </a>
                                <a href="{{ route('admin.reviewer.list') }}">
                                    <i class="fa-solid fa-users-line text-warning"></i>
                                    {{ __('Reviewer roster & availability') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row rg-20">
                    <div class="col-xl-7">
                        <div class="pipeline-card h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mini-title mb-0">{{ __('Active reviewer workload') }}</h3>
                                <span class="badge bg-light text-dark">{{ __('Top 10 active assignments') }}</span>
                            </div>
                            @forelse($activeReviews as $review)
                                <div class="pipeline-item">
                                    <div>
                                        <strong class="d-block mb-1">{{ $review['manuscript'] }}</strong>
                                        <span class="fs-12 text-para-text">
                                            <i class="fa-regular fa-user me-1"></i>{{ $review['reviewer'] ?? __('Unassigned') }}
                                        </span>
                                        @if(($review['invitation_status'] ?? 'accepted') !== 'accepted')
                                            <div class="mt-2">
                                                <span class="invite-pill {{ $review['invitation_status'] ?? 'pending' }}">
                                                    <i class="fa-regular fa-envelope-open"></i>
                                                    {{ __('Invitation: :status', ['status' => ucwords(str_replace('_', ' ', $review['invitation_status'] ?? 'pending'))]) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="review-status badge {{ $review['status_badge'] }}">
                                            {{ $review['status'] }}
                                        </span>
                                    </div>
                                    <div class="review-progress" data-progress="{{ $review['progress'] }}">
                                        <div class="bar">
                                            <span class="progress-fill"></span>
                                        </div>
                                        <small class="text-para-text fs-12">{{ $review['progress'] }}%</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fs-12 text-para-text">
                                            <i class="fa-regular fa-calendar me-1"></i>
                                            {{ $review['due_at'] ?? __('No deadline') }}
                                        </div>
                                        @if($review['is_overdue'])
                                            <span class="badge bg-danger mt-1">{{ __('Overdue') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-para-text mb-0">{{ __('No reviewer assignments currently in progress.') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="workflow-card mb-20">
                            <h3 class="mini-title">{{ __('Revision tracker') }}</h3>
                            @forelse($revisionQueue as $revision)
                                <div class="revision-row">
                                    <div>
                                        <strong class="d-block mb-1">{{ $revision['title'] }}</strong>
                                        <span class="fs-12 text-para-text">{{ $revision['author'] ?? __('Unknown author') }}</span>
                                    </div>
                                    <div class="text-para-text fs-12">
                                        <i class="fa-solid fa-book me-1"></i>{{ $revision['journal'] ?? __('No journal') }}
                                    </div>
                                    <div class="text-para-text fs-12">
                                        <i class="fa-solid fa-hashtag me-1"></i>{{ $revision['order_id'] }}
                                    </div>
                                    <div class="text-para-text fs-12 text-end">
                                        {{ $revision['last_update'] }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-para-text mb-0">{{ __('No manuscripts awaiting author revisions.') }}</p>
                            @endforelse
                        </div>
                        <div class="workflow-card">
                            <h3 class="mini-title">{{ __('Publication queue') }}</h3>
                            @forelse($publicationQueue as $publication)
                                <div class="publication-row">
                                    <div>
                                        <strong class="d-block mb-1">{{ $publication['title'] }}</strong>
                                        <span class="fs-12 text-para-text">{{ $publication['author'] ?? __('Unknown author') }}</span>
                                    </div>
                                    <div class="text-para-text fs-12">
                                        <i class="fa-solid fa-book-open me-1"></i>{{ $publication['journal'] ?? __('No journal') }}
                                    </div>
                                    <div class="text-para-text fs-12">
                                        <i class="fa-solid fa-hashtag me-1"></i>{{ $publication['order_id'] }}
                                    </div>
                                    <div class="text-para-text fs-12 text-end">
                                        {{ $publication['updated_at'] }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-para-text mb-0">{{ __('Nothing queued for publication yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/editor-dashboard.js') }}"></script>
@endpush

