<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manuscript Acceptance Certificate</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0a1628;
            --deep-ink: #162238;
            --muted-ink: #4a5568;
            --paper: #fefdfb;
            --accent: #d4af37;
            --accent-deep: #b8941f;
            --seal: #1a2f4a;
            --border: rgba(10, 22, 40, 0.15);
            --highlight: #f0e6d2;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cormorant Garamond', serif;
            background: linear-gradient(135deg, #e8edf2 0%, #f5f7fa 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            color: var(--ink);
        }

        .certificate-wrapper {
            max-width: 1100px;
            width: 100%;
            position: relative;
        }

        .certificate-container {
            background: var(--paper);
            padding: 80px 90px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.12),
                0 8px 16px rgba(0, 0, 0, 0.08);
            position: relative;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        /* Ornate corner decorations */
        .corner-decoration {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 3px solid var(--accent);
            opacity: 0.4;
        }
        .corner-decoration.top-left { top: 20px; left: 20px; border-right: none; border-bottom: none; }
        .corner-decoration.top-right { top: 20px; right: 20px; border-left: none; border-bottom: none; }
        .corner-decoration.bottom-left { bottom: 20px; left: 20px; border-right: none; border-top: none; }
        .corner-decoration.bottom-right { bottom: 20px; right: 20px; border-left: none; border-top: none; }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-family: 'Playfair Display', serif;
            font-size: 120px;
            font-weight: 900;
            color: var(--accent);
            opacity: 0.03;
            pointer-events: none;
            z-index: 1;
            letter-spacing: 8px;
        }

        .content-layer {
            position: relative;
            z-index: 2;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 30px;
        }

        .emblem {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            border: 3px solid var(--accent);
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #ffffff 0%, #fefdfb 100%);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
            position: relative;
        }

        .emblem::before {
            content: '';
            position: absolute;
            inset: 8px;
            border: 1px solid var(--accent);
            border-radius: 50%;
            opacity: 0.4;
        }

        .emblem-inner {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 32px;
            color: var(--deep-ink);
            letter-spacing: 2px;
        }

        .journal-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--deep-ink);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .journal-subtitle {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--muted-ink);
            margin-bottom: 16px;
        }

        .issn {
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            color: var(--muted-ink);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            text-align: center;
            margin: 50px 0 30px;
            letter-spacing: 2px;
            color: var(--deep-ink);
            text-transform: uppercase;
            position: relative;
        }

        .certificate-title::after {
            content: '';
            display: block;
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            margin: 20px auto 0;
        }

        .certificate-number {
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--accent-deep);
            letter-spacing: 2px;
            margin-bottom: 35px;
        }

        .content {
            font-size: 19px;
            line-height: 1.9;
            color: var(--ink);
            text-align: justify;
            font-weight: 600;
        }

        .content p { margin-bottom: 22px; }

        .highlight {
            font-weight: 700;
            color: var(--deep-ink);
            background: linear-gradient(180deg, transparent 60%, var(--highlight) 60%);
            padding: 0 4px;
        }

        .manuscript-details {
            margin: 40px 0;
            padding: 30px;
            border: 2px solid var(--accent);
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.03) 0%, rgba(212, 175, 55, 0.01) 100%);
            position: relative;
        }

        .manuscript-details::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
        }

        .manuscript-details h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--accent-deep);
            margin-bottom: 20px;
            text-align: center;
        }

        .detail-grid {
            display: grid;
            gap: 16px;
        }

        .detail-item {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 20px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
            font-size: 17px;
        }

        .detail-item:last-child { border-bottom: none; }

        .detail-label {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: var(--muted-ink);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-value { 
            color: var(--ink);
            font-weight: 600;
        }

        .verification-box {
            background: var(--paper);
            border: 2px dashed var(--accent);
            padding: 20px;
            margin: 35px 0;
            text-align: center;
        }

        .verification-box h4 {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent-deep);
            margin-bottom: 16px;
        }

        .qr-code-container {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .qr-code {
            width: 140px;
            height: 140px;
            border: 3px solid var(--accent);
            padding: 8px;
            background: white;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.2);
        }

        .verification-url {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            color: var(--muted-ink);
            letter-spacing: 1px;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 40px;
            align-items: center;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid var(--accent);
        }

        .signature-block {
            text-align: center;
        }

        .sig-line {
            width: 100%;
            max-width: 280px;
            margin: 0 auto 12px;
            border-bottom: 2px solid var(--deep-ink);
            height: 60px;
            position: relative;
        }

        .sig-line::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            height: 50px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60"><path d="M10,35 Q15,25 25,30 T45,35 Q50,30 58,32 Q65,34 70,28 Q75,22 82,30 L90,35 Q95,38 102,35 Q108,32 115,35 T135,32 Q142,30 148,35 Q153,40 160,35 Q165,30 172,33 Q178,36 185,32" stroke="%231a2f4a" stroke-width="2.5" fill="none" stroke-linecap="round"/><path d="M15,45 Q25,42 35,45 T55,43 Q65,41 75,45 Q82,48 90,45 T110,43 Q120,41 130,44 Q138,47 145,44 T165,45 Q172,46 180,43" stroke="%231a2f4a" stroke-width="1.8" fill="none" opacity="0.7" stroke-linecap="round"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.7;
        }

        .signature-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--deep-ink);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signature-title {
            font-size: 14px;
            color: var(--muted-ink);
            font-style: italic;
            line-height: 1.5;
        }

        .official-seal {
            width: 160px;
            height: 160px;
            border: 4px solid var(--accent);
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1), transparent 70%);
            position: relative;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .official-seal::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 2px dashed var(--accent);
            border-radius: 50%;
            opacity: 0.5;
        }

        .seal-content {
            text-align: center;
        }

        .seal-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.4;
            color: var(--deep-ink);
        }

        .seal-year {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 900;
            margin-top: 8px;
            color: var(--accent-deep);
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            font-size: 13px;
            color: var(--muted-ink);
            line-height: 1.8;
        }

        .footer-item { 
            margin: 6px 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .issue-date {
            margin-top: 16px;
            font-style: italic;
            color: var(--accent-deep);
            font-weight: 600;
        }

        @media print {
            body { 
                background: white; 
                padding: 0; 
            }
            .certificate-container { 
                box-shadow: none; 
                padding: 60px;
            }
        }

        @media (max-width: 900px) {
            .certificate-container { 
                padding: 50px 40px; 
            }
            .certificate-title { 
                font-size: 38px; 
            }
            .journal-title { 
                font-size: 28px; 
            }
            .content { 
                font-size: 17px; 
            }
            .detail-item { 
                grid-template-columns: 1fr; 
                gap: 8px;
            }
            .signature-section { 
                grid-template-columns: 1fr; 
                gap: 30px;
            }
            .sig-line { 
                width: 240px; 
            }
            .corner-decoration {
                width: 50px;
                height: 50px;
            }
        }

        /* Security features */
        .security-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 10px,
                    rgba(212, 175, 55, 0.01) 10px,
                    rgba(212, 175, 55, 0.01) 20px
                );
            pointer-events: none;
            z-index: 0;
        }

        /* Holographic effect */
        .holographic-strip {
            position: absolute;
            top: 35%;
            right: -10px;
            width: 15px;
            height: 30%;
            background: linear-gradient(
                180deg,
                rgba(212, 175, 55, 0.1) 0%,
                rgba(212, 175, 55, 0.3) 50%,
                rgba(212, 175, 55, 0.1) 100%
            );
            transform: skewY(-5deg);
            opacity: 0.4;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate-container">
            <div class="security-pattern"></div>
            <div class="holographic-strip"></div>
            <div class="watermark">OFFICIAL</div>
            
            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>

            <div class="content-layer">
                <div class="header">
                    <div class="emblem">
                        <div class="emblem-inner">IA</div>
                    </div>
                    <h1 class="journal-title">International Journal of<br>Advanced Research</h1>
                    <div class="journal-subtitle">Peer-Reviewed Academic Publication</div>
                    <div class="issn">ISSN: 2589-7845 (Print) • 2589-7853 (Online)</div>
                </div>

                <h2 class="certificate-title">Certificate of Acceptance</h2>
                <div class="certificate-number">CERTIFICATE NO: IJARS-2026-CERT-001472</div>

                <div class="content">
                    <p>
                        This certificate officially attests that the manuscript entitled <span class="highlight">"Innovative Approaches to Machine Learning in Healthcare Diagnostics: A Comprehensive Analysis"</span> has been 
                        formally accepted for publication in the <span class="highlight">International Journal of Advanced Research</span>, 
                        following rigorous peer-review and editorial evaluation procedures.
                    </p>

                    <p>
                        Based upon comprehensive reviewers' assessments and the unanimous decision of the Editorial Board, the manuscript 
                        has been determined to meet the highest scholarly, methodological, and ethical standards of this journal. 
                        No further substantive scientific revisions are required. The manuscript will proceed directly to the production 
                        workflow for copyediting, typesetting, and final formatting.
                    </p>

                    <div class="manuscript-details">
                        <h3>Official Manuscript Record</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Manuscript ID</span>
                                <span class="detail-value">MS-2026-IJARS-001472</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">DOI</span>
                                <span class="detail-value">10.5284/ijars.2026.001472</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Corresponding Author</span>
                                <span class="detail-value">Sarah Mitchell Chen</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Contributing Authors</span>
                                <span class="detail-value">S.M. Chen, J.K. Williams, R. Patel, M. Anderson</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date of Acceptance</span>
                                <span class="detail-value">January 7, 2026</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Anticipated Publication</span>
                                <span class="detail-value">Volume 15, Issue 2 (March 2026)</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Article Type</span>
                                <span class="detail-value">Original Research Article</span>
                            </div>
                        </div>
                    </div>

                    <div class="verification-box">
                        <h4>Scan to Verify Certificate</h4>
                        <div class="qr-code-container">
                            <svg class="qr-code" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <!-- Top-left position marker -->
                                <rect fill="#0a1628" x="0" y="0" width="20" height="20"/>
                                <rect fill="#fefdfb" x="2" y="2" width="16" height="16"/>
                                <rect fill="#0a1628" x="5" y="5" width="10" height="10"/>
                                
                                <!-- Top-right position marker -->
                                <rect fill="#0a1628" x="80" y="0" width="20" height="20"/>
                                <rect fill="#fefdfb" x="82" y="2" width="16" height="16"/>
                                <rect fill="#0a1628" x="85" y="5" width="10" height="10"/>
                                
                                <!-- Bottom-left position marker -->
                                <rect fill="#0a1628" x="0" y="80" width="20" height="20"/>
                                <rect fill="#fefdfb" x="2" y="82" width="16" height="16"/>
                                <rect fill="#0a1628" x="5" y="85" width="10" height="10"/>
                                
                                <!-- Data pattern -->
                                <rect fill="#0a1628" x="25" y="5" width="5" height="5"/>
                                <rect fill="#0a1628" x="35" y="5" width="5" height="5"/>
                                <rect fill="#0a1628" x="45" y="5" width="5" height="5"/>
                                <rect fill="#0a1628" x="55" y="5" width="5" height="5"/>
                                <rect fill="#0a1628" x="65" y="5" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="5" y="25" width="5" height="5"/>
                                <rect fill="#0a1628" x="25" y="25" width="5" height="5"/>
                                <rect fill="#0a1628" x="35" y="25" width="5" height="5"/>
                                <rect fill="#0a1628" x="55" y="25" width="5" height="5"/>
                                <rect fill="#0a1628" x="65" y="25" width="5" height="5"/>
                                <rect fill="#0a1628" x="85" y="25" width="5" height="5"/>
                                <rect fill="#0a1628" x="95" y="25" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="5" y="35" width="5" height="5"/>
                                <rect fill="#0a1628" x="25" y="35" width="5" height="5"/>
                                <rect fill="#0a1628" x="45" y="35" width="5" height="5"/>
                                <rect fill="#0a1628" x="55" y="35" width="5" height="5"/>
                                <rect fill="#0a1628" x="75" y="35" width="5" height="5"/>
                                <rect fill="#0a1628" x="85" y="35" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="5" y="45" width="5" height="5"/>
                                <rect fill="#0a1628" x="15" y="45" width="5" height="5"/>
                                <rect fill="#0a1628" x="35" y="45" width="5" height="5"/>
                                <rect fill="#0a1628" x="45" y="45" width="5" height="5"/>
                                <rect fill="#0a1628" x="65" y="45" width="5" height="5"/>
                                <rect fill="#0a1628" x="75" y="45" width="5" height="5"/>
                                <rect fill="#0a1628" x="95" y="45" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="5" y="55" width="5" height="5"/>
                                <rect fill="#0a1628" x="25" y="55" width="5" height="5"/>
                                <rect fill="#0a1628" x="35" y="55" width="5" height="5"/>
                                <rect fill="#0a1628" x="55" y="55" width="5" height="5"/>
                                <rect fill="#0a1628" x="75" y="55" width="5" height="5"/>
                                <rect fill="#0a1628" x="85" y="55" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="5" y="65" width="5" height="5"/>
                                <rect fill="#0a1628" x="15" y="65" width="5" height="5"/>
                                <rect fill="#0a1628" x="25" y="65" width="5" height="5"/>
                                <rect fill="#0a1628" x="45" y="65" width="5" height="5"/>
                                <rect fill="#0a1628" x="65" y="65" width="5" height="5"/>
                                <rect fill="#0a1628" x="85" y="65" width="5" height="5"/>
                                <rect fill="#0a1628" x="95" y="65" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="25" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="35" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="45" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="55" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="65" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="75" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="85" y="85" width="5" height="5"/>
                                <rect fill="#0a1628" x="95" y="85" width="5" height="5"/>
                                
                                <rect fill="#0a1628" x="25" y="95" width="5" height="5"/>
                                <rect fill="#0a1628" x="45" y="95" width="5" height="5"/>
                                <rect fill="#0a1628" x="65" y="95" width="5" height="5"/>
                                <rect fill="#0a1628" x="85" y="95" width="5" height="5"/>
                            </svg>
                        </div>
                        <div class="verification-url">Certificate ID: IJARS-2026-CERT-001472</div>
                    </div>

                    <p>
                        The corresponding author will receive detailed instructions regarding copyright transfer agreements, 
                        author licensing options, proof corrections, and open access policies. Publication fees, if applicable, 
                        will be communicated separately per the journal's standard author agreement.
                    </p>

                    <p>
                        This certificate serves as official documentation of manuscript acceptance and may be used for academic, 
                        professional, or institutional purposes. The certificate remains valid contingent upon successful completion 
                        of all publication requirements and adherence to the journal's publication ethics guidelines.
                    </p>
                </div>

                <div class="signature-section">
                    <div class="signature-block">
                        <div class="sig-line"></div>
                        <div class="signature-name">Michael R. Thompson</div>
                        <div class="signature-title">Editor-in-Chief</div>
                        <div class="signature-title">International Journal of Advanced Research</div>
                    </div>

                    <div class="official-seal">
                        <div class="seal-content">
                            <div class="seal-text">Official<br>Academic<br>Seal</div>
                            <div class="seal-year">2026</div>
                        </div>
                    </div>

                    <div class="signature-block">
                        <div class="sig-line"></div>
                        <div class="signature-name">Amanda Foster</div>
                        <div class="signature-title">Managing Editor</div>
                        <div class="signature-title">Editorial Board</div>
                    </div>
                </div>

                <div class="footer">
                    <div class="footer-item">Published by: Academic Press International Publishing House</div>
                    <div class="footer-item">Editorial Office: 425 Research Parkway, Cambridge, MA 02138, USA</div>
                    <div class="footer-item">Email: editorial@ijars-journal.org | Web: www.ijars-journal.org</div>
                    <div class="footer-item issue-date">
                        Certificate Issued: January 7, 2026 at 14:32 UTC
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add a subtle animation on page load
        window.addEventListener('load', () => {
            document.querySelector('.certificate-container').style.animation = 'fadeIn 0.6s ease-out';
        });

        // Define fadeIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
