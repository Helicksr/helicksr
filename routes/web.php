<?php

use App\Http\Controllers\LickController;
use App\Http\Controllers\TagController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('tags', [TagController::class, 'index'])->name('tags');

    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', [LickController::class, 'index'])->name('index');
        Route::get('/create', [LickController::class, 'create'])->name('create');
        Route::post('/create', [LickController::class, 'store'])->name('store');
        Route::get('/{lick}', [LickController::class, 'show'])->name('show');
        Route::get('/{lick}/edit', [LickController::class, 'edit'])->name('edit');
        Route::put('/{lick}', [LickController::class, 'update'])->name('update');
        Route::delete('/{lick}', [LickController::class, 'destroy'])->name('destroy');
    });
});
