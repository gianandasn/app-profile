<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\QuestionController;
use App\Http\Controllers\Api\V1\QuestionTopicController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\SlideController;
use App\Http\Middleware\SetApiLocale;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - V1
|--------------------------------------------------------------------------
|
| REST API endpoints designed to be consumed by the Flutter frontend.
|
*/

Route::prefix('v1')->middleware(SetApiLocale::class)->group(function () {
    // CMS Endpoints
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);
    Route::get('pages/{slug}', [PageController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('slides', [SlideController::class, 'index']);
    Route::get('menus/{location}', [MenuController::class, 'show']);
    Route::get('settings', [SettingController::class, 'index']);

    // Citizen Questions & FAQ Endpoints
    Route::get('question-topics', [QuestionTopicController::class, 'index']);
    Route::get('questions', [QuestionController::class, 'index']);
    Route::post('questions', [QuestionController::class, 'store'])->middleware('throttle:5,1');
    Route::get('faqs', [FaqController::class, 'index']);
});

