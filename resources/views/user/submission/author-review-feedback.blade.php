<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Review Feedback Summary') }} - {{ config('app.name') }}</title>
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

        .status-accepted {
            background: #d4edda;
            color: #155724;
        }

        .status-revision-required {
            background: #f8d7da;
            color: #721c24;
        }

        .status-rejected {
            background: #fee2e2;
            color: #b91c1c;
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
</head>
<body>
@php
    $user = auth()->user();
    $avatar = $user ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 2)) : 'AU';
    $userName = $user->name ?? __('Author');
    $orderId = $order->order_id ?? '—';
    $manuscriptTitle = $submission->article_title ?? __('Untitled Manuscript');
    $journalTitle = $submission->journal->title ?? __('Not Assigned');
    $articleType = $submission->article_type->name ?? '—';
    $submittedAt = $submission->created_at ? \Carbon\Carbon::parse($submission->created_at)->translatedFormat('F d, Y') : '—';
    $lastUpdated = $submission->updated_at ? \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('F d, Y') : '—';
    $keywords = collect(explode(',', (string) ($submission->article_keywords ?? '')))->map(fn($k) => trim($k))->filter();
    $keywordsList = $keywords->implode(', ') ?: '—';
    $authors = $authors ?? collect();
    $authorNames = $authors->pluck('name')->filter()->implode(', ') ?: '—';
    $corresponding = $correspondingAuthor ?? [];
    $correspondingName = $corresponding['name'] ?? '—';
    $correspondingEmail = $corresponding['email'] ?? '—';
    $correspondingAffiliation = $corresponding['affiliation'] ?? '—';
    $abstract = $submission->article_abstract ?? '';
    $statusLabel = $statusMeta['label'] ?? __('Pending');
    $decisionLabel = ucwords(str_replace('_', ' ', $submission->approval_status ?? 'pending'));
    $decisionBadge = 'status-pending';
    if (str_contains($submission->approval_status ?? '', 'accepted')) {
        $decisionBadge = 'status-accepted';
    } elseif (str_contains($submission->approval_status ?? '', 'revision')) {
        $decisionBadge = 'status-revision-required';
    } elseif (str_contains($submission->approval_status ?? '', 'rejected')) {
        $decisionBadge = 'status-rejected';
    }
    $decisionHeadline = $statusMeta['headline'] ?? $statusLabel;
    $decisionBody = $statusMeta['body'] ?? '';
    $completedReviews = $completedReviews ?? collect();
    $reviewStats = $reviewStats ?? [];
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
    $checklistLabels = $checklistLabels ?? [];
    $manuscriptUrl = $files['manuscript'] ?? null;
    $coverLetterUrl = $files['cover_letter'] ?? null;
    $supplementaryFiles = collect($files['supplements'] ?? []);
@endphp
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">{{ config('app.name') }} — {{ __('Author Workspace') }}</div>
            <div class="user-info">
                <div class="user-avatar">{{ $avatar }}</div>
                <span>{{ $userName }}</span>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-content">
            <a href="{{ route('user.dashboard') }}" class="nav-item">{{ __('Dashboard') }}</a>
            <a href="{{ route('user.orders.list') }}" class="nav-item active">{{ __('My Manuscripts') }}</a>
            <a href="{{ route('user.submission.select-a-journal', ['by' => 'by-subject']) }}" class="nav-item">{{ __('New Submission') }}</a>
            <a href="{{ route('user.profile.index') }}" class="nav-item">{{ __('Profile') }}</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a> /
            <a href="{{ route('user.orders.list') }}">{{ __('My Manuscripts') }}</a> /
            <a href="{{ route('user.orders.dashboard', $orderId) }}">{{ $orderId }}</a> /
            <span>{{ __('Review Feedback') }}</span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title-section">
                <h1>{{ __('Review Feedback Summary') }}</h1>
                <p class="page-subtitle">{{ __('Review detailed feedback from peer reviewers on your manuscript') }}</p>
            </div>
            @if($completedReviews->isNotEmpty())
                <div class="deadline-badge" style="background: #28a745;">
                    <div class="deadline-label">{{ __('Reviews Received') }}</div>
                    <div class="deadline-date">{{ $completedReviews->count() }} {{ __('of') }} {{ $reviewStats['assigned_reviewers'] ?? $completedReviews->count() }}</div>
                </div>
            @endif
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
                            <div class="meta-value">{{ $orderId }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Journal') }}</div>
                            <div class="meta-value">{{ $journalTitle }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Article Type') }}</div>
                            <div class="meta-value">{{ $articleType }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Submitted') }}</div>
                            <div class="meta-value">{{ $submittedAt }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Last Updated') }}</div>
                            <div class="meta-value">{{ $lastUpdated }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">{{ __('Keywords') }}</div>
                            <div class="meta-value">{{ $keywordsList }}</div>
                        </div>
                    </div>
                    <div class="manuscript-actions">
                        @if($manuscriptUrl)
                            <a href="{{ $manuscriptUrl }}" target="_blank" class="btn-manuscript">📄 {{ __('View Manuscript PDF') }}</a>
                        @endif
                        @if($coverLetterUrl)
                            <a href="{{ $coverLetterUrl }}" target="_blank" class="btn-manuscript">📋 {{ __('Cover Letter') }}</a>
                        @endif
                        @if($supplementaryFiles->isNotEmpty())
                            <a href="#" class="btn-manuscript">📎 {{ __('Supplementary Materials') }} ({{ $supplementaryFiles->count() }})</a>
                        @endif
                        <a href="{{ route('user.orders.dashboard', $orderId) }}" class="btn-manuscript">📊 {{ __('Back to Dashboard') }}</a>
                    </div>
                </div>

                <!-- Editorial Decision Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Editorial Decision') }}</h2>
                        <span class="status-badge {{ $decisionBadge }}">{{ $decisionLabel }}</span>
                    </div>
                    <p style="color: #666; margin-bottom: 1rem;">{{ $decisionHeadline }}</p>
                    <p style="color: #555; line-height: 1.6;">{{ $decisionBody }}</p>
                </div>

                <!-- Manuscript Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Manuscript Overview') }}</h2>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">{{ __('Authors') }}</div>
                        <div class="detail-value">{{ $authorNames }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">{{ __('Corresponding Author') }}</div>
                        <div class="detail-value">{{ $correspondingName }} ({{ $correspondingEmail }})</div>
                    </div>

                    @if($correspondingAffiliation && $correspondingAffiliation !== '—')
                    <div class="detail-row">
                        <div class="detail-label">{{ __('Institution') }}</div>
                        <div class="detail-value">{{ $correspondingAffiliation }}</div>
                    </div>
                    @endif

                    @if($abstract)
                    <div class="abstract-box">
                        <div class="abstract-title">{{ __('Abstract') }}</div>
                        <div class="abstract-text">{{ $abstract }}</div>
                    </div>
                    @endif
                </div>

                @if($completedReviews->isEmpty())
                    <div class="card">
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
                    <hr class="section-divider">

                    <!-- Round Header -->
                    <div class="card" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%); color: white; margin-bottom: 1.5rem;">
                        <div style="padding: 1.5rem;">
                            <h2 style="font-size: 1.5rem; margin: 0; display: flex; align-items: center; gap: 1rem;">
                                <span style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-weight: bold;">{{ $roundNum }}</span>
                                <span>{{ __('Round :round Comments', ['round' => $roundNum]) }}</span>
                            </h2>
                            <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem;">
                                {{ $roundNum == 1 ? __('Initial review comments from peer reviewers') : __('Reviewer feedback after your revision') }}
                            </p>
                        </div>
                    </div>

                    @foreach($roundReviews as $review)
                    @php
                        $reviewerName = $review->reviewer?->name ?? __('Anonymous Reviewer');
                        $reviewerInitials = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($reviewerName, 0, 2));
                        $recommendation = $review->overall_recommendation ?? 'minor_revisions';
                        $recommendationLabel = $recommendationLabels[$recommendation] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', $recommendation));
                        $recommendationClass = $recommendationClasses[$recommendation] ?? 'minor';
                        $submittedOn = $review->submitted_at
                            ? \Carbon\Carbon::parse($review->submitted_at)->translatedFormat('F d, Y')
                            : ($review->updated_at ? \Carbon\Carbon::parse($review->updated_at)->translatedFormat('F d, Y') : '—');
                        $specificChecks = (array) ($review->specific_checks ?? []);
                    @endphp

                    <!-- Reviewer Feedback Card -->
                    <div class="card" style="margin-bottom: 1.5rem;">
                        <div class="section-header">
                            <div class="section-number">{{ $loop->iteration }}</div>
                            <div class="section-title">{{ __('Reviewer Feedback') }} - {{ $reviewerName }}</div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 6px;">
                            <div>
                                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">{{ $reviewerName }}</div>
                                <div style="font-size: 0.85rem; color: #666;">{{ __('Submitted on') }} {{ $submittedOn }}</div>
                            </div>
                            <span class="status-badge" style="
                                padding: 0.5rem 1rem;
                                border-radius: 20px;
                                font-weight: 600;
                                font-size: 0.85rem;
                                background: {{ $recommendationClass === 'accept' ? '#d4edda' : ($recommendationClass === 'minor' ? '#fff3cd' : ($recommendationClass === 'major' ? '#f8d7da' : '#fee2e2')) }};
                                color: {{ $recommendationClass === 'accept' ? '#155724' : ($recommendationClass === 'minor' ? '#856404' : ($recommendationClass === 'major' ? '#721c24' : '#b91c1c')) }};
                            ">
                                {{ $recommendationLabel }}
                            </span>
                        </div>

                        <!-- Quality Ratings -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 1rem;">{{ __('Quality Assessment') }}</h3>
                            <div class="quality-grid">
                            @foreach($ratingFields as $field => $label)
                                @if(!is_null($review->$field))
                                <div class="quality-item">
                                    <div class="quality-header">
                                        <div class="quality-label">{{ $label }}</div>
                                        <div class="quality-score">{{ $review->$field }}/5</div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            @if(!is_null($review->quality_rating))
                                <div class="quality-item">
                                    <div class="quality-header">
                                        <div class="quality-label">{{ __('Overall Quality Score') }}</div>
                                        <div class="quality-score">{{ number_format($review->quality_rating, 2) }}</div>
                                    </div>
                                </div>
                            @endif
                            </div>
                        </div>

                        @if($review->comment_strengths)
                        <div class="comment-field" style="margin-bottom: 1.5rem;">
                            <div class="field-label">✅ {{ __('Key Strengths') }}</div>
                            <div class="feedback-text" style="background: #f8f9fa; padding: 1rem; border-radius: 6px; color: #555; line-height: 1.7;">
                                {!! nl2br(e($review->comment_strengths)) !!}
                            </div>
                        </div>
                        @endif

                        @if($review->comment_weaknesses)
                        <div class="comment-field" style="margin-bottom: 1.5rem;">
                            <div class="field-label">⚠️ {{ __('Weaknesses & Areas for Improvement') }}</div>
                            <div class="feedback-text" style="background: #f8f9fa; padding: 1rem; border-radius: 6px; color: #555; line-height: 1.7;">
                                {!! nl2br(e($review->comment_weaknesses)) !!}
                            </div>
                        </div>
                        @endif

                        @if($review->comment_for_authors)
                        <div class="comment-field" style="margin-bottom: 1.5rem;">
                            <div class="field-label">💬 {{ __('Comments for Authors') }}</div>
                            <div class="feedback-text" style="background: #f8f9fa; padding: 1rem; border-radius: 6px; color: #555; line-height: 1.7;">
                                {!! nl2br(e($review->comment_for_authors)) !!}
                            </div>
                        </div>
                        @endif

                        @if($review->comments)
                        <div class="comment-field" style="margin-bottom: 1.5rem;">
                            <div class="field-label">📋 {{ __('Additional Comments') }}</div>
                            <div class="feedback-text" style="background: #f8f9fa; padding: 1rem; border-radius: 6px; color: #555; line-height: 1.7;">
                                {!! nl2br(e($review->comments)) !!}
                            </div>
                        </div>
                        @endif

                        @if(!empty($specificChecks) && !empty($checklistLabels))
                        <div class="comment-field" style="margin-bottom: 1.5rem;">
                            <div class="field-label">📋 {{ __('Manuscript Validation Checklist') }}</div>
                            <ul class="checklist" style="list-style: none; padding: 0;">
                                @foreach($checklistLabels as $key => $label)
                                    @php $complete = !empty($specificChecks[$key]); @endphp
                                    <li class="checklist-item" style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="color: {{ $complete ? '#28a745' : '#ccc' }};">{{ $complete ? '✓' : '○' }}</span>
                                        <span style="color: {{ $complete ? '#2c3e50' : '#999' }};">{{ $label }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @endforeach
                @endif
            </div>

            <!-- Right Sidebar -->
            <div>
                <!-- Review Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Review Statistics') }}</h2>
                    </div>

                    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <div class="stat-card" style="background: #f8f9fa; padding: 1.25rem; border-radius: 6px; text-align: center; border-left: 4px solid #2a5298;">
                            <span class="stat-value" style="font-size: 2rem; font-weight: bold; color: #2a5298; display: block; margin-bottom: 0.5rem;">{{ $completedReviews->count() }}</span>
                            <span class="stat-label" style="font-size: 0.85rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Reviews Received') }}</span>
                        </div>
                        <div class="stat-card" style="background: #f8f9fa; padding: 1.25rem; border-radius: 6px; text-align: center; border-left: 4px solid #2a5298;">
                            <span class="stat-value" style="font-size: 2rem; font-weight: bold; color: #2a5298; display: block; margin-bottom: 0.5rem;">{{ $reviewStats['average_rating'] ? number_format($reviewStats['average_rating'], 2) : '—' }}</span>
                            <span class="stat-label" style="font-size: 0.85rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Avg. Quality') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{ __('Quick Actions') }}</h2>
                    </div>

                    <div class="actions" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="{{ route('user.orders.revision.form', $orderId) }}" class="btn btn-primary" style="width: 100%; text-align: center;">🛠️ {{ __('Prepare Revision') }}</a>
                        <a href="{{ route('user.orders.dashboard', $orderId) }}" class="btn btn-outline" style="width: 100%; text-align: center;">📊 {{ __('Back to Dashboard') }}</a>
                        <a href="{{ route('user.ticket.list') }}" class="btn btn-outline" style="width: 100%; text-align: center;">💬 {{ __('Contact Editor') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
