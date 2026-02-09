@extends('user.layouts.app')
@push('title')
{{$pageTitle}}
@endpush

@push('style')
<style>
/* All the CSS from the original file remains the same */
    /* * { box-sizing: border-box; margin: 0; padding: 0; } */
    
    :root {
      --primary: #0f172a;
      --accent: #3b82f6;
      --accent-hover: #2563eb;
      --success: #10b981;
      --error: #ef4444;
      --bg-main: #f8fafc;
      --bg-card: #ffffff;
      --border: #e2e8f0;
      --text-primary: #0f172a;
      --text-secondary: #64748b;
      --text-muted: #94a3b8;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      /* min-height: 100vh; 
      padding: 40px 20px;
      color: var(--text-primary);
      line-height: 1.6; */
    }
    
    .container {
      max-width: 1500px;
      margin: 38px auto;
    }
    
    .header {
      text-align: center;
      margin-bottom: 38px;
      color: white;
    }
    
    .header h1 {
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 12px !important;
      letter-spacing: -0.02em;
    }
    
    .header p {
      font-size: 18px;
      opacity: 0.95;
      font-weight: 400;
    }
    
    .card {
      background: var(--bg-card);
      border-radius: 24px;
      padding: 48px;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .section-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 24px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--border);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .section-title::before {
      content: '';
      width: 4px;
      height: 20px;
      background: var(--accent);
      border-radius: 2px;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
      margin-bottom: 32px;
    }
    
    .form-group {
      display: flex;
      flex-direction: column;
    }
    
    .form-group.full {
      grid-column: 1 / -1;
    }
    
    label {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    
    .required {
      color: var(--error);
    }
    
    .hint {
      font-size: 13px;
      color: var(--text-muted);
      font-weight: 400;
      margin-left: 4px;
    }
    
    input[type="text"],
    input[type="email"],
    select,
    textarea {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--border);
      border-radius: 12px;
      font-size: 15px;
      font-family: inherit;
      transition: all 0.2s ease;
      background: var(--bg-main);
    }
    
    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: var(--accent);
      background: white;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    
    textarea {
      min-height: 120px;
      resize: vertical;
    }
    
    .file-upload-area {
      border: 2px dashed var(--border);
      border-radius: 12px;
      padding: 32px;
      text-align: center;
      background: var(--bg-main);
      transition: all 0.2s ease;
      cursor: pointer;
      position: relative;
    }
    
    .file-upload-area:hover {
      border-color: var(--accent);
      background: rgba(59, 130, 246, 0.05);
    }
    
    .file-upload-area.has-file {
      border-color: var(--success);
      background: rgba(16, 185, 129, 0.05);
    }
    
    .file-upload-icon {
      font-size: 48px;
      margin-bottom: 12px;
      opacity: 0.4;
    }
    
    .file-upload-text {
      font-size: 15px;
      color: var(--text-secondary);
      margin-bottom: 8px;
    }
    
    .file-upload-text strong {
      color: var(--accent);
    }
    
    .file-name-display {
      font-size: 14px;
      color: var(--text-primary);
      font-weight: 600;
      margin-top: 12px;
    }
    
    input[type="file"] {
      position: absolute;
      opacity: 0;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      cursor: pointer;
    }
    
    .cover-preview-area {
      display: flex;
      gap: 16px;
      align-items: flex-start;
    }
    
    .cover-preview {
      width: 160px;
      height: 240px;
      border-radius: 8px;
      object-fit: cover;
      border: 2px solid var(--border);
      display: none;
      box-shadow: var(--shadow-md);
    }
    
    .cover-preview.visible {
      display: block;
    }
    
    .checkbox-group {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 16px;
      background: var(--bg-main);
      border-radius: 12px;
      border: 2px solid var(--border);
    }
    
    input[type="checkbox"] {
      width: 20px;
      height: 20px;
      margin-top: 2px;
      cursor: pointer;
      accent-color: var(--accent);
    }
    
    .checkbox-label {
      font-size: 14px;
      color: var(--text-secondary);
      line-height: 1.5;
      cursor: pointer;
    }
    
    .actions {
      display: flex;
      gap: 16px;
      align-items: center;
      margin-top: 32px;
      padding-top: 32px;
      border-top: 2px solid var(--border);
    }
    
    .book-btn {
      padding: 14px 32px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    
    .btn-primary {
      background: var(--accent);
      color: white;
      box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover:not(:disabled) {
      background: var(--accent-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
    
    .btn-secondary {
      background: var(--bg-main);
      color: var(--text-primary);
      border: 2px solid var(--border);
    }
    
    .btn-secondary:hover {
      background: white;
      border-color: var(--text-muted);
    }
    
    .status-message {
      flex: 1;
      font-size: 14px;
      font-weight: 500;
    }
    
    .status-message.error {
      color: var(--error);
    }
    
    .status-message.success {
      color: var(--success);
    }
    
    .upload-progress {
      margin-top: 24px;
      padding: 20px;
      background: var(--bg-main);
      border-radius: 12px;
      display: none;
    }
    
    .upload-progress.visible {
      display: block;
    }
    
    .progress-bar-container {
      height: 8px;
      background: white;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 12px;
    }
    
    .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--accent), #60a5fa);
      width: 0%;
      transition: width 0.3s ease;
    }
    
    .progress-info {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: var(--text-secondary);
    }
    
    .footer-note {
      margin-top: 24px;
      padding: 20px;
      background: var(--bg-main);
      border-radius: 12px;
      border-left: 4px solid var(--accent);
      font-size: 13px;
      color: var(--text-secondary);
      line-height: 1.6;
    }
    
    .error-message {
      color: var(--error);
      font-size: 13px;
      margin-top: 4px;
    }
    
    @media (max-width: 768px) {
      .card { padding: 32px 24px; }
      .form-grid { grid-template-columns: 1fr; }
      .header h1 { font-size: 28px; }
      .header p { font-size: 16px; }
    }
</style>

@endpush

@section('content')
<div class="container">
    <div class="header">
      <h1>{{ __('Submit Your Book') }}</h1>
      <p>{{ __('Share your work with our editorial team for review and publication') }}</p>
    </div>
    
    <div class="card">
      <form id="bookForm" action="{{ route('user.submit-your-book.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        
        <div class="section-title">{{ __('Book Information') }}</div>
        <div class="form-grid">
          <div class="form-group">
            <label>{{ __('Book Title') }} <span class="required">*</span></label>
            <input id="title" name="title" type="text" required maxlength="200" placeholder="{{ __('Enter the book title') }}" value="{{ old('title') }}" />
            <div id="title-error" class="error-message"></div>
          </div>
          
          <div class="form-group">
            <label>{{ __('Author Name') }} <span class="required">*</span></label>
            <input id="author" name="author" type="text" required maxlength="120" placeholder="{{ __('Author\'s full name') }}" value="{{ old('author') }}" />
            <div id="author-error" class="error-message"></div>
          </div>
          
          <div class="form-group">
            <label>{{ __('Genre / Category') }}</label>
            <input id="genre" name="genre" type="text" placeholder="{{ __('e.g., Science Fiction, Biography') }}" value="{{ old('genre') }}" />
            <div id="genre-error" class="error-message"></div>
          </div>
          
          <div class="form-group">
            <label>{{ __('Language') }}</label>
            <select id="language" name="language">
              <option value="">{{ __('Select language') }}</option>
              <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>{{ __('English') }}</option>
              <option value="Arabic" {{ old('language') == 'Arabic' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
              <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>{{ __('French') }}</option>
              <option value="Spanish" {{ old('language') == 'Spanish' ? 'selected' : '' }}>{{ __('Spanish') }}</option>
              <option value="German" {{ old('language') == 'German' ? 'selected' : '' }}>{{ __('German') }}</option>
              <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>
            <div id="language-error" class="error-message"></div>
          </div>
          
          <div class="form-group">
            <label>{{ __('Publication Year') }}</label>
            <input id="year" name="year" type="text" inputmode="numeric" pattern="\d{4}" placeholder="2025" value="{{ old('year') }}" />
            <div id="year-error" class="error-message"></div>
          </div>
          
          <div class="form-group">
            <label>{{ __('Contact Email') }} <span class="required">*</span></label>
            <input id="email" name="email" type="email" required placeholder="your.email@example.com" value="{{ old('email') }}" />
            <div id="email-error" class="error-message"></div>
          </div>
          
          <div class="form-group full">
            <label>{{ __('Book Description') }} <span class="hint">({{ __('50-300 words recommended') }})</span></label>
            <textarea id="summary" name="summary" placeholder="{{ __('Provide a compelling summary of your book that highlights its key themes, plot, or main ideas...') }}">{{ old('summary') }}</textarea>
            <div id="summary-error" class="error-message"></div>
          </div>
        </div>
        
        <div class="section-title">{{ __('File Uploads') }}</div>
        <div class="form-grid">
          <div class="form-group full">
            <label>{{ __('Book Manuscript') }} <span class="required">*</span> <span class="hint">({{ __('PDF, EPUB, DOCX; max 100 MB') }})</span></label>
            <div class="file-upload-area" id="bookFileArea">
              <div class="file-upload-icon">📄</div>
              <div class="file-upload-text">
                <strong>{{ __('Click to upload') }}</strong> {{ __('or drag and drop') }}
              </div>
              <div class="hint">{{ __('Supported formats: PDF, EPUB, DOCX') }}</div>
              <div class="file-name-display" id="fileName"></div>
              <input id="bookFile" name="bookFile" type="file" accept="application/pdf,application/epub+zip,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.epub,.pdf,.docx" required />
            </div>
            <div id="bookFile-error" class="error-message"></div>
          </div>
          
          <div class="form-group full">
            <label>{{ __('Cover Image') }} <span class="hint">({{ __('Optional - JPG, PNG') }})</span></label>
            <div class="cover-preview-area">
              <img id="coverPreview" class="cover-preview" alt="{{ __('book_submission.cover_preview') }}" />
              <div style="flex: 1;">
                <div class="file-upload-area" id="coverFileArea">
                  <div class="file-upload-icon">🖼️</div>
                  <div class="file-upload-text">
                    <strong>{{ __('Upload cover image') }}</strong>
                  </div>
                  <div class="hint">{{ __('For best results, use 1600x2400px') }}</div>
                  <input id="coverImage" name="coverImage" type="file" accept="image/*" />
                </div>
              </div>
            </div>
            <div id="coverImage-error" class="error-message"></div>
          </div>
        </div>
        
        <div class="section-title">{{ __('Publishing Preferences') }}</div>
        <div class="form-group full">
          <div class="checkbox-group">
            <input id="allowPublic" name="allowPublic" type="checkbox" {{ old('allowPublic') ? 'checked' : '' }} />
            <label for="allowPublic" class="checkbox-label">
              {{ __('I authorize the public display of the book title and author name on your platform during the review process. All submissions remain confidential until approved by the editorial team.') }}
            </label>
          </div>
          <div id="allowPublic-error" class="error-message"></div>
        </div>
        
        <div class="actions">
          <button type="submit" id="submitBtn" class="book-btn btn-primary">
            <span>📤</span> {{ __('Submit Book') }}
          </button>
          <button type="button" id="clearBtn" class="book-btn btn-secondary">
            {{ __('Clear Form') }}
          </button>
          <div id="status" class="status-message"></div>
        </div>
        
        <div class="upload-progress" id="uploadProgress">
          <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
          </div>
          <div class="progress-info">
            <span>{{ __('Uploading') }}: <strong id="progressPercent">0%</strong></span>
            <span>{{ __('Speed') }}: <span id="uploadSpeed">0 KB/s</span></span>
          </div>
        </div>
      </form>
      
      <div class="footer-note">
        <strong>📋 {{ __('Submission Guidelines') }}:</strong> {{ __('Your manuscript will be reviewed by our editorial team within 2-4 weeks. We accept original works in multiple formats and languages. All submissions are treated confidentially. For technical support, contact submissions@example.com') }}
      </div>
    </div>
  </div>

@endsection

@push('script')
<script>
    (function() {
      const form = document.getElementById('bookForm');
      const bookFile = document.getElementById('bookFile');
      const bookFileArea = document.getElementById('bookFileArea');
      const fileName = document.getElementById('fileName');
      const cover = document.getElementById('coverImage');
      const coverFileArea = document.getElementById('coverFileArea');
      const coverPreview = document.getElementById('coverPreview');
      const submitBtn = document.getElementById('submitBtn');
      const clearBtn = document.getElementById('clearBtn');
      const status = document.getElementById('status');
      const uploadProgress = document.getElementById('uploadProgress');
      const progressBar = document.getElementById('progressBar');
      const progressPercent = document.getElementById('progressPercent');
      const uploadSpeed = document.getElementById('uploadSpeed');

      const MAX_SIZE = 100 * 1024 * 1024; // 100 MB

      // Clear error messages when user starts typing
      document.querySelectorAll('input, textarea, select').forEach(element => {
        element.addEventListener('input', () => {
          const errorElement = document.getElementById(`${element.name}-error`);
          if (errorElement) {
            errorElement.textContent = '';
          }
        });
      });

      bookFile.addEventListener('change', e => {
        const f = e.target.files && e.target.files[0];
        if (!f) {
          fileName.textContent = '';
          bookFileArea.classList.remove('has-file');
          return;
        }
        
        const sizeMB = (f.size / (1024 * 1024)).toFixed(2);
        fileName.textContent = `${f.name} (${sizeMB} MB)`;
        bookFileArea.classList.add('has-file');
        
        if (f.size > MAX_SIZE) {
          status.textContent = "❌ {{ __('File is too large (maximum 100 MB)') }}";
          status.className = 'status-message error';
        } else {
          status.textContent = '';
          status.className = 'status-message';
        }
      });

      cover.addEventListener('change', e => {
        const f = e.target.files && e.target.files[0];
        if (!f) {
          coverPreview.classList.remove('visible');
          coverFileArea.classList.remove('has-file');
          return;
        }
        
        if (!f.type.startsWith('image/')) return;
        
        const url = URL.createObjectURL(f);
        coverPreview.src = url;
        coverPreview.classList.add('visible');
        coverFileArea.classList.add('has-file');
      });

      clearBtn.addEventListener('click', () => {
        form.reset();
        coverPreview.classList.remove('visible');
        bookFileArea.classList.remove('has-file');
        coverFileArea.classList.remove('has-file');
        fileName.textContent = '';
        status.textContent = '';
        status.className = 'status-message';
        progressBar.style.width = '0%';
        progressPercent.textContent = '0%';
        uploadProgress.classList.remove('visible');
        
        // Clear all error messages
        document.querySelectorAll('.error-message').forEach(el => {
          el.textContent = '';
        });
      });

      form.addEventListener('submit', function(evt) {
        evt.preventDefault();
        status.textContent = '';
        status.className = 'status-message';

        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => {
          el.textContent = '';
        });

        if (!form.reportValidity()) return;
        
        const f = bookFile.files && bookFile.files[0];
        if (!f) {
          status.textContent = "❌ {{ __('Please attach your book manuscript') }}";
          status.className = 'status-message error';
          return;
        }
        
        if (f.size > MAX_SIZE) {
          status.textContent = "❌ {{ __('File is too large (maximum 100 MB)') }}";
          status.className = 'status-message error';
          return;
        }

        const fd = new FormData(form);
        submitBtn.disabled = true;
        uploadProgress.classList.add('visible');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        const start = Date.now();
        let lastLoaded = 0, lastTime = start;

        xhr.upload.onprogress = function(e) {
          if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = pct + '%';
            progressPercent.textContent = pct + '%';

            const now = Date.now();
            const dt = (now - lastTime) / 1000;
            if (dt > 0) {
              const bytes = e.loaded - lastLoaded;
              const speed = Math.round((bytes / 1024) / dt);
              uploadSpeed.textContent = speed + ' KB/s';
              lastLoaded = e.loaded;
              lastTime = now;
            }
          }
        };

        xhr.onload = function() {
          submitBtn.disabled = false;
          uploadProgress.classList.remove('visible');
          
          try {
            const res = JSON.parse(xhr.responseText || '{}');
            
            if (xhr.status >= 200 && xhr.status < 300) {
              status.textContent = '✅ ' + (res.message || "{{ __('Book submitted successfully!') }}");
              status.className = 'status-message success';
              form.reset();
              coverPreview.classList.remove('visible');
              bookFileArea.classList.remove('has-file');
              coverFileArea.classList.remove('has-file');
              fileName.textContent = '';
            } else if (xhr.status === 422) {
              // Validation errors
              if (res.errors) {
                for (const [field, messages] of Object.entries(res.errors)) {
                  const errorElement = document.getElementById(`${field}-error`);
                  if (errorElement) {
                    errorElement.textContent = messages[0];
                  }
                }
                status.textContent = "❌ {{ __('Validation errors') }}";
                status.className = 'status-message error';
              }
            } else {
              status.textContent = '❌ ' + (res.message || "{{ __('Upload failed') }}");
              status.className = 'status-message error';
            }
          } catch (err) {
            status.textContent = "❌ {{ __('Network error during upload') }}";
            status.className = 'status-message error';
          }
        };

        xhr.onerror = function() {
          submitBtn.disabled = false;
          uploadProgress.classList.remove('visible');
          status.textContent = "❌ {{ __('Network error during upload') }}";
          status.className = 'status-message error';
        };

        xhr.send(fd);
      });
    })();
  </script>
@endpush