<?php

use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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


Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');
    Artisan::call('optimize:clear');
    Artisan::call('storage:link');
    // Artisan::call('optimize');
    session()->flash('message', 'System Updated Successfully.');
    return redirect()->route('frontend.home');
});
    Route::post('/user-login', [HomeController::class, 'Userlogin'])->name('Userlogin');
    Route::get('/user-logout', [HomeController::class, 'userLogout'])->name('userLogout');
    Route::get('/', [HomeController::class, 'index']);
    Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/events', [HomeController::class, 'events'])->name('events');
    Route::get('/events-booking/{id}', [HomeController::class, 'eventsBookingByid'])->name('eventsBookingbyid');
    Route::post('/events-booking-store', [HomeController::class, 'store'])->name('store');
    Route::get('/mybookinglist', [HomeController::class, 'myBookingList'])->name('mybookinglist');
});
