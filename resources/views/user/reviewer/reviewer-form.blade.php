@extends('user.layouts.app')

@push('title')
    {{ __('Reviewer Workspace') }} - {{ $submission?->article_title ?? __('Review') }}
@endpush

@push('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title-section h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #666;
            font-size: 1rem;
        }

        .deadline-badge {
            background: #e74c3c;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .deadline-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 0.3rem;
        }

        .deadline-date {
            font-size: 1.3rem;
            font-weight: bold;
        }

        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
            margin-bottom: 2rem;
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

        /* Status Badge */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-in-progress {
            background: #cce5ff;
            color: #004085;
        }

        /* Manuscript Details */
        .manuscript-header {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .manuscript-title {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .manuscript-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1rem;
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
        }

        .btn-manuscript:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Detail Row */
        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #2c3e50;
        }

        /* Abstract Box */
        .abstract-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            border-left: 4px solid #2a5298;
            margin: 1.5rem 0;
        }

        .abstract-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .abstract-text {
            color: #555;
            line-height: 1.8;
            text-align: justify;
        }

        /* Section Divider */
        .section-divider {
            border: 0;
            height: 2px;
            background: linear-gradient(90deg, #2a5298, transparent);
            margin: 2.5rem 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-number {
            width: 40px;
            height: 40px;
            background: #2a5298;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1.4rem;
            color: #2c3e50;
            font-weight: 600;
        }

        /* COI Section */
        .coi-alert {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .coi-alert-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: #856404;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .checkbox-label {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.75rem;
            background: white;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .checkbox-label:hover {
            background: #f8f9fa;
        }

        .checkbox-label input[type="checkbox"] {
            margin-top: 0.25rem;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .coi-explanation {
            margin-top: 1rem;
            display: none;
        }

        /* Quality Assessment */
        .quality-grid {
            display: grid;
            gap: 1.5rem;
        }

        .quality-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #2a5298;
        }

        .quality-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .quality-label {
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .help-icon {
            width: 20px;
            height: 20px;
            background: #6c757d;
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: help;
            position: relative;
        }

        .help-icon:hover .tooltip {
            display: block;
        }

        .tooltip {
            display: none;
            position: absolute;
            bottom: 125%;
            right: 0;
            background: #2c3e50;
            color: white;
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            width: 280px;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            line-height: 1.5;
        }

        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            right: 10px;
            border: 6px solid transparent;
            border-top-color: #2c3e50;
        }

        .quality-score {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2a5298;
            min-width: 60px;
            text-align: right;
        }

        /* Rating Scale */
        .rating-scale {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .rating-option {
            flex: 1;
            padding: 1rem 0.5rem;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .rating-option:hover {
            border-color: #2a5298;
            background: #f8f9fa;
        }

        .rating-option input[type="radio"] {
            display: none;
        }

        .rating-option.selected {
            background: #2a5298;
            border-color: #2a5298;
            color: white;
        }

        .rating-value {
            font-size: 1.5rem;
            font-weight: bold;
            display: block;
            margin-bottom: 0.25rem;
        }

        .rating-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Overall Score Card */
        .overall-score-card {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            margin: 2rem 0;
        }

        .overall-score-label {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .overall-score-value {
            font-size: 4rem;
            font-weight: 700;
            margin: 1rem 0;
        }

        .overall-score-subtitle {
            font-size: 0.9rem;
            opacity: 0.85;
        }

        /* Recommendation Cards */
        .recommendation-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .recommendation-card {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            background: white;
        }

        .recommendation-card:hover {
            border-color: #2a5298;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .recommendation-card.selected {
            border-color: #2a5298;
            background: #f0f4ff;
        }

        .recommendation-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .recommendation-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .recommendation-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .recommendation-card.accept .recommendation-icon {
            background: #d4edda;
            color: #155724;
        }

        .recommendation-card.minor .recommendation-icon {
            background: #fff3cd;
            color: #856404;
        }

        .recommendation-card.major .recommendation-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .recommendation-card.reject .recommendation-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .recommendation-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
        }

        .recommendation-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .subcategory-select {
            margin-top: 1rem;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            width: 100%;
            font-size: 0.9rem;
            display: none;
        }

        /* Comments Section */
        .comment-field {
            margin-bottom: 2rem;
        }

        .field-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .required-badge {
            background: #e74c3c;
            color: white;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .field-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            line-height: 1.6;
            resize: vertical;
            min-height: 120px;
        }

        .textarea:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .textarea-large {
            min-height: 200px;
        }

        .char-counter {
            text-align: right;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        /* Checklist */
        .checklist {
            list-style: none;
        }

        .checklist-item {
            padding: 1rem;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }

        .checklist-item:hover {
            background: #f8f9fa;
        }

        .checklist-item.completed {
            background: #d4edda;
            border-color: #28a745;
        }

        .checklist-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .checklist-item label {
            cursor: pointer;
            color: #2c3e50;
            flex: 1;
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

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Right Sidebar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2a5298, #1e3c72);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-weight: 600;
            color: #2a5298;
            font-size: 1.1rem;
            text-align: center;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 500;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
            border-left: 4px solid #2a5298;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #2a5298;
            display: block;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        /* Guidelines Box */
        .guideline-box {
            background: #f0f4ff;
            padding: 1.5rem;
            border-radius: 6px;
            border-left: 4px solid #2a5298;
        }

        .guideline-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .guideline-list {
            list-style-position: inside;
            color: #555;
            font-size: 0.9rem;
        }

        .guideline-list li {
            margin-bottom: 0.75rem;
            padding-left: 0.5rem;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 6px;
            display: flex;
            align-items: start;
            gap: 1rem;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-icon {
            font-size: 1.5rem;
        }

        /* Auto-save Toast */
        .autosave-toast {
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

        .autosave-toast.show {
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

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-overlay.show {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
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

            .recommendation-grid {
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

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .manuscript-meta {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .rating-scale {
                flex-wrap: wrap;
            }

            .rating-option {
                flex: 1 1 calc(50% - 0.5rem);
            }
        }
    </style>
@endpush

@section('content')
@php
    $manuscriptTitle = $submission?->article_title ?? __('Untitled manuscript');
    $manuscriptId = $submission?->client_order_id ?? $review->client_order_id ?? __('N/A');
    $journalTitle = $submission?->journal?->title ?? __('Journal not specified');
    $articleType = $submission?->article_type?->name ?? __('Not specified');
    $submittedAt = optional($submission?->created_at)->translatedFormat('F d, Y') ?? '—';
    $lastUpdated = optional($submission?->updated_at)->translatedFormat('F d, Y') ?? '—';
    $keywordsList = $submission?->article_keywords ? collect(explode(',', $submission->article_keywords))->map(fn($k)=>trim($k))->filter()->implode(', ') : '—';
    $reviewerAvatar = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($reviewer->name ?? __('Reviewer'), 0, 2));
    $statusLabel = $review->status ? \Illuminate\Support\Str::of($review->status)->replace('_', ' ')->title() : __('Pending Review');
    $deadlineLabel = $dueAt ? $dueAt->translatedFormat('M d, Y') : __('Not set');
    $files = $files ?? [];
    $manuscriptUrl = $files['manuscript'] ?? null;
    $coverLetterUrl = $files['cover_letter'] ?? null;
    $supplementaryFiles = collect($files['supplements'] ?? []);
    $autosaveUrl = route('user.reviewer.reviews.autosave', $review);
    $submitUrl = route('user.reviewer.reviews.submit', $review);

    // Check if review is submitted (read-only mode)
    $isReviewSubmitted = $review->status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED && $review->submitted_at !== null;
@endphp

<div class="container-fluid py-4">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a> /
            <a href="{{ route('user.orders.list') }}">{{ __('Active Reviews') }}</a> /
            <span>{{ $manuscriptId }}</span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title-section">
                <h1>{{ $manuscriptTitle }}</h1>
                <p class="page-subtitle">{{ $journalTitle }} — {{ $articleType }}</p>
            </div>
            <div class="deadline-badge">
                <div class="deadline-label">{{ __('Review Deadline') }}</div>
                <div class="deadline-date">{{ $deadlineLabel }}</div>
            </div>
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
                            <div class="meta-label">Manuscript ID</div>
                            <div class="meta-value">{{ $manuscriptId }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Round</div>
                            <div class="meta-value">Round {{ $currentRound ?? 1 }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Current Version</div>
                            <div class="meta-value">Version {{ $currentVersion ?? 1 }}</div>
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
                            <div class="meta-label">Last Updated</div>
                            <div class="meta-value">{{ $lastUpdated }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Keywords</div>
                            <div class="meta-value">{{ $keywordsList }}</div>
                        </div>
                    </div>
                    <div class="manuscript-actions">
                        @if(isset($files['manuscript']) && $files['manuscript'])
                        <a href="{{ $files['manuscript'] }}" target="_blank" class="btn-manuscript">📄 {{ __('Download Manuscript PDF') }}</a>
                        @endif
                        @if(isset($files['revised_manuscript']) && $files['revised_manuscript'] && $latestRevision)
                        <a href="{{ $files['revised_manuscript'] }}" target="_blank" class="btn-manuscript">📄 {{ __('Download Revised Manuscript') }}</a>
                        @endif
                        @if(isset($files['author_response']) && $files['author_response'])
                        <a href="{{ $files['author_response'] }}" target="_blank" class="btn-manuscript">📨 {{ __('Download Authors\' Response') }}</a>
                        @endif
                        @if($supplementaryFiles->isNotEmpty())
                        <a href="#" class="btn-manuscript">📎 {{ __('Supplementary Materials') }} ({{ $supplementaryFiles->count() }})</a>
                        @endif
                        <a href="#" class="btn-manuscript">📧 Contact Editor</a>
                    </div>
                </div>
                <!-- Review Version History - Organized by Round -->
                @if(isset($versionHistory) && $versionHistory->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Your Review History</h2>
                    </div>
                    <div>
                        @php
                            // Group versions by round
                            $versionsByRound = $versionHistory->groupBy(function($version) {
                                return $version->round ?? 1;
                            })->sortKeys();
                        @endphp

                        @foreach($versionsByRound as $roundNum => $versionsInRound)
                        <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid #dee2e6;">
                            <h3 style="font-size: 1rem; font-weight: 600; color: #2c3e50; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                Review Round {{ $roundNum }}
                                @if($roundNum == $currentRound)
                                    <span style="background: #28a745; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">CURRENT</span>
                                @endif
                            </h3>

                            {{-- Show Author's Revisions for this Round --}}
                            @php
                                $roundRevisions = isset($revisionsByRound) && $revisionsByRound instanceof \Illuminate\Support\Collection
                                    ? $revisionsByRound->get($roundNum, collect())
                                    : collect();
                                if (!($roundRevisions instanceof \Illuminate\Support\Collection)) {
                                    $roundRevisions = collect($roundRevisions);
                                }
                                $latestRoundRevision = $roundRevisions->sortByDesc('created_at')->first();
                            @endphp

                            @if($latestRoundRevision)
                            <div style="margin-bottom: 1rem; padding: 0.85rem; border-radius: 6px; background: #f4f9ff; border: 1px solid #d2e3fc;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <strong style="color: #1d4ed8; font-size: 0.9rem;">{{ __('Authors\' Revisions') }}</strong>
                                    <span style="font-size: 0.8rem; color: #64748b;">Round {{ $roundNum }} Revision</span>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                                    @if(!empty($latestRoundRevision->manuscript_url))
                                        <a href="{{ $latestRoundRevision->manuscript_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.9rem; color: #0f172a; text-decoration: none;">
                                            📄 {{ __('Revised Manuscript (PDF)') }}
                                        </a>
                                    @endif
                                    @if(!empty($latestRoundRevision->response_url))
                                        <a href="{{ $latestRoundRevision->response_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.9rem; color: #0f172a; text-decoration: none;">
                                            📨 {{ __('Response to Reviewers') }}
                                        </a>
                                    @endif
                                    {{-- Author Comments - Always Visible --}}
                                    @php
                                        $metadata = $latestRoundRevision->metadata ?? [];
                                        $hasAuthorComments = !empty($metadata['general_response']) || !empty($metadata['reviewer_responses']) || !empty($latestRoundRevision->response_summary);
                                        $reviewerId = auth()->id();
                                        $hasResponseForThisReviewer = isset($metadata['reviewer_responses'][$reviewerId]) && !empty($metadata['reviewer_responses'][$reviewerId]);
                                    @endphp
                                    @if($hasAuthorComments)
                                        <div style="margin-top: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 6px; font-size: 0.9rem; border: 1px solid #e0e0e0;">
                                            <strong style="color: #2c3e50; display: block; margin-bottom: 0.75rem; font-size: 0.95rem;">{{ __('Author Comments - Round') }} {{ $roundNum }}:</strong>
                                            @if(!empty($metadata['general_response']))
                                                <div style="margin-bottom: 1rem;">
                                                    <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('General Response to All Reviewers') }}:</strong>
                                                    <p style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">{!! nl2br(e($metadata['general_response'])) !!}</p>
                                                </div>
                                            @endif
                                            @if($hasResponseForThisReviewer)
                                                <div style="margin-bottom: 1rem;">
                                                    <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('Response to Your Comments') }}:</strong>
                                                    <p style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">{!! nl2br(e($metadata['reviewer_responses'][$reviewerId])) !!}</p>
                                                </div>
                                            @endif
                                            @if(!empty($latestRoundRevision->response_summary))
                                                <div style="margin-bottom: 1rem;">
                                                    <strong style="color: #2c3e50; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">{{ __('Summary of Major Changes') }}:</strong>
                                                    <p style="color: #555; line-height: 1.6; margin: 0; font-size: 0.9rem;">{!! nl2br(e($latestRoundRevision->response_summary)) !!}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if(!empty($latestRoundRevision->attachment_links) && $latestRoundRevision->attachment_links->count() > 0)
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                            @foreach($latestRoundRevision->attachment_links as $attachment)
                                                @if(!empty($attachment['url']))
                                                <a href="{{ $attachment['url'] }}" target="_blank" style="font-size: 0.85rem; color: #2563eb; text-decoration: none;">
                                                    📎 {{ $attachment['label'] }}
                                                </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #475569;">
                                    {{ __('Last updated') }}: {{ optional($latestRoundRevision->created_at)->format('M d, Y') }}
                                </div>
                                @if($roundRevisions->count() > 1)
                                    <div style="margin-top: 0.35rem; font-size: 0.75rem; color: #64748b;">
                                        {{ __('Includes') }} {{ $roundRevisions->count() }} {{ __('uploaded revisions for this round.') }}
                                    </div>
                                @endif
                            </div>
                            @endif

                            {{-- Show Review Versions for this Round --}}
                            @foreach($versionsInRound as $version)
                            <div style="padding: 0.75rem; margin-bottom: 0.5rem; border: 1px solid #e9ecef; border-radius: 6px; {{ $version->id === $review->id ? 'background: #e8f4f8; border-left: 3px solid #2a5298;' : 'background: white;' }}">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div>
                                    <strong style="color: #2c3e50;">
                                        Version {{ $version->version ?? 1 }}
                                        @if($version->id === $review->id)
                                            <span style="background: #28a745; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">CURRENT</span>
                                        @elseif($version->submitted_at)
                                            <span style="background: #6c757d; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">SUBMITTED</span>
                                        @endif
                                    </strong>
                                </div>
                                <div style="font-size: 0.85rem; color: #6c757d;">
                                    {{ $version->submitted_at ? $version->submitted_at->format('M d, Y') : 'Draft' }}
                                </div>
                            </div>
                            @if($version->overall_recommendation)
                            <div style="margin-top: 0.5rem;">
                                @php
                                    $recLabels = [
                                        'accept' => 'Accept',
                                        'minor_revisions' => 'Minor revisions',
                                        'major_revisions' => 'Major revisions',
                                        'reject' => 'Reject'
                                    ];
                                    $recLabel = $recLabels[$version->overall_recommendation] ?? ucfirst($version->overall_recommendation);
                                    $recColors = [
                                        'accept' => '#28a745',
                                        'minor_revisions' => '#ffc107',
                                        'major_revisions' => '#fd7e14',
                                        'reject' => '#dc3545'
                                    ];
                                    $recColor = $recColors[$version->overall_recommendation] ?? '#6c757d';
                                @endphp
                                <span style="display: inline-block; padding: 0.25rem 0.75rem; background: {{ $recColor }}; color: white; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                    {{ $recLabel }}
                                </span>
                            </div>
                            @endif
                            @if($version->submitted_at && $version->id !== $review->id)
                            <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #6c757d;">
                                Round {{ $version->round ?? 1 }}
                            </div>
                            @endif

                            {{-- Show expandable comments for submitted versions --}}
                            @if($version->submitted_at && $version->id !== $review->id)
                            <div style="margin-top: 0.75rem;">
                                <button type="button"
                                        onclick="toggleVersionComments({{ $version->id }})"
                                        style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.85rem; color: #2a5298; width: 100%; text-align: left;">
                                    <i class="fa-solid fa-chevron-down" id="icon-{{ $version->id }}"></i> View Comments
                                </button>
                                <div id="comments-{{ $version->id }}" style="display: none; margin-top: 0.75rem; padding: 1rem; background: #f8f9fa; border-radius: 6px; font-size: 0.9rem;">
                                    @if($version->comment_for_authors)
                                    <div style="margin-bottom: 0.75rem;">
                                        <strong style="color: #2c3e50;">Comments for Authors:</strong>
                                        <p style="color: #555; margin-top: 0.25rem; line-height: 1.6;">{{ $version->comment_for_authors }}</p>
                                    </div>
                                    @endif
                                    @if($version->comment_strengths)
                                    <div style="margin-bottom: 0.75rem;">
                                        <strong style="color: #2c3e50;">Strengths:</strong>
                                        <p style="color: #555; margin-top: 0.25rem; line-height: 1.6;">{{ $version->comment_strengths }}</p>
                                    </div>
                                    @endif
                                    @if($version->comment_weaknesses)
                                    <div style="margin-bottom: 0.75rem;">
                                        <strong style="color: #2c3e50;">Weaknesses:</strong>
                                        <p style="color: #555; margin-top: 0.25rem; line-height: 1.6;">{{ $version->comment_weaknesses }}</p>
                                    </div>
                                    @endif
                                    @if($version->comment_for_editor)
                                    <div style="margin-bottom: 0.75rem;">
                                        <strong style="color: #2c3e50;">Confidential Comments (for Editor):</strong>
                                        <p style="color: #555; margin-top: 0.25rem; line-height: 1.6;">{{ $version->comment_for_editor }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            </div>
                            @endforeach
                        </div>
                        @endforeach

                        @if(isset($isNewVersion) && $isNewVersion)
                        <div style="padding: 1rem; background: #fff3cd; border-left: 3px solid #ffc107; margin-top: 1rem; border-radius: 4px;">
                            <strong style="color: #856404;">📝 You are now preparing Version {{ ($currentVersion ?? 1) + 1 }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <!-- Manuscript Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Manuscript Overview</h2>
                        <span class="status-badge status-in-progress">In Progress</span>
                    </div>

                    @if($abstract)
                    <div class="abstract-box">
                        <div class="abstract-title">Abstract</div>
                        <div class="abstract-text">
                            {{ $abstract }}
                        </div>
                    </div>
                    @else
                    <div style="padding: 1.5rem; color: #666;">
                        <p>No abstract provided.</p>
                    </div>
                    @endif
                </div>

                <!-- Review Form -->
                <form id="reviewForm" data-autosave-url="{{ $autosaveUrl }}" data-submit-url="{{ $submitUrl }}">
                    <input type="hidden" name="rating_literature" id="rating_literature">
                    <input type="hidden" name="rating_data" id="rating_data">
                    <input type="hidden" name="rating_ethics" id="rating_ethics">
                    <hr class="section-divider">

                    <!-- Section 1: Conflict of Interest -->
                    <div class="card">
                        <div class="section-header">
                            <div class="section-number">1</div>
                            <div class="section-title">Conflict of Interest Declaration</div>
                        </div>

                        <div class="coi-alert">
                            <div class="coi-alert-title">
                                <span>⚠️</span>
                                <span>@if(!$isReviewSubmitted)Please Declare Any Potential Conflicts of Interest@else Conflict of Interest Declaration @endif</span>
                            </div>

                            @if(!$isReviewSubmitted)
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="coi_none" id="coiNone" onchange="handleCOI()" required>
                                    <span>I declare that I have no conflicts of interest that could bias my review of this manuscript</span>
                                </label>

                                <label class="checkbox-label">
                                    <input type="checkbox" name="coi_declare" id="coiDeclare" onchange="handleCOI()">
                                    <span>I wish to declare potential conflicts of interest (please explain below)</span>
                                </label>
                            </div>

                            <div class="coi-explanation" id="coiExplanation">
                                <div class="comment-field" style="margin-top: 1rem; margin-bottom: 0;">
                                    <textarea class="textarea" name="coi_details"
                                              placeholder="Please describe any relationships, financial interests, previous collaborations, or other factors that could be perceived as conflicts of interest..."></textarea>
                                </div>
                            </div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                                @if($review->conflict_declared)
                                    @if($review->conflict_details)
                                        <p style="color: #2c3e50; margin: 0;"><strong>Conflict Declared:</strong> {{ $review->conflict_details }}</p>
                                    @else
                                        <p style="color: #2c3e50; margin: 0;"><strong>Status:</strong> No conflicts of interest declared</p>
                                    @endif
                                @else
                                    <p style="color: #2c3e50; margin: 0;"><strong>Status:</strong> No conflicts of interest declared</p>
                                @endif
                            </div>
                            @endif
                        </div>

                        @if(!$isReviewSubmitted)
                        <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">
                            <strong>Examples of conflicts include:</strong> financial relationships with authors or their institutions,
                            personal relationships, collaboration within the past 3 years, shared institutional affiliations,
                            or any other circumstances that could affect your objectivity.
                        </p>
                        @endif
                    </div>

                    <hr class="section-divider">

                    <!-- Section 2: Quality Assessment -->
                    <div class="card">
                        <div class="section-header">
                            <div class="section-number">2</div>
                            <div class="section-title">Comprehensive Quality Assessment</div>
                        </div>

                        @if(!$isReviewSubmitted)
                        <p style="margin-bottom: 2rem; color: #666; font-size: 0.95rem;">
                            Rate each criterion on a scale from 0 (poor) to 5 (excellent). Hover over the help icon (?) for detailed guidance on each criterion.
                        </p>
                        @else
                        <p style="margin-bottom: 2rem; color: #666; font-size: 0.95rem;">
                            <strong>Review Submitted:</strong> Ratings are shown in read-only mode below.
                        </p>
                        @endif

                        <div class="quality-grid">
                            <!-- Originality & Novelty -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        💡 Originality & Novelty
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Assess whether the work presents new ideas, methods, or findings. Consider: novel approach to existing problem, new application of established method, or incremental but significant advance.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-originality">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="originality" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="originality" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="originality" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="originality" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="originality" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="originality" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Methodological Rigor -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        🔬 Methodological Rigor
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Evaluate appropriateness and rigor of research methods. Consider: methods clearly described, appropriate for research questions, properly executed and validated.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-methodology">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="methodology" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="methodology" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="methodology" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="methodology" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="methodology" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="methodology" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Results & Analysis -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        📊 Results & Analysis
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Assess quality of results presentation and analysis. Consider: results clearly presented, appropriate statistical analysis, conclusions supported by data.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-results">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="results" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="results" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="results" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="results" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="results" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="results" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Clarity & Organization -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        📝 Clarity & Organization
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Evaluate clarity of writing and logical organization. Consider: well-structured, clear writing, logical flow, appropriate section organization.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-clarity">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="clarity" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="clarity" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="clarity" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="clarity" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="clarity" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="clarity" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Significance & Impact -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        ⭐ Significance & Impact
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Assess potential significance and impact of the work. Consider: contribution to field, practical applications, advancement of knowledge.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-significance">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="significance" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="significance" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="significance" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="significance" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="significance" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="significance" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Literature Review -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        📚 Literature Review Quality
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Evaluate comprehensiveness and quality of literature review. Consider: relevant sources cited, current literature included, proper context provided.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-literature">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="literature" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="literature" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="literature" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="literature" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="literature" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="literature" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Data Quality -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        💾 Data Quality & Availability
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Assess quality of data and its availability for verification. Consider: sufficient data presented, data quality adequate, reproducibility considerations.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-data">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="data" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="data" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="data" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="data" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="data" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="data" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Ethical Compliance -->
                            <div class="quality-item">
                                <div class="quality-header">
                                    <div class="quality-label">
                                        ⚖️ Ethical Compliance
                                        <span class="help-icon">?
                                            <span class="tooltip">
                                                Evaluate adherence to ethical standards and regulations. Consider: ethical approvals obtained, informed consent, data protection, conflicts disclosed.
                                            </span>
                                        </span>
                                    </div>
                                    <div class="quality-score" id="score-ethics">0/5</div>
                                </div>
                                <div class="rating-scale" @if($isReviewSubmitted) style="pointer-events: none; opacity: 0.6;" @endif>
                                    <label class="rating-option">
                                        <input type="radio" name="ethics" value="0" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">0</span>
                                        <span class="rating-label">Poor</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="ethics" value="1" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">1</span>
                                        <span class="rating-label">Fair</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="ethics" value="2" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">2</span>
                                        <span class="rating-label">Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="ethics" value="3" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">3</span>
                                        <span class="rating-label">V.Good</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="ethics" value="4" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">4</span>
                                        <span class="rating-label">Excellent</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="ethics" value="5" @if($isReviewSubmitted) disabled @endif>
                                        <span class="rating-value">5</span>
                                        <span class="rating-label">Outstanding</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Overall Score Card -->
                        <div class="overall-score-card">
                            <div class="overall-score-label">Overall Quality Score</div>
                            <div class="overall-score-value" id="overallScore">0.0</div>
                            <div class="overall-score-subtitle">Based on 8 comprehensive evaluation criteria</div>
                        </div>

                        @if($isReviewSubmitted)
                        <div style="margin-top: 1.5rem; padding: 1rem; background: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                            <strong>Note:</strong> This review has been submitted. Ratings cannot be modified.
                        </div>
                        @endif
                    </div>

                    <hr class="section-divider">

                    <!-- Section 3: Overall Recommendation -->
                    <div class="card">
                        <div class="section-header">
                            <div class="section-number">3</div>
                            <div class="section-title">Overall Recommendation</div>
                        </div>

                        @if(!$isReviewSubmitted)
                        <p style="margin-bottom: 1.5rem; color: #666; font-size: 0.95rem;">
                            Select your recommendation for this manuscript. Choose the most appropriate category and provide additional context through the subcategory selection.
                        </p>
                        @else
                        <p style="margin-bottom: 1.5rem; color: #666; font-size: 0.95rem;">
                            <strong>Your Recommendation:</strong>
                            @if($review->overall_recommendation)
                                @php
                                    $recLabels = [
                                        'accept' => 'Accept',
                                        'minor' => 'Minor Revisions',
                                        'minor_revisions' => 'Minor Revisions',
                                        'major' => 'Major Revisions',
                                        'major_revisions' => 'Major Revisions',
                                        'reject' => 'Reject'
                                    ];
                                    $recLabel = $recLabels[$review->overall_recommendation] ?? ucfirst(str_replace('_', ' ', $review->overall_recommendation));
                                @endphp
                                <span style="display: inline-block; padding: 0.5rem 1rem; background: #28a745; color: white; border-radius: 6px; font-weight: 600; margin-left: 0.5rem;">{{ $recLabel }}</span>
                            @else
                                <span style="color: #666;">Not specified</span>
                            @endif
                        </p>
                        @endif

                        @if(!$isReviewSubmitted)
                        <div class="recommendation-grid">
                            <!-- Accept -->
                            <label class="recommendation-card accept" id="rec-accept">
                                <input type="radio" name="recommendation" value="accept" onchange="handleRecommendation('accept')" @if($isReviewSubmitted) disabled @endif>
                                <div class="recommendation-header">
                                    <div class="recommendation-icon">✓</div>
                                    <div class="recommendation-title">Accept</div>
                                </div>
                                <div class="recommendation-description">
                                    The manuscript meets all quality standards and is ready for publication with minimal or no changes required.
                                </div>
                                <select class="subcategory-select" id="accept-subcategory" @if($isReviewSubmitted) disabled @endif>
                                    <option value="">Select specific recommendation...</option>
                                    <option value="no_revisions">Accept without any revisions</option>
                                    <option value="editorial">Accept with minor editorial corrections</option>
                                </select>
                            </label>

                            <!-- Minor Revisions -->
                            <label class="recommendation-card minor" id="rec-minor">
                                <input type="radio" name="recommendation" value="minor" onchange="handleRecommendation('minor')" @if($isReviewSubmitted) disabled @endif>
                                <div class="recommendation-header">
                                    <div class="recommendation-icon">◐</div>
                                    <div class="recommendation-title">Minor Revisions</div>
                                </div>
                                <div class="recommendation-description">
                                    Minor improvements needed that can be verified by the editor without requiring another full review.
                                </div>
                                <select class="subcategory-select" id="minor-subcategory" @if($isReviewSubmitted) disabled @endif>
                                    <option value="">Select specific recommendation...</option>
                                    <option value="technical">Minor technical revisions required</option>
                                    <option value="presentation">Presentation improvements needed</option>
                                    <option value="clarifications">Minor clarifications required</option>
                                    <option value="formatting">Formatting and style adjustments</option>
                                </select>
                            </label>

                            <!-- Major Revisions -->
                            <label class="recommendation-card major" id="rec-major">
                                <input type="radio" name="recommendation" value="major" onchange="handleRecommendation('major')" @if($isReviewSubmitted) disabled @endif>
                                <div class="recommendation-header">
                                    <div class="recommendation-icon">⟳</div>
                                    <div class="recommendation-title">Major Revisions</div>
                                </div>
                                <div class="recommendation-description">
                                    Significant changes are needed. The revised manuscript will require another full peer review process.
                                </div>
                                <select class="subcategory-select" id="major-subcategory" @if($isReviewSubmitted) disabled @endif>
                                    <option value="">Select specific recommendation...</option>
                                    <option value="methodology">Methodological concerns need addressing</option>
                                    <option value="experiments">Additional experiments/analysis required</option>
                                    <option value="restructuring">Significant restructuring required</option>
                                    <option value="literature">Literature review needs expansion</option>
                                </select>
                            </label>

                            <!-- Reject -->
                            <label class="recommendation-card reject" id="rec-reject">
                                <input type="radio" name="recommendation" value="reject" onchange="handleRecommendation('reject')" @if($isReviewSubmitted) disabled @endif>
                                <div class="recommendation-header">
                                    <div class="recommendation-icon">✕</div>
                                    <div class="recommendation-title">Reject</div>
                                </div>
                                <div class="recommendation-description">
                                    The manuscript does not meet the publication standards or is not suitable for this journal.
                                </div>
                                <select class="subcategory-select" id="reject-subcategory" @if($isReviewSubmitted) disabled @endif>
                                    <option value="">Select specific recommendation...</option>
                                    <option value="scope">Outside journal scope</option>
                                    <option value="resubmit">Reject but encourage resubmission after major work</option>
                                    <option value="fundamental">Fundamental methodological flaws</option>
                                    <option value="quality">Insufficient scientific quality/rigor</option>
                                </select>
                            </label>
                        </div>
                        @endif
                    </div>

                    <hr class="section-divider">

                    <!-- Section 4: Detailed Comments -->
                    <div class="card">
                        <div class="section-header">
                            <div class="section-number">4</div>
                            <div class="section-title">Detailed Comments & Structured Feedback</div>
                        </div>

                        <!-- Summary -->
                        <div class="comment-field">
                            <div class="field-label">
                                📋 Review Summary
                                @if(!$isReviewSubmitted)
                                <span class="required-badge">Required</span>
                                @endif
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                Provide a concise overview of the manuscript's main contribution and your overall assessment (100-200 words recommended)
                            </div>
                            <textarea class="textarea" name="summary" id="summary"
                                      placeholder="Summarize the manuscript's key findings, methodology, and your general impression. This summary will be shared with the authors..."
                                      oninput="updateCharCount(this, 'summaryCount')" required></textarea>
                            <div class="char-counter" id="summaryCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->comment_for_authors ?? 'No summary provided.' }}</div>
                            @endif
                        </div>

                        <!-- Strengths -->
                        <div class="comment-field">
                            <div class="field-label">
                                ✅ Key Strengths
                                @if(!$isReviewSubmitted)
                                <span class="required-badge">Required</span>
                                @endif
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                Highlight the manuscript's strengths. List at least 2-3 specific positive aspects that demonstrate the work's value and contributions.
                            </div>
                            <textarea class="textarea textarea-large" name="strengths" id="strengths"
                                      placeholder="List the manuscript's strengths with specific examples:&#10;1. Novel approach to addressing the research problem...&#10;2. Comprehensive experimental design with appropriate controls...&#10;3. Clear and logical presentation of complex concepts..."
                                      oninput="updateCharCount(this, 'strengthsCount')" required></textarea>
                            <div class="char-counter" id="strengthsCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->comment_strengths ?? 'No strengths provided.' }}</div>
                            @endif
                        </div>

                        <!-- Weaknesses & Improvements -->
                        <div class="comment-field">
                            <div class="field-label">
                                ⚠️ Weaknesses & Areas for Improvement
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                Identify limitations and recommend specific improvements. Be constructive and provide actionable feedback for the authors.
                            </div>
                            <textarea class="textarea textarea-large" name="weaknesses" id="weaknesses"
                                      placeholder="Describe weaknesses and suggest specific improvements:&#10;1. The sample size appears limited for generalizability - consider expanding to include...&#10;2. Statistical analysis could be strengthened by...&#10;3. Discussion section needs better contextualization of findings within existing literature..."
                                      oninput="updateCharCount(this, 'weaknessesCount')"></textarea>
                            <div class="char-counter" id="weaknessesCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->comment_weaknesses ?? 'No weaknesses provided.' }}</div>
                            @endif
                        </div>

                        <!-- Major Issues -->
                        <div class="comment-field">
                            <div class="field-label">
                                🔴 Major Issues (If Applicable)
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                List any critical concerns that must be addressed. Reference specific sections, page numbers, or line numbers when possible for clarity.
                            </div>
                            <textarea class="textarea textarea-large" name="major_issues"
                            id="major_issues"
                            placeholder="Example format:&#10;• Page 5, Lines 125-130: The statistical test used is inappropriate for this data type because...&#10;• Section 3.2: The methodology lacks sufficient detail regarding participant recruitment criteria...&#10;• Figure 3: The results appear inconsistent with the claims made in the discussion..."
                                      oninput="updateCharCount(this, 'majorCount')"></textarea>
                            <div class="char-counter" id="majorCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->major_issues ?? 'No major issues provided.' }}</div>
                            @endif
                        </div>

                        <!-- Minor Issues -->
                        <div class="comment-field">
                            <div class="field-label">
                                🔵 Minor Issues & Editorial Suggestions
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                Note minor issues such as typos, formatting inconsistencies, unclear phrases, or areas needing clarification.
                            </div>
                            <textarea class="textarea" name="minor_issues" id="minor_issues"
                                      placeholder="List minor corrections and suggestions:&#10;• Page 3, Line 45: Typo - 'recieve' should be 'receive'&#10;• Figure 2: Labels on x-axis need clarification&#10;• Abstract: Consider shortening the first sentence for better clarity&#10;• References: Ensure consistent formatting throughout"
                                      oninput="updateCharCount(this, 'minorCount')"></textarea>
                            <div class="char-counter" id="minorCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->minor_issues ?? 'No minor issues provided.' }}</div>
                            @endif
                        </div>

                        <!-- Questions for Authors -->
                        <div class="comment-field">
                            <div class="field-label">
                                ❓ Questions for Authors
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                Pose specific questions that authors should address in their response or revision.
                            </div>
                            <textarea class="textarea" name="questions" id="questions"
                                      placeholder="Numbered questions for the authors:&#10;1. Can you clarify the rationale for choosing method X over the more commonly used method Y?&#10;2. What was the justification for the sample size? Was a power analysis conducted?&#10;3. How do these findings compare with the conflicting results reported by Smith et al. (2023)?&#10;4. Have you considered potential confounding factors such as...?"
                                      oninput="updateCharCount(this, 'questionsCount')"></textarea>
                            <div class="char-counter" id="questionsCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->questions_for_authors ?? 'No questions provided.' }}</div>
                            @endif
                        </div>

                        <!-- Confidential Comments -->
                        <div class="comment-field">
                            <div class="field-label">
                                🔒 Confidential Comments for Editor Only
                            </div>
                            @if(!$isReviewSubmitted)
                            <div class="field-description">
                                Share insights, concerns, or recommendations with the editor that should NOT be disclosed to the authors.
                            </div>
                            <textarea class="textarea" name="confidential" id="confidential"
                                      placeholder="These comments will only be visible to the editor and will not be shared with the authors. You may include:&#10;• Concerns about potential plagiarism or data fabrication&#10;• Suggestions about handling the manuscript&#10;• Comments about the manuscript's fit with the journal's scope&#10;• Observations about author qualifications or institutional affiliations"
                                      oninput="updateCharCount(this, 'confidentialCount')"></textarea>
                            <div class="char-counter" id="confidentialCount">0 characters</div>
                            @else
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; color: #2c3e50; white-space: pre-wrap; line-height: 1.6;">{{ $review->comment_for_editor ?? 'No confidential comments provided.' }}</div>
                            @endif
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Section 5: Validation Checklist -->
                    <div class="card">
                        <div class="section-header">
                            <div class="section-number">5</div>
                            <div class="section-title">Manuscript Validation Checklist</div>
                        </div>

                        @if(!$isReviewSubmitted)
                        <p style="margin-bottom: 1.5rem; color: #666; font-size: 0.95rem;">
                            Check all items that apply to this manuscript. Unchecked items indicate areas requiring attention from the authors.
                        </p>
                        @else
                        <p style="margin-bottom: 1.5rem; color: #666; font-size: 0.95rem;">
                            <strong>Validation Checklist:</strong> Review submitted - checklist items are displayed below.
                        </p>
                        @endif

                        <ul class="checklist">
                            <li class="checklist-item">
                                <input type="checkbox" id="check1" name="checks" value="title_accurate" @if($isReviewSubmitted) disabled @endif>
                                <label for="check1">Title is accurate, informative, and reflects the content</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check2" name="checks" value="abstract_scope" @if($isReviewSubmitted) disabled @endif>
                                <label for="check2">Abstract accurately reflects the study scope and key findings</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check3" name="checks" value="methods_appropriate" @if($isReviewSubmitted) disabled @endif>
                                <label for="check3">Methods are appropriate, clearly described, and reproducible</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check4" name="checks" value="results_clear" @if($isReviewSubmitted) disabled @endif>
                                <label for="check4">Results are clearly presented with appropriate statistical analysis</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check5" name="checks" value="discussion_aligned" @if($isReviewSubmitted) disabled @endif>
                                <label for="check5">Discussion interprets results appropriately and acknowledges limitations</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check6" name="checks" value="references_relevant" @if($isReviewSubmitted) disabled @endif>
                                <label for="check6">References are relevant, current, and properly formatted</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check7" name="checks" value="ethics_addressed" @if($isReviewSubmitted) disabled @endif>
                                <label for="check7">Ethical considerations are properly addressed (consent, approvals, etc.)</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check8" name="checks" value="language_clear" @if($isReviewSubmitted) disabled @endif>
                                <label for="check8">Language and grammar are clear and appropriate for publication</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check9" name="checks" value="figures_labeled" @if($isReviewSubmitted) disabled @endif>
                                <label for="check9">Figures and tables are properly labeled and referenced in text</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check10" name="checks" value="data_available" @if($isReviewSubmitted) disabled @endif>
                                <label for="check10">Data availability statement is included and appropriate</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check11" name="checks" value="no_plagiarism" @if($isReviewSubmitted) disabled @endif>
                                <label for="check11">No obvious plagiarism or duplicate publication concerns</label>
                            </li>
                            <li class="checklist-item">
                                <input type="checkbox" id="check12" name="checks" value="conclusions_supported" @if($isReviewSubmitted) disabled @endif>
                                <label for="check12">Conclusions are supported by the data and appropriately contextualized</label>
                            </li>
                        </ul>
                    </div>

                    <!-- Review Guidelines -->
                    <div class="guideline-box" style="margin-top: 2rem;">
                        <div class="guideline-title">
                            <span>📖</span>
                            <span>Review Guidelines & Best Practices</span>
                        </div>
                        <ul class="guideline-list">
                            <li>Maintain reviewer confidentiality and objectivity throughout the review process</li>
                            <li>Provide constructive, evidence-based feedback to help authors improve their work</li>
                            <li>Be specific when identifying issues - cite page/line numbers where applicable</li>
                            <li>Focus on scientific merit and methodological soundness rather than personal preferences</li>
                            <li>Respect author's writing style and theoretical perspective unless it impedes clarity</li>
                            <li>Complete your review within the agreed deadline or request an extension if needed</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #f0f0f0; justify-content: space-between;">
                        <div class="actions">
                            <button type="button" class="btn btn-outline" onclick="requestExtension()">⏰ Request Extension</button>
                            <button type="button" class="btn btn-outline" onclick="contactEditor()">✉️ Contact Editor</button>
                        </div>
                        <div class="actions">
                            @if(!$isReviewSubmitted)
                            <button type="button" class="btn btn-secondary" onclick="saveDraft()">💾 Save Draft</button>
                            <button type="button" class="btn btn-primary" onclick="submitReview()">🚀 Submit Review</button>
                            @else
                            <div style="padding: 1rem; background: #d4edda; border-radius: 6px; border-left: 4px solid #28a745; color: #155724;">
                                <strong>✓ Review Submitted</strong> - This review has been successfully submitted on {{ $review->submitted_at ? $review->submitted_at->format('M d, Y') : 'N/A' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </form>

                <script>
                    // Calculate Overall Score - defined globally for access from IIFE
                    function calculateOverallScore() {
                        const criteria = ['originality', 'methodology', 'results', 'clarity', 'significance', 'literature', 'data', 'ethics'];
                        let total = 0;
                        let count = 0;

                        criteria.forEach(criterion => {
                            const selected = document.querySelector(`input[name="${criterion}"]:checked`);
                            if (selected) {
                                total += parseInt(selected.value);
                                count++;
                            }
                        });

                        const average = count > 0 ? (total / count).toFixed(1) : '0.0';
                        const overallScoreEl = document.getElementById('overallScore');
                        if (overallScoreEl) {
                            overallScoreEl.textContent = average;
                        }
                    }

                    (function () {
                        const form = document.getElementById('reviewForm');
                        if (!form) return;
                        const autosaveUrl = form.dataset.autosaveUrl;
                        const submitUrl = form.dataset.submitUrl;
                        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        let autosaveTimer = null;

                        function updateScoreDisplays() {
                            const fields = [
                                { name: 'originality', id: 'score-originality' },
                                { name: 'methodology', id: 'score-methodology' },
                                { name: 'results', id: 'score-results' },
                                { name: 'clarity', id: 'score-clarity' },
                                { name: 'significance', id: 'score-significance' },
                                { name: 'literature', id: 'score-literature' },
                                { name: 'data', id: 'score-data' },
                                { name: 'ethics', id: 'score-ethics' },
                            ];
                            fields.forEach(f => {
                                const checked = form.querySelector(`input[name=\"${f.name}\"]:checked`);
                                const target = document.getElementById(f.id);
                                if (target && checked) target.textContent = `${checked.value}/5`;
                            });
                            calculateOverallScore();
                        }
                        async function post(url) {
                            if (!url || !csrf) return;
                            const data = new FormData(form);
                            console.log(data);
                            const res = await fetch(url, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                                body: data
                            });
                            if (!res.ok) {
                                const err = await res.json().catch(() => ({}));
                                throw new Error(err?.message || 'Request failed');
                            }
                            return res.json().catch(() => ({}));
                        }

                        function debouncedAutosave() {
                            clearTimeout(autosaveTimer);
                            autosaveTimer = setTimeout(() => {
                                post(autosaveUrl).catch(console.error);
                            }, 1200);
                        }

                        form.addEventListener('input', (e) => {
                            if (e.target.matches('input[type=\"radio\"]')) {
                                updateScoreDisplays();
                                // Mirror literature/data/ethics to hidden fields expected by backend
                                const name = e.target.getAttribute('name');
                                if (['literature','data','ethics'].includes(name)) {
                                    const hiddenId = `rating_${name}`;
                                    const selected = form.querySelector(`input[name=\"${name}\"]:checked`);
                                    const hidden = document.getElementById(hiddenId);
                                    if (hidden && selected) hidden.value = selected.value;
                                }
                            }
                            debouncedAutosave();
                        });
                        form.addEventListener('change', debouncedAutosave);

                        // Global submit handler (bind to any submit button present in template)
                        document.addEventListener('click', (e) => {
                            const btn = e.target.closest('[data-action=\"submit-review\"]');
                            if (!btn) return;
                            e.preventDefault();
                            post(submitUrl)
                                .then(() => {
                                    alert('✅ {{ __('Review submitted successfully.') }}');
                                    location.reload();
                                })
                                .catch(err => alert(err.message));
                        });

                        updateScoreDisplays();
                    })();
                </script>
            </div>

            <!-- Right Sidebar -->
            <div>
                <!-- Review Progress -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Review Progress</h2>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill" style="width: 0%;"></div>
                    </div>
                    <div class="progress-text" id="progressText">0% Complete</div>

                    <div style="margin-top: 1.5rem;">
                        <div class="info-item">
                            <div class="info-label">Last Auto-Saved</div>
                            <div class="info-value" id="lastSaved">Not saved yet</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Time Remaining</div>
                            <div class="info-value" style="color: #e74c3c; font-weight: 600;">{{ $daysRemaining ?? 'N/A' }} days</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Review Round</div>
                            <div class="info-value">Round {{ $currentRound ?? 1 }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Current Version</div>
                            <div class="info-value">Version {{ $currentVersion ?? 1 }}</div>
                        </div>
                    </div>
                </div>

                <!-- Reviewer Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Your Review Statistics</h2>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value">47</span>
                            <span class="stat-label">Total Reviews</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">3</span>
                            <span class="stat-label">Active Reviews</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">18</span>
                            <span class="stat-label">Avg. Days</span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-value">4.8</span>
                            <span class="stat-label">Quality Rating</span>
                        </div>
                    </div>
                </div>

                <!-- Review Guidelines Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Quick Guidelines</h2>
                    </div>

                    <div class="guideline-box">
                        <div class="guideline-title">
                            <span>⭐</span>
                            <span>Key Reminders</span>
                        </div>

                        <ul class="guideline-list">
                            <li>Be constructive and respectful</li>
                            <li>Provide specific examples</li>
                            <li>Focus on the science, not authors</li>
                            <li>Maintain confidentiality</li>
                            <li>Declare any conflicts</li>
                            <li>Complete within deadline</li>
                        </ul>
                    </div>

                    <div class="actions" style="flex-direction: column; margin-top: 1rem;">
                        <a href="#" class="btn btn-outline">📖 Full Guidelines</a>
                        <a href="#" class="btn btn-outline">❓ Review FAQs</a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Quick Actions</h2>
                    </div>

                    <div class="actions" style="flex-direction: column;">
                        <button class="btn btn-outline" onclick="window.print()">🖨️ Print Review Form</button>
                        <button class="btn btn-outline" onclick="viewGuidelines()">📋 View Journal Policies</button>
                        <button class="btn btn-secondary" onclick="reportEthical()">⚠️ Report Ethical Concerns</button>
                        <button class="btn btn-danger" onclick="declineReview()">✕ Decline This Review</button>
                    </div>
                </div>

                <!-- Help & Support -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Help & Support</h2>
                    </div>

                    <div class="alert alert-info">
                        <span class="alert-icon">💡</span>
                        <div style="font-size: 0.9rem; line-height: 1.5;">
                            Need assistance? Contact the editorial office at <strong>support@aisrp.org</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-save Toast -->
    <div class="autosave-toast" id="autosaveToast">
        <span>✓</span>
        <span>Review draft saved automatically</span>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    </div>
</div>
@endsection

@push('script')
<script>
        // Initial payload from server to hydrate the form on load
        window.initialReview = {
            overall_recommendation: @json($review->overall_recommendation),
            rating_originality: @json($review->rating_originality),
            rating_methodology: @json($review->rating_methodology),
            rating_results: @json($review->rating_results),
            rating_clarity: @json($review->rating_clarity),
            rating_significance: @json($review->rating_significance),
            rating_literature: @json($review->rating_literature),
            rating_data: @json($review->rating_data),
            rating_ethics: @json($review->rating_ethics),
            comment_for_authors: @json($review->comment_for_authors),
            questions_for_authors: @json($review->questions_for_authors),
            minor_issues: @json($review->minor_issues),
            major_issues: @json($review->major_issues),
            comment_strengths: @json($review->comment_strengths),
            comment_weaknesses: @json($review->comment_weaknesses),
            comment_for_editor: @json($review->comment_for_editor),
            specific_checks: @json((array)($review->specific_checks ?? [])),
            progress: @json($review->progress ?? 0),
            conflict_declared: @json($review->conflict_declared ?? null),
            conflict_details: @json($review->conflict_details ?? null),
        };

        function hydrateFormFromInitial() {
            const R = window.initialReview || {};
            const form = document.getElementById('reviewForm');
            if (!form) return;

            // Recommendation mapping
            const recMap = {
                accept: 'accept',
                minor_revisions: 'minor',
                major_revisions: 'major',
                reject: 'reject',
            };
            if (R.overall_recommendation && recMap[R.overall_recommendation]) {
                const v = recMap[R.overall_recommendation];
                const radio = form.querySelector(`input[name="recommendation"][value="${v}"]`);
                if (radio) {
                    radio.checked = true;
                    const card = document.getElementById(`rec-${v}`);
                    card && card.classList.add('selected');
                }
            }

            // Ratings
            const ratingPairs = [
                ['rating_originality', 'originality'],
                ['rating_methodology', 'methodology'],
                ['rating_results', 'results'],
                ['rating_clarity', 'clarity'],
                ['rating_significance', 'significance'],
                ['rating_literature', 'literature'],
                ['rating_data', 'data'],
                ['rating_ethics', 'ethics'],
            ];
            ratingPairs.forEach(([field, inputName]) => {
                let val = R[field];
                if (val !== null && val !== undefined && val !== '') {
                    const opt = form.querySelector(`input[name="${inputName}"][value="${val}"]`);
                    if (opt) {
                        opt.checked = true;
                        const optionEl = opt.closest('.rating-option');
                        optionEl && optionEl.classList.add('selected');
                        const scoreEl = document.getElementById(`score-${inputName}`);
                        scoreEl && (scoreEl.textContent = `${val}/5`);
                        // Keep hidden mirrors in sync on hydration
                        const hidden = document.getElementById(`rating_${inputName}`);
                        if (hidden) hidden.value = String(val);
                    }
                }
            });

            // Comments - check if elements exist before setting values (they might be hidden if review is submitted)
            const summaryEl = document.getElementById('summary');
            if (summaryEl && R.comment_for_authors) summaryEl.value = R.comment_for_authors;
            const questionsEl = document.getElementById('questions');
            if (questionsEl && R.questions_for_authors) questionsEl.value = R.questions_for_authors;
            const minorIssuesEl = document.getElementById('minor_issues');
            if (minorIssuesEl && R.minor_issues) minorIssuesEl.value = R.minor_issues;
            const majorIssuesEl = document.getElementById('major_issues');
            if (majorIssuesEl && R.major_issues) majorIssuesEl.value = R.major_issues;
            const strengthsEl = document.getElementById('strengths');
            if (strengthsEl && R.comment_strengths) strengthsEl.value = R.comment_strengths;
            const weaknessesEl = document.getElementById('weaknesses');
            if (weaknessesEl && R.comment_weaknesses) weaknessesEl.value = R.comment_weaknesses;
            const confidentialEl = document.getElementById('confidential');
            if (confidentialEl && R.comment_for_editor) confidentialEl.value = R.comment_for_editor;

            // Checklist mapping
            const checks = R.specific_checks || {};
            const checkMap = [
                ['title', 'check1'],
                ['abstract', 'check2'],
                ['methods', 'check3'],
                ['results', 'check4'],
                ['discussion', 'check5'],
                ['references', 'check6'],
                ['ethics', 'check7'],
                ['language', 'check8'],
                ['figures', 'check9'],
            ];
            checkMap.forEach(([key, id]) => {
                const el = document.getElementById(id);
                if (el && (checks[key] === true || checks[key] === 1 || checks[key] === '1')) {
                    el.checked = true;
                    const item = el.closest('.checklist-item');
                    item && item.classList.add('completed');
                }
            });

            // Progress
            if (typeof R.progress === 'number') {
                const fill = document.getElementById('progressFill');
                const text = document.getElementById('progressText');
                if (fill) fill.style.width = `${R.progress}%`;
                if (text) text.textContent = `${R.progress}% Complete`;
            }

            // Conflict of Interest (COI) hydration - check if elements exist (they might be hidden if review is submitted)
            if (R.conflict_declared !== null && R.conflict_declared !== undefined) {
                const coiNone = document.getElementById('coiNone');
                const coiDeclare = document.getElementById('coiDeclare');
                const coiExplanation = document.getElementById('coiExplanation');
                const coiDetails = document.querySelector('textarea[name="coi_details"]');

                // Only try to set values if elements exist
                if (coiNone && coiDeclare) {
                    if (R.conflict_declared === true || R.conflict_declared === 1) {
                        // Conflict declared
                        coiDeclare.checked = true;
                        coiNone.checked = false;
                        if (coiExplanation) coiExplanation.style.display = 'block';
                        if (coiDetails && R.conflict_details) {
                            coiDetails.value = R.conflict_details;
                        }
                    } else {
                        // No conflict declared
                        coiNone.checked = true;
                        coiDeclare.checked = false;
                        if (coiExplanation) coiExplanation.style.display = 'none';
                    }
                }
            }

            // Calculate overall score after hydrating all form data (ratings, checkboxes, etc.)
            calculateOverallScore();
        }

        // Auto-save functionality
        let autoSaveTimer;
        let formChanged = false;

        function setupAutoSave() {
            const form = document.getElementById('reviewForm');
            if (!form) return; // Form doesn't exist (review submitted)
            const inputs = form.querySelectorAll('input, textarea, select');

            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    formChanged = true;
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(saveDraft, 3000);
                });

                input.addEventListener('input', () => {
                    updateProgress();
                });
            });
        }

        async function saveDraft() {
            if (!formChanged) return;

            const form = document.getElementById('reviewForm');
            const formData = new FormData(form);
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const autosaveUrl = form?.dataset?.autosaveUrl;

            if (!autosaveUrl || !csrf) {
                console.warn('Autosave URL or CSRF token missing');
                return;
            }

            // Include COI fields in FormData
            const coiNone = document.getElementById('coiNone');
            const coiDeclare = document.getElementById('coiDeclare');
            const coiDetails = document.querySelector('textarea[name="coi_details"]');

            if (coiNone && coiNone.checked) {
                formData.append('coi_none', '1');
            }
            if (coiDeclare && coiDeclare.checked) {
                formData.append('coi_declare', '1');
                if (coiDetails && coiDetails.value) {
                    formData.append('coi_details', coiDetails.value);
                }
            }

            try {
                const res = await fetch(autosaveUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (res.ok) {
            // Show toast notification
            const toast = document.getElementById('autosaveToast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);

            // Update last saved time
            const now = new Date();
            const lastSavedEl = document.getElementById('lastSaved');
            if (lastSavedEl) lastSavedEl.textContent = now.toLocaleString();
            formChanged = false;
                } else {
                    console.error('Autosave failed:', await res.text());
                }
            } catch (error) {
                console.error('Autosave error:', error);
            }
        }

        // COI Handler
        function handleCOI() {
            const noCOI = document.getElementById('coiNone');
            const declareCOI = document.getElementById('coiDeclare');
            const explanation = document.getElementById('coiExplanation');

            if (!noCOI || !declareCOI) return; // Elements don't exist (review submitted)

            if (declareCOI.checked) {
                noCOI.checked = false;
                if (explanation) explanation.style.display = 'block';
            } else if (noCOI.checked) {
                declareCOI.checked = false;
                if (explanation) explanation.style.display = 'none';
            }

            updateProgress();
        }

        // Rating Selection Handler
        document.addEventListener('DOMContentLoaded', function() {
            const ratingOptions = document.querySelectorAll('.rating-option');

            ratingOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (!radio || radio.disabled) return; // Skip if disabled (review submitted)

                    const criterion = radio.name;
                    const value = radio.value;

                    // Remove selected class from siblings
                    const ratingScale = this.closest('.rating-scale');
                    if (ratingScale) {
                        const siblings = ratingScale.querySelectorAll('.rating-option');
                        siblings.forEach(sib => sib.classList.remove('selected'));
                    }

                    // Add selected class to this option
                    this.classList.add('selected');
                    radio.checked = true;

                    // Update score display
                    const scoreEl = document.getElementById(`score-${criterion}`);
                    if (scoreEl) scoreEl.textContent = `${value}/5`;

                    // Calculate overall score
                    calculateOverallScore();
                    updateProgress();
                });
            });
        });

        // calculateOverallScore is now defined above in the IIFE section

        // Recommendation Handler
        function handleRecommendation(type) {
            // Hide all subcategory selects
            document.querySelectorAll('.subcategory-select').forEach(select => {
                select.style.display = 'none';
            });

            // Show selected subcategory
            const subcategoryEl = document.getElementById(`${type}-subcategory`);
            if (subcategoryEl) subcategoryEl.style.display = 'block';

            // Update card styling
            document.querySelectorAll('.recommendation-card').forEach(card => {
                card.classList.remove('selected');
            });
            const recCard = document.getElementById(`rec-${type}`);
            if (recCard) recCard.classList.add('selected');

            updateProgress();
        }

        // Character Counter
        function updateCharCount(textarea, counterId) {
            if (!textarea) return;
            const count = textarea.value.length;
            const counterEl = document.getElementById(counterId);
            if (counterEl) counterEl.textContent = `${count} characters`;
        }

        // Progress Tracking
        function updateProgress() {
            let completed = 0;
            let total = 5;

            // Check COI (Step 1)
            const coiNoneEl = document.getElementById('coiNone');
            const coiDeclareEl = document.getElementById('coiDeclare');
            if (coiNoneEl && coiDeclareEl && (coiNoneEl.checked || coiDeclareEl.checked)) {
                completed++;
            }

            // Check Quality Assessment (Step 2) - at least 6 ratings
            const ratings = document.querySelectorAll('input[type="radio"]:checked');
            const ratingCriteria = ['originality', 'methodology', 'results', 'clarity', 'significance', 'literature', 'data', 'ethics'];
            const ratedCriteria = ratingCriteria.filter(c => document.querySelector(`input[name="${c}"]:checked`)).length;
            if (ratedCriteria >= 6) {
                completed++;
            }

            // Check Recommendation (Step 3)
            if (document.querySelector('input[name="recommendation"]:checked')) {
                completed++;
            }

            // Check Comments (Step 4) - summary and strengths required
            const summaryEl = document.getElementById('summary');
            const strengthsEl = document.getElementById('strengths');
            if (summaryEl && strengthsEl) {
                const summary = summaryEl.value;
                const strengths = strengthsEl.value;
                if (summary.length > 100 && strengths.length > 50) {
                    completed++;
                }
            }

            // Check Checklist (Step 5) - at least 8 items
            const checkedItems = document.querySelectorAll('.checklist-item input[type="checkbox"]:checked').length;
            if (checkedItems >= 8) {
                completed++;
            }

            // Update progress bar
            const percentage = Math.round((completed / total) * 100);
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            if (progressFill) progressFill.style.width = `${percentage}%`;
            if (progressText) progressText.textContent = `${percentage}% Complete`;
        }

        // Checklist Handler
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checklist-item input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const item = this.closest('.checklist-item');
                    if (this.checked) {
                        item.classList.add('completed');
                    } else {
                        item.classList.remove('completed');
                    }
                    updateProgress();
                });
            });
        });

        // Form Validation
        function validateForm() {
            let isValid = true;
            const errors = [];

            // COI check
            const coiNoneCheck = document.getElementById('coiNone');
            const coiDeclareCheck = document.getElementById('coiDeclare');
            if (coiNoneCheck && coiDeclareCheck && !coiNoneCheck.checked && !coiDeclareCheck.checked) {
                errors.push('❌ Please complete the Conflict of Interest declaration');
                isValid = false;
            }

            // Quality scores check
            const criteria = ['originality', 'methodology', 'results', 'clarity', 'significance', 'literature', 'data', 'ethics'];
            const unrated = criteria.filter(c => !document.querySelector(`input[name="${c}"]:checked`));
            if (unrated.length > 0) {
                errors.push('❌ Please provide ratings for all 8 quality criteria');
                isValid = false;
            }

            // Recommendation check
            if (!document.querySelector('input[name="recommendation"]:checked')) {
                errors.push('❌ Please select an overall recommendation');
                isValid = false;
            }

            // Summary check
            const summaryCheck = document.getElementById('summary');
            if (summaryCheck) {
                const summary = summaryCheck.value;
                if (summary.length < 100) {
                    errors.push('❌ Summary must be at least 100 characters (currently: ' + summary.length + ')');
                    isValid = false;
                }
            }

            // Strengths check
            const strengthsCheck = document.getElementById('strengths');
            if (strengthsCheck) {
                const strengths = strengthsCheck.value;
                if (strengths.length < 50) {
                    errors.push('❌ Strengths section must be at least 50 characters (currently: ' + strengths.length + ')');
                    isValid = false;
                }
            }

            // Checklist check
            const checkedItems = document.querySelectorAll('.checklist-item input[type="checkbox"]:checked').length;
            if (checkedItems < 8) {
                errors.push('❌ Please complete at least 8 validation checks (currently: ' + checkedItems + ')');
                isValid = false;
            }

            if (!isValid) {
                alert('⚠️ Please complete all required sections:\n\n' + errors.join('\n'));
            }

            return isValid;
        }

        // Submit Review (AJAX → server)
        async function submitReview() {
            if (!validateForm()) {
                return;
            }
            const isNewVersion = {{ isset($isNewVersion) && $isNewVersion ? 'true' : 'false' }};
            const currentVersion = {{ $currentVersion ?? 1 }};
            const nextVersion = currentVersion + 1;

            let confirmMessage = '🚀 Are you ready to submit your review?';
            if (isNewVersion) {
                confirmMessage = `🚀 Are you ready to submit Version ${nextVersion}?\n\nThis will create a new review version. Previous versions cannot be edited. Please ensure all sections are complete and accurate.`;
            } else {
                confirmMessage = '🚀 Are you ready to submit your review?\n\nOnce submitted, you will not be able to edit this version. Please ensure all sections are complete and accurate.';
            }

            if (!confirm(confirmMessage)) {
                return;
            }
            const formEl = document.getElementById('reviewForm');
            const csrf = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content');
            const submitUrl = formEl?.dataset?.submitUrl;
            if (!formEl || !csrf || !submitUrl) {
                alert('Missing configuration. Please reload the page.');
                return;
            }
            // Build payload expected by backend
            const fd = new FormData();
            // Overall recommendation
            const chosenRec = (formEl.querySelector('input[name=\"recommendation\"]:checked') || {}).value || '';
            const recMap = { accept: 'accept', minor: 'minor_revisions', major: 'major_revisions', reject: 'reject' };
            if (chosenRec) {
                fd.append('overall_recommendation', recMap[chosenRec] || chosenRec);
            }
            // Ratings
            const ratingOf = (name) => {
                const el = formEl.querySelector(`input[name=\"${name}\"]:checked`);
                return el ? el.value : '';
            };
            fd.append('rating_originality', ratingOf('originality'));
            fd.append('rating_methodology', ratingOf('methodology'));
            fd.append('rating_results', ratingOf('results'));
            fd.append('rating_clarity', ratingOf('clarity'));
            fd.append('rating_significance', ratingOf('significance'));
            fd.append('rating_literature', ratingOf('literature'));
            fd.append('rating_data', ratingOf('data'));
            fd.append('rating_ethics', ratingOf('ethics'));

            // Comments
            const val = (id) => (document.getElementById(id)?.value || '').trim();
            const summary = val('summary');
            const strengths = val('strengths');
            const weaknesses = document.getElementById('strengths') ? val('weaknesses') : val('weaknesses'); // keep structure
            const confidential = val('confidential');
            const majorIssues = val('major_issues');
            const minorIssues = val('minor_issues');
            const Questions = val('questions');

            // Map into backend fields
            fd.append('comment_for_authors', summary);
            fd.append('comment_strengths', strengths);
            fd.append('comment_weaknesses', val('weaknesses'));
            fd.append('comment_for_editor', confidential);
            fd.append('major_issues', majorIssues);
            fd.append('minor_issues', minorIssues);
            fd.append('questions_for_authors', Questions);

            // Conflict of Interest (COI) fields
            const coiNone = document.getElementById('coiNone');
            const coiDeclare = document.getElementById('coiDeclare');
            const coiDetails = document.querySelector('textarea[name="coi_details"]');
            if (coiNone && coiNone.checked) {
                fd.append('coi_none', '1');
            }
            if (coiDeclare && coiDeclare.checked) {
                fd.append('coi_declare', '1');
                if (coiDetails && coiDetails.value) {
                    fd.append('coi_details', coiDetails.value);
                }
            }

            // Checklist mapping → specific_checks[...]
            const setCheck = (key, checked) => fd.append(`specific_checks[${key}]`, checked ? '1' : '0');
            setCheck('title', document.getElementById('check1')?.checked);
            setCheck('abstract', document.getElementById('check2')?.checked);
            setCheck('methods', document.getElementById('check3')?.checked);
            setCheck('results', document.getElementById('check4')?.checked);
            setCheck('discussion', document.getElementById('check5')?.checked);
            setCheck('references', document.getElementById('check6')?.checked);
            setCheck('ethics', document.getElementById('check7')?.checked);
            setCheck('language', document.getElementById('check8')?.checked);
            setCheck('figures', document.getElementById('check9')?.checked);
            // Submit
            document.getElementById('loadingOverlay').classList.add('show');
            try {
                const res = await fetch(submitUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    let errorMessage = data?.message || 'Submission failed.';

                    // Check for validation errors
                    if (data?.errors) {
                        const errorList = Object.entries(data.errors)
                            .map(([field, messages]) => {
                                const fieldName = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                return `${fieldName}: ${Array.isArray(messages) ? messages.join(', ') : messages}`;
                            })
                            .join('\n');
                        errorMessage = `Validation errors:\n\n${errorList}`;

                        // Log to console for debugging
                        console.error('Validation errors:', data.errors);
                        console.error('Missing fields:', Object.keys(data.errors));
                    }

                    throw new Error(errorMessage);
                }
                const successMessage = data?.version ?
                    `✅ Review Version ${data.version} submitted successfully!` :
                    '✅ {{ __('Review submitted successfully.') }}';
                alert(successMessage);
                // If a new version was created, redirect to the new review
                if (data?.review_id && data.review_id !== {{ $review->id }}) {
                    window.location.href = window.location.href.replace(/\/\d+$/, '/' + data.review_id);
                } else {
                location.reload();
                }
            } catch (e) {
                alert(e.message);
            } finally {
                document.getElementById('loadingOverlay').classList.remove('show');
            }
        }

        // Utility Functions
        function requestExtension() {
            const reason = prompt('Please provide a brief reason for requesting an extension:');
            if (reason) {
                alert('✅ Extension request submitted!\n\nThe editorial office will review your request and respond within 24 hours.');
            }
        }

        // Toggle version comments visibility
        function toggleVersionComments(versionId) {
            const commentsDiv = document.getElementById('comments-' + versionId);
            const icon = document.getElementById('icon-' + versionId);
            if (commentsDiv && icon) {
                if (commentsDiv.style.display === 'none' || !commentsDiv.style.display) {
                    commentsDiv.style.display = 'block';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    commentsDiv.style.display = 'none';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        }

        // Toggle author comments visibility (Reviewer view)
        function toggleAuthorComments(roundNum, revisionId) {
            const commentsDiv = document.getElementById('authorComments-' + revisionId);
            if (commentsDiv) {
                if (commentsDiv.style.display === 'none' || !commentsDiv.style.display) {
                    commentsDiv.style.display = 'block';
                } else {
                    commentsDiv.style.display = 'none';
                }
            }
        }

        function contactEditor() {
            alert('📧 Opening message composer...\n\nYou can send a secure message to the editor about this manuscript.');
        }

        function viewGuidelines() {
            alert('📚 Opening comprehensive review guidelines...');
        }

        function reportEthical() {
            if (confirm('⚠️ You are about to report ethical concerns.\n\nThis will notify the editorial office immediately. Do you wish to continue?')) {
                alert('Your ethical concerns have been reported to the editorial office. They will investigate and respond appropriately.');
            }
        }

        function declineReview() {
            if (confirm('⚠️ Are you sure you want to decline this review invitation?\n\nThis action cannot be undone. The editorial office will be notified immediately.')) {
                const reason = prompt('Please provide a brief reason for declining (optional):');
                alert('✅ Review declined.\n\nThe editorial office has been notified and will find an alternative reviewer.');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Only hydrate and setup autosave if form fields are visible (not submitted)
            @if(!$isReviewSubmitted)
            hydrateFormFromInitial();
            setupAutoSave();
            @endif
            updateProgress();

            console.log('AISRP Enhanced Reviewer Form loaded successfully');
        });
    </script>
@endpush

