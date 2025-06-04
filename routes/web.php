<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    Auth::logout(); // セッションを破棄してログアウト
    $request->session()->invalidate(); // セッションを無効化
    $request->session()->regenerateToken(); // CSRF保護のためトークン再生成

    return redirect()->route('login'); // ログイン画面にリダイレクト
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodoController::class, 'store']);
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
});

require __DIR__.'/auth.php';
