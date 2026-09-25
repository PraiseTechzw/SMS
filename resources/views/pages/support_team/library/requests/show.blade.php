@extends('layouts.master')
@section('page_title', 'Book Request')
@section('content')
<div class="card"><div class="card-header"><h5 class="card-title">Book request details</h5></div><div class="card-body"><p><strong>Book:</strong> {{ optional($requestRecord->book)->name }}</p><p><strong>Requester:</strong> {{ optional($requestRecord->user)->name }}</p><p><strong>Period:</strong> {{ $requestRecord->start_date }} to {{ $requestRecord->end_date }}</p><p><strong>Status:</strong> {{ ucfirst($requestRecord->status ?: 'pending') }}</p><a href="{{ route('book_requests.edit', $requestRecord) }}" class="btn btn-primary">Edit request</a></div></div>
@endsection
