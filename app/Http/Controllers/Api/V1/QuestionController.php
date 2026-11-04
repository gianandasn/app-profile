<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    /**
     * Display a listing of answered questions published for the public.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->input('per_page', 15), 50);

        $questions = Question::query()
            ->with([
                'topic',
                'answers' => function ($query) {
                    $query->where('is_published', true)
                        ->with('user')
                        ->latest('answered_at');
                },
            ])
            ->where('status', 'answered')
            ->whereHas('answers', function ($query) {
                $query->where('is_published', true);
            })
            ->when($request->filled('topic'), function ($query) use ($request) {
                $query->whereHas('topic', function ($q) use ($request) {
                    $q->where('slug', $request->input('topic'));
                });
            })
            ->latest()
            ->paginate($perPage);

        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created question submitted by a citizen.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['required', 'exists:question_topics,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $validated['status'] = 'pending';
        $validated['ip_address'] = $request->ip();

        $question = Question::create($validated);
        $question->load('topic');

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim dan akan segera ditinjau oleh petugas.',
            'data' => new QuestionResource($question),
        ], 201);
    }
}

