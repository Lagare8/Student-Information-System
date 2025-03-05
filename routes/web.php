<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Public routes - no auth required
Route::get('/', function () {
    return view('welcome');
});

// Dashboard - requires auth and email verification
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes - requires auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes - requires auth and admin role
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Student Management
    Route::get('admin/students', [AdminController::class, 'students'])->name('admin.students');
    Route::post('admin/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
    Route::put('admin/students/{id}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
    Route::delete('admin/students/{id}', [AdminController::class, 'destroyStudent'])->name('admin.students.destroy');
    Route::post('admin/students/{id}/enroll', [AdminController::class, 'enrollStudent'])->name('admin.students.enroll');
    
    // Subject Management
    Route::get('admin/subject', [AdminController::class, 'subjects'])->name('admin.subject');
    Route::post('admin/subjects', [AdminController::class, 'storeSubject'])->name('admin.subjects.store');
    Route::delete('admin/subjects/{id}', [AdminController::class, 'destroySubject'])->name('admin.subjects.destroy');
    Route::put('admin/subjects/{id}', [AdminController::class, 'updateSubject'])->name('admin.subjects.update');
    
    // Grade Management
    Route::get('admin/grades', [AdminController::class, 'grades'])->name('admin.grades');
    Route::put('admin/grades/{enrollment}', [AdminController::class, 'updateGrades'])->name('admin.grades.update');
    
    // Enrollment Subject Management
    Route::delete('admin/enrollment/{enrollment}/subjects/{subject}', [AdminController::class, 'removeSubject'])
        ->name('admin.enrollment.removeSubject');
    Route::post('admin/enrollment/{enrollment}/subjects', [AdminController::class, 'addSubject'])
        ->name('admin.enrollment.addSubject');
});

// Auth routes (login, register, reset password)
require __DIR__.'/auth.php';
