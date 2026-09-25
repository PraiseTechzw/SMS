@extends('layouts.master')
@section('page_title', 'Parent Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header header-elements-inline"><h5 class="card-title"><i class="icon-home4 mr-2"></i>Parent dashboard</h5>{!! Qs::getPanelOptions() !!}</div>
            <div class="card-body">
                <div class="row">
                    @foreach($students as $student)
                        @php($records = $attendance->get($student->user_id, collect()))
                        @php($present = $records->where('status', 'present')->count())
                        @php($absent = $records->where('status', 'absent')->count())
                        @php($late = $records->where('status', 'late')->count())
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $student->user->photo }}" class="rounded-circle mr-2" style="width:48px;height:48px;object-fit:cover" alt="{{ $student->user->name }}">
                                    <div><h6 class="mb-0">{{ $student->user->name }}</h6><small class="text-muted">{{ $student->my_class->name }} {{ $student->section->name }}</small></div>
                                </div>
                                <div class="row text-center mb-3">
                                    <div class="col-4"><strong class="text-success d-block">{{ $present }}</strong><small>Present</small></div>
                                    <div class="col-4"><strong class="text-danger d-block">{{ $absent }}</strong><small>Absent</small></div>
                                    <div class="col-4"><strong class="text-warning d-block">{{ $late }}</strong><small>Late</small></div>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.show', Qs::hash($student->id)) }}" class="btn btn-light"><i class="icon-user mr-1"></i>Profile</a>
                                    <a href="{{ route('marks.year_selector', Qs::hash($student->user_id)) }}" class="btn btn-primary"><i class="icon-book mr-1"></i>Report cards</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($students->isEmpty())<div class="col-12"><div class="alert alert-info">No children are linked to your parent account yet.</div></div>@endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header header-elements-inline"><h5 class="card-title"><i class="icon-coins mr-2"></i>School fees balance history</h5>{!! Qs::getPanelOptions() !!}</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0"><thead><tr><th>Student</th><th>Fee item</th><th>Year</th><th>Paid</th><th>Balance</th><th>Status</th></tr></thead><tbody>
                @forelse($fees as $fee)
                    @php($outstanding = $fee->paid ? 0 : ($fee->balance ?: optional($fee->payment)->amount))
                    <tr><td>{{ optional($fee->student)->name }}</td><td>{{ optional($fee->payment)->title }}</td><td>{{ $fee->year }}</td><td>{{ number_format($fee->amt_paid ?: 0, 2) }}</td><td class="font-weight-bold {{ $outstanding > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($outstanding ?: 0, 2) }}</td><td><span class="badge {{ $fee->paid ? 'badge-success' : 'badge-warning' }}">{{ $fee->paid ? 'Cleared' : 'Outstanding' }}</span></td></tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No fee records are available yet.</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
        <div class="card">
            <div class="card-header header-elements-inline"><h5 class="card-title"><i class="icon-history mr-2"></i>Recent fee payments</h5>{!! Qs::getPanelOptions() !!}</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0"><thead><tr><th>Date</th><th>Student</th><th>Fee item</th><th>Amount paid</th><th>Balance after payment</th></tr></thead><tbody>
                @forelse($receiptHistory as $receipt)
                    <tr><td>{{ optional($receipt->created_at)->format('d M Y') }}</td><td>{{ $receipt->student_name }}</td><td>{{ $receipt->payment_title }}</td><td class="text-success">{{ number_format($receipt->amt_paid, 2) }}</td><td>{{ number_format($receipt->balance, 2) }}</td></tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No payment transactions are available yet.</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header header-elements-inline"><h5 class="card-title"><i class="icon-megaphone mr-2"></i>School notices</h5>{!! Qs::getPanelOptions() !!}</div>
            <div class="card-body">
                @forelse($notices as $notice)
                    <div class="border-bottom pb-3 mb-3"><h6 class="mb-1">{{ $notice->title }}</h6><small class="text-muted">{{ optional($notice->published_at)->format('d M Y') }}</small><p class="mt-2 mb-0">{{ $notice->body }}</p></div>
                @empty
                    <p class="text-muted mb-0">No current notices.</p>
                @endforelse
            </div>
        </div>
        <div class="card bg-primary text-white">
            <div class="card-body"><h5><i class="icon-info22 mr-2"></i>Dashboard guide</h5><p class="mb-0">Attendance is shown for the current calendar year. Fee balances include all recorded school-fee transactions linked to your children.</p></div>
        </div>
    </div>
</div>
@endsection
