<?php

use App\Http\Controllers\NodeController;
use Illuminate\Support\Facades\Route;

Route::get('nodes/{id}', [NodeController::class, 'show']);
Route::post('add-node', [NodeController::class, 'addNode']);
Route::post('update-parent-node', [NodeController::class, 'updateParentNode']);
