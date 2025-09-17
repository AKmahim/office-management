<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\ContentController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CashInController;

use App\Http\Controllers\DownloadPageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});
// ================================================================ download page routes =================================================
Route::get('/find-content', [DownloadPageController::class, 'index'])->name('find-content');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================ dashboard route ============
    Route::get('/dashboard/content-statistics', [DashboardController::class, 'lastTenDaysContent']);
    Route::get('/dashboard/storage-statistics', [DashboardController::class, 'storageStatistics'])->name('dashboard.storage.statistics');


    // ============================ event management route ============
    Route::prefix('event')->controller(EventController::class)->group(function () {
        Route::get('/', 'index')->name('event.index');
        Route::get('/create', 'create')->name('event.create');
        Route::post('/', 'store')->name('event.store');
        // Route::get('/{event}', 'show')->name('event.show');
        Route::get('/edit/{id}', 'edit')->name('event.edit');
        // Route::patch('/{event}', 'update')->name('event.update');
        Route::get('/destroy/{id}', 'destroy')->name('event.destroy');
        //event statistics
        Route::get('/statistics', 'statistics')->name('event.statistics');
        Route::get('/statistics/filter', 'statisticsFilter')->name('event.statistics.filter');
    });

     // ============================ content management route ============
    Route::prefix('content')->controller(ContentController::class)->group(function () {
        Route::get('/', 'index')->name('content.index');
        // Route::get('/create', 'create')->name('event.create');
        // Route::post('/', 'store')->name('event.store');
        // // Route::get('/{event}', 'show')->name('event.show');
        // Route::get('/edit/{id}', 'edit')->name('event.edit');
        // // Route::patch('/{event}', 'update')->name('event.update');
        // Route::get('/destroy/{id}', 'destroy')->name('event.destroy');
    });

    // ============================ cash in management route ============
    Route::prefix('cashin')->controller(CashInController::class)->group(function () {
        Route::get('/', 'index')->name('cashin.index');
        Route::get('/create', 'create')->name('cashin.create');
        Route::post('/', 'store')->name('cashin.store');
        Route::get('/{id}', 'show')->name('cashin.show');
        Route::get('/edit/{id}', 'edit')->name('cashin.edit');
        Route::put('/{id}', 'update')->name('cashin.update');
        Route::delete('/{id}', 'destroy')->name('cashin.destroy');
        Route::get('/statistics/view', 'statistics')->name('cashin.statistics');
    });
});

require __DIR__.'/auth.php';


