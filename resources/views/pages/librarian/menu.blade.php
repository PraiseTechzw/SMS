<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}"><i class="icon-home4"></i> Library overview</a>
</li>
<li class="nav-item">
    <a href="{{ route('books.index') }}" class="nav-link {{ Route::is('books.*') ? 'active' : '' }}"><i class="icon-books"></i> Books</a>
</li>
<li class="nav-item">
    <a href="{{ route('book_requests.index') }}" class="nav-link {{ Route::is('book_requests.*') ? 'active' : '' }}"><i class="icon-list-unordered"></i> Book requests</a>
</li>
