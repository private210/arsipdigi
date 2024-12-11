<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FileDownloadController;

Route::get('/download-image/{id}', [ImageController::class, 'download'])->name('download.images');
Route::get('/download/{resource}/{id}', [FileDownloadController::class, 'download'])->name('download.file');

Route::get('/', function () {
    return redirect('/admin/login');
});
