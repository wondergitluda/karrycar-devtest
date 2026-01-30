<?php

use App\Http\Controllers\ReferentsController;
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
         * Shipments Routes
         */
        Route::get('/shipments', [ShipmentsController::class, 'index'])->name('shipments.index');
        Route::get('/shipments/{shipment}/show', [ShipmentsController::class, 'show'])->name('shipments.show');
        Route::post('/shipments/{shipment}/addReferent', [ShipmentsController::class, 'addReferent'])->name('shipments.add_referent');
        Route::delete('/shipments/{shipment}/removeReferent/{pivotId}', [ShipmentsController::class, 'removeReferent'])->name('shipments.remove_referent');
        Route::delete('/shipments/{shipment}', [ShipmentsController::class, 'destroy'])->name('shipments.destroy');
        /**
         * Referents Routes
         */
        Route::get('/referents', [ReferentsController::class, 'index'])->name('referents.index');
        Route::get('/referents/{referent}/show', [ReferentsController::class, 'show'])->name('referents.show');
        Route::post('/referents/{referent}/addReferent', [ReferentsController::class, 'addReferent'])->name('referents.add_referent');
    });

require __DIR__.'/settings.php';
