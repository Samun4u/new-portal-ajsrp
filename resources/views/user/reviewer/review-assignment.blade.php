<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Revision Workspace') }} - {{ config('app.name') }}</title>
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

        /* Header */
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #2a5298;
        }

        /* Navigation */
        .nav {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .nav-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            gap: 2rem;
        }

        .nav-item {
            padding: 1rem 0;
            color: #666;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .nav-item:hover, .nav-item.active {
            color: #2a5298;
            border-bottom-color: #2a5298;
        }

        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
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

        .status-revision-required {
            background: #fff3cd;
            color: #856404;
        }

        .status-major-revision {
            background: #f8d7da;
            color: #721c24;
        }

        .status-minor-revision {
            background: #cce5ff;
            color: #004085;
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

        /* Revision Notice - Major Alert */
        .revision-notice {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 6px solid #ffc107;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
        }

        .revision-notice-title {
            font-weight: 700;
            color: #856404;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .revision-notice-content {
            color: #856404;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .revision-deadline-box {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .deadline-item {
            text-align: center;
        }

        .deadline-label {
            font-weight: 600;
            color: #856404;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .deadline-value {
            font-weight: 700;
            color: #dc3545;
            font-size: 1.3rem;
        }

        /* Editorial Decision */
        .editorial-decision {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            border-left: 4px solid #2a5298;
            margin-bottom: 2rem;
        }

        .decision-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .decision-icon {
            width: 60px;
            height: 60px;
            background: #2a5298;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .decision-info h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .decision-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .decision-content {
            background: white;
            padding: 1.5rem;
            border-radius: 6px;
            line-height: 1.8;
            color: #2c3e50;
        }

        .decision-content p {
            margin-bottom: 1rem;
        }

        .decision-content p:last-child {
            margin-bottom: 0;
        }

        /* Reviewer Feedback Cards */
        .reviewer-card {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }

        .reviewer-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .reviewer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .reviewer-details h4 {
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .reviewer-details p {
            font-size: 0.85rem;
            color: #666;
        }

        .recommendation-badge {
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .recommendation-minor {
            background: #cce5ff;
            color: #004085;
        }

        .recommendation-major {
            background: #f8d7da;
            color: #721c24;
        }

        .recommendation-accept {
            background: #d4edda;
            color: #155724;
        }

        /* Quality Scores Section */
        .quality-scores {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .quality-scores-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.05rem;
        }

        .scores-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .score-item {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 6px;
        }

        .score-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2a5298;
            display: block;
            margin-bottom: 0.25rem;
        }

        .score-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Feedback Sections */
        .feedback-section {
            margin-bottom: 1.5rem;
        }

        .feedback-section:last-child {
            margin-bottom: 0;
        }

        .feedback-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feedback-content {
            color: #555;
            line-height: 1.8;
            padding: 1.25rem;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #2a5298;
        }

        .feedback-content p {
            margin-bottom: 1rem;
        }

        .feedback-content p:last-child {
            margin-bottom: 0;
        }

        .feedback-content ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .feedback-content ul li {
            margin-bottom: 0.5rem;
        }

        /* Action Items Checklist */
        .action-checklist {
            list-style: none;
            margin-top: 1rem;
        }

        .action-item {
            display: flex;
            align-items: start;
            gap: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            border-left: 3px solid #ffc107;
        }

        .action-item input[type="checkbox"] {
            margin-top: 0.25rem;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .action-item.completed {
            background: #d4edda;
            border-left-color: #28a745;
        }

        .action-item label {
            cursor: pointer;
            color: #2c3e50;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Revision Upload Section */
        .upload-section {
            background: #f0f4ff;
            padding: 2rem;
            border-radius: 8px;
            border: 2px dashed #2a5298;
            text-align: center;
            margin-bottom: 2rem;
            transition: all 0.2s ease;
        }

        .upload-section.drag-active {
            border-color: #1e3c72;
            background: rgba(42, 82, 152, 0.08);
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .upload-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .upload-description {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .selected-files {
            margin-top: 1rem;
            text-align: left;
        }

        .selected-files ul {
            list-style: none;
            color: #2c3e50;
        }

        .selected-files li {
            margin-bottom: 0.3rem;
            padding: 0.5rem 0.75rem;
            background: #fff;
            border-radius: 4px;
            box-shadow: inset 0 0 0 1px #e0e7ff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Response Form */
        .response-form {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            display: block;
            font-size: 1rem;
        }

        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            line-height: 1.6;
            resize: vertical;
            min-height: 150px;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .char-counter {
            text-align: right;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.5rem;
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

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 6px;
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.5rem;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
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

        .actions.vertical {
            flex-direction: column;
        }

        .actions.vertical .btn {
            width: 100%;
        }

        /* Progress Bar */
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
        }

        /* Timeline Item */
        .timeline-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #2a5298;
            margin-bottom: 1rem;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .timeline-content {
            color: #2c3e50;
            line-height: 1.6;
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

        /* Toast */
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

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .manuscript-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .scores-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .revision-deadline-box {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-content {
                flex-direction: column;
                gap: 0;
            }

            .container {
                padding: 0 1rem;
            }

            .manuscript-meta {
                grid-template-columns: 1fr;
            }

            .scores-grid {
                grid-template-columns: 1fr;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
@php
    $manuscriptTitle = $submission?->article_title ?? __('Untitled manuscript');
    $manuscriptId = $submission?->client_order_id ?? $review->client_order_id ?? __('N/A');
    $decisionDateLabel = optional($review->submitted_at ?? $review->updated_at ?? $assignment?->created_at)->translatedFormat('F d, Y');
    $statusLabel = $review->status
        ? \Illuminate\Support\Str::of($review->status)->replace('_', ' ')->title()
        : __('Pending Review');
    $invitationLabel = \Illuminate\Support\Str::of($invitationStatus)->replace('_', ' ')->title();
    $journalTitle = $submission?->journal?->title ?? __('Journal not specified');
    $articleType = $submission?->article_type?->name ?? __('Not specified');
    $submittedAt = optional($submission?->created_at)->translatedFormat('F d, Y');
    $reviewDurationDays = $submission?->created_at ? now()->diffInDays($submission->created_at) : null;
    $reviewDurationLabel = $reviewDurationDays === null
        ? __('—')
        : $reviewDurationDays . ' ' . \Illuminate\Support\Str::plural('day', $reviewDurationDays);
    $deadlineLabel = $dueAt ? $dueAt->translatedFormat('M d, Y') : __('Not set');
    if (!isset($daysRemaining)) {
        $daysRemainingLabel = __('—');
    } elseif ($daysRemaining >= 0) {
        $daysRemainingLabel = $daysRemaining . ' ' . \Illuminate\Support\Str::plural('day', $daysRemaining);
    } else {
        $daysRemainingLabel = __('Overdue by') . ' ' . abs($daysRemaining) . ' ' . \Illuminate\Support\Str::plural('day', abs($daysRemaining));
    }
    $reviewerAvatar = \Illuminate\Support\Str::upper(
        \Illuminate\Support\Str::substr($reviewer->name ?? __('Reviewer'), 0, 2)
    );
    $assignerName = $assignment?->assigner?->name ?? optional($order?->assignee)->assigner?->name ?? config('app.name');
    $authorsList = $submission?->authors?->pluck('name')->filter()->implode(', ') ?? __('Not provided');
    $manuscriptAbstract = $submission?->article_abstract ?? __('Abstract not provided.');
    $keywordsList = $submission?->article_keywords
        ? collect(explode(',', $submission->article_keywords))->map(fn ($keyword) => trim($keyword))->filter()->implode(', ')
        : __('Not provided');
    $revisionSubmitRoute = route('user.reviewer.reviews.revision', $review);
    $recommendationLabels = [
        'accept' => __('Accept'),
        'minor_revisions' => __('Minor Revisions'),
        'major_revisions' => __('Major Revisions'),
        'reject' => __('Reject'),
    ];
    $recommendationClasses = [
        'accept' => 'recommendation-accept',
        'minor_revisions' => 'recommendation-minor',
        'major_revisions' => 'recommendation-major',
        'reject' => 'recommendation-major',
    ];
    $recommendationDescriptions = [
        'accept' => __('Ready for publication with minimal or no edits.'),
        'minor_revisions' => __('Minor improvements required; no re-review needed.'),
        'major_revisions' => __('Significant changes are required and will need re-review.'),
        'reject' => __('Not suitable for publication in its current form.'),
    ];
    $ratingFields = [
        'rating_originality' => __('Originality'),
        'rating_methodology' => __('Methodology'),
        'rating_results' => __('Results'),
        'rating_clarity' => __('Clarity'),
        'rating_significance' => __('Significance'),
    ];
    $peerReviews = ($peerReviews ?? collect())->values();
@endphp
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">{{ config('app.name') }} — {{ __('Reviewer Workspace') }}</div>
            <div class="user-info">
                <div class="user-avatar">{{ $reviewerAvatar }}</div>
                <span>{{ $reviewer->name ?? __('Reviewer') }}</span>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-content">
            <a href="{{ route('user.dashboard') }}" class="nav-item">{{ __('Dashboard') }}</a>
            <a href="{{ route('user.orders.list') }}" class="nav-item active">{{ __('Assigned Reviews') }}</a>
            <a href="{{ route('user.submission.select-a-journal', ['by' => 'by-subject']) }}" class="nav-item">{{ __('New Submission') }}</a>
            <a href="{{ route('user.ticket.list') }}" class="nav-item">{{ __('Support') }}</a>
            <a href="{{ route('user.profile.index') }}" class="nav-item">{{ __('Profile') }}</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a> /
            <a href="{{ route('user.orders.list') }}">{{ __('Assigned Reviews') }}</a> /
            <span>{{ $manuscriptId }}</span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1>{{ __('Review Assignment Workspace') }}</h1>
            <p class="page-subtitle">
                {{ __('Review the manuscript details, work through the checklist, and submit your final recommendation once you are ready.') }}
            </p>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Main Content -->
            <div>
                <!-- Manuscript Header -->
                <div class="manuscript-header">
                    <div class="manuscript-title">{{ $manuscriptTitle }}</div>
                    <div class="manuscript-meta">
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Manuscript ID') }}</div>
                            <div class="meta-value">{{ $manuscriptId }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Decision Date') }}</div>
                            <div class="meta-value">{{ $decisionDateLabel ?? __('—') }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Current Decision') }}</div>
                            <div class="meta-value">{{ $statusLabel }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Reviews Received') }}</div>
                            <div class="meta-value">
                                {{ $statistics['completed_reviews'] }}
                                / {{ $statistics['total_reviews'] }}
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Review Duration') }}</div>
                            <div class="meta-value">{{ $reviewDurationLabel }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Invitation Status') }}</div>
                            <div class="meta-value">{{ $invitationLabel }}</div>
                        </div>
                    </div>
                </div>

                <!-- Revision Notice -->
                <div class="revision-notice">
                    <div class="revision-notice-title">
                        <span>⏰</span>
                        <span>{{ __('Action Required: Complete and submit your review') }}</span>
                    </div>
                    <div class="revision-notice-content">
                        {{ __('Thank you for accepting this assignment. Please examine the manuscript, document your feedback, and share your final recommendation before the deadline shown below. If you need additional time, contact the editorial office as soon as possible.') }}
                    </div>
                    <div class="revision-deadline-box">
                        <div class="deadline-item">
                            <div class="deadline-label">{{ __('Review Deadline') }}</div>
                            <div class="deadline-value">{{ $deadlineLabel }}</div>
                        </div>
                        <div class="deadline-item">
                            <div class="deadline-label">{{ __('Days Remaining') }}</div>
                            <div class="deadline-value">{{ $daysRemainingLabel }}</div>
                        </div>
                        <div class="deadline-item">
                            <div class="deadline-label">{{ __('Invitation Status') }}</div>
                            <div class="deadline-value">{{ $invitationLabel }}</div>
                        </div>
                    </div>
                </div>

                <!-- Editorial Decision -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Assignment Brief') }}</h2>
                        <span class="status-badge status-minor-revision">{{ $statusLabel }}</span>
                    </div>

                    <div class="editorial-decision">
                        <div class="decision-header">
                            <div class="decision-icon">✉️</div>
                            <div class="decision-info">
                                <h3>{{ $assignerName }}</h3>
                                <p>{{ __('Handling Editor') }}</p>
                            </div>
                        </div>
                        <div class="decision-content">
                            <p>{{ __('Dear') }} {{ $reviewer->name ?? __('Reviewer') }},</p>

                            <p>
                                {{ __('Thank you for evaluating') }}
                                “{{ $manuscriptTitle }}”
                                {{ __('for') }}
                                {{ $journalTitle }}.
                            </p>

                            <p>{{ __('Please review the submission, confirm the checklist items, and share clear comments for both the authors and the editor. Your assessment will guide the final decision for this manuscript.') }}</p>

                            <p><strong>{{ __('Assignment Summary') }}</strong></p>
                            <ul>
                                <li>{{ __('Client Order ID:') }} {{ $manuscriptId }}</li>
                                <li>{{ __('Article Type:') }} {{ $articleType }}</li>
                                <li>{{ __('Deadline:') }} {{ $deadlineLabel }}</li>
                            </ul>

                            <p>{{ __('Feel free to contact the editorial office if you encounter any issues accessing files or require extra time.') }}</p>

                            <p>{{ __('Sincerely,') }}<br>
                            {{ $assignerName }}</p>
                        </div>
                    </div>
                </div>

                <div class="card" id="reviewer-workspace">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Review Workspace') }}</h2>
                        <span class="status-badge {{ $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED ? 'status-accepted' : 'status-pending' }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <form id="review-workspace-form"
                          data-autosave-url="{{ route('user.reviewer.reviews.autosave', $review) }}"
                          data-submit-url="{{ route('user.reviewer.reviews.submit', $review) }}"
                          data-invitation-status="{{ $invitationStatus }}">
                        @csrf

                        <div class="recommendation-grid" style="margin-top: 0;">
                            @foreach($recommendationLabels as $key => $label)
                                @php
                                    $isSelected = ($review->overall_recommendation ?? 'minor_revisions') === $key;
                                @endphp
                                <label class="recommendation-card {{ $recommendationClasses[$key] ?? '' }} {{ $isSelected ? 'selected' : '' }}">
                                    <input type="radio"
                                           name="overall_recommendation"
                                           value="{{ $key }}"
                                           {{ $isSelected ? 'checked' : '' }}>
                                    <div class="recommendation-header">
                                        <div class="recommendation-icon">
                                            @switch($key)
                                                @case('accept') ✓ @break
                                                @case('minor_revisions') ◐ @break
                                                @case('major_revisions') ⟳ @break
                                                @case('reject') ✕ @break
                                            @endswitch
                                        </div>
                                        <div class="recommendation-title">{{ $label }}</div>
                                    </div>
                                    <div class="recommendation-description">
                                        {{ $recommendationDescriptions[$key] ?? '' }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="quality-scores" style="margin-top: 2rem;">
                            <div class="quality-scores-title">📊 {{ __('Quality Assessment Scores') }}</div>
                            <div class="scores-grid">
                                @foreach($ratingFields as $field => $label)
                                    @php
                                        $currentValue = $review->{$field} ?? 0;
                                    @endphp
                                    <div class="score-item">
                                        <label class="score-label" for="{{ $field }}">{{ $label }}</label>
                                        <input type="range"
                                               min="1"
                                               max="5"
                                               step="1"
                                               name="{{ $field }}"
                                               id="{{ $field }}"
                                               data-target="{{ $field }}"
                                               value="{{ $currentValue ?: 3 }}">
                                        <span class="score-value" data-target="{{ $field }}">
                                            {{ $currentValue ? $currentValue . '/5' : '3/5' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="comment-field">
                            <div class="field-label">
                                ✅ {{ __('Key Strengths') }}
                                <span class="required-badge">{{ __('Required') }}</span>
                            </div>
                            <textarea class="form-textarea"
                                      name="comment_strengths"
                                      rows="4"
                                      required>{{ $review->comment_strengths }}</textarea>
                        </div>

                        <div class="comment-field">
                            <div class="field-label">
                                ⚠️ {{ __('Areas Requiring Attention') }}
                                <span class="required-badge">{{ __('Required') }}</span>
                            </div>
                            <textarea class="form-textarea"
                                      name="comment_weaknesses"
                                      rows="4"
                                      required>{{ $review->comment_weaknesses }}</textarea>
                        </div>

                        <div class="comment-field">
                            <div class="field-label">
                                💬 {{ __('Comments for Authors') }}
                                <span class="required-badge">{{ __('Required') }}</span>
                            </div>
                            <textarea class="form-textarea"
                                      name="comment_for_authors"
                                      rows="6"
                                      required>{{ $review->comment_for_authors }}</textarea>
                            <small class="text-muted">{{ __('This will be shared with the authors exactly as written.') }}</small>
                        </div>

                        <div class="comment-field">
                            <div class="field-label">
                                🔒 {{ __('Confidential Comments for Editor') }}
                            </div>
                            <textarea class="form-textarea"
                                      name="comment_for_editor"
                                      rows="4">{{ $review->comment_for_editor }}</textarea>
                            <small class="text-muted">{{ __('Visible only to the editorial team.') }}</small>
                        </div>

                        <div class="card" style="box-shadow:none;padding:0;margin-top:1.5rem;">
                            <div class="card-header" style="padding-left:0;">
                                <h2 class="card-title">{{ __('Reviewer Checklist') }}</h2>
                            </div>
                            <ul class="action-checklist">
                                @foreach($checklist as $key => $value)
                                    @php
                                        $inputId = 'checklist-' . $key;
                                    @endphp
                                    <li class="action-item {{ $value ? 'completed' : '' }}">
                                        <label for="{{ $inputId }}">
                                            <input type="checkbox"
                                                   id="{{ $inputId }}"
                                                   name="specific_checks[{{ $key }}]"
                                                   value="1"
                                                   onchange="toggleAction(this)"
                                                   {{ $value ? 'checked' : '' }}>
                                            {{ \Illuminate\Support\Str::headline($key) }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="actions" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #f0f0f0; justify-content: flex-end; gap: 1rem;">
                            <button type="button" class="btn btn-outline" id="save-progress">💾 {{ __('Save Draft') }}</button>
                            <button type="button" class="btn btn-primary" id="submit-review">🚀 {{ __('Submit Review') }}</button>
                        </div>
                    </form>
                </div>

                @if($peerReviews->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">{{ __('Peer Reviews Submitted') }}</h2>
                        </div>
                        @foreach($peerReviews as $index => $reviewItem)
                            @php
                                $reviewerName = $reviewItem->reviewer?->name ?? (__('Reviewer') . ' ' . ($index + 1));
                                $reviewerInitials = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($reviewerName, 0, 2));
                                $recommendationKey = $reviewItem->overall_recommendation ?? 'minor_revisions';
                                $recommendationLabel = $recommendationLabels[$recommendationKey] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', $recommendationKey));
                                $recommendationClass = $recommendationClasses[$recommendationKey] ?? 'recommendation-minor';
                                $submittedLabel = optional($reviewItem->submitted_at ?? $reviewItem->updated_at)->translatedFormat('M d, Y');
                            @endphp
                            <div class="reviewer-card">
                                <div class="reviewer-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">{{ $reviewerInitials }}</div>
                                        <div class="reviewer-details">
                                            <h4>{{ $reviewerName }}</h4>
                                            <p>{{ __('Submitted:') }} {{ $submittedLabel ?? __('—') }}</p>
                                        </div>
                                    </div>
                                    <span class="recommendation-badge {{ $recommendationClass }}">
                                        {{ $recommendationLabel }}
                                    </span>
                                </div>
                                <div class="quality-scores">
                                    <div class="quality-scores-title">📊 {{ __('Quality Assessment Scores') }}</div>
                                    <div class="scores-grid">
                                        @php $hasScore = false; @endphp
                                        @foreach($ratingFields as $field => $label)
                                            @if(!is_null($reviewItem->{$field}))
                                                @php $hasScore = true; @endphp
                                                <div class="score-item">
                                                    <span class="score-value">{{ $reviewItem->{$field} }}/5</span>
                                                    <span class="score-label">{{ $label }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(!is_null($reviewItem->quality_rating))
                                            @php $hasScore = true; @endphp
                                            <div class="score-item">
                                                <span class="score-value">{{ number_format($reviewItem->quality_rating, 2) }}</span>
                                                <span class="score-label">{{ __('Overall Quality') }}</span>
                                            </div>
                                        @endif
                                        @if(!$hasScore)
                                            <div class="score-item">
                                                <span class="score-value">—</span>
                                                <span class="score-label">{{ __('No ratings provided') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($reviewItem->comment_strengths)
                                    <div class="feedback-section">
                                        <div class="feedback-label">✅ {{ __('Key Strengths') }}</div>
                                        <div class="feedback-content">{!! nl2br(e($reviewItem->comment_strengths)) !!}</div>
                                    </div>
                                @endif
                                @if($reviewItem->comment_weaknesses)
                                    <div class="feedback-section">
                                        <div class="feedback-label">⚠️ {{ __('Areas Requiring Attention') }}</div>
                                        <div class="feedback-content">{!! nl2br(e($reviewItem->comment_weaknesses)) !!}</div>
                                    </div>
                                @endif
                                @if($reviewItem->comment_for_authors)
                                    <div class="feedback-section">
                                        <div class="feedback-label">💬 {{ __('Comments for Authors') }}</div>
                                        <div class="feedback-content">{!! nl2br(e($reviewItem->comment_for_authors)) !!}</div>
                                    </div>
                                @endif
                                @if($reviewItem->comment_for_editor)
                                    <div class="feedback-section">
                                        <div class="feedback-label">🔒 {{ __('Confidential Comments for Editor') }}</div>
                                        <div class="feedback-content">{!! nl2br(e($reviewItem->comment_for_editor)) !!}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Action Items Summary -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Reviewer Checklist') }}</h2>
                    </div>

                    <p style="margin-bottom: 1.5rem; color: #666;">
                        {{ __('Use this checklist to confirm each review requirement before submitting your recommendation.') }}
                    </p>

                    <ul class="action-checklist">
                        @foreach($checklist as $key => $value)
                            @php $inputId = 'action-' . $key; @endphp
                            <li class="action-item {{ $value ? 'completed' : '' }}">
                                <input type="checkbox"
                                       id="{{ $inputId }}"
                                       onchange="toggleAction(this)"
                                       {{ $value ? 'checked' : '' }}>
                                <label for="{{ $inputId }}">
                                    {{ \Illuminate\Support\Str::headline($key) }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Submit Revision Section -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Submit Your Revision') }}</h2>
                    </div>

                    <div class="alert alert-warning">
                        <span class="alert-icon">⚠️</span>
                        <div>
                            <strong>{{ __('Before submitting:') }}</strong>
                            {{ __('Ensure you have addressed all reviewer comments and prepared the required documents (revised manuscript, response letter, and supplementary materials).') }}
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                            {{ __('Please fix the following issues before resubmitting:') }}
                            <ul style="margin: 0.75rem 0 0 1rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($revisionSubmitRoute)
                        <form id="revision-upload-form"
                              action="{{ $revisionSubmitRoute }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="alert alert-danger d-none" id="revision-error-alert" role="alert"></div>

                            <!-- Manuscript Upload -->
                            <div class="upload-section upload-zone"
                                 data-input-selector=".upload-input--manuscript"
                                 data-preview-selector=".selected-files--manuscript">
                                <div class="upload-icon">📄</div>
                                <div class="upload-title">{{ __('Revised Manuscript (required)') }}</div>
                                <div class="upload-description">
                                    {{ __('Upload the clean version of your manuscript (.doc, .docx, .pdf). Maximum size 50MB.') }}
                                </div>
                                <div class="file-input-wrapper">
                                    <button type="button"
                                            class="btn btn-primary"
                                            onclick="document.querySelector('.upload-input--manuscript').click();">
                                        {{ __('Choose File') }}
                                    </button>
                                    <input type="file"
                                           class="upload-input upload-input--manuscript"
                                           name="manuscript_file"
                                           accept=".doc,.docx,.pdf"
                                           required
                                           aria-label="{{ __('Select revised manuscript file') }}">
                                </div>
                                <div class="selected-files selected-files--manuscript" aria-live="polite"></div>
                            </div>

                            <!-- Response Letter Upload -->
                            <div class="upload-section upload-zone"
                                 style="margin-top: 1.5rem;"
                                 data-input-selector=".upload-input--response"
                                 data-preview-selector=".selected-files--response">
                                <div class="upload-icon">📝</div>
                                <div class="upload-title">{{ __('Response Letter (optional)') }}</div>
                                <div class="upload-description">
                                    {{ __('Attach your detailed point-by-point response letter if available.') }}
                                </div>
                                <div class="file-input-wrapper">
                                    <button type="button"
                                            class="btn btn-primary"
                                            onclick="document.querySelector('.upload-input--response').click();">
                                        {{ __('Choose File') }}
                                    </button>
                                    <input type="file"
                                           class="upload-input upload-input--response"
                                           name="response_file"
                                           accept=".doc,.docx,.pdf"
                                           aria-label="{{ __('Select response letter file') }}">
                                </div>
                                <div class="selected-files selected-files--response" aria-live="polite"></div>
                            </div>

                            <!-- Attachments Upload -->
                            <div class="upload-section upload-zone"
                                 style="margin-top: 1.5rem;"
                                 data-input-selector=".upload-input--attachments"
                                 data-preview-selector=".selected-files--attachments">
                                <div class="upload-icon">📤</div>
                                <div class="upload-title">{{ __('Supplementary Attachments') }}</div>
                                <div class="upload-description">
                                    {{ __('Drag & drop additional files (figures, tables, data) or use the button below. You can upload multiple files (Max 50MB each).') }}
                                </div>
                                <div class="file-input-wrapper">
                                    <button type="button"
                                            class="btn btn-primary"
                                            onclick="document.querySelector('.upload-input--attachments').click();">
                                        {{ __('Choose Files') }}
                                    </button>
                                    <input type="file"
                                           class="upload-input upload-input--attachments"
                                           name="attachments[]"
                                           multiple
                                           accept=".pdf,.doc,.docx,.zip,.xls,.xlsx,.ppt,.pptx,.csv,.jpg,.png"
                                           aria-label="{{ __('Select supplementary attachment files') }}">
                                </div>
                                <div class="selected-files selected-files--attachments" aria-live="polite"></div>
                            </div>

                            <!-- Response to Reviewers Form -->
                            <div class="response-form">
                                <h3 style="margin: 2rem 0 1rem; color: #2c3e50;">{{ __('Response to Reviewers') }}</h3>
                                <p style="margin-bottom: 1.5rem; color: #666;">
                                    {{ __('Provide a detailed summary of updates and, if needed, separate responses for each reviewer.') }}
                                </p>

                                <div class="form-group">
                                    <label class="form-label">{{ __('General Response to All Reviewers') }}</label>
                                    <textarea name="response_summary"
                                              class="form-textarea"
                                              placeholder="{{ __('Thank you for your constructive feedback. We have carefully addressed all comments and made the following revisions...') }}"
                                              oninput="updateCharCount(this, 'generalCount')">{{ old('response_summary') }}</textarea>
                                    <div class="char-counter" id="generalCount">0 {{ __('characters') }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('Detailed Response to Reviewer 1') }}</label>
                                    <textarea name="responses[reviewer_1]"
                                              class="form-textarea"
                                              style="min-height: 200px;"
                                              placeholder="{{ __('Point-by-point response to Reviewer 1...') }}"
                                              oninput="updateCharCount(this, 'r1Count')"></textarea>
                                    <div class="char-counter" id="r1Count">0 {{ __('characters') }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('Detailed Response to Reviewer 2') }}</label>
                                    <textarea name="responses[reviewer_2]"
                                              class="form-textarea"
                                              style="min-height: 200px;"
                                              placeholder="{{ __('Point-by-point response to Reviewer 2...') }}"
                                              oninput="updateCharCount(this, 'r2Count')"></textarea>
                                    <div class="char-counter" id="r2Count">0 {{ __('characters') }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('Detailed Response to Reviewer 3') }}</label>
                                    <textarea name="responses[reviewer_3]"
                                              class="form-textarea"
                                              style="min-height: 200px;"
                                              placeholder="{{ __('Point-by-point response to Reviewer 3...') }}"
                                              oninput="updateCharCount(this, 'r3Count')"></textarea>
                                    <div class="char-counter" id="r3Count">0 {{ __('characters') }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('Summary of Major Changes') }}</label>
                                    <textarea class="form-textarea"
                                              name="responses[summary]"
                                              placeholder="{{ __('Summarize the major changes made to the manuscript: 1. ... 2. ... 3. ...') }}"
                                              oninput="updateCharCount(this, 'summaryCount')"></textarea>
                                    <div class="char-counter" id="summaryCount">0 {{ __('characters') }}</div>
                                </div>
                            </div>

                            <div class="actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #f0f0f0; justify-content: space-between;">
                                <div class="actions">
                                    <button class="btn btn-secondary" type="button" onclick="saveDraft()">💾 {{ __('Save Draft') }}</button>
                                    <button class="btn btn-outline" type="button" onclick="previewSubmission()">👁️ {{ __('Preview Submission') }}</button>
                                </div>
                                <button class="btn btn-success"
                                        type="button"
                                        data-role="revision-submit"
                                        onclick="submitRevision()">🚀 {{ __('Submit Revision') }}</button>
                            </div>
                        </form>
                    @else
                        <p style="margin-top: 1rem;">
                            {{ __('Revision submission is not available for this assignment.') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar -->
            <div>
                <!-- Revision Progress -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Review Progress') }}</h2>
                    </div>

                    <div class="progress-section" style="margin: 0;">
                        <div class="progress-header">
                            <div class="progress-title">{{ __('Completion Status') }}</div>
                            <div class="progress-percentage" id="progressPercent">
                                {{ $review->progress ?? 0 }}%
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                 id="progressBar"
                                 style="width: {{ $review->progress ?? 0 }}%;"></div>
                        </div>
                        <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">
                            {{ __('Complete each checklist item to reach 100% before submitting your final review.') }}
                        </p>
                    </div>
                </div>

                <!-- Review Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Review Summary') }}</h2>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value">{{ $statistics['total_reviews'] }}</span>
                            <span class="stat-label">{{ __('Total Reviewers') }}</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">
                                {{ $statistics['average_rating'] ? number_format($statistics['average_rating'], 2) : '—' }}
                            </span>
                            <span class="stat-label">{{ __('Avg. Quality Score') }}</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">{{ $daysRemaining ?? '—' }}</span>
                            <span class="stat-label">{{ __('Days Remaining') }}</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">{{ $checklist->count() }}</span>
                            <span class="stat-label">{{ __('Checklist Items') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Quick Actions</h2>
                    </div>

                    <div class="actions vertical">
                        <button class="btn btn-outline" onclick="downloadReviews()">⬇️ Download All Reviews (PDF)</button>
                        <button class="btn btn-outline" onclick="downloadOriginal()">📄 Download Original Manuscript</button>
                        <button class="btn btn-outline" onclick="viewGuidelines()">📖 View Revision Guidelines</button>
                        <button class="btn btn-outline" onclick="contactEditor()">📧 Contact Editor</button>
                        <button class="btn btn-secondary" onclick="requestExtension()">⏰ Request Extension</button>
                    </div>
                </div>

                <!-- Important Reminders -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Important Timeline') }}</h2>
                    </div>

                    @forelse($timeline as $event)
                        <div class="timeline-item">
                            <div class="timeline-date">
                                {{ optional($event['timestamp'])->translatedFormat('F d, Y h:i A') ?? '—' }}
                            </div>
                            <div class="timeline-content">
                                {{ $event['label'] }}
                            </div>
                        </div>
                    @empty
                        <p style="color: #666; padding: 0 1rem 1rem;">
                            {{ __('No timeline events recorded yet.') }}
                        </p>
                    @endforelse
                </div>

                <!-- Help Resources -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Help & Resources</h2>
                    </div>

                    <ul class="resource-list">
                        <li class="resource-item">
                            <a href="#">📖 How to Respond to Reviewers</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">✏️ Using Track Changes</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">❓ Revision Process FAQ</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">📧 Contact Editorial Office</a>
                        </li>
                        <li class="resource-item">
                            <a href="#">📅 Request Deadline Extension</a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Need Help?</h2>
                    </div>

                    <div class="alert alert-info">
                        <span class="alert-icon">💡</span>
                        <div>
                            Questions about the review? Contact us at <strong>support@aisrp.org</strong>
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

    <script>
        // Toast notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Character counter
        function updateCharCount(textarea, counterId) {
            const count = textarea.value.length;
            document.getElementById(counterId).textContent = `${count} characters`;
        }

        // Action item toggle
        function toggleAction(checkbox) {
            const item = checkbox.closest('.action-item');
            if (checkbox.checked) {
                item.classList.add('completed');
            } else {
                item.classList.remove('completed');
            }
            updateProgress();
        }

        // Update progress bar
        function updateProgress() {
            const total = document.querySelectorAll('.action-item').length;
            const completed = document.querySelectorAll('.action-item.completed').length;
            const percentage = Math.round((completed / total) * 100);

            document.getElementById('progressBar').style.width = `${percentage}%`;
            document.getElementById('progressPercent').textContent = `${percentage}%`;
        }

        // Action handlers
        function saveDraft() {
            showToast('💾 Draft saved successfully');
        }

        function previewSubmission() {
            alert('👁️ Opening submission preview...\n\nYou can review all uploaded files and responses before final submission.');
        }

        function downloadReviews() {
            showToast('⬇️ Downloading all reviews as PDF...');
        }

        function downloadOriginal() {
            showToast('📄 Downloading original manuscript...');
        }

        function viewGuidelines() {
            alert('📖 Opening revision guidelines...\n\nDetailed instructions on how to prepare and submit your revision.');
        }

        function contactEditor() {
            alert('📧 Opening message composer...\n\nSend a secure message to Prof. Michael Chen regarding your revision.');
        }

        function requestExtension() {
            const reason = prompt('Please provide a brief reason for requesting a deadline extension:');
            if (reason) {
                showToast('⏰ Extension request submitted');
                setTimeout(() => {
                    alert('✅ Extension request submitted!\n\nThe editorial office will review your request and respond within 24-48 hours.');
                }, 1500);
            }
        }

        function filesLabel(count) {
            return count === 1 ? '{{ __('file selected') }}' : '{{ __('files selected') }}';
        }

        function renderFiles(previewContainer, files, single = false) {
            if (!previewContainer) {
                return;
            }
            previewContainer.innerHTML = '';
            if (!files || files.length === 0) {
                return;
            }

            const list = document.createElement('ul');
            const source = single ? [files[0]] : Array.from(files);

            source.forEach(file => {
                if (!file) {
                    return;
                }
                const item = document.createElement('li');
                const size = (file.size / 1024).toFixed(1);
                item.textContent = `${file.name} (${size} KB)`;
                list.appendChild(item);
            });

            previewContainer.appendChild(list);
        }

        function setupUploadZones() {
            const zones = document.querySelectorAll('.upload-zone');

            zones.forEach(zone => {
                const input = zone.querySelector('.upload-input');
                const previewSelector = zone.dataset.previewSelector;
                const previewContainer = previewSelector ? zone.querySelector(previewSelector) : zone.querySelector('.selected-files');
                const allowMultiple = input && input.multiple;

                if (!input) {
                    return;
                }

                const highlight = () => zone.classList.add('drag-active');
                const unhighlight = () => zone.classList.remove('drag-active');

                const syncFiles = (files) => {
                    if (!files?.length) {
                        input.value = '';
                        renderFiles(previewContainer, null);
                        return;
                    }

                    const normalized = allowMultiple ? Array.from(files) : [files[0]];
                    const dataTransfer = new DataTransfer();
                    normalized.forEach(file => {
                        if (file) {
                            dataTransfer.items.add(file);
                        }
                    });
                    input.files = dataTransfer.files;

                    renderFiles(previewContainer, input.files, !allowMultiple);
                    showToast(`📤 ${normalized.length} ${filesLabel(normalized.length)}`);
                };

                ['dragenter', 'dragover'].forEach(eventName => {
                    zone.addEventListener(eventName, event => {
                        event.preventDefault();
                        highlight();
                    });
                });

                ['dragleave', 'dragend'].forEach(eventName => {
                    zone.addEventListener(eventName, event => {
                        event.preventDefault();
                        unhighlight();
                    });
                });

                zone.addEventListener('drop', event => {
                    event.preventDefault();
                    unhighlight();
                    if (event.dataTransfer?.files?.length) {
                        syncFiles(event.dataTransfer.files);
                    }
                });

                input.addEventListener('change', event => {
                    const files = event.target.files;
                    if (files && files.length > 0) {
                        syncFiles(files);
                    } else {
                        renderFiles(previewContainer, null);
                    }
                });
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('AISRP Author Revision Dashboard loaded successfully');
            updateProgress();
            setupUploadZones();
        });

        async function submitRevision() {
            const form = document.getElementById('revision-upload-form');
            if (!form) {
                return;
            }

            const manuscriptInput = form.querySelector('.upload-input--manuscript');
            if (!manuscriptInput || !manuscriptInput.files || manuscriptInput.files.length === 0) {
                alert('⚠️ {{ __('Please upload your revised manuscript before submitting.') }}');
                return;
            }

            const submitButton = form.querySelector('[data-role="revision-submit"]');
            const errorAlert = document.getElementById('revision-error-alert');
            if (errorAlert) {
                errorAlert.classList.add('d-none');
                errorAlert.innerHTML = '';
            }

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.dataset.originalText = submitButton.innerHTML;
                submitButton.innerHTML = '⏳ {{ __('Submitting...') }}';
            }

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                    },
                    body: formData,
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const message = data.message || '{{ __('Unable to submit your files right now. Please try again or contact support.') }}';
                    if (errorAlert) {
                        errorAlert.classList.remove('d-none');
                        errorAlert.textContent = message;
                    } else {
                        alert(message);
                    }
                    throw new Error(message);
                }

                showToast('✅ ' + (data.message || '{{ __('Revision submitted successfully.') }}'));

                const redirectUrl = data.redirect_url || window.location.href;
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 1000);
            } catch (error) {
                console.error(error);
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButton.dataset.originalText || '🚀 {{ __('Submit Revision') }}';
                }
            }
        }

        (function setupReviewForm() {
            const reviewForm = document.getElementById('review-workspace-form');
            if (!reviewForm) {
                return;
            }

            const autosaveUrl = reviewForm.dataset.autosaveUrl;
            const submitUrl = reviewForm.dataset.submitUrl;
            const invitationStatus = reviewForm.dataset.invitationStatus;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const saveDraftBtn = document.getElementById('save-progress');
            const submitReviewBtn = document.getElementById('submit-review');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            let autosaveTimer = null;

            const updateProgressDisplay = (value) => {
                if (progressBar) {
                    progressBar.style.width = `${value}%`;
                }
                if (progressPercent) {
                    progressPercent.textContent = `${value}%`;
                }
            };

            const buildFormData = () => {
                const formData = new FormData(reviewForm);
                reviewForm.querySelectorAll('.action-checklist input[type="checkbox"]').forEach((checkbox) => {
                    if (!checkbox.checked) {
                        formData.append(checkbox.name, '0');
                    }
                });
                return formData;
            };

            const handleProgressResponse = (data) => {
                const progress = data?.data?.progress ?? data?.progress;
                if (typeof progress === 'number') {
                    updateProgressDisplay(progress);
                }
            };

            const autosaveReview = async () => {
                if (!autosaveUrl || !csrfToken) {
                    return;
                }
                const payload = buildFormData();
                try {
                    const response = await fetch(autosaveUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: payload,
                    });

                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(data?.message || '{{ __('Unable to save your progress right now.') }}');
                    }

                    handleProgressResponse(data);
                    showToast('💾 {{ __('Draft saved') }}');
                } catch (error) {
                    console.error(error);
                }
            };

            const debouncedAutosave = () => {
                clearTimeout(autosaveTimer);
                autosaveTimer = setTimeout(autosaveReview, 1200);
            };

            reviewForm.addEventListener('input', (event) => {
                if (event.target.matches('input[type="range"]')) {
                    const target = event.target.dataset.target || event.target.name;
                    const label = reviewForm.querySelector(`.score-value[data-target="${target}"]`);
                    if (label) {
                        label.textContent = `${event.target.value}/5`;
                    }
                }
                debouncedAutosave();
            });

            reviewForm.addEventListener('change', (event) => {
                if (event.target.matches('.action-checklist input[type="checkbox"]')) {
                    const item = event.target.closest('.action-item');
                    if (item) {
                        event.target.checked ? item.classList.add('completed') : item.classList.remove('completed');
                    }
                }
                debouncedAutosave();
            });

            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    autosaveReview();
                });
            }

            if (submitReviewBtn) {
                submitReviewBtn.addEventListener('click', async (event) => {
                    event.preventDefault();
                    if (!submitUrl || !csrfToken) {
                        return;
                    }
                    if (!window.confirm('{{ __('Submit your review? This will finalize your feedback for the editorial team.') }}')) {
                        return;
                    }
                    const payload = buildFormData();
                    try {
                        const response = await fetch(submitUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: payload,
                        });
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data?.message || '{{ __('Unable to submit your review right now.') }}');
                        }
                        handleProgressResponse(data);
                        showToast('🎉 {{ __('Review submitted successfully.') }}');
                        window.location.reload();
                    } catch (error) {
                        alert(error.message);
                    }
                });
            }

            if (invitationStatus !== 'accepted') {
                reviewForm.querySelectorAll('input, textarea, button, select').forEach((element) => {
                    element.setAttribute('disabled', 'disabled');
                });
            }
        })();
    </script>
</body>
</html>
