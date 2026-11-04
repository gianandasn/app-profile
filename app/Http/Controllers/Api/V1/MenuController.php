<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a menu by its location (header, footer, sidebar) with nested active items.
     */
    public function show(string $location): MenuResource
    {
        $menu = Menu::query()
            ->active()
            ->byLocation($location)
            ->with([
                'items' => function ($query) {
                    $query->roots()
                        ->active()
                        ->ordered()
                        ->with([
                            'children' => function ($childQuery) {
                                $childQuery->active()
                                    ->ordered()
                                    ->with([
                                        'children' => fn ($subChildQuery) => $subChildQuery->active()->ordered(),
                                    ]);
                            },
                        ]);
                },
            ])
            ->firstOrFail();

        return new MenuResource($menu);
    }
}

