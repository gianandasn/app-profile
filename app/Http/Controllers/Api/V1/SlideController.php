<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SlideResource;
use App\Models\Slide;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SlideController extends Controller
{
    /**
     * Display a listing of active and currently scheduled slides.
     */
    public function index(): AnonymousResourceCollection
    {
        $today = now()->toDateString();

        $slides = Slide::query()
            ->active()
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            })
            ->ordered()
            ->get();

        return SlideResource::collection($slides);
    }
}

