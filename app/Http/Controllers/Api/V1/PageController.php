<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Display the specified page by slug.
     */
    public function show(string $slug): PageResource
    {
        $page = Page::query()
            ->published()
            ->where(function ($q) use ($slug) {
                $q->where('slug->' . app()->getLocale(), $slug)
                    ->orWhere('slug->id', $slug)
                    ->orWhere('slug->en', $slug);
            })
            ->firstOrFail();

        return new PageResource($page);
    }
}

