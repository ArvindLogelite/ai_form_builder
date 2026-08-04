<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\FormSubmissionController;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\SubmissionController;

Route::get('/public/forms/{slug}', [FormController::class, 'publicForm']);

Route::apiResource('forms', FormController::class);

Route::post('/forms/{slug}/submit', [FormSubmissionController::class, 'store']);

Route::patch('/forms/{id}/status',[FormController::class, 'changeStatus']);

Route::post('/ai/generate', [AIController::class, 'generate']);

Route::post('/ai/edit', [AIController::class, 'edit']);

Route::post('/import', [ImportController::class, 'import']);

Route::get('/forms/{id}/submissions',[SubmissionController::class,'index']);

Route::get('/forms/{id}/submissions/export',[SubmissionController::class,'export']);

Route::post('/import/save', [ImportController::class, 'save']);