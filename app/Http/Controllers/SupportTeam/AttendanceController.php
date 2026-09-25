<?php
namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT');
    }

    public function index()
    {
        return view('pages.support_team.attendance.index', ['attendance' => Attendance::with('student')->latest('attendance_date')->latest()->take(200)->get()]);
    }

    public function create()
    {
        return view('pages.support_team.attendance.form', ['attendance' => new Attendance(), 'students' => User::where('user_type', 'student')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['student_id' => 'required|exists:users,id', 'attendance_date' => 'required|date', 'status' => 'required|in:present,absent,late,excused', 'remarks' => 'nullable|string|max:255']);
        Attendance::updateOrCreate(['student_id' => $data['student_id'], 'attendance_date' => $data['attendance_date']], $data);
        return redirect()->route('attendance.index')->with('flash_success', 'Attendance saved successfully.');
    }

    public function edit(Attendance $attendance)
    {
        return view('pages.support_team.attendance.form', ['attendance' => $attendance, 'students' => User::where('user_type', 'student')->orderBy('name')->get()]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate(['student_id' => 'required|exists:users,id', 'attendance_date' => 'required|date', 'status' => 'required|in:present,absent,late,excused', 'remarks' => 'nullable|string|max:255']);
        $attendance->update($data);
        return redirect()->route('attendance.index')->with('flash_success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')->with('flash_success', 'Attendance deleted successfully.');
    }
}
