<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Information Submission Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #1E3A8A, #1E40AF);
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 30px;
        }
        .email-footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .section-title {
            color: #1E3A8A;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #1E3A8A;
        }
        .field-row {
            display: flex;
            margin-bottom: 10px;
        }
        .field-label {
            font-weight: bold;
            width: 180px;
            color: #555;
        }
        .field-value {
            flex: 1;
        }
        .author-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .author-table th {
            background-color: #f0f4ff;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .author-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .corresponding {
            background-color: #e8f5e9;
        }
        .timestamp {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
        @media (max-width: 600px) {
            .field-row {
                flex-direction: column;
                margin-bottom: 15px;
            }
            .field-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Author Information Submission</h1>
            <p>Arab Institute for Science and Research Publishing</p>
        </div>
        
        <div class="email-body">
            <div class="section">
                <div class="section-title">Research Details</div>
                
                <div class="field-row">
                    <div class="field-label">Arabic Title:</div>
                    <div class="field-value">{{ $researchData['research']['arabicTitle'] ?? 'Not provided' }}</div>
                </div>
                
                <div class="field-row">
                    <div class="field-label">English Title:</div>
                    <div class="field-value">{{ $researchData['research']['englishTitle'] ?? 'Not provided' }}</div>
                </div>

                @php
                    $sciences = [
                        "education"              => "Education",
                        "economics"              => "Economics",
                        "medicine"               => "Medicine",
                        "psychology"             => "Psychology",
                        "sociology"              => "Sociology",
                        "engineering"            => "Engineering",
                        "computer_science"       => "Computer Science",
                        "physics"                => "Physics",
                        "chemistry"              => "Chemistry",
                        "biology"                => "Biology",
                        "mathematics"            => "Mathematics",
                        "curriculum_instruction" => "Curriculum & Instruction",
                        "humanities"             => "Humanities",
                        "political_science"      => "Political Science",
                        "arabic_language_literature" => "Arabic Language & Literature",
                        "linguistics"            => "Linguistics",
                        "islamic_studies"        => "Islamic Studies",
                        "theology_sharia"        => "Theology & Sharia",
                        "information_technology" => "Information Technology",
                        "pharmacy"               => "Pharmacy & Pharmaceutical Sciences",
                        "nursing_public_health"  => "Nursing & Public Health",
                        "veterinary_medicine"    => "Veterinary Medicine",
                        "agricultural_sciences"  => "Agricultural Sciences",
                        "agribusiness"           => "Agribusiness & Agricultural Economics",
                        "environmental_sciences" => "Environmental Sciences",
                        "climate_change"         => "Climate Change & Sustainability",
                        "business_admin"         => "Business Administration & Management",
                        "finance_accounting"     => "Finance & Accounting",
                        "law"                    => "Law & Legal Studies",
                        "public_admin"           => "Public Administration & Policy",
                        "risk_management"        => "Risk Management",
                        "crisis_management"      => "Crisis Management",
                        "disaster_studies"       => "Disaster Studies & Emergency Management",
                        "general_science"        => "General Science & Multidisciplinary Research",
                        "other"                  => "Other (not specified)"
                    ]
                @endphp
                
                <div class="field-row">
                    <div class="field-label">Research Field:</div>
                    <div class="field-value">
                        @if(isset($researchData['research']['science']) && $researchData['research']['science'] == 'other')
                            {{ $researchData['research']['otherScience'] ?? 'Other (not specified)' }}
                        @else
                            {{ $sciences[$researchData['research']['science']] ?? 'Not specified' }}
                        @endif
                    </div>
                </div>
                
                <div class="field-row">
                    <div class="field-label">Selected Journal:</div>
                    <div class="field-value">
                        @if(isset($researchData['research']['journal']))
                            @php
                                $journals = [
                                    "JEPS"   => "Journal of Educational and Psychological Sciences (JEPS)",
                                    "JCTM"   => "Journal of Curriculum and Teaching Methodology (JCTM)",
                                    "JHSS"   => "Journal of Humanities and Social Sciences (JHSS)",
                                    "JALSL"  => "Journal of Arabic Language Sciences and Literature (JALSL)",
                                    "JIS"    => "Journal of Islamic Sciences (JIS)",
                                    "JNSLAS" => "Journal of Natural Sciences, Life and Applied Sciences (JNSLAS)",
                                    "JESIT"  => "Journal of Engineering Sciences and Information Technology (JESIT)",
                                    "JMPS"   => "Journal of Medical and Pharmaceutical Sciences (JMPS)",
                                    "JAEVS"  => "Journal of Agricultural, Environmental and Veterinary Sciences (JAEVS)",
                                    "JEALS"  => "Journal of Economic, Administrative and Legal Sciences (JEALS)",
                                    "JRCM"   => "Journal of Risk and Crisis Management (JRCM)",
                                    "AJSRP"  => "Arab Journal of Sciences & Research Publishing (AJSRP)",
                                ];
                            @endphp
                            {{ $journals[$researchData['research']['journal']] ?? $researchData['research']['journal'] }}
                        @else
                            Not provided
                        @endif
                    </div>
                </div>
                
                <div class="field-row" style="display: none;">
                    <div class="field-label">Keywords:</div>
                    <div class="field-value">{{ $researchData['research']['keywords'] ?? 'Not provided' }}</div>
                </div>
                
                @if(isset($researchData['manuscript_path']))
                <div class="field-row">
                    <div class="field-label">Manuscript:</div>
                    <div class="field-value">
                        <a href="{{ asset('storage/' . $researchData['manuscript_path']) }}" target="_blank">
                            Download Manuscript
                        </a>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="section">
                <div class="section-title">Authors Information</div>
                
                <table class="author-table">
                    <thead>
                        <tr>
                            <th>Title (Arabic)</th>
                            <th>Title (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Name (English)</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Corresponding</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($researchData['authors'] as $index => $author)
                        <tr class="{{ $author['corresponding'] ? 'corresponding' : '' }}">
                            <td>{{ $author['titleAr'] ?? 'Not provided' }}</td>
                            <td>{{ $author['titleEn'] ?? 'Not provided' }}</td>
                            <td>{{ $author['nameAr'] ?? 'Not provided' }}</td>
                            <td>{{ $author['nameEn'] ?? 'Not provided' }}</td>
                            <td>{{ $author['email'] ?? 'Not provided' }}</td>
                            <td>{{ $author['phone'] ?? 'Not provided' }}</td>
                            <td>{{ $author['corresponding'] ? 'Yes' : 'No' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="section">
                <div class="section-title">Academic Information</div>
                
                <table class="author-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Degree (Arabic)</th>
                            <th>Degree (English)</th>
                            <th>Affiliation (Arabic)</th>
                            <th>Affiliation (English)</th>
                            <th>ORCID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($researchData['authors'] as $index => $author)
                        <?php
                            $countries = config('countries_data_ar_en');
                            $authorCountry = collect($countries)->firstWhere('code', $author['country']);
                            $authorCountryNameEn = $authorCountry ? $authorCountry['nameEn'] : '';
                            $authorCountryNameAr = $authorCountry ? $authorCountry['name'] : '';

                            // $affiliationAr = trim(($author['departmentAr'] ?? '') . ' - ' .
                            //  ($author['facultyAr'] ?? '') . ' - ' .
                            //  ($author['universityAr'] ?? '') . ' - ' .
                            //  ($authorCountryNameEn ?? ''));

                            $affiliationArParts = [
                                $author['departmentAr'] ?? '',
                                $author['facultyAr'] ?? '',
                                $author['universityAr'] ?? '',
                                $authorCountryNameAr
                            ];

                            // Remove empty parts
                            $affiliationArParts = array_filter($affiliationArParts);

                            $affiliationAr = implode(' - ', $affiliationArParts);

                            // $affiliationEn = trim(($author['departmentEn'] ?? '') . ' - ' .
                            //  ($author['facultyEn'] ?? '') . ' - ' .
                            //  ($author['universityEn'] ?? '') . ' - ' .
                            //  ($authorCountryNameEn ?? ''));

                            $affiliationEnParts = [
                                $author['departmentEn'] ?? '',
                                $author['facultyEn'] ?? '',
                                $author['universityEn'] ?? '',
                                $authorCountryNameEn
                            ];

                            // Remove empty parts
                            $affiliationEnParts = array_filter($affiliationEnParts);

                            $affiliationEn = implode(' - ', $affiliationEnParts);
                        ?>
                        <tr>
                            <td>{{ $author['nameEn'] ?? 'Not provided' }}</td>
                            <td>{{ $author['degreeAr'] ?? 'Not provided' }}</td>
                            <td>{{ $author['degreeEn'] ?? 'Not provided' }}</td>
                            <td>{{ $affiliationAr }}</td>
                            <td>{{ $affiliationEn }}</td>
                            <td>{{ $author['orcid'] ?? 'Not provided' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(!empty(trim($researchData['feedback'])))
            <div class="section">
                <div class="section-title">Feedback</div>
                <blockquote style="background:#f0f4ff; padding:15px; border-left:4px solid #1E3A8A; border-radius:5px; margin:0;">
                    {!! nl2br(e($researchData['feedback'])) !!}
                </blockquote>
            </div>
            @endif
            
            <div class="timestamp">
                <p>Submitted on: {{ now()->format('F j, Y, g:i a') }}</p>
                <p>IP Address: {{ request()->ip() }}</p>
            </div>
        </div>
        
        <div class="email-footer">
            <p>© {{ date('Y') }} Arab Institute for Science and Research Publishing. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>