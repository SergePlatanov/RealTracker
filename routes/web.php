<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnoController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('admin/login', function () {
    return redirect()->route('login');
})->name('filament.admin.auth.login'); // Сохраняем имя для обратной совместимости

require __DIR__.'/auth.php';
