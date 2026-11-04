<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FaqController extends Controller
{
    /**
     * Display a listing of active FAQs, optionally filtered by topic slug.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $faqs = Faq::query()
            ->with('topic')
            ->active()
            ->when($request->filled('topic'), function ($query) use ($request) {
                $query->whereHas('topic', function ($q) use ($request) {
                    $q->where('slug', $request->input('topic'));
                });
            })
            ->ordered()
            ->get();

        return FaqResource::collection($faqs);
    }
}

