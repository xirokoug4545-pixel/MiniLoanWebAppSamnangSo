<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RepaymentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->names('Categories');
    Route::resource('employees', EmployeeController::class);
});

Route::middleware(['auth', 'role:admin,loan_officer'])->group(function () {
    Route::resource('customers', CustomerController::class);
});

Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/schedules/{schedule}/pay', [RepaymentController::class, 'create'])->name('repayments.create');
    Route::post('/schedules/{schedule}/pay', [RepaymentController::class, 'store'])->name('repayments.store');
    Route::post('/loans/{loan}/repay', [RepaymentController::class, 'storeForLoan'])->whereNumber('loan')->name('loans.repay');
});
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin,loan_officer,cashier,customer'])->group(function () {
        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::get('/loans/{loan}', [LoanController::class, 'show'])->whereNumber('loan')->name('loans.show');
        Route::get('/loans/{loan}/schedule', [ScheduleController::class, 'show'])->whereNumber('loan')->name('loans.schedule');
        Route::get('/schedules/{loan}', [ScheduleController::class, 'show'])->name('schedules.show');
    });

    Route::middleware(['role:customer'])->group(function () {
        Route::get('/loans/apply', [LoanController::class, 'create'])->name('loans.create');
        Route::get('/loans/overdue', [LoanController::class, 'overdue'])->name('loans.overdue');
        Route::post('/loans/apply', [LoanController::class, 'store'])->name('loans.store');
    });

    // Admin & Loan Officer Routes
    Route::middleware(['role:admin,loan_officer'])->group(function () {
        Route::get('/loans/pending', [LoanController::class, 'pending'])->name('loans.pending');
    });

    Route::middleware(['role:admin,loan_officer'])->group(function () {
        Route::put('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');
        Route::get('/dashboard/overdue', [LoanController::class, 'overdueDashboard'])->name('dashboard.overdue');
    });
});