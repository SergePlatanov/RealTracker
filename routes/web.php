<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnoController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/*
$proxy_scheme = getenv('PROXY_SCHEME');

if (!empty($proxy_scheme)) {
   URL::forceScheme($proxy_scheme);
}*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => true,//Route::has('login'),
        'canRegister' => Route::has('register'),
        'appVersion' => config('app.name') . ' ' . getAppVersion(),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::group(['middleware' => ['permission:reading']], function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/product/{id}', [ProductController::class, 'getProduct'])->name('product');
});

Route::group(['middleware' => ['permission:edit event']], function () {
    Route::resource('events', EventController::class)->only([
        'edit', 'create', 'update', 'store', 'destroy'
    ]);
});

Route::middleware(['auth', 'role:admin,super user'])->group(function () {
    Route::resource('users', UsersController::class);
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);
});

/*
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
require __DIR__.'/auth.php';
