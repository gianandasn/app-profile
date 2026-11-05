<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Display a listing of published posts.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->input('per_page', 15), 50);

        $posts = Post::query()
            ->with(['category', 'user'])
            ->published()
            ->when($request->filled('category'), function ($query) use ($request) {
                $cat = $request->input('category');
                $query->whereHas('category', function ($q) use ($cat) {
                    $q->where('slug->' . app()->getLocale(), $cat)
                        ->orWhere('slug->id', $cat)
                        ->orWhere('slug->en', $cat);
                });
            })
            ->when($request->has('featured'), function ($query) use ($request) {
                $query->where('is_featured', filter_var($request->input('featured'), FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title->' . app()->getLocale(), 'like', "%{$search}%")
                        ->orWhere('title->id', 'like', "%{$search}%")
                        ->orWhere('title->en', 'like', "%{$search}%")
                        ->orWhere('summary->' . app()->getLocale(), 'like', "%{$search}%")
                        ->orWhere('summary->id', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->paginate($perPage);

        return PostResource::collection($posts);
    }

    /**
     * Display the specified post by slug.
     */
    public function show(string $slug): PostResource
    {
        $post = Post::query()
            ->with(['category', 'user'])
            ->published()
            ->where(function ($q) use ($slug) {
                $q->where('slug->' . app()->getLocale(), $slug)
                    ->orWhere('slug->id', $slug)
                    ->orWhere('slug->en', $slug);
            })
            ->firstOrFail();

        $post->increment('view_count');

        return new PostResource($post);
    }
}

