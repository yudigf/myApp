<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Demo\DemoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::controller(DemoController::class)->group(function () {
    Route::get('/about', 'index')->name('about.page')->middleware('check');
    Route::get('/contact', 'contact')->name('contact.page')->middleware('contact');
});

// Grup route untuk DemoController
// Route::prefix('')->middleware('check')->group(function () {
//     Route::get('/about', [DemoController::class, 'index'])->name('about.page');
//     Route::get('/contact', [DemoController::class, 'contact'])->name('contact.page')->middleware('contact');
// });

//Admin All Route
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__.'/auth.php';
