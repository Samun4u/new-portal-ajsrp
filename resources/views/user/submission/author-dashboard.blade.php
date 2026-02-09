@extends('user.layouts.app')

@push('title')
    {{ __('Author Dashboard') }} - {{ $orderIdDisplay ?? '' }}
@endpush

@push('style')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Workflow Tracker */
        .workflow-tracker {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .workflow-tracker h3 {
            text-align: center;
            color: #1a1a1a;
            margin-bottom: 40px;
            font-weight: 600;
            font-size: 24px;
        }

        .progress-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
        }

        .progress-container .step {
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .progress-container .step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 24px;
            left: 50%;
            width: 100%;
            height: 4px;
            background-color: #e0e0e0;
            z-index: 0;
        }

        .progress-container .circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #e0e0e0;
            margin: 0 auto 12px;
            line-height: 48px;
            color: #ffffff;
            font-weight: bold;
            font-size: 18px;
            position: relative;
            z-index: 1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .progress-container .label {
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        .progress-container .step.completed .circle {
            background-color: #2e7d32;
        }

        .progress-container .step.completed:not(:last-child)::after {
            background-color: #2e7d32;
        }

        .progress-container .step.completed .circle::after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
        }

        .progress-container .step.active .circle {
            background-color: #1976d2;
            box-shadow: 0 0 0 4px rgba(25, 118, 210, 0.2);
        }

        .progress-container .step:not(.completed):not(.active) .circle {
            background-color: #ffffff;
            color: #999;
            border: 3px solid #e0e0e0;
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: #2a5298;
            text-decoration: none;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #666;
            font-size: 1rem;
        }

        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-title {
            font-size: 1.3rem;
            color: #2c3e50;
            font-weight: 600;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-submitted {
            background: #cce5ff;
            color: #004085;
        }

        .status-under-review {
            background: #fff3cd;
            color: #856404;
        }

        .status-revision-required {
            background: #f8d7da;
            color: #721c24;
        }

        .status-accepted {
            background: #d4edda;
            color: #155724;
        }

        .status-published {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Manuscript Header */
        .manuscript-header {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .manuscript-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .manuscript-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .meta-label {
            font-size: 0.75rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-weight: 600;
            font-size: 1rem;
        }

        .manuscript-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .btn-manuscript {
            padding: 0.6rem 1.2rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-manuscript:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Progress Section */
        .progress-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #2a5298;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .progress-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .progress-percentage {
            font-weight: 700;
            color: #2a5298;
            font-size: 1.1rem;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2a5298, #1e3c72);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-steps {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .progress-step {
            flex: 1;
            min-width: 150px;
            padding: 0.75rem;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .step-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .step-icon.complete {
            background: #28a745;
            color: white;
        }

        .step-icon.active {
            background: #2a5298;
            color: white;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 2.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e0e0e0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.5rem;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #2a5298;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #2a5298;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .timeline-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.05rem;
        }

        .timeline-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Detail Row */
        .detail-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #2c3e50;
        }

        /* Files Section */
        .file-list {
            list-style: none;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }

        .file-item:hover {
            background: #e9ecef;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .file-icon {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .file-size {
            font-size: 0.85rem;
            color: #666;
        }

        .file-actions {
            display: flex;
            gap: 0.75rem;
        }

        .file-actions a {
            color: #2a5298;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .file-actions a:hover {
            text-decoration: underline;
        }

        /* Messages */
        .message-item {
            padding: 1.25rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #2a5298;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .message-from {
            font-weight: 600;
            color: #2c3e50;
        }

        .message-date {
            font-size: 0.85rem;
            color: #666;
        }

        .message-body {
            color: #555;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 1.25rem;
            border-radius: 6px;
            text-align: center;
            border-left: 4px solid #2a5298;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #2a5298;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info Box */
        .info-box {
            background: #f0f4ff;
            padding: 1.25rem;
            border-radius: 6px;
            border-left: 4px solid #2a5298;
            margin-bottom: 1.5rem;
        }

        .info-box-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box-content {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 6px;
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .alert-icon {
            font-size: 1.5rem;
        }

        /* Buttons */
        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #2a5298;
            color: white;
        }

        .btn-primary:hover {
            background: #1e3c72;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-outline {
            background: white;
            color: #2a5298;
            border: 2px solid #2a5298;
        }

        .btn-outline:hover {
            background: #2a5298;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Review Feedback Section */
        .review-feedback {
            background: #fff;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .review-feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .reviewer-label {
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .review-recommendation {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .recommendation-accept {
            background: #d4edda;
            color: #155724;
        }

        .recommendation-minor {
            background: #fff3cd;
            color: #856404;
        }

        .recommendation-major {
            background: #f8d7da;
            color: #721c24;
        }

        .feedback-section {
            margin-bottom: 1.5rem;
        }

        .feedback-section:last-child {
            margin-bottom: 0;
        }

        .feedback-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .feedback-text {
            color: #555;
            line-height: 1.7;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        /* Resources List */
        .resource-list {
            list-style: none;
        }

        .resource-item {
            margin-bottom: 0.75rem;
        }

        .resource-item a {
            color: #2a5298;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .resource-item a:hover {
            background: #f0f4ff;
        }

        /* Action Buttons in Cards */
        .actions.vertical {
            flex-direction: column;
        }

        .actions.vertical .btn {
            width: 100%;
        }

        /* Revision Required Section */
        .revision-notice {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #dc3545;
        }

        .revision-notice-title {
            font-weight: 600;
            color: #721c24;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .revision-notice-content {
            color: #721c24;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .revision-deadline {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .deadline-label {
            font-weight: 600;
            color: #721c24;
        }

        .deadline-date {
            font-weight: 700;
            color: #dc3545;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .manuscript-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .manuscript-meta {
                grid-template-columns: 1fr;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .file-item {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .message-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .progress-steps {
                flex-direction: column;
            }

            .progress-step {
                min-width: auto;
            }
        }

        /* Loading & Toast */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: none;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
            z-index: 1000;
        }

        .toast.show {
            display: flex;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $avatar = $user ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 2)) : 'AU';
    $userName = $user->name ?? 'Author';

    $orderIdDisplay = $order->order_id ?? '—';
    $manuscriptTitle = $submission->article_title ?? 'Untitled Manuscript';
    $journalTitle = $submission->journal->title ?? 'Not Assigned';
    $articleType = $submission->article_type->name ?? '—';
    $submittedAt = $submission->created_at ? \Carbon\Carbon::parse($submission->created_at)->translatedFormat('F d, Y') : '—';

    $statusLabel = $statusMeta['label'] ?? 'Pending';
    $statusBadge = $statusMeta['badge_class'] ?? 'status-under-review';
    $statusIcon = $statusMeta['icon'] ?? '🔍';
    $statusHeadline = $statusMeta['headline'] ?? $statusLabel;
    $statusBody = $statusMeta['body'] ?? '';

    $daysInReview = $dashboardMeta['days_in_review'];
    $daysInReviewLabel = $daysInReview !== null
        ? $daysInReview . ' ' . \Illuminate\Support\Str::plural('Day', $daysInReview)
        : '—';

    $expectedDecisionDate = $dashboardMeta['expected_decision'] ?? null;
    $expectedDecision = $expectedDecisionDate instanceof \Carbon\Carbon
        ? $expectedDecisionDate->translatedFormat('F d, Y') . ' (approximately)'
        : ($expectedDecisionDate ?: '—');

    $progressSteps = collect($progress['steps'] ?? []);
    $progressPercentage = $progress['percentage'] ?? 0;

    $assignedReviewers = $reviewStats['assigned_reviewers'] ?? 0;
    $completedReviewCount = $reviewStats['completed_reviews'] ?? 0;
    $reviewsReceivedLabel = ($statusMeta['reviews_received'] ?? $completedReviewCount) . ' of ' . ($statusMeta['total_reviews'] ?? max($assignedReviewers, $completedReviewCount));
    $pendingReviewCount = max(0, $assignedReviewers - $completedReviewCount);

    $daysToDecision = $expectedDecisionDate instanceof \Carbon\Carbon
        ? max(0, $expectedDecisionDate->diffInDays(now()))
        : null;

    $manuscriptUrl = $files['manuscript'] ?? null;
    $coverLetterUrl = $files['cover_letter'] ?? null;
    $supplementaryFiles = collect($files['supplements'] ?? []);

    $fileEntries = collect();
    if ($manuscriptUrl) {
        $fileEntries->push([
            'icon' => '📄',
            'name' => 'Main Manuscript',
            'url' => $manuscriptUrl,
            'meta' => 'Uploaded ' . $submittedAt,
        ]);
    }
    if ($coverLetterUrl) {
        $fileEntries->push([
            'icon' => '✉️',
            'name' => 'Cover Letter',
            'url' => $coverLetterUrl,
            'meta' => 'Uploaded ' . $submittedAt,
        ]);
    }
    foreach ($supplementaryFiles as $index => $file) {
        if (!empty($file['url'])) {
            $fileEntries->push([
                'icon' => '📎',
                'name' => 'Supplementary File ' . ($index + 1),
                'url' => $file['url'],
                'meta' => 'Uploaded ' . $submittedAt,
            ]);
        }
    }

    $authorNames = $authors->pluck('name')->filter()->implode(', ') ?: '—';
    $corresponding = $correspondingAuthor ?? [];
    $correspondingName = $corresponding['name'] ?? '—';
    $correspondingEmail = $corresponding['email'] ?? '—';
    $correspondingAffiliation = $corresponding['affiliation'] ?? '—';
    $keywordsList = $keywords->filter()->implode(', ') ?: 'Not provided';

    $timelineItems = collect($timeline ?? []);
    $communications = $timelineItems
        ->map(function ($item) {
            return [
                'from' => 'Editorial Office',
                'date' => optional($item['timestamp'])->translatedFormat('F d, Y') ?? '—',
                'body' => $item['description'] ?? '',
            ];
        })
        ->filter(fn ($entry) => !empty($entry['body']))
        ->take(3);
    if ($communications->isEmpty()) {
        $communications = collect([[
            'from' => 'Editorial Office',
            'date' => now()->translatedFormat('F d, Y'),
            'body' => $statusBody,
        ]]);
    }

    $handlingAssignment = $assignments->first(function ($assignment) {
        return !empty($assignment->assigner);
    });
    $handlingEditor = $handlingAssignment?->assigner?->name ?? 'Editorial Team';
    $similarityStatus = $dashboardMeta['similarity_status'] ?? null;
@endphp

<div class="container-fluid py-4">
    <div class="container">
        @if(isset($workflowTracker) && $workflowTracker)
        <!-- Workflow Status Tracker -->
        <div class="workflow-tracker">
            <h3>{{ __('Manuscript Submission Status') }}</h3>
            <div class="progress-container">
                @foreach($workflowTracker['steps'] as $step)
                <div class="step {{ $step['state'] === 'completed' ? 'completed' : ($step['state'] === 'active' ? 'active' : '') }}">
                    <div class="circle">
                        @if($step['state'] === 'completed')
                            <!-- Checkmark will be added via CSS -->
                        @elseif($step['state'] === 'active')
                            {{ $step['number'] }}
                        @else
                            {{ $step['number'] }}
                        @endif
                    </div>
                    <div class="label">{{ $step['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Main Content -->
            <div>
                <!-- Manuscript Header -->
                <div class="manuscript-header">
                    <div class="manuscript-title">{{ $manuscriptTitle }}</div>
                    <div class="manuscript-meta">
                        <div class="meta-item">
                            <div class="meta-label">Manuscript ID</div>
                            <div class="meta-value">{{ $orderIdDisplay }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Journal</div>
                            <div class="meta-value">{{ $journalTitle }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Article Type</div>
                            <div class="meta-value">{{ $articleType }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Submitted</div>
                            <div class="meta-value">{{ $submittedAt }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Current Status</div>
                            <div class="meta-value">{{ $statusLabel }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Days in Review</div>
                            <div class="meta-value">{{ $daysInReviewLabel }}</div>
                        </div>
                    </div>
                    <div class="manuscript-actions">
                        <a href="{{ $manuscriptUrl ?: '#' }}" class="btn-manuscript" target="_blank" rel="noopener">📄 View Manuscript</a>
                        <a href="{{ $manuscriptUrl ?: '#' }}" class="btn-manuscript" target="_blank" rel="noopener">⬇️ Download PDF</a>
                        <a href="{{ $supplementaryFiles->first()['url'] ?? '#' }}" class="btn-manuscript" target="_blank" rel="noopener">📎 Supplementary Files</a>
                        <a href="mailto:{{ $correspondingEmail }}" class="btn-manuscript">📧 Contact Editor</a>
                        <a href="#" class="btn-manuscript">📊 View Statistics</a>
                    </div>
                </div>

                <!-- Proofreading Review Alert -->
                @php
                    $pendingProofs = $submission->proofFiles->where('status', 'pending');
                @endphp
                @if($pendingProofs->count() > 0)
                <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; margin-bottom: 1.5rem; border: none;">
                    <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.5rem;">
                            <span>📄</span>
                            <span>{{ __('Action Required: Review Proof Version') }}</span>
                        </h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <p style="margin-bottom: 1rem; font-size: 1.05rem;">
                            {{ __('A proof version has been uploaded for your review. Please review and approve or request corrections.') }}
                        </p>
                        @foreach($pendingProofs as $proof)
                            <a href="{{ route('user.submission.proofreading.review', encrypt($proof->id)) }}"
                               class="btn btn-light"
                               style="background: white; color: #f5576c; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; display: inline-block; transition: transform 0.2s; margin-right: 0.5rem;">
                                {{ __('Review Proof Version') }} {{ $proof->version }} →
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Galley Review Alert -->
                @php
                    $pendingGalleys = $submission->galleyFiles->where('status', 'pending');
                @endphp
                @if($pendingGalleys->count() > 0)
                <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; margin-bottom: 1.5rem; border: none;">
                    <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.5rem;">
                            <span>📑</span>
                            <span>{{ __('Action Required: Review Galley (Final Layout) Version') }}</span>
                        </h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <p style="margin-bottom: 1rem; font-size: 1.05rem;">
                            {{ __('The final galley (layout) version has been uploaded for your review. Please review and approve or request corrections.') }}
                        </p>
                        @foreach($pendingGalleys as $galley)
                            <a href="{{ route('user.submission.galley.review', encrypt($galley->id)) }}"
                               class="btn btn-light"
                               style="background: white; color: #00f2fe; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; display: inline-block; transition: transform 0.2s; margin-right: 0.5rem;">
                                {{ __('Review Galley Version') }} {{ $galley->version }} →
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Final Metadata Form Alert -->
                @php
                    $acceptedStatuses = ['accepted', 'proof_approved', 'in_proofreading', 'accepted_for_publication'];
                    $showMetadataForm = in_array($submission->approval_status, $acceptedStatuses) && $submission->metadata_status === 'pending_author';
                @endphp
                @if($showMetadataForm)
                <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-bottom: 1.5rem; border: none;">
                    <div class="card-header" style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                        <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.5rem;">
                            <span>📝</span>
                            <span>{{ __('Action Required: Final Metadata Form') }}</span>
                        </h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <p style="margin-bottom: 1rem; font-size: 1.05rem;">
                            {{ __('Your paper has been accepted! Please complete the Final Metadata Form to proceed with publication.') }}
                        </p>
                        <a href="{{ route('user.submission.final-metadata.form', encrypt($submission->id)) }}"
                           class="btn btn-light"
                           style="background: white; color: #667eea; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; display: inline-block; transition: transform 0.2s;">
                            {{ __('Complete Final Metadata Form') }} →
                        </a>
                    </div>
                </div>
                @endif

                <!-- Current Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Current Status</h2>
                        <span class="status-badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </div>

                    <div class="info-box">
                        <div class="info-box-title">
                            <span>{{ $statusIcon }}</span>
                            <span>{{ $statusHeadline }}</span>
                        </div>
                        <div class="info-box-content">
                            {{ $statusBody }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Handling Editor</div>
                        <div class="detail-value">{{ $handlingEditor }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Reviewers Assigned</div>
                        <div class="detail-value">{{ $assignedReviewers }} reviewer{{ $assignedReviewers === 1 ? '' : 's' }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Reviews Received</div>
                        <div class="detail-value">{{ $reviewsReceivedLabel }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Expected Decision</div>
                        <div class="detail-value">{{ $expectedDecision }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Similarity Check</div>
                        <div class="detail-value">{{ $similarityStatus ?? '—' }}</div>
                    </div>
                </div>

                <!-- Manuscript Details -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Manuscript Details</h2>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Authors</div>
                        <div class="detail-value">{{ $authorNames }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Corresponding Author</div>
                        <div class="detail-value">{{ $correspondingName }} ({{ $correspondingEmail }})</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Institution</div>
                        <div class="detail-value">{{ $correspondingAffiliation }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Keywords</div>
                        <div class="detail-value">{{ $keywordsList }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Abstract Word Count</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Manuscript Word Count</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Figures/Tables</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">References</div>
                        <div class="detail-value">—</div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Submission Timeline</h2>
                    </div>

                    <div class="timeline">
                        @forelse($timelineItems as $event)
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    📅 {{ optional($event['timestamp'])->translatedFormat('F d, Y') ?? '—' }}
                                </div>
                                <div class="timeline-title">{{ $event['label'] ?? 'Update' }}</div>
                                <div class="timeline-description">
                                    {{ $event['description'] ?? '' }}
                                </div>
                            </div>
                        @empty
                            <div class="timeline-item">
                                <div class="timeline-date">📅 {{ $submittedAt }}</div>
                                <div class="timeline-title">Manuscript Submitted</div>
                                <div class="timeline-description">
                                    Your submission has been received and is entering the editorial workflow.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Submitted Files -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Submitted Files</h2>
                    </div>

                    <ul class="file-list">
                        @forelse($fileEntries as $file)
                            <li class="file-item">
                                <div class="file-info">
                                    <div class="file-icon">{{ $file['icon'] }}</div>
                                    <div>
                                        <div class="file-name">{{ $file['name'] }}</div>
                                        <div class="file-size">{{ $file['meta'] ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="file-actions">
                                    <a href="{{ $file['url'] }}" target="_blank" rel="noopener">Download</a>
                                    <a href="{{ $file['url'] }}" target="_blank" rel="noopener">View</a>
                                </div>
                            </li>
                        @empty
                            <li class="file-item">
                                <div class="file-info">
                                    <div class="file-icon">📄</div>
                                    <div>
                                        <div class="file-name">No files uploaded yet</div>
                                        <div class="file-size">Please upload required documents</div>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Communications -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Editorial Communications</h2>
                    </div>

                    @foreach($communications as $message)
                        <div class="message-item">
                            <div class="message-header">
                                <span class="message-from">📧 {{ $message['from'] }}</span>
                                <span class="message-date">{{ $message['date'] }}</span>
                            </div>
                            <div class="message-body">
                                {{ $message['body'] }}
                            </div>
                        </div>
                    @endforeach

                    <div class="actions" style="margin-top: 1.5rem;">
                        <button class="btn btn-primary" onclick="composeMessage()">✉️ Send Message to Editor</button>
                        <button class="btn btn-outline" onclick="viewAllMessages()">📬 View All Messages</button>
                    </div>
                </div>

                <!-- Review Feedback Section -->
                @php
                    $completedReviews = $completedReviews ?? collect();
                    $checklistLabels = $checklistLabels ?? [];
                    $ratingFields = [
                        'rating_originality' => __('Originality & Novelty'),
                        'rating_methodology' => __('Methodological Rigor'),
                        'rating_results' => __('Results & Analysis'),
                        'rating_clarity' => __('Clarity & Organization'),
                        'rating_significance' => __('Significance & Impact'),
                    ];
                    $recommendationLabels = [
                        'accept' => __('Accept'),
                        'minor_revisions' => __('Minor Revisions'),
                        'major_revisions' => __('Major Revisions'),
                        'reject' => __('Reject'),
                    ];
                    $recommendationClasses = [
                        'accept' => 'accept',
                        'minor_revisions' => 'minor',
                        'major_revisions' => 'major',
                        'reject' => 'reject',
                    ];
                @endphp

                @if($completedReviews->isEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Review Feedback</h2>
                        </div>
                        <div style="padding: 2rem; text-align: center; color: #666;">
                            <p style="font-size: 1.1rem; margin-bottom: 0.5rem;">{{ __('Review feedback is still being prepared.') }}</p>
                            <p>{{ __('Reviewers are currently evaluating your manuscript. You will be notified once all reviews are submitted.') }}</p>
                        </div>
                    </div>
                @else
                    <!-- Review Feedback Sections - Organized by Round -->
                    @php
                        // Group reviews by round and get latest version for each reviewer in each round
                        $reviewsByRound = [];
                        foreach($completedReviews as $review) {
                            // Ensure round and version are set (default to 1 if null)
                            $round = $review->round ?? 1;
                            $version = $review->version ?? 1;
                            $reviewerId = $review->reviewer_id;

                            // Normalize round and version on the review object
                            if (is_null($review->round) || $review->round == 0) {
                                $review->round = 1;
                            }
                            if (is_null($review->version) || $review->version == 0) {
                                $review->version = 1;
                            }

                            if (!isset($reviewsByRound[$round])) {
                                $reviewsByRound[$round] = [];
                            }

                            // Only keep the latest version for each reviewer in each round
                            if (!isset($reviewsByRound[$round][$reviewerId]) ||
                                $review->version > ($reviewsByRound[$round][$reviewerId]->version ?? 0)) {
                                $reviewsByRound[$round][$reviewerId] = $review;
                            }
                        }
                        ksort($reviewsByRound); // Sort rounds ascending
                    @endphp

                    @foreach($reviewsByRound as $roundNum => $roundReviews)
                    <hr style="margin: 2rem 0; border: none; border-top: 2px solid #e9ecef;">

                    {{-- Show Author's Revisions for this Round BEFORE the reviews --}}
                    @php
                        // Revisions are numbered starting from 1
                        // Round 1: No revision (initial submission)
                        // Round 2: Shows revision version 1 (first revision submitted after Round 1)
                        // Round 3: Shows revision version 2 (second revision submitted after Round 2)
                        // So for Round N (N > 1), show revision version (N - 1)
                        $revisionVersionForRound = $roundNum > 1 ? ($roundNum - 1) : 1;
                        $latestRoundRevision = null;

                        if (isset($revisionsByRound) && $revisionsByRound) {
                            // Handle both Collection and array formats
                            $roundRevisions = null;
                            $revisionsByRound = (object)$revisionsByRound;
                            if (is_object($revisionsByRound) && method_exists($revisionsByRound, 'get')) {

                                // Try with integer key first
                                $roundRevisions = $revisionsByRound->get($revisionVersionForRound);
                                // If not found, try with string key
                                if (!$roundRevisions) {
                                    $roundRevisions = $revisionsByRound->get((string)$revisionVersionForRound);
                                }

                            } elseif (is_array($revisionsByRound)) {
                                // Try integer key first
                                if (isset($revisionsByRound[$revisionVersionForRound])) {
                                    $roundRevisions = $revisionsByRound[$revisionVersionForRound];
                                } elseif (isset($revisionsByRound[(string)$revisionVersionForRound])) {
                                    // Try string key
                                    $roundRevisions = $revisionsByRound[(string)$revisionVersionForRound];
                                }
                            }

                            //

                            if ($roundRevisions) {
                                if (!($roundRevisions instanceof \Illuminate\Support\Collection)) {
                                    $roundRevisions = collect($roundRevisions);
                                }
                                if ($roundRevisions->isNotEmpty()) {
                                    $latestRoundRevision = $roundRevisions->sortByDesc('created_at')->first();
                                }
                            }
                        }


                    @endphp

                    @if($latestRoundRevision)
                    <div class="card" style="margin-bottom: 1.5rem; border: 2px solid #d2e3fc; background: #f4f9ff;">
                        <div style="padding: 1.5rem;">
                            <h3 style="font-size: 1.2rem; font-weight: 600; color: #1d4ed8; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                📄 {{ __('Your Submitted Revisions - Round') }} {{ $roundNum }}
                            </h3>

                            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem;">
                                @if(!empty($latestRoundRevision->manuscript_url))
                                    <a href="{{ $latestRoundRevision->manuscript_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; color: #2563eb; text-decoration: none; padding: 0.5rem 0.75rem; background: white; border-radius: 6px; border: 1px solid #dbeafe;">
                                        📄 {{ __('Revised Manuscript (PDF)') }}
                                    </a>
                                @endif
                                @if(!empty($latestRoundRevision->response_url))
                                    <a href="{{ $latestRoundRevision->response_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; color: #2563eb; text-decoration: none; padding: 0.5rem 0.75rem; background: white; border-radius: 6px; border: 1px solid #dbeafe;">
                                        📨 {{ __('Response to Reviewers') }}
                                    </a>
                                @endif
                                @if(!empty($latestRoundRevision->attachment_links) && $latestRoundRevision->attachment_links->count() > 0)
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                        @foreach($latestRoundRevision->attachment_links as $attachment)
                                            @if(!empty($attachment['url']))
                                            <a href="{{ $attachment['url'] }}" target="_blank" style="font-size: 0.9rem; color: #2563eb; text-decoration: none; padding: 0.5rem 0.75rem; background: white; border-radius: 6px; border: 1px solid #dbeafe;">
                                                📎 {{ $attachment['label'] }}
                                            </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Display Author Comments from Revision --}}
                            @php
                                $metadata = $latestRoundRevision->metadata ?? [];
                                $hasAuthorComments = !empty($metadata['general_response']) || !empty($metadata['reviewer_responses']) || !empty($latestRoundRevision->response_summary);
                            @endphp
                            @if($hasAuthorComments)
                                <div style="margin-top: 1rem; padding: 1rem; background: white; border-radius: 6px; border: 1px solid #e0e0e0;">
                                    <strong style="color: #2c3e50; display: block; margin-bottom: 0.75rem; font-size: 0.95rem;">{{ __('Your Comments - Round') }} {{ $roundNum }}:</strong>
                                    @if(!empty($metadata['general_response']))
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('General Response to All Reviewers') }}:</strong>
                                            <p style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">{!! nl2br(e($metadata['general_response'])) !!}</p>
                                        </div>
                                    @endif
                                    @if(!empty($metadata['reviewer_responses']))
                                        @foreach($metadata['reviewer_responses'] as $reviewerId => $response)
                                            @php
                                                $reviewerName = \App\Models\Reviews::where('reviewer_id', $reviewerId)->where('round', $roundNum - 1)->first()?->reviewer?->name ?? __('Reviewer') . ' ' . $reviewerId;
                                            @endphp
                                            @if(!empty($response))
                                                <div style="margin-bottom: 1rem;">
                                                    <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('Response to') }} {{ $reviewerName }}:</strong>
                                                    <p style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">{!! nl2br(e($response)) !!}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(!empty($latestRoundRevision->response_summary))
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('Summary of Major Changes') }}:</strong>
                                            <p style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">{!! nl2br(e($latestRoundRevision->response_summary)) !!}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div style="margin-top: 0.75rem; font-size: 0.85rem; color: #64748b;">
                                {{ __('Submitted on') }}: {{ optional($latestRoundRevision->created_at)->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Round Header -->
                    <div class="card" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%); color: white; margin-bottom: 1.5rem;">
                        <div style="padding: 1.5rem;">
                            <h2 style="font-size: 1.5rem; margin: 0; display: flex; align-items: center; gap: 1rem;">
                                <span style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-weight: bold;">{{ $roundNum }}</span>
                                <span>{{ __('Round') }} {{ $roundNum }} {{ __('Comments') }}</span>
                            </h2>
                            <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem;">
                                {{ $roundNum == 1 ? __('Initial review comments from peer reviewers') : __('Reviewer feedback after your revision') }}
                            </p>
                        </div>
                    </div>

                    @foreach($roundReviews as $review)
                    @php
                        $reviewerName = $review->reviewer?->name ?? __('Anonymous Reviewer');
                        $recommendation = $review->overall_recommendation ?? 'minor_revisions';
                        $recommendationLabel = $recommendationLabels[$recommendation] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', $recommendation));
                        $recommendationClass = $recommendationClasses[$recommendation] ?? 'minor';
                        $submittedOn = $review->submitted_at
                            ? \Carbon\Carbon::parse($review->submitted_at)->translatedFormat('F d, Y')
                            : ($review->updated_at ? \Carbon\Carbon::parse($review->updated_at)->translatedFormat('F d, Y') : '—');
                        $specificChecks = (array) ($review->specific_checks ?? []);
                    @endphp

                    <!-- Reviewer Feedback Card -->
                    <div class="review-feedback" style="margin-bottom: 1.5rem;">
                        <div class="review-feedback-header">
                            <div class="reviewer-label">
                                👤 {{ $reviewerName }}
                            </div>
                            <div class="review-recommendation recommendation-{{ $recommendationClass }}">
                                {{ $recommendationLabel }}
                            </div>
                        </div>

                        <div style="font-size: 0.85rem; color: #666; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f0f0f0;">
                            {{ __('Submitted on') }} {{ $submittedOn }}
                        </div>

                        <!-- Quality Ratings -->
                        @php $hasRatings = false; @endphp
                        @foreach($ratingFields as $field => $label)
                            @if(!is_null($review->$field))
                                @php $hasRatings = true; @endphp
                                @break
                            @endif
                        @endforeach

                        @if($hasRatings || !is_null($review->quality_rating))
                        <div class="feedback-section">
                            <div class="feedback-label">{{ __('Quality Assessment') }}</div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 0.5rem;">
                            @foreach($ratingFields as $field => $label)
                                @if(!is_null($review->$field))
                                <div style="background: #f8f9fa; padding: 0.75rem; border-radius: 6px;">
                                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.25rem;">{{ $label }}</div>
                                    <div style="font-size: 1.2rem; font-weight: 600; color: #2a5298;">{{ $review->$field }}/5</div>
                                </div>
                                @endif
                            @endforeach
                            @if(!is_null($review->quality_rating))
                                <div style="background: #f0f4ff; padding: 0.75rem; border-radius: 6px; border: 2px solid #2a5298;">
                                    <div style="font-size: 0.85rem; color: #2a5298; margin-bottom: 0.25rem; font-weight: 600;">{{ __('Overall Quality Score') }}</div>
                                    <div style="font-size: 1.2rem; font-weight: 700; color: #2a5298;">{{ number_format($review->quality_rating, 2) }}</div>
                                </div>
                            @endif
                            </div>
                        </div>
                        @endif

                        @if($review->comment_strengths)
                        <div class="feedback-section">
                            <div class="feedback-label">✅ {{ __('Key Strengths') }}</div>
                            <div class="feedback-text">{!! nl2br(e($review->comment_strengths)) !!}</div>
                        </div>
                        @endif

                        @if($review->comment_weaknesses)
                        <div class="feedback-section">
                            <div class="feedback-label">⚠️ {{ __('Weaknesses & Areas for Improvement') }}</div>
                            <div class="feedback-text">{!! nl2br(e($review->comment_weaknesses)) !!}</div>
                        </div>
                        @endif

                        @if($review->comment_for_authors)
                        <div class="feedback-section">
                            <div class="feedback-label">💬 {{ __('Comments for Authors') }}</div>
                            <div class="feedback-text">{!! nl2br(e($review->comment_for_authors)) !!}</div>
                        </div>
                        @endif

                        @if($review->questions_for_authors)
                        <div class="feedback-section">
                            <div class="feedback-label">❓ {{ __('Questions for Authors') }}</div>
                            <div class="feedback-text">{!! nl2br(e($review->questions_for_authors)) !!}</div>
                        </div>
                        @endif

                        @if($review->minor_issues)
                        <div class="feedback-section">
                            <div class="feedback-label">📝 {{ __('Minor Issues') }}</div>
                            <div class="feedback-text">{!! nl2br(e($review->minor_issues)) !!}</div>
                        </div>
                        @endif

                        @if($review->major_issues)
                        <div class="feedback-section">
                            <div class="feedback-label">🔴 {{ __('Major Issues') }}</div>
                            <div class="feedback-text">{!! nl2br(e($review->major_issues)) !!}</div>
                        </div>
                        @endif

                        @if(!empty($specificChecks) && !empty($checklistLabels))
                        <div class="feedback-section">
                            <div class="feedback-label">📋 {{ __('Manuscript Validation Checklist') }}</div>
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 6px; margin-top: 0.5rem;">
                                @foreach($checklistLabels as $key => $label)
                                    @php $complete = !empty($specificChecks[$key]); @endphp
                                    <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0; border-bottom: 1px solid #e9ecef;">
                                        <span style="color: {{ $complete ? '#28a745' : '#ccc' }}; font-size: 1.2rem;">{{ $complete ? '✓' : '○' }}</span>
                                        <span style="color: {{ $complete ? '#2c3e50' : '#999' }};">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @endforeach
                @endif

                <!-- Submit Revision Section -->
                @if($completedReviews->isNotEmpty())
                <hr style="margin: 2rem 0; border: none; border-top: 2px solid #e9ecef;">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Submit Your Revision</h2>
                    </div>

                    <div class="alert alert-warning" style="background: #fff3cd; border: 1px solid #ffc107; padding: 1rem 1.5rem; border-radius: 6px; margin-bottom: 1.5rem; display: flex; align-items: start; gap: 1rem;">
                        <span style="font-size: 1.5rem;">⚠️</span>
                        <div>
                            <strong>Before submitting:</strong> Ensure you have addressed all reviewer comments and prepared all required documents (revised manuscript with tracked changes, clean version, response letter, and updated supplementary materials).
                        </div>
                    </div>

                    <form id="revisionForm" action="{{ route('user.orders.revision.submit', ['order_id' => $orderIdDisplay]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Upload Section -->
                        <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; border: 2px dashed #dee2e6;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📤</div>
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; font-size: 1.1rem;">Upload Revised Manuscript Files</div>
                            <div style="color: #666; margin-bottom: 1.5rem;">Upload your revised manuscript (with tracked changes), clean version, and response to reviewers</div>
                            <div style="display: inline-block; position: relative;">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('manuscript_file').click()" style="pointer-events: none;">
                                    Choose Files
                                </button>
                                <input type="file" id="manuscript_file" name="manuscript_file" accept=".doc,.docx,.pdf" required style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="handleFileSelect(this)">
                            </div>
                            <div id="fileInfo" style="margin-top: 1rem; font-size: 0.85rem; color: #666; display: none;"></div>
                            <p style="margin-top: 1rem; font-size: 0.85rem; color: #666;">Accepted formats: DOC, DOCX, PDF • Maximum 50MB per file</p>
                        </div>

                        <!-- Response File Upload -->
                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem;">Response to Reviewers (PDF/DOC)</label>
                            <input type="file" name="response_file" accept=".doc,.docx,.pdf" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 6px;">
                            <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #666;">Upload your point-by-point response document (optional)</p>
                        </div>

                        <!-- Response to Reviewers Form -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="margin-bottom: 1rem; color: #2c3e50; font-size: 1.2rem;">Response to Reviewers</h3>
                            <p style="margin-bottom: 1.5rem; color: #666;">Provide a detailed point-by-point response to all reviewer comments</p>

                            @php
                                $completedReviewsForResponse = $completedReviews->unique('reviewer_id');
                            @endphp

                            @foreach($completedReviewsForResponse as $index => $reviewResponse)
                                @php
                                    $reviewerName = $reviewResponse->reviewer?->name ?? __('Anonymous Reviewer');
                                    $latestReviewForReviewer = $completedReviews->where('reviewer_id', $reviewResponse->reviewer_id)->sortByDesc('version')->first();
                                @endphp
                                <div class="feedback-section" style="margin-bottom: 1.5rem;">
                                    <label class="feedback-label" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                                        Detailed Response to {{ $reviewerName }}
                                    </label>
                                    <textarea
                                        name="response_to_reviewer_{{ $latestReviewForReviewer->reviewer_id }}"
                                        class="form-textarea"
                                        style="width: 100%; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; font-family: inherit; font-size: 0.95rem; line-height: 1.6; resize: vertical; min-height: 200px;"
                                        placeholder="Point-by-point response to {{ $reviewerName }}:&#10;&#10;Comment 1: ...&#10;Response: ...&#10;Changes made: ..."
                                        oninput="updateCharCount(this, 'r{{ $index }}Count')"></textarea>
                                    <div class="char-counter" id="r{{ $index }}Count" style="text-align: right; font-size: 0.8rem; color: #6c757d; margin-top: 0.5rem;">0 characters</div>
                                </div>
                            @endforeach

                            <div class="feedback-section" style="margin-bottom: 1.5rem;">
                                <label class="feedback-label" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                                    General Response to All Reviewers
                                </label>
                                <textarea
                                    name="general_response"
                                    class="form-textarea"
                                    style="width: 100%; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; font-family: inherit; font-size: 0.95rem; line-height: 1.6; resize: vertical; min-height: 150px;"
                                    placeholder="Thank you for your constructive feedback. We have carefully addressed all comments and made the following revisions to improve our manuscript..."
                                    oninput="updateCharCount(this, 'generalCount')"></textarea>
                                <div class="char-counter" id="generalCount" style="text-align: right; font-size: 0.8rem; color: #6c757d; margin-top: 0.5rem;">0 characters</div>
                            </div>

                            <div class="feedback-section" style="margin-bottom: 1.5rem;">
                                <label class="feedback-label" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem; display: block;">
                                    Summary of Major Changes
                                </label>
                                <textarea
                                    name="response_summary"
                                    class="form-textarea"
                                    style="width: 100%; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; font-family: inherit; font-size: 0.95rem; line-height: 1.6; resize: vertical; min-height: 150px;"
                                    placeholder="Summarize the major changes made to the manuscript:&#10;1. ...&#10;2. ...&#10;3. ..."
                                    oninput="updateCharCount(this, 'summaryCount')"></textarea>
                                <div class="char-counter" id="summaryCount" style="text-align: right; font-size: 0.8rem; color: #6c757d; margin-top: 0.5rem;">0 characters</div>
                            </div>
                        </div>

                        <!-- Additional Attachments -->
                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; font-weight: 600; color: #2c3e50; margin-bottom: 0.5rem;">Additional Attachments (Optional)</label>
                            <input type="file" name="attachments[]" multiple accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx,.zip" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 6px;">
                            <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #666;">You can upload multiple additional files (figures, tables, supplementary materials)</p>
                        </div>

                        <div class="actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #f0f0f0; display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                            <div class="actions" style="display: flex; gap: 1rem;">
                                <button type="button" class="btn btn-secondary" onclick="saveDraftRevision()">💾 Save Draft</button>
                                <button type="button" class="btn btn-outline" onclick="previewRevisionSubmission()">👁️ Preview Submission</button>
                            </div>
                            <button type="submit" class="btn btn-success" id="submitRevisionBtn">🚀 Submit Revision</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div>
                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Review Statistics</h2>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value">{{ $daysInReview ?? '—' }}</span>
                            <span class="stat-label">Days in Review</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">{{ $reviewsReceivedLabel }}</span>
                            <span class="stat-label">Reviews Received</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">{{ $daysToDecision ? '~' . $daysToDecision : '—' }}</span>
                            <span class="stat-label">Days to Decision</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">{{ $similarityStatus ?? '—' }}</span>
                            <span class="stat-label">Similarity Score</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Quick Actions</h2>
                    </div>

                    <div class="actions vertical">
                        <button class="btn btn-primary" onclick="contactEditor()">📧 Contact Editor</button>
                        <button class="btn btn-outline" onclick="viewGuidelines()">📖 View Submission Guidelines</button>
                        <button class="btn btn-outline" onclick="downloadAllFiles()">⬇️ Download All Files</button>
                        <button class="btn btn-outline" onclick="trackCitations()">📊 Track Citation Metrics</button>
                        <button class="btn btn-secondary" onclick="withdrawSubmission()">⚠️ Withdraw Submission</button>
                    </div>
                </div>

                <!-- Author Information -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Author Information</h2>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Corresponding</div>
                        <div class="detail-value">{{ $correspondingName }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Institution</div>
                        <div class="detail-value">{{ $correspondingAffiliation }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">{{ $correspondingEmail }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">ORCID</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Co-Authors</div>
                        <div class="detail-value">{{ $authorNames }}</div>
                    </div>
                </div>

                <!-- Journal Information -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Journal Information</h2>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Journal Name</div>
                        <div class="detail-value">{{ $journalTitle }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Impact Factor</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Acceptance Rate</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Avg. Review Time</div>
                        <div class="detail-value">—</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Publisher</div>
                        <div class="detail-value">{{ config('app.name') }}</div>
                    </div>
                </div>

                <!-- Help & Resources -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Help & Resources</h2>
                    </div>

                    <ul class="resource-list">
                        <li class="resource-item">
                            <a href="#">📖 Author Guidelines</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">❓ Peer Review FAQ</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">📧 Contact Editorial Office</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">⚖️ Publication Ethics</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">💰 Article Processing Charges</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">📊 Open Access Options</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">🔍 Track Your Submission</a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Support</h2>
                    </div>

                    <div class="alert alert-info">
                        <span class="alert-icon">💡</span>
                        <div>
                            <strong>Need Help?</strong><br>
                            Contact us at <strong>support@aisrp.org</strong> or call +1 (555) 123-4567
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <span>✓</span>
    <span id="toastMessage">Action completed successfully</span>
</div>
@endsection

@push('script')
<script>
        // Check for success message and clear form if present
        @if(session('success'))
            // Clear form data
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('revisionForm');
                if (form) {
                    form.reset();
                    // Clear file info display
                    const fileInfo = document.getElementById('fileInfo');
                    if (fileInfo) {
                        fileInfo.style.display = 'none';
                    }
                    // Clear all character counters
                    document.querySelectorAll('.char-counter').forEach(counter => {
                        counter.textContent = '0 characters';
                    });
                    // Clear localStorage draft
                    localStorage.removeItem('revision_draft_' + '{{ $orderIdDisplay }}');
                }
                // Show success message
                showToast('{{ session('success') }}');
            });
        @endif

        // Toast notification function
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            if (toast && toastMessage) {
                toastMessage.textContent = message;
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 5000);
            }
        }

        // File selection handler
        function handleFileSelect(input) {
            const fileInfo = document.getElementById('fileInfo');
            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                fileInfo.innerHTML = `📄 Selected: <strong>${file.name}</strong> (${fileSize} MB)`;
                fileInfo.style.display = 'block';
            } else {
                fileInfo.style.display = 'none';
            }
        }

        // Character counter
        function updateCharCount(textarea, counterId) {
            const counter = document.getElementById(counterId);
            if (counter) {
                const length = textarea.value.length;
                counter.textContent = `${length} characters`;
            }
        }

        // Save draft revision (localStorage)
        function saveDraftRevision() {
            const form = document.getElementById('revisionForm');
            if (form) {
                const formData = new FormData(form);
                const draftData = {};
                for (let [key, value] of formData.entries()) {
                    if (key.includes('response') || key === 'response_summary') {
                        draftData[key] = value;
                    }
                }
                localStorage.setItem('revision_draft_' + '{{ $orderIdDisplay }}', JSON.stringify(draftData));
                showToast('💾 Draft saved successfully');
            }
        }

        // Preview revision submission
        function previewRevisionSubmission() {
            const form = document.getElementById('revisionForm');
            if (form) {
                const manuscriptFile = document.getElementById('manuscript_file');
                if (!manuscriptFile.files || manuscriptFile.files.length === 0) {
                    alert('⚠️ Please upload your revised manuscript file before previewing.');
                    return;
                }
                alert('👁️ Preview functionality will show a summary of your submission.\n\nThis feature is coming soon!');
            }
        }

        // Submit revision form handler
        document.getElementById('revisionForm')?.addEventListener('submit', function(e) {
            const manuscriptFile = document.getElementById('manuscript_file');
            if (!manuscriptFile || !manuscriptFile.files || manuscriptFile.files.length === 0) {
                e.preventDefault();
                alert('⚠️ Please upload your revised manuscript file before submitting.');
                return false;
            }

            if (!confirm('🚀 Are you ready to submit your revision?\n\nPlease ensure:\n• All files are uploaded\n• All reviewer comments are addressed\n• Response letter is complete\n\nOnce submitted, you cannot edit your revision.')) {
                e.preventDefault();
                return false;
            }

            const submitBtn = document.getElementById('submitRevisionBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Submitting...';
            }
        });

        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Action handlers
        function composeMessage() {
            alert('📧 Opening message composer...\n\nYou can send a secure message to the editor about your manuscript.');
        }

        function viewAllMessages() {
            alert('📬 Loading all editorial communications...');
        }

        function contactEditor() {
            alert('📧 Opening contact form for Prof. Michael Chen...');
        }

        function viewGuidelines() {
            alert('📖 Opening author submission guidelines...');
        }

        function downloadAllFiles() {
            showToast('⬇️ Preparing download package...');
            setTimeout(() => {
                alert('✅ All files packaged successfully!\n\nDownload will begin shortly.');
            }, 1500);
        }

        function trackCitations() {
            alert('📊 Loading citation tracking dashboard...');
        }

        function withdrawSubmission() {
            if (confirm('⚠️ Are you sure you want to withdraw this submission?\n\nThis action cannot be undone. The editorial office will be notified immediately.')) {
                showToast('Withdrawal request submitted');
                setTimeout(() => {
                    alert('✅ Withdrawal request has been submitted to the editorial office.\n\nYou will receive confirmation via email shortly.');
                }, 1500);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('AISRP Author Dashboard loaded successfully');

            // Add click handlers to action buttons
            const buttons = document.querySelectorAll('.btn-manuscript');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const text = this.textContent.trim();

                    if (text.includes('View Manuscript')) {
                        alert('📄 Opening manuscript viewer...');
                    } else if (text.includes('Download PDF')) {
                        showToast('⬇️ Downloading manuscript PDF...');
                    } else if (text.includes('Supplementary')) {
                        alert('📎 Opening supplementary files...');
                    } else if (text.includes('Contact')) {
                        contactEditor();
                    } else if (text.includes('Statistics')) {
                        trackCitations();
                    }
                });
            });

            // File action handlers
            const fileLinks = document.querySelectorAll('.file-actions a');
            fileLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.textContent.trim();
                    const fileName = this.closest('.file-item').querySelector('.file-name').textContent;

                    if (action === 'Download') {
                        showToast(`⬇️ Downloading ${fileName}...`);
                    } else if (action === 'View') {
                        alert(`📄 Opening ${fileName}...`);
                    }
                });
            });
        });
    </script>
@endpush
