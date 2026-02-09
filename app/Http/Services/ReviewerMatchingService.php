<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\ClientOrderSubmission;
use App\Models\ClientOrderAssignee;
use App\Models\AuthorDetails;
use App\Models\Journal;
use App\Models\JournalSubject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReviewerMatchingService
{
    /**
     * Get matched reviewers for a submission
     */
    public function getMatchedReviewers(ClientOrderSubmission $submission, $orderId, $tenantId, $search = ''): Collection
    {
        // Get paper details
        $paperData = $this->extractPaperData($submission);

        // Build reviewer query with search filters
        $reviewerQuery = User::where('role', USER_ROLE_REVIEWER)
            ->where('status', STATUS_ACTIVE)
            ->where('tenant_id', $tenantId)
            ->with('userDetail');

        // Apply search filter if provided
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $reviewerQuery->where(function($query) use ($searchTerm) {
                // Search by name
                $query->where('name', 'like', $searchTerm)
                    // Search by email
                    ->orWhere('email', 'like', $searchTerm)
                    // Search by field of study
                    ->orWhere('field_of_study', 'like', $searchTerm)
                    // Search in subject areas (JSON field)
                    ->orWhere('subject_areas', 'like', $searchTerm)
                    // Search in expertise keywords (JSON field)
                    ->orWhere('expertise_keywords', 'like', $searchTerm)
                    // Search in user details company/institution
                    ->orWhereHas('userDetail', function($q) use ($searchTerm) {
                        $q->where('basic_company', 'like', $searchTerm);
                    });
            });
        }

        $reviewers = $reviewerQuery->get();

        Log::info('ReviewerMatching: Found reviewers', [
            'total_reviewers' => $reviewers->count(),
        ]);

        $matches = collect();

        foreach ($reviewers as $reviewer) {
            // Check institution conflict first (if conflict, skip)
            if ($this->hasInstitutionConflict($reviewer, $paperData['author_institutions'])) {
                continue;
            }

            // Calculate match score
            $matchResult = $this->calculateMatch($paperData, $reviewer);

            // If search is provided, include reviewers even with 0 score (they match search criteria)
            // Otherwise, only include reviewers with score > 0
            if (empty($search) && $matchResult['score'] <= 0) {
                continue;
            }

            // Check if already assigned
            $isAssigned = ClientOrderAssignee::where('order_id', $orderId)
                ->where('assigned_to', $reviewer->id)
                ->exists();

            $matches->push([
                'reviewer' => $reviewer,
                'score' => $matchResult['score'],
                'match_level' => $this->getMatchLevel($matchResult['score']),
                'match_details' => $matchResult['details'],
                'is_assigned' => $isAssigned,
            ]);
        }

        // Sort by score (highest first), then by name if scores are equal
        return $matches->sortBy(function($match) {
            return [-$match['score'], $match['reviewer']->name];
        })->values();
    }

    /**
     * Extract paper data for matching
     */
    private function extractPaperData(ClientOrderSubmission $submission): array
    {
        // Get keywords - try multiple sources
        $keywordString = $submission->article_keywords ?? '';

        // If article_keywords is empty, try to extract from title/abstract as fallback
        if (empty($keywordString)) {
            Log::warning('ReviewerMatching: No article_keywords found for submission ' . $submission->id);
        }

        $keywords = $this->extractKeywords($keywordString);

        // Get journal and its subjects
        $journal = $submission->journal;
        $journalSubjects = collect();
        if ($journal) {
            $journalSubjects = $journal->subjects()->pluck('name')->map('strtolower');
        }

        // Get author institutions
        $authorInstitutions = $this->extractAuthorInstitutions($submission);

        Log::info('ReviewerMatching: Extracted paper data', [
            'keywords_count' => count($keywords),
            'keywords' => $keywords,
            'journal_subjects_count' => $journalSubjects->count(),
        ]);

        return [
            'journal_id' => $submission->journal_id,
            'journal_title' => $journal->title ?? '',
            'journal_subjects' => $journalSubjects,
            'article_type_id' => $submission->article_type_id,
            'keywords' => $keywords,
            'title' => $submission->article_title ?? '',
            'abstract' => $submission->article_abstract ?? '',
            'author_institutions' => $authorInstitutions,
        ];
    }

    /**
     * Extract keywords from string
     */
    private function extractKeywords(?string $keywordString): array
    {
        if (empty($keywordString)) {
            return [];
        }

        $keywords = preg_split('/[,;]+/', $keywordString);
        $keywords = array_map('trim', $keywords);
        $keywords = array_map('strtolower', $keywords);
        $keywords = array_filter($keywords);

        return array_values($keywords);
    }

    /**
     * Extract author institutions from submission
     */
    private function extractAuthorInstitutions(ClientOrderSubmission $submission): array
    {
        $institutions = [];

        $authors = AuthorDetails::where('client_order_submission_id', $submission->id)->get();

        foreach ($authors as $author) {
            $affiliations = is_string($author->affiliation)
                ? json_decode($author->affiliation, true)
                : $author->affiliation;

            if (is_array($affiliations)) {
                foreach ($affiliations as $affiliation) {
                    if (is_array($affiliation)) {
                        // Handle nested array structure
                        $institution = implode(' ', array_values($affiliation));
                    } else {
                        $institution = $affiliation;
                    }

                    if (!empty($institution)) {
                        $institutions[] = strtolower(trim($institution));
                    }
                }
            }
        }

        return array_unique($institutions);
    }

    /**
     * Check if reviewer has institution conflict with authors
     */
    private function hasInstitutionConflict(User $reviewer, array $authorInstitutions): bool
    {
        $reviewerInstitution = strtolower(trim($reviewer->userDetail->basic_company ?? ''));

        if (empty($reviewerInstitution)) {
            return false;
        }

        foreach ($authorInstitutions as $authorInstitution) {
            // Check for exact match or partial match
            if (
                $reviewerInstitution === $authorInstitution ||
                strpos($reviewerInstitution, $authorInstitution) !== false ||
                strpos($authorInstitution, $reviewerInstitution) !== false
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate match score between paper and reviewer
     */
    private function calculateMatch(array $paperData, User $reviewer): array
    {
        $score = 0;
        $details = [
            'keyword_matches' => 0,
            'journal_match' => false,
            'subject_area_match' => false,
            'matched_keywords' => [],
        ];

        // Get reviewer data
        $reviewerKeywords = $this->getReviewerKeywords($reviewer);
        $reviewerSubjects = $this->getReviewerSubjects($reviewer);

        if (empty($reviewerKeywords) && empty($reviewerSubjects)) {
            return ['score' => 0, 'details' => $details];
        }

        // 1. Journal/Subject Area Match (30 points)
        if ($paperData['journal_subjects']->isNotEmpty() && $reviewerSubjects->isNotEmpty()) {
            $subjectMatches = $paperData['journal_subjects']->intersect($reviewerSubjects);
            if ($subjectMatches->isNotEmpty()) {
                $score += 30;
                $details['journal_match'] = true;
                $details['subject_area_match'] = true;
                $details['matched_subjects'] = $subjectMatches->values()->toArray();
            }
        }

        // 2. Keyword Matching (50 points max)
        if (!empty($paperData['keywords']) && !empty($reviewerKeywords)) {
            // Exact matches
            $exactMatches = array_intersect($paperData['keywords'], $reviewerKeywords);
            $exactMatchCount = count($exactMatches);

            if ($exactMatchCount > 0) {
                $score += min($exactMatchCount * 10, 40);
                $details['keyword_matches'] = $exactMatchCount;
                $details['matched_keywords'] = array_values($exactMatches);
            }

            // Partial matches in title/abstract (10 points max)
            $textContent = strtolower(($paperData['title'] ?? '') . ' ' . ($paperData['abstract'] ?? ''));
            $partialMatches = 0;
            foreach ($reviewerKeywords as $keyword) {
                if (strlen($keyword) > 3 && strpos($textContent, $keyword) !== false) {
                    $partialMatches++;
                }
            }
            $score += min($partialMatches * 2, 10);
        } else {
            // Log why keyword matching didn't run
            if (empty($paperData['keywords'])) {
                Log::debug('ReviewerMatching: No paper keywords for matching');
            }
            if (empty($reviewerKeywords)) {
                Log::debug('ReviewerMatching: Reviewer ' . $reviewer->id . ' has no keywords for matching');
            }
        }

        // 3. Field of Study Match (20 points)
        if (!empty($reviewer->field_of_study)) {
            $reviewerField = strtolower($reviewer->field_of_study);
            $textContent = strtolower(($paperData['title'] ?? '') . ' ' . ($paperData['abstract'] ?? ''));

            if (strpos($textContent, $reviewerField) !== false) {
                $score += 20;
                $details['field_match'] = true;
            }
        }

        // Cap at 100
        $score = min($score, 100);

        return [
            'score' => round($score, 2),
            'details' => $details,
        ];
    }

    /**
     * Get reviewer keywords
     */
    private function getReviewerKeywords(User $reviewer): array
    {
        $keywords = is_array($reviewer->expertise_keywords)
            ? $reviewer->expertise_keywords
            : json_decode($reviewer->expertise_keywords ?? '[]', true);

        if (!is_array($keywords)) {
            Log::debug('ReviewerMatching: Reviewer ' . $reviewer->id . ' has no expertise_keywords or invalid format');
            return [];
        }

        $normalized = array_map('strtolower', array_filter($keywords));

        if (empty($normalized)) {
            Log::debug('ReviewerMatching: Reviewer ' . $reviewer->id . ' has empty keywords after filtering');
        }

        return $normalized;
    }

    /**
     * Get reviewer subject areas
     */
    private function getReviewerSubjects(User $reviewer): Collection
    {
        $subjects = is_array($reviewer->subject_areas)
            ? $reviewer->subject_areas
            : json_decode($reviewer->subject_areas ?? '[]', true);

        if (!is_array($subjects)) {
            return collect();
        }

        return collect($subjects)->map('strtolower');
    }

    /**
     * Get match level description
     */
    private function getMatchLevel(float $score): string
    {
        if ($score >= 80)
            return 'excellent';
        if ($score >= 60)
            return 'good';
        if ($score >= 40)
            return 'moderate';
        if ($score >= 20)
            return 'weak';
        return 'poor';
    }
}



