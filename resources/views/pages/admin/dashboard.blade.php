@extends('layouts.master')
@section('page_title', 'My Dashboard')

@section('content')
    <div class="card card-body">
        <h3 class="mb-1">Administration dashboard</h3>
        <p class="text-muted mb-0">Your live administration dashboard is available at <a href="{{ route('dashboard') }}">Dashboard</a>, with student, staff, class, subject, and exam overview metrics.</p>
    </div>
    @endsection
