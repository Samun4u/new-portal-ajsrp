<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\ReviewerMatchingService;
use App\Models\ClientOrder;
use App\Models\ClientOrderSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewerMatchingController extends Controller
{
    protected $matchingService;

    public function __construct(ReviewerMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Get smart reviewer recommendations for a paper
     */
    public function getRecommendations(Request $request)
    {
        $orderId = $request->input('order_id');
        $search = $request->input('search', ''); // Search term for name, email, specialty, keywords

        // Get the order
        $order = ClientOrder::find($orderId);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Get the submission
        $submission = ClientOrderSubmission::with(['journal.subjects', 'article_type'])
            ->where('client_order_id', $order->order_id)
            ->first();

        if (!$submission) {
            return response()->json(['error' => 'Submission not found'], 404);
        }

        // Debug: Log submission data
        Log::info('Reviewer Matching Debug', [
            'submission_id' => $submission->id,
            'article_keywords' => $submission->article_keywords,
            'article_title' => $submission->article_title,
            'journal_id' => $submission->journal_id,
            'search' => $search,
        ]);

        // Get matched reviewers with search filter
        $matches = $this->matchingService->getMatchedReviewers(
            $submission,
            $order->id,
            auth()->user()->tenant_id,
            $search
        );

        // Format response
        $recommendations = $matches->map(function ($match) {
            $reviewer = $match['reviewer'];
            $details = $match['match_details'];

            // Build match notes
            $notes = [];

            if ($details['journal_match']) {
                $notes[] = __('Journal match');
            }

            if ($details['keyword_matches'] > 0) {
                $notes[] = __('Matched :count keywords', ['count' => $details['keyword_matches']]);
            }

            if (isset($details['subject_area_match']) && $details['subject_area_match']) {
                $notes[] = __('Subject area match');
            }

            if (isset($details['field_match']) && $details['field_match']) {
                $notes[] = __('Field of study match');
            }

            return [
                    'id' => $reviewer->id,
                    'name' => $reviewer->name,
                    'email' => $reviewer->email,
                'institution' => $reviewer->userDetail->basic_company ?? 'N/A',
                'field_of_study' => $reviewer->field_of_study ?? '',
                'subject_areas' => $reviewer->subject_areas ?? [],
                'experience_years' => $reviewer->experience_years ?? 0,
                'match_score' => $match['score'],
                'match_level' => $match['match_level'],
                'match_notes' => $notes,
                'matched_keywords' => $details['matched_keywords'] ?? [],
                'is_assigned' => $match['is_assigned'],
            ];
        });

        return response()->json([
            'success' => true,
            'paper' => [
                'title' => $submission->article_title,
                'journal' => $submission->journal->title ?? '',
                'keywords' => $this->extractKeywords($submission->article_keywords ?? ''),
            ],
            'reviewers' => $recommendations->values(),
            'total_found' => $recommendations->count(),
        ]);
    }

    /**
     * Extract keywords from string (helper for response)
     */
    private function extractKeywords(?string $keywordString): array
    {
        if (empty($keywordString)) {
            return [];
        }

        $keywords = preg_split('/[,;]+/', $keywordString);
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords);

        return array_values($keywords);
    }
}

