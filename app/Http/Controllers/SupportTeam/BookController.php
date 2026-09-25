<?php
namespace App\Http\Controllers\SupportTeam;

use App\Book;
use App\Http\Controllers\Controller;
use App\Models\MyClass;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('pages.support_team.library.books.index', ['books' => Book::with('my_class')->latest()->get()]);
    }

    public function create()
    {
        return view('pages.support_team.library.books.form', ['book' => new Book(), 'classes' => MyClass::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'author' => 'nullable|string|max:100', 'book_type' => 'nullable|string|max:100', 'description' => 'nullable|string', 'location' => 'nullable|string|max:100', 'url' => 'nullable|url|max:255', 'my_class_id' => 'nullable|exists:my_classes,id', 'total_copies' => 'required|integer|min:0', 'issued_copies' => 'nullable|integer|min:0|lte:total_copies']);
        Book::create($data);
        return redirect()->route('books.index')->with('flash_success', 'Book added successfully.');
    }

    public function show(Book $book)
    {
        return view('pages.support_team.library.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('pages.support_team.library.books.form', ['book' => $book, 'classes' => MyClass::orderBy('name')->get()]);
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'author' => 'nullable|string|max:100', 'book_type' => 'nullable|string|max:100', 'description' => 'nullable|string', 'location' => 'nullable|string|max:100', 'url' => 'nullable|url|max:255', 'my_class_id' => 'nullable|exists:my_classes,id', 'total_copies' => 'required|integer|min:0', 'issued_copies' => 'nullable|integer|min:0|lte:total_copies']);
        $book->update($data);
        return redirect()->route('books.index')->with('flash_success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('flash_success', 'Book deleted successfully.');
    }
}
