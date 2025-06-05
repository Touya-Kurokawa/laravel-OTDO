<?php

use Illuminate\Support\Facades\Route;

// 例: 認証済みユーザーのみにアクセス許可
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});
