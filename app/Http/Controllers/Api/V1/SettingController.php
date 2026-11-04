<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SettingController extends Controller
{
    /**
     * Display a listing of settings, optionally filtered by group.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $settings = Setting::query()
            ->when($request->filled('group'), function ($query) use ($request) {
                $query->where('group', $request->input('group'));
            })
            ->get();

        return SettingResource::collection($settings);
    }
}

