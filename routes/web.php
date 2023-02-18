<?php

use App\Http\Controllers\LickController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\SharedLickController;
use App\Http\Controllers\ShareTargetController;
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

Route::middleware(['guest'])->get('/', function () {
    return Inertia::render('LandingPage', [
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
    Route::put('/user/notification-settings', [NotificationSettingsController::class, 'update'])
        ->name('notification-settings.update');

    Route::get('/dashboard', function () {
        return Inertia::render('DashboardPage');
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

    Route::prefix('shared')->name('shared.')->group(function () {
        Route::get('/', [SharedLickController::class, 'index'])->name('index');
        Route::post('/{lick}', [SharedLickController::class, 'create'])->name('create');
    });

    Route::get('/share-targets', ShareTargetController::class)->name('share-targets');
});
