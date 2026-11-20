<?php

use App\Http\Controllers\AstronomicalObjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentCategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ObservationLocationController;
use App\Http\Controllers\ObservationSessionController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/search', SearchController::class)->name('search');

Route::get('sessions/export', [ObservationSessionController::class, 'export'])->name('sessions.export');
Route::resource('sessions', ObservationSessionController::class);

Route::resource('astronomical-objects', AstronomicalObjectController::class);

Route::resource('locations', ObservationLocationController::class)
    ->parameters(['locations' => 'location']);

Route::resource('equipment-categories', EquipmentCategoryController::class)->except('show');
Route::resource('equipment', EquipmentController::class)->parameters(['equipment' => 'equipment']);
