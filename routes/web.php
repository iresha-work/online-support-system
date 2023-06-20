<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminTicketController;

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

// submit ticket
Route::post('/submit-ticket', [TicketController::class, 'store']);
Route::post('/submit-ticket-reply', [TicketController::class, 'update']);
Route::get('/ticket-status/{token}', [TicketController::class, 'show']);
Route::get('/get-relate-tickets', [TicketController::class, 'show_relate_tickets']);


Route::get('/dashboard', [AdminTicketController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/ticket-list', [AdminTicketController::class, 'index'])->name('ticket.list');
    Route::get('/tickets-data', [AdminTicketController::class, 'getTickets'])->name('tickets.data');
    Route::get('/ticket-data', [AdminTicketController::class, 'getTicket'])->name('ticket.data');
    Route::get('/admin-ticket-status/{token}', [AdminTicketController::class, 'showTicket']);
    Route::get('/admin-get-relate-tickets', [AdminTicketController::class, 'showRelateTickets']);
    Route::post('/admin-submit-ticket-reply', [AdminTicketController::class, 'update']);
});

require __DIR__.'/auth.php';
