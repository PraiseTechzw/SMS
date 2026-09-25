@extends('layouts.master')
@section('page_title', $book->name)
@section('content')
<div class="card"><div class="card-header header-elements-inline"><h5 class="card-title">{{ $book->name }}</h5><a href="{{ route('books.edit', $book) }}" class="btn btn-primary">Edit</a></div><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Author</dt><dd class="col-sm-9">{{ $book->author ?: '—' }}</dd><dt class="col-sm-3">Type</dt><dd class="col-sm-9">{{ $book->book_type ?: '—' }}</dd><dt class="col-sm-3">Class</dt><dd class="col-sm-9">{{ optional($book->my_class)->name ?: 'General' }}</dd><dt class="col-sm-3">Copies</dt><dd class="col-sm-9">{{ $book->total_copies ?: 0 }} total / {{ $book->issued_copies ?: 0 }} issued</dd><dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $book->description ?: '—' }}</dd></dl></div></div>
@endsection
