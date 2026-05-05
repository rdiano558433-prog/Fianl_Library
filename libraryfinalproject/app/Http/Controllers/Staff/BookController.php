<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query()
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('author', 'like', "%{$request->search}%")
                ->orWhere('isbn', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('staff.books.index', compact('books'));
    }

    public function create()
    {
        return view('staff.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'isbn'        => 'required|unique:books,isbn',
            'copies'      => 'required|integer|min:1',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        Book::create($validated);

        return redirect()->route('staff.books.index')
            ->with('success', 'Book added successfully.');
    }

    public function show(Book $book)
    {
        $book->load('borrowings.user');
        return view('staff.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('staff.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'isbn'        => "required|unique:books,isbn,{$book->id}",
            'copies'      => 'required|integer|min:1',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return redirect()->route('staff.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        // Prevent deletion if book has active borrowings
        if ($book->borrowings()->whereIn('status', ['borrowed', 'overdue'])->exists()) {
            return back()->with('error', 'Cannot delete a book with active borrowings.');
        }

        $book->delete();

        return redirect()->route('staff.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}