<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\BulanController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\KegiatanController;
use App\Http\Controllers\Api\SubKegiatanController;

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return Inertia::render('AdminDashboard', [
        'user' => auth()->user(),
        'months' => [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
    ]);
})->name('admin.dashboard');

Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::middleware(['auth', 'role:user'])->get('/user', function () {
    return Inertia::render('UserDashboard');
})->name('user.dashboard');

Route::get('/add-month', function () {
    return Inertia::render('AddMonth');
})->name('add.month');



Route::resource('bulan', BulanController::class);
Route::resource('program', ProgramController::class);
Route::resource('kegiatan', KegiatanController::class);
Route::resource('sub_kegiatan', SubKegiatanController::class);

require __DIR__.'/auth.php';
