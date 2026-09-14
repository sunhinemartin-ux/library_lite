<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Role-Based Redirection Page
Route::get('/dashboard', function () {
    $role = Auth::user()->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'librarian') {
        return redirect()->route('librarian.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected Routes with Middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [UserController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware(['auth', 'role:librarian,admin'])->group(function () {
    Route::get('/librarian/dashboard', function () {
        return view('librarian-dashboard');
    })->name('librarian.dashboard');
});

Route::middleware(['auth', 'role:student,librarian,admin'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student-dashboard');
    })->name('student.dashboard');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';