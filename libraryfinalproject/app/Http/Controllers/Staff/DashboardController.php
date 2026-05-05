<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function index()
    {
        Borrowing::markOverdueRecords();

        $stats = [
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'overdue_count'     => Borrowing::where('status', 'overdue')->count(),
            'total_books'       => Book::count(),
            'returned_today'    => Borrowing::where('status', 'returned')->whereDate('return_date', today())->count(),
        ];

        $recentBorrowings = Borrowing::with(['user', 'book', 'issuedBy'])
            ->latest()
            ->take(10)
            ->get();

        return view('staff.dashboard', compact('stats', 'recentBorrowings'));
    }
}