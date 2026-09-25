<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Report Card - {{ $sr->user->name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/my_print.css') }}">
    <style>
        body { color:#172033; font-family: Arial, sans-serif; }
        .report-card { max-width: 1100px; margin: 0 auto; padding: 24px; }
        .report-header { display:flex; align-items:center; gap:18px; border-bottom:3px solid #18283f; padding-bottom:16px; }
        .report-header img { max-width:100px; max-height:100px; object-fit:contain; }
        .school-copy { flex:1; text-align:center; }
        .school-copy h1 { margin:0; color:#18283f; font-size:25px; }
        .school-copy p { margin:4px 0; }
        .student-photo { width:90px; height:90px; object-fit:cover; border-radius:10px; }
        .student-meta { display:grid; grid-template-columns:repeat(4, 1fr); gap:8px; margin:18px 0; }
        .meta-item { border:1px solid #d8dee9; padding:9px; }
        .meta-item small { display:block; color:#667085; text-transform:uppercase; font-size:10px; }
        table { width:100%; border-collapse:collapse; margin:14px 0; }
        th, td { border:1px solid #b7c0cf; padding:7px; text-align:center; }
        th { background:#eef2f7; color:#18283f; }
        td.subject { text-align:left; font-weight:bold; }
        .section-title { color:#18283f; margin:22px 0 6px; }
        .signature-row { display:grid; grid-template-columns:repeat(3, 1fr); gap:35px; margin-top:42px; }
        .signature { border-top:1px solid #172033; padding-top:6px; text-align:center; }
        .no-print { text-align:right; margin-bottom:12px; }
        @media print { .no-print { display:none; } .report-card { padding:0; } }
    </style>
</head>
<body>
<div class="report-card">
    <div class="no-print"><button onclick="window.print()">Print report card</button></div>
    <header class="report-header">
        <img src="{{ $school['logo'] ?? asset('global_assets/images/logo_light.png') }}" alt="School logo">
        <div class="school-copy">
            <h1>{{ strtoupper($school['system_name'] ?? Qs::getSystemName()) }}</h1>
            <p>{{ $school['address'] ?? '' }}</p>
            <strong>ACADEMIC REPORT CARD · {{ $year }}</strong>
            <p>{{ strtoupper($class_type->name) }} · {{ strtoupper($class_type->code) }}</p>
        </div>
        <img class="student-photo" src="{{ $sr->user->photo }}" alt="{{ $sr->user->name }}">
    </header>

    <section class="student-meta">
        <div class="meta-item"><small>Student</small>{{ strtoupper($sr->user->name) }}</div>
        <div class="meta-item"><small>Admission number</small>{{ $sr->adm_no }}</div>
        <div class="meta-item"><small>Class / section</small>{{ $my_class->name }} / {{ $sr->section->name }}</div>
        <div class="meta-item"><small>Grading profile</small>{{ in_array($class_type->code, ['U', 'PG']) ? 'UNIVERSITY' : 'ZIMSEC' }}</div>
    </section>

    <h3 class="section-title">Term summary</h3>
    <table>
        <thead><tr><th>Term</th><th>Assessment</th><th>Total</th><th>Average</th><th>Class average</th><th>Position</th></tr></thead>
        <tbody>
        @foreach($exams as $ex)
            @php($record = $exam_records->where('exam_id', $ex->id)->first())
            @if($record)
                <tr>
                    <td>Term {{ $ex->term }}</td>
                    <td>{{ $ex->name }}</td>
                    <td>{{ $record->total ?? '-' }}</td>
                    <td>{{ $record->ave ?? '-' }}</td>
                    <td>{{ $record->class_ave ?? '-' }}</td>
                    <td>{!! $record->pos ? Mk::getSuffix($record->pos) : '-' !!}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <h3 class="section-title">Subject performance</h3>
    <table>
        <thead>
        <tr><th rowspan="2">Subject</th>@foreach($exams as $ex)<th>Term {{ $ex->term }}</th>@endforeach<th rowspan="2">Year average</th><th rowspan="2">Final grade</th><th rowspan="2">Remark</th></tr>
        </thead>
        <tbody>
        @foreach($subjects as $sub)
            @php($subjectMarks = $marks->where('subject_id', $sub->id))
            @php($values = collect())
            <tr>
                <td class="subject">{{ $sub->name }}</td>
                @foreach($exams as $ex)
                    @php($mark = $subjectMarks->where('exam_id', $ex->id)->first())
                    @php($value = $mark ? $mark->{'tex'.$ex->term} : null)
                    @if($value !== null) @php($values->push($value)) @endif
                    <td>{{ $value !== null ? $value : '-' }}{{ $mark && $mark->grade ? ' · '.$mark->grade->name : '' }}</td>
                @endforeach
                @php($average = $values->count() ? round($values->avg(), 1) : null)
                @php($finalMark = $subjectMarks->sortByDesc('exam_id')->first())
                <td>{{ $average ?? '-' }}</td>
                <td>{{ $finalMark && $finalMark->grade ? $finalMark->grade->name : '-' }}</td>
                <td>{{ $finalMark && $finalMark->grade ? $finalMark->grade->remark : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="signature-row">
        <div class="signature">Class teacher</div>
        <div class="signature">Head of department</div>
        <div class="signature">Head teacher / registrar</div>
    </div>
</div>
<script>window.addEventListener('load', function () { window.print(); });</script>
</body>
</html>
