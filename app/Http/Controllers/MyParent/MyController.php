<?php

namespace App\Http\Controllers\MyParent;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Notice;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use Illuminate\Support\Facades\Auth;

class MyController extends Controller
{
    protected $student;
    protected $payment;
    public function __construct(StudentRepo $student, PaymentRepo $payment)
    {
        $this->student = $student;
        $this->payment = $payment;
    }

    public function children()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $students = $this->student->getRecord(['my_parent_id' => Auth::user()->id])->with(['my_class', 'section'])->get();
        $studentIds = $students->pluck('user_id');
        $attendance = Attendance::whereIn('student_id', $studentIds)
            ->whereBetween('attendance_date', [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()])
            ->orderByDesc('attendance_date')->get()->groupBy('student_id');
        $fees = $this->payment->getAllMyPRsForStudents($studentIds->all());
        $receiptHistory = $fees->flatMap(function ($fee) {
            return $fee->receipt->map(function ($receipt) use ($fee) {
                $receipt->student_name = optional($fee->student)->name;
                $receipt->payment_title = optional($fee->payment)->title;
                return $receipt;
            });
        })->sortByDesc('created_at')->take(10);
        $notices = Notice::visible()->whereIn('audience', ['all', 'parents'])->latest('published_at')->latest()->take(6)->get();

        return view('pages.parent.dashboard', compact('students', 'attendance', 'fees', 'receiptHistory', 'notices'));
    }

}
