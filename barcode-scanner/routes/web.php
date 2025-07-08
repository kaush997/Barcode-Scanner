<?php

use App\Http\Controllers\BarcodeController;

Route::get('/', [BarcodeController::class, 'index']);
Route::post('/save', [BarcodeController::class, 'store'])->name('barcode.store');

