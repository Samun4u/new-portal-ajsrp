<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Book Submission Details') }}</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }
        
        .invoice-content {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: 700;
        }
        
        .status-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #4f46e5;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .detail-item {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 500;
        }
        
        .summary-box {
            background: #f8fafc;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 30px;
        }
        
        .summary-text {
            line-height: 1.7;
            color: #475569;
        }
        
        .file-attachments {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .file-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .file-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .file-name {
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-top: 10px;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn:hover {
            background: #4338ca;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid #4f46e5;
            color: #4f46e5;
        }
        
        .btn-outline:hover {
            background: #4f46e5;
            color: white;
        }
        
        .footer-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .public-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .public-yes {
            background: #d1fae5;
            color: #065f46;
        }
        
        .public-no {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-pending {
            color: #fbbf24;
        }
        
        .status-approved {
            color: #10b981;
        }
        
        .status-rejected {
            color: #ef4444;
        }
        
        /* Image Preview Modal */
        .image-preview-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .image-preview-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }
        
        .image-preview-content img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }
        
        .close-preview {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 30px;
            cursor: pointer;
            background: none;
            border: none;
        }
        
        @media (max-width: 768px) {
            .invoice-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-content">
        <div class="invoice-header">
            <div class="invoice-title">
                <i class="fas fa-book-open"></i> {{ __('Book Submission Details') }}
            </div>
            <div class="status-badge">
                {{ __('Status') }}: 
                <span id="statusValue" class="status-{{ $bookSubmission->status }}">
                    @if($bookSubmission->status === 'pending')
                        {{ __('Pending Review') }}
                    @elseif($bookSubmission->status === 'approved')
                        {{ __('Approved') }}
                    @elseif($bookSubmission->status === 'rejected')
                        {{ __('Rejected') }}
                    @else
                        {{ ucfirst($bookSubmission->status) }}
                    @endif
                </span>
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="section-title">
                <i class="fas fa-info-circle"></i> {{ __('Basic Information') }}
            </div>
            
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">{{ __('Title') }}</div>
                    <div class="detail-value">{{ $bookSubmission->title }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('Author') }}</div>
                    <div class="detail-value">{{ $bookSubmission->author }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('Genre') }}</div>
                    <div class="detail-value">{{ $bookSubmission->genre }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('Language') }}</div>
                    <div class="detail-value">{{ $bookSubmission->language }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('Publication Year') }}</div>
                    <div class="detail-value">{{ $bookSubmission->publication_year }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('Client Email') }}</div>
                    <div class="detail-value">{{ $bookSubmission->email }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">{{ __('Public Access') }}</div>
                    <div class="detail-value">
                        @if($bookSubmission->allow_public)
                            <span class="public-badge public-yes">
                                <i class="fas fa-check-circle"></i> &nbsp; {{ __('Allowed') }}
                            </span>
                        @else
                            <span class="public-badge public-no">
                                <i class="fas fa-times-circle"></i> &nbsp; {{ __('Not Allowed') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="section-title">
                <i class="fas fa-file-alt"></i> {{ __('Book Summary') }}
            </div>
            
            <div class="summary-box">
                <div class="summary-text">{{ $bookSubmission->summary }}</div>
            </div>
            
            <div class="section-title">
                <i class="fas fa-paperclip"></i> {{ __('Attachments') }}
            </div>
            
            <div class="file-attachments">
                @if($bookSubmission->book_file_id)
                <div class="file-card">
                    @php
                        $bookFileExtension = pathinfo(getFileData($bookSubmission->book_file_id, 'file_name'), PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array($bookFileExtension, ['pdf']))
                        <i class="fas fa-file-pdf file-icon" style="color: #e74c3c;"></i>
                    @elseif(in_array($bookFileExtension, ['doc', 'docx']))
                        <i class="fas fa-file-word file-icon" style="color: #2b579a;"></i>
                    @elseif(in_array($bookFileExtension, ['txt']))
                        <i class="fas fa-file-alt file-icon" style="color: #7f8c8d;"></i>
                    @else
                        <i class="fas fa-file file-icon" style="color: #4f46e5;"></i>
                    @endif
                    
                    <div class="file-name">{{ __('Book File') }}</div>
                    <div class="detail-label">{{ __('Uploaded') }}: {{ $bookSubmission->created_at->format('Y-m-d') }}</div>
                    <a href="{{ getFileUrl($bookSubmission->book_file_id) }}" target="_blank" class="btn">
                        <i class="fas fa-download"></i> {{ __('Download') }}
                    </a>
                </div>
                @endif
                
                @if($bookSubmission->cover_image_file_id)
                <div class="file-card">
                    <i class="fas fa-image file-icon" style="color: #9b59b6;"></i>
                    <div class="file-name">{{ __('Book Cover') }}</div>
                    <div class="detail-label">{{ __('Uploaded') }}: {{ $bookSubmission->created_at->format('Y-m-d') }}</div>
                    <a class="btn preview-image-btn" href="{{ getFileUrl($bookSubmission->cover_image_file_id) }}" target="_blank">
                        <i class="fas fa-eye"></i> {{ __('Preview') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>