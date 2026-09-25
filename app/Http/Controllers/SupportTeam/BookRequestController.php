<?php
namespace App\Http\Controllers\SupportTeam;

use App\Book;
use App\BookRequest;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class BookRequestController extends Controller
{
    public function index()
    {
        return view('pages.support_team.library.requests.index', ['requests' => BookRequest::with(['book', 'user'])->latest()->get()]);
    }

    public function create()
    {
        return view('pages.support_team.library.requests.form', ['requestRecord' => new BookRequest(), 'books' => Book::orderBy('name')->get(), 'users' => User::whereIn('user_type', ['student', 'teacher'])->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['book_id' => 'required|exists:books,id', 'user_id' => 'required|exists:users,id', 'start_date' => 'required|date', 'end_date' => 'required|date|after_or_equal:start_date', 'status' => 'nullable|in:pending,approved,returned,rejected']);
        BookRequest::create($data + ['returned' => '0']);
        return redirect()->route('book_requests.index')->with('flash_success', 'Book request recorded successfully.');
    }

    public function show(BookRequest $bookRequest)
    {
        return view('pages.support_team.library.requests.show', ['requestRecord' => $bookRequest->load(['book', 'user'])]);
    }

    public function edit(BookRequest $bookRequest)
    {
        return view('pages.support_team.library.requests.form', ['requestRecord' => $bookRequest, 'books' => Book::orderBy('name')->get(), 'users' => User::whereIn('user_type', ['student', 'teacher'])->orderBy('name')->get()]);
    }

    public function update(Request $request, BookRequest $bookRequest)
    {
        $data = $request->validate(['book_id' => 'required|exists:books,id', 'user_id' => 'required|exists:users,id', 'start_date' => 'required|date', 'end_date' => 'required|date|after_or_equal:start_date', 'status' => 'nullable|in:pending,approved,returned,rejected']);
        $bookRequest->update($data + ['returned' => $data['status'] === 'returned' ? '1' : '0']);
        return redirect()->route('book_requests.index')->with('flash_success', 'Book request updated successfully.');
    }

    public function destroy(BookRequest $bookRequest)
    {
        $bookRequest->delete();
        return redirect()->route('book_requests.index')->with('flash_success', 'Book request deleted successfully.');
    }
}
