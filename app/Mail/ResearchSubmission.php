<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

class ResearchSubmission extends Mailable
{
    use Queueable, SerializesModels;
    
    public $researchData;
    public $isConfirmation;
    private $docxFilePath;

    public function __construct($researchData, $isConfirmation = false)
    {
        $this->researchData = $researchData;
        $this->isConfirmation = $isConfirmation;
        $this->docxFilePath = $this->generateDocx();
    }

    public function build()
    {
        $subject = $this->isConfirmation 
            ? 'Research Submission Confirmation - Arab Institute' 
            : 'Author Information Submission';
            
        // return $this->subject($subject)
        //             ->view('mail.research-submission');

        $mail = $this->subject($subject)
                    ->view('mail.research-submission');
        
        // Attach the generated DOCX file
        if ($this->docxFilePath && file_exists($this->docxFilePath)) {
            $mail->attach($this->docxFilePath, [
                'as' => 'research_submission_' . date('Y-m-d_H-i-s') . '.docx',
                'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);
        }
            
        return $mail;
    }

    private function generateDocx()
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
            
            $this->addBilingualField($section, 'Arabic Title', 'العنوان العربي', $this->researchData['research']['arabicTitle'] ?? 'N/A');
            $this->addBilingualField($section, 'English Title', 'العنوان الإنجليزي', $this->researchData['research']['englishTitle'] ?? 'N/A');
            
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
            if (isset($this->researchData['research']['science'])) {
                if ($this->researchData['research']['science'] == 'other') {
                    $researchField = ($this->researchData['research']['otherScience'] ?? 'Other - أخرى') . ' - أخرى';
                } else {
                    $researchField = $sciences[$this->researchData['research']['science']] ?? 'Not specified - غير محدد';
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
            if (isset($this->researchData['research']['journal'])) {
                $journal = $journals[$this->researchData['research']['journal']] ?? $this->researchData['research']['journal'];
            }
            $this->addBilingualField($section, 'Selected Journal', 'المجلة المختارة', $journal);
            
            // Keywords
            if (!empty($this->researchData['research']['keywords'])) {
                $this->addBilingualField($section, 'Keywords', 'الكلمات المفتاحية', $this->researchData['research']['keywords']);
            }

            //paper IDs
            if (!empty($this->researchData['research']['paperIdAr'])) {
                $this->addBilingualField($section, 'Paper ID (Arabic)', 'معرف البحث/الورقة (Paper ID)', $this->researchData['research']['paperIdAr']);
            }
            if (!empty($this->researchData['research']['paperIdEn'])) {
                $this->addBilingualField($section, 'Paper ID (English)', 'معرف البحث/الورقة (Paper ID)', $this->researchData['research']['paperIdEn']);
            }
            // Thesis extraction answer
            if (!empty($this->researchData['research']['thesisExtraction'])) {
                $this->addBilingualField($section, 'Thesis Extraction', 'استخراج البحث', $this->researchData['research']['thesisExtraction']);
            }
            
            
            $section->addTextBreak(1);
            
            // Authors section
            $this->addBilingualSection($section, 'Authors Information', 'معلومات الباحثين', 14);
            
            foreach ($this->researchData['authors'] as $index => $author) {
                $authorNumber = $index + 1;
                $this->addBilingualSection($section, "Author {$authorNumber}", "الباحث {$authorNumber}", 12);
                
                $this->addBilingualField($section, 'Name', 'الاسم', $author['nameEn'] ?? 'N/A', $author['nameAr'] ?? 'N/A');
                $this->addBilingualField($section, 'Title', 'اللقب', $author['titleEn'] ?? 'N/A', $author['titleAr'] ?? 'N/A');
                $this->addBilingualField($section, 'Email', 'البريد الإلكتروني', $author['email'] ?? 'N/A');
                $this->addBilingualField($section, 'Phone', 'رقم الهاتف', $author['phone'] ?? 'N/A');
                $this->addBilingualField($section, 'Degree', 'الدرجة العلمية', $author['degreeEn'] ?? 'N/A', $author['degreeAr'] ?? 'N/A');
                
                // Affiliation information
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
        $section->addText($englishText, ['bold' => true, 'size' => $fontSize]);
        $section->addText($arabicText, ['bold' => true, 'size' => $fontSize], ['rtl' => true, 'align' => 'right']);
        $section->addTextBreak(1);
    }

    private function addBilingualField($section, $englishLabel, $arabicLabel, $englishValue, $arabicValue = null)
    {
        $section->addText("{$englishLabel}: {$englishValue}");
        
        if ($arabicValue !== null) {
            $section->addText("{$arabicLabel}: {$arabicValue}", [], ['rtl' => true, 'align' => 'right']);
        } else {
            $section->addText("{$arabicLabel}: {$englishValue}", [], ['rtl' => true, 'align' => 'right']);
        }
        
        $section->addTextBreak(1);
    }

    //  // Clean up the temporary file after sending
    // public function __destruct()
    // {
    //     if ($this->docxFilePath && file_exists($this->docxFilePath)) {
    //         @unlink($this->docxFilePath);
    //     }
    // }
}