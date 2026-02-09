<?php

namespace App\Services;

use App\Models\ClientOrderSubmission;
use App\Models\GalleyFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OjsApiService
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct()
    {
        $this->baseUrl = config('ojs.api_url', env('OJS_API_URL'));
        $this->apiKey = config('ojs.api_key', env('OJS_API_KEY'));
        $this->apiSecret = config('ojs.api_secret', env('OJS_API_SECRET'));
    }

    /**
     * Submit article to OJS via QuickSubmit API
     * Task 18: OJS REST API Integration
     */
    public function submitArticle(ClientOrderSubmission $submission)
    {
        try {
            if (!$this->baseUrl || !$this->apiKey) {
                throw new \Exception('OJS API credentials not configured');
            }

            $journal = $submission->journal;
            if (!$journal || !$journal->ojs_context) {
                throw new \Exception('Journal OJS context not configured');
            }

            // Get approved galley
            $approvedGalley = $submission->galleyFiles()
                ->where('status', 'approved')
                ->first();

            if (!$approvedGalley || !$approvedGalley->file) {
                throw new \Exception('No approved galley file found');
            }

            // Prepare article data
            $articleData = $this->prepareArticleData($submission, $approvedGalley);

            // Submit to OJS
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/journals/{$journal->ojs_context}/articles", $articleData);

            if ($response->successful()) {
                $responseData = $response->json();

                // Update submission with OJS data
                $submission->ojs_article_id = $responseData['data']['id'] ?? null;
                $submission->ojs_article_url = $responseData['data']['urlPublished'] ?? null;
                $submission->approval_status = 'published';
                if (isset($responseData['data']['datePublished'])) {
                    $submission->publication_date = $responseData['data']['datePublished'];
                }
                $submission->save();

                Log::info('Article submitted to OJS successfully', [
                    'submission_id' => $submission->id,
                    'ojs_article_id' => $submission->ojs_article_id,
                ]);

                return [
                    'success' => true,
                    'article_id' => $submission->ojs_article_id,
                    'article_url' => $submission->ojs_article_url,
                ];
            } else {
                $error = $response->json();
                Log::error('OJS API submission failed', [
                    'submission_id' => $submission->id,
                    'error' => $error,
                ]);

                throw new \Exception('OJS API Error: ' . ($error['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('OJS submission error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Prepare article data for OJS API
     */
    private function prepareArticleData(ClientOrderSubmission $submission, GalleyFile $galley)
    {
        $finalMetadata = $submission->finalMetadata;
        $authors = $submission->authors;

        // Get galley file content
        $galleyContent = base64_encode(
            Storage::disk(config('app.STORAGE_DRIVER'))->get($galley->file->path)
        );

        // Prepare authors array
        $authorsData = [];
        foreach ($authors as $index => $author) {
            $authorsData[] = [
                'firstName' => $author->first_name,
                'lastName' => $author->last_name,
                'email' => $author->email,
                'orcid' => $author->orcid ?? '',
                'affiliation' => is_array($author->affiliation)
                    ? implode(', ', $author->affiliation)
                    : $author->affiliation,
                'country' => $author->nationality ?? '',
                'userGroupId' => 14, // Author user group ID (adjust as needed)
            ];
        }

        // Prepare keywords
        $keywords = is_array($finalMetadata->final_keywords)
            ? $finalMetadata->final_keywords
            : explode(',', $finalMetadata->final_keywords);

        $articleData = [
            'publication' => [
                'title' => [
                    'en_US' => $finalMetadata->final_title,
                ],
                'subtitle' => $finalMetadata->short_title ? [
                    'en_US' => $finalMetadata->short_title,
                ] : null,
                'abstract' => [
                    'en_US' => $finalMetadata->final_abstract,
                ],
                'keywords' => [
                    'en_US' => $keywords,
                ],
                'authors' => $authorsData,
                'sectionId' => 1, // Default section (adjust as needed)
                'issueId' => $submission->issue ? $submission->issue->ojs_issue_id : null,
            ],
            'galley' => [
                'label' => 'PDF',
                'file' => [
                    'name' => $galley->file->original_name,
                    'mimeType' => $galley->file->file_type,
                    'contents' => $galleyContent,
                ],
            ],
        ];

        // Add optional fields
        if ($finalMetadata->funding_statement) {
            $articleData['publication']['funding'] = [
                'en_US' => $finalMetadata->funding_statement,
            ];
        }

        if ($finalMetadata->conflict_statement) {
            $articleData['publication']['competingInterests'] = [
                'en_US' => $finalMetadata->conflict_statement,
            ];
        }

        if ($finalMetadata->acknowledgements) {
            $articleData['publication']['acknowledgments'] = [
                'en_US' => $finalMetadata->acknowledgements,
            ];
        }

        return $articleData;
    }

    /**
     * Get article status from OJS
     */
    public function getArticleStatus($ojsArticleId, $journalContext)
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/api/v1/journals/{$journalContext}/articles/{$ojsArticleId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('OJS get article status error: ' . $e->getMessage());
            return null;
        }
    }
}

