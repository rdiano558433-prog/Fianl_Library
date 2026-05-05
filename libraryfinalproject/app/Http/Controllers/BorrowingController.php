<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'book'])->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        return view('borrowings.create', compact('books'));
    }

    public function store(Request $request)
    {
        // Add logic to store borrowing
    }

    public function show(Borrowing $borrowing)
    {
        return view('borrowings.show', compact('borrowing'));
    }

    public function returnBook(Borrowing $borrowing)
    {
        // Add logic to return book
    }

    public function borrow(Book $book)
    {
        // Add logic for user to borrow
    }

    public function myBooks()
    {
        $borrowings = Borrowing::where('user_id', auth()->id())->get();
        return view('user.my-books', compact('borrowings'));
    }
}