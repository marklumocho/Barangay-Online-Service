<?php
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApplicationController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Applications
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::patch('/applications/{application}/ready', [ApplicationController::class, 'markReady'])->name('applications.ready');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    // Resident registry (staff only)
    Route::post('/residents', [ResidentController::class, 'store'])->name('residents.store');
    Route::delete('/residents/{barangayResident}', [ResidentController::class, 'destroy'])->name('residents.destroy');

    // Staff accounts (staff only)
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
});