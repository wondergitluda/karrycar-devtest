<?php

use App\Http\Controllers\ShipmentReferentController;
use App\Http\Controllers\ShipmentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        /**
         * Shipments
         */
        Route::resource('shipments', ShipmentsController::class)->only(['index', 'show', 'destroy']);

        /**
         * Shipment Referents
         */
        Route::resource('shipments.referents', ShipmentReferentController::class)->only(['index', 'store', 'destroy']);
    });

require __DIR__.'/settings.php';
