<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }
    return redirect()->route('login'); 
})->name('home');

// Public book search
Route::get('/search', [BookController::class, 'search'])->name('books.search.public');

require __DIR__ . '/auth.php'; 

// Profile Routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/borrowings', [ReportController::class, 'borrowings'])->name('borrowings');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/overdue', [ReportController::class, 'overdue'])->name('overdue');
        Route::get('/user-activity', [ReportController::class, 'userActivity'])->name('user-activity');
        Route::get('/popular', [ReportController::class, 'popularBooks'])->name('popular');
    });
});

// Staff Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.staff.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');

    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.staff.show');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/borrowings', [ReportController::class, 'borrowings'])->name('borrowings');
        Route::get('/overdue', [ReportController::class, 'overdue'])->name('overdue');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    });
});

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        $borrowings = \App\Models\Borrowing::with('book')
            ->where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->get();
        $overdues = \App\Models\Borrowing::with('book')
            ->where('user_id', auth()->id())
            ->where('status', 'overdue')
            ->get();
        $history = \App\Models\Borrowing::with('book')
            ->where('user_id', auth()->id())
            ->latest()->take(5)->get();
        return view('user.dashboard', compact('borrowings', 'overdues', 'history'));
    })->name('dashboard');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.user.show');
    Route::post('/books/{book}/borrow', [BorrowingController::class, 'borrow'])->name('books.borrow');
    Route::get('/my-books', [BorrowingController::class, 'myBooks'])->name('my-books');
});

// Add a generic dashboard route that redirects based on role
Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route(auth()->user()->role . '.dashboard');
})->name('dashboard');
