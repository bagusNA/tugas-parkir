<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('auth.login.post');

Route::middleware('auth')->prefix('/admin')->group(function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::controller(TicketController::class)->group(function () {
        Route::get('/ticket', 'index')->name('ticket.index');
        Route::post('/ticket/create', 'create')->name('ticket.create');
        Route::post('/ticket/finish', 'finishBySearch')->name('ticket.finishBySearch');
        Route::post('/ticket/{ticket}/finish', 'finish')->name('ticket.finish');
    });
});
