<?php

use App\Models\Page;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $page = Page::query()->where('slug', 'glavnaya')->first();

    return view('pages.show', [
        'page' => $page,
        'title' => $page?->title ?? 'АО «ТомскАгроИнвест»',
    ]);
})->name('home');

Route::get('/{slug}', function (string $slug) {
    $page = Page::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

    return view('pages.show', [
        'page' => $page,
        'title' => $page->title,
    ]);
})->where('slug', '[A-Za-z0-9\-]+')->name('pages.show');
