<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $borrowings = Borrowing::with(['user', 'book', 'issuedBy'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%{$request->search}%"))
                ->orWhereHas('book', fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('staff.borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->orderBy('name')->get();
        $books = Book::where('available_copies', '>', 0)->orderBy('title')->get();

        return view('staff.borrowings.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->available_copies < 1) {
            return back()->with('error', 'No available copies for this book.');
        }

        Borrowing::create([
            ...$validated,
            'issued_by'   => Auth::id(),
            'borrow_date' => today(),
            'status'      => 'borrowed',
        ]);

        $book->decrement('available_copies');

        return redirect()->route('staff.borrowings.index')
            ->with('success', 'Book issued successfully.');
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book', 'issuedBy']);
        return view('staff.borrowings.show', compact('borrowing'));
    }

    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return back()->with('error', 'This book has already been returned.');
        }

        $borrowing->update([
            'status'      => 'returned',
            'return_date' => today(),
        ]);

        $borrowing->book->increment('available_copies');

        return redirect()->route('staff.borrowings.index')
            ->with('success', 'Book returned successfully.');
    }

    public function overdue()
    {
        $overdues = Borrowing::with(['user', 'book'])
            ->where('status', 'overdue')
            ->latest()
            ->paginate(15);

        return view('staff.borrowings.overdue', compact('overdues'));
    }
}