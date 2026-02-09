<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: none; border-radius: 12px; overflow: hidden; }
        .header { background: #0a1628; color: white; padding: 30px; text-align: center; }
        .label { font-weight: 600; color: #6c757d; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .value { color: #212529; font-weight: 500; }
        .valid-badge { background-color: #d1e7dd; color: #0f5132; padding: 8px 20px; border-radius: 50px; font-weight: bold; display: inline-block; margin-bottom: 30px; border: 1px solid #badbcc; }
        .detail-row { border-bottom: 1px solid #eee; padding: 15px 0; }
        .detail-row:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="header">
                        <h3 class="m-0">Certificate Verification</h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="text-center">
                            <div class="valid-badge">✓ Officially Verified</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">Certificate No.</div>
                            <div class="col-md-8 value">{{ $certificate->certificate_number }}</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">Journal</div>
                            <div class="col-md-8 value">{{ $certificate->journal_name }}</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">Article Title</div>
                            <div class="col-md-8 value">{{ $certificate->paper_title }}</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">Authors</div>
                            <div class="col-md-8 value">{{ $certificate->author_names }}</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">DOI</div>
                            <div class="col-md-8 value">{{ $certificate->doi ?? 'N/A' }}</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">Publication</div>
                            <div class="col-md-8 value">Volume {{ $certificate->volume }}, Issue {{ $certificate->issue }}</div>
                        </div>

                        <div class="detail-row row">
                            <div class="col-md-4 label">Date Issued</div>
                            <div class="col-md-8 value">{{ $certificate->date ? \Carbon\Carbon::parse($certificate->date)->format('F j, Y') : 'N/A' }}</div>
                        </div>

                        <div class="text-center mt-5">
                            @if($certificate->pdf_path)
                                <a href="{{ route('certificates.download', $certificate->id) }}" class="btn btn-primary px-4 py-2">Download Certificate PDF</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
