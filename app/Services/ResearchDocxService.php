<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;

class ResearchDocxService
{
    public function generateDocx(array $researchData)
    {
        try {
            $phpWord = new PhpWord();
            
            // Set RTL for Arabic sections
            $rtlStyle = ['rtl' => true, 'align' => 'right'];
            $ltrStyle = ['rtl' => false, 'align' => 'left'];
            
            $section = $phpWord->addSection();
            
            // Title with both languages
            $section->addText(
                'Research Submission Details',
                ['bold' => true, 'size' => 16],
                ['alignment' => 'center']
            );
            
            $section->addText(
                'تفاصيل تقديم البحث - المؤسسة العربية للعلوم ونشر الأبحاث',
                ['bold' => true, 'size' => 14],
                ['alignment' => 'center', 'rtl' => true]
            );
            
            $section->addTextBreak(2);
            
            // Research Details - Bilingual
            $this->addBilingualSection($section, 'Research Details', 'تفاصيل البحث', 14);
            
            $this->addBilingualField($section, 'Arabic Title', 'العنوان العربي', $researchData['research']['arabicTitle'] ?? 'N/A');
            $this->addBilingualField($section, 'English Title', 'العنوان الإنجليزي', $researchData['research']['englishTitle'] ?? 'N/A');
            
            // Research Field
            $sciences = [
                "education" => "Education - التربية",
                "economics" => "Economics - الاقتصاد",
                "medicine" => "Medicine - الطب",
                "psychology" => "Psychology - علم النفس",
                "sociology" => "Sociology - علم الاجتماع",
                "engineering" => "Engineering - الهندسة",
                "computer_science" => "Computer Science - علوم الحاسوب",
                "physics" => "Physics - الفيزياء",
                "chemistry" => "Chemistry - الكيمياء",
                "biology" => "Biology - علم الأحياء",
                "mathematics" => "Mathematics - الرياضيات",
                "curriculum_instruction" => "Curriculum & Instruction - المناهج وطرق التدريس",
                "humanities" => "Humanities - العلوم الإنسانية",
                "political_science" => "Political Science - العلوم السياسية",
                "arabic_language_literature" => "Arabic Language & Literature - اللغة العربية وآدابها",
                "linguistics" => "Linguistics - اللسانيات",
                "islamic_studies" => "Islamic Studies - الدراسات الإسلامية",
                "theology_sharia" => "Theology & Sharia - اللاهوت والشريعة",
                "information_technology" => "Information Technology - تكنولوجيا المعلومات",
                "pharmacy" => "Pharmacy & Pharmaceutical Sciences - الصيدلة والعلوم الصيدلانية",
                "nursing_public_health" => "Nursing & Public Health - التمريض والصحة العامة",
                "veterinary_medicine" => "Veterinary Medicine - الطب البيطري",
                "agricultural_sciences" => "Agricultural Sciences - العلوم الزراعية",
                "agribusiness" => "Agribusiness & Agricultural Economics - الأعمال الزراعية والاقتصاد الزراعي",
                "environmental_sciences" => "Environmental Sciences - علوم البيئة",
                "climate_change" => "Climate Change & Sustainability - تغير المناخ والاستدامة",
                "business_admin" => "Business Administration & Management - إدارة الأعمال",
                "finance_accounting" => "Finance & Accounting - المالية والمحاسبة",
                "law" => "Law & Legal Studies - القانون والدراسات القانونية",
                "public_admin" => "Public Administration & Policy - الإدارة العامة والسياسات",
                "risk_management" => "Risk Management - إدارة المخاطر",
                "crisis_management" => "Crisis Management - إدارة الأزمات",
                "disaster_studies" => "Disaster Studies & Emergency Management - دراسات الكوارث وإدارة الطوارئ",
                "general_science" => "General Science & Multidisciplinary Research - العلوم العامة والبحوث متعددة التخصصات",
                "other" => "Other - أخرى"
            ];
            
            $researchField = 'Not specified - غير محدد';
            if (isset($researchData['research']['science'])) {
                if ($researchData['research']['science'] == 'other') {
                    $researchField = ($researchData['research']['otherScience'] ?? 'Other - أخرى') . ' - أخرى';
                } else {
                    $researchField = $sciences[$researchData['research']['science']] ?? 'Not specified - غير محدد';
                }
            }
            $this->addBilingualField($section, 'Research Field', 'علم البحث', $researchField);
            
            // Journal
            $journals = [
                "JEPS" => "Journal of Educational and Psychological Sciences (JEPS) - مجلة العلوم التربوية والنفسية",
                "JCTM" => "Journal of Curriculum and Teaching Methodology (JCTM) - مجلة المناهج وطرق التدريس",
                "JHSS" => "Journal of Humanities and Social Sciences (JHSS) - مجلة العلوم الإنسانية والإجتماعية",
                "JALSL" => "Journal of Arabic Language Sciences and Literature (JALSL) - مجلة علوم اللغة العربية وآدابها",
                "JIS" => "Journal of Islamic Sciences (JIS) - مجلة العلوم الإسلامية",
                "JNSLAS" => "Journal of Natural Sciences, Life and Applied Sciences (JNSLAS) - مجلة العلوم الطبيعية والحياتية والتطبيقية",
                "JESIT" => "Journal of Engineering Sciences and Information Technology (JESIT) - مجلة العلوم الهندسية وتكنولوجيا المعلومات",
                "JMPS" => "Journal of Medical and Pharmaceutical Sciences (JMPS) - مجلة العلوم الطبية والصيدلانية",
                "JAEVS" => "Journal of Agricultural, Environmental and Veterinary Sciences (JAEVS) - مجلة العلوم الزراعية والبيئية والبيطرية",
                "JEALS" => "Journal of Economic, Administrative and Legal Sciences (JEALS) - مجلة العلوم الإقتصادية والإدارية والقانونية",
                "JRCM" => "Journal of Risk and Crisis Management (JRCM) - مجلة إدارة المخاطر والأزمات",
                "AJSRP" => "Arab Journal of Sciences & Research Publishing (AJSRP) - المجلة العربية للعلوم ونشر الأبحاث",
            ];
            
            $journal = 'Not provided - غير مقدم';
            if (isset($researchData['research']['journal'])) {
                $journal = $journals[$researchData['research']['journal']] ?? $researchData['research']['journal'];
            }
            $this->addBilingualField($section, 'Selected Journal', 'المجلة المختارة', $journal);
            
            // Keywords
            if (!empty($researchData['research']['keywords'])) {
                $this->addBilingualField($section, 'Keywords', 'الكلمات المفتاحية', $researchData['research']['keywords']);
            }

            //paper IDs
            if (!empty($researchData['research']['paperIdAr'])) {
                $this->addBilingualField($section, 'Paper ID (Arabic)', 'معرف البحث/الورقة (Paper ID)', $researchData['research']['paperIdAr']);
            }
            if (!empty($researchData['research']['paperIdEn'])) {
                $this->addBilingualField($section, 'Paper ID (English)', 'معرف البحث/الورقة (Paper ID)', $researchData['research']['paperIdEn']);
            }
            // Thesis extraction answer
            if (!empty($researchData['research']['thesisExtraction'])) {
                $this->addBilingualField($section, 'Thesis Extraction', 'استخراج البحث', $researchData['research']['thesisExtraction']);
            }
            
            
            $section->addTextBreak(1);
            
            // Authors section
            $this->addBilingualSection($section, 'Authors Information', 'معلومات الباحثين', 14);
            
            foreach ($researchData['authors'] as $index => $author) {
                $authorNumber = $index + 1;
                $this->addBilingualSection($section, "Author {$authorNumber}", "الباحث {$authorNumber}", 12);
                
                $this->addBilingualField($section, 'Name', 'الاسم', $author['nameEn'] ?? 'N/A', $author['nameAr'] ?? 'N/A');
                $this->addBilingualField($section, 'Title', 'اللقب', $author['titleEn'] ?? 'N/A', $author['titleAr'] ?? 'N/A');
                $this->addBilingualField($section, 'Email', 'البريد الإلكتروني', $author['email'] ?? 'N/A');
                $this->addBilingualField($section, 'Phone', 'رقم الهاتف', $author['phone'] ?? 'N/A');
                $this->addBilingualField($section, 'Degree', 'الدرجة العلمية', $author['degreeEn'] ?? 'N/A', $author['degreeAr'] ?? 'N/A');
                
                // Affiliation information
                if (isset($author['formattedAffiliationEn']) && isset($author['formattedAffiliationAr'])) {
                    $affiliationEn = $author['formattedAffiliationEn'];
                    $affiliationAr = $author['formattedAffiliationAr'];
                } else {
                    $countries = config('countries_data_ar_en');
                    $authorCountry = collect($countries)->firstWhere('code', $author['country'] ?? '');
                    $countryName = $authorCountry ? $authorCountry['nameEn'] . ' / ' . $authorCountry['name'] : ($author['country'] ?? '');
                    
                    $affiliationEnParts = [
                        $author['departmentEn'] ?? '',
                        $author['facultyEn'] ?? '',
                        $author['universityEn'] ?? '',
                        $countryName
                    ];
                    $affiliationEnParts = array_filter($affiliationEnParts);
                    $affiliationEn = implode(' - ', $affiliationEnParts);
                    
                    $affiliationArParts = [
                        $author['departmentAr'] ?? '',
                        $author['facultyAr'] ?? '',
                        $author['universityAr'] ?? '',
                        $countryName
                    ];
                    $affiliationArParts = array_filter($affiliationArParts);
                    $affiliationAr = implode(' - ', $affiliationArParts);
                }
                
                $this->addBilingualField($section, 'Affiliation', 'الانتماء المؤسسي', $affiliationEn, $affiliationAr);
                
                // ORCID
                if (!empty($author['orcid'])) {
                    $this->addBilingualField($section, 'ORCID iD', 'معرف ORCID', $author['orcid']);
                }
                
                // Corresponding author
                if (!empty($author['corresponding']) && $author['corresponding']) {
                    $this->addBilingualField($section, 'Corresponding Author', 'الباحث المراسل', 'Yes', 'نعم');
                }
                
                $section->addTextBreak(1);
            }
            
            // Footer with date
            $section->addTextBreak(2);
            $section->addText(
                'Generated on: ' . date('Y-m-d H:i:s') . ' / تم الإنشاء في: ' . date('Y-m-d H:i:s'),
                ['size' => 9, 'color' => '999999'],
                ['alignment' => 'right']
            );
            
            // Save the document
            $fileName = 'research_submission_' . time() . '.docx';
            $filePath = storage_path('app/temp/' . $fileName);
            
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($filePath);
            
            return $filePath;
            
        } catch (\Exception $e) {
            Log::error('DOCX Generation Error: ' . $e->getMessage());
            return null;
        }
    }

    // Helper methods for bilingual content
    private function addBilingualSection($section, $englishText, $arabicText, $fontSize = 12)
    {
        $section->addText($this->sanitize($englishText), ['bold' => true, 'size' => $fontSize]);
        $section->addText($this->sanitize($arabicText), ['bold' => true, 'size' => $fontSize], ['rtl' => true, 'align' => 'right']);
        $section->addTextBreak(1);
    }

    private function addBilingualField($section, $englishLabel, $arabicLabel, $englishValue, $arabicValue = null)
    {
        $englishValue = $this->sanitize($englishValue);
        $englishLabel = $this->sanitize($englishLabel);
        $arabicLabel = $this->sanitize($arabicLabel);
        
        $section->addText("{$englishLabel}: {$englishValue}");
        
        if ($arabicValue !== null) {
            $arabicValue = $this->sanitize($arabicValue);
            $section->addText("{$arabicLabel}: {$arabicValue}", [], ['rtl' => true, 'align' => 'right']);
        } else {
            $section->addText("{$arabicLabel}: {$englishValue}", [], ['rtl' => true, 'align' => 'right']);
        }
        
        $section->addTextBreak(1);
    }

    private function sanitize($text)
    {
        if (is_null($text)) {
            return '';
        }
        // Remove control characters that are invalid in XML
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
