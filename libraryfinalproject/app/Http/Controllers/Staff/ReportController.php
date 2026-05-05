<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('staff.reports.index');
    }

    public function borrowings(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
        ]);

        $borrowings = Borrowing::with(['user', 'book'])
            ->when($request->from, fn($q) => $q->whereDate('borrow_date', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->whereDate('borrow_date', '<=', $request->to))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        $summary = [
            'total'    => $borrowings->count(),
            'returned' => $borrowings->where('status', 'returned')->count(),
            'overdue'  => $borrowings->where('status', 'overdue')->count(),
            'active'   => $borrowings->where('status', 'borrowed')->count(),
        ];

        return view('staff.reports.borrowings', compact('borrowings', 'summary'));
    }

    public function popularBooks()
    {
        $books = Book::withCount('borrowings')
            ->orderByDesc('borrowings_count')
            ->take(20)
            ->get();

        return view('staff.reports.popular-books', compact('books'));
    }

    public function activeMembers()
    {
        $members = \App\Models\User::where('role', 'member')
            ->withCount(['borrowings' => fn($q) => $q->where('status', 'borrowed')])
            ->having('borrowings_count', '>', 0)
            ->orderByDesc('borrowings_count')
            ->get();

        return view('staff.reports.active-members', compact('members'));
    }
}