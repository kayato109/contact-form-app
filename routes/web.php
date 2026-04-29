<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/thanks', [ContactController::class, 'thanks']);

Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

Route::middleware('auth')->group(function () {

    // 管理画面
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/contacts/{contact}', [AdminController::class, 'show']);
    Route::delete('/admin/contacts/{contact}', [AdminController::class, 'destroy'])->name('contacts.destroy');

    // タグ管理
    Route::post('/admin/tags', [TagController::class, 'store']);
    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::put('/admin/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/admin/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

    // CSV エクスポート
    Route::get('/contacts/export', [ContactController::class, 'export']);
});
