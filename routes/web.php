<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnoController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\DashboardController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('products', ProductController::class);

Route::get('/product/{id}', [ProductController::class, 'getProduct'])->name('product');

Route::get('/service', [ServiceController::class, 'index'])->name('service');

/*
Route::get('/events/{id}', [ServiceController::class, 'EventEdit'])->name('events.edit');
Route::get('/events/create', [ServiceController::class, 'EventCreate'])->name('events.create');
Route::patch('/events', [ServiceController::class, 'EventUpdate'])->name('events.update');
Route::post('/events', [ServiceController::class, 'EventStore'])->name('events.store');
Route::delete('/events/{id}', [ServiceController::class, 'EventDestroy'])->name('events.destroy');
*/
Route::resource('technos', TechnoController::class)->only([
    'edit', 'create', 'update', 'store', 'destroy'
]);

Route::resource('status', StatusController::class)->only([
    'edit', 'create', 'update', 'store', 'destroy'
]);

Route::resource('events', EventsController::class)->only([
    'edit', 'create', 'update', 'store', 'destroy'
]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
