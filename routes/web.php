<?php

use App\Http\Controllers\OpenAIController;
use Illuminate\Support\Facades\Route;

Route::get('/',[OpenAIController::class, 'index'])->name('index');

Route::post('/ai', [OpenAIController::class, 'ai'])->name('ai');
