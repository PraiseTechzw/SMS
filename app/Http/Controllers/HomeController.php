<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Models\Attendance;
use App\Book;
use App\BookRequest;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\MyClass;
use App\Models\PaymentRecord;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\TimeTable;
use App\Repositories\MyClassRepo;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $user, $student, $class, $payment;
    public function __construct(UserRepo $user, StudentRepo $student, MyClassRepo $class, PaymentRepo $payment)
    {
        $this->user = $user;
        $this->student = $student;
        $this->class = $class;
        $this->payment = $payment;
    }


    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {
        $role = Qs::getUserType();
        if($role === 'parent') { return redirect()->route('parent.dashboard'); }
        if($role === 'student') { return $this->studentDashboard(); }
        if($role === 'teacher') { return $this->teacherDashboard(); }
        if($role === 'accountant') { return $this->accountantDashboard(); }
        if($role === 'librarian') { return $this->librarianDashboard(); }
        return $this->administrationDashboard($role);
    }

    protected function administrationDashboard($role)
    {
        $users = $this->user->getAll();
        return view('pages.role_dashboards.administration', [
            'role' => $role,
            'users' => $users,
            'students' => StudentRecord::where('grad', 0)->count(),
            'classes' => MyClass::count(),
            'subjects' => Subject::count(),
            'parents' => $users->where('user_type', 'parent')->count(),
            'teachers' => $users->where('user_type', 'teacher')->count(),
            'recent_students' => StudentRecord::with(['user', 'my_class'])->latest()->take(6)->get(),
            'session' => Qs::getCurrentSession(),
        ]);
    }

    protected function accountantDashboard()
    {
        $session = Qs::getCurrentSession();
        $records = PaymentRecord::where('year', $session)->with('payment')->get();
        return view('pages.role_dashboards.accountant', [
            'session' => $session,
            'payments' => $this->payment->getActivePayments()->get(),
            'records' => $records,
            'invoiced' => $records->sum(function ($record) { return optional($record->payment)->amount ?: 0; }),
            'collected' => $records->sum('amt_paid'),
            'outstanding' => $records->sum(function ($record) { return $record->paid ? 0 : ($record->balance ?: optional($record->payment)->amount); }),
        ]);
    }

    protected function teacherDashboard()
    {
        $subjects = $this->class->findSubjectByTeacher(Auth::user()->id);
        $sections = Section::where('teacher_id', Auth::user()->id)->with('my_class')->get();
        return view('pages.role_dashboards.teacher', [
            'subjects' => $subjects,
            'sections' => $sections,
            'students' => StudentRecord::whereIn('section_id', $sections->pluck('id'))->where('grad', 0)->count(),
            'exams' => Exam::where('year', Qs::getCurrentSession())->orderBy('term')->get(),
            'timetables' => TimeTable::whereIn('subject_id', $subjects->pluck('id'))->with(['subject', 'time_slot'])->orderBy('exam_date')->take(8)->get(),
        ]);
    }

    protected function studentDashboard()
    {
        $record = StudentRecord::where('user_id', Auth::user()->id)->with(['my_class', 'section'])->first();
        $session = Qs::getCurrentSession();
        return view('pages.role_dashboards.student', [
            'record' => $record,
            'session' => $session,
            'attendance' => Attendance::where('student_id', Auth::user()->id)->whereYear('attendance_date', now()->year)->orderByDesc('attendance_date')->take(8)->get(),
            'marks' => Mark::where(['student_id' => Auth::user()->id, 'year' => $session])->with(['subject', 'grade'])->latest()->take(8)->get(),
            'fees' => $this->payment->getAllMyPR(Auth::user()->id, $session)->with('payment')->get(),
        ]);
    }

    protected function librarianDashboard()
    {
        $books = Book::query();
        return view('pages.role_dashboards.librarian', [
            'books' => $books->count(),
            'copies' => (int) $books->sum('total_copies'),
            'issued' => (int) $books->sum('issued_copies'),
            'requests' => BookRequest::where(function ($query) { $query->whereNull('status')->orWhere('status', 'pending'); })->count(),
            'recent_requests' => BookRequest::with('book')->latest()->take(8)->get(),
        ]);
    }
}
