<?php

namespace App\Http\Controllers\Intern;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InternAttendance;
use App\Models\User;
use App\Models\Leave;
use App\Models\Permission;
use App\Models\StudentTask;
use App\Models\StudentTaskHistory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;


class InternController extends Controller
{
    public function login()
    {
        return view('Intern.login');
    }

    public function intern_login_form(Request $request)
    {
        Log::info('Student login request received', [
            'email' => $request->email,
        ]);

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::where('email', $request->email)
                ->where('role', 'intern')
                ->first();

            if (!$user) {
                Log::warning('Student login failed: User not found', [
                    'email' => $request->email,
                ]);
                return redirect()->back()
                    ->with('error', 'Invalid credentials')
                    ->withInput();
            }

            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Student login failed: Wrong password', [
                    'email' => $request->email,
                ]);
                return redirect()->back()
                    ->with('error', 'Invalid credentials')
                    ->withInput();
            }


            Auth::guard('intern')->login($user);

            Log::info('Student logged in successfully', [
                'intern_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect()->route('intern.dashboard')
                ->with('success', 'Logged in successfully!');
        } catch (\Throwable $e) {
            Log::error('Student login error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function intern_logout(Request $request)
    {
        Auth::guard('intern')->logout();
        return redirect()->route('intern.login')->with('success', 'You have been logged out successfully.');
    }

    public function intern_reset_password()
    {
        return view('Intern.intern_reset_password');
    }

    public function intern_reset_password_form(Request $request)
    {
        try {
            $user = Auth::guard('intern')->user();

            Log::info('Password reset attempt', ['intern_id' => $user->id]);

            $validator = Validator::make($request->all(), [
                'oldpass' => 'required',
                'newpass' => 'required|string|min:6',
                'conpass' => 'required|same:newpass',
            ]);

            if ($validator->fails()) {
                Log::warning('Password reset validation failed', [
                    'intern_id' => $user->id,
                    'errors'   => $validator->errors()->toArray(),
                ]);

                return redirect()
                    ->route('intern_reset_password')
                    ->withErrors($validator)
                    ->withInput();
            }

            // Check old password
            if (!Hash::check($request->oldpass, $user->password)) {
                Log::warning('Old password mismatch', ['intern_id' => $user->id]);

                return redirect()
                    ->route('intern_reset_password')
                    ->with('error', 'Old password is incorrect!')
                    ->withInput();
            }

            DB::beginTransaction();

            $user->password = Hash::make($request->newpass);
            $user->save();

            DB::commit();

            Log::info('Password reset successful', ['intern_id' => $user->id]);

            return redirect()
                ->route('intern.login')
                ->with('success', 'Password reset successfully!');
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Password reset failed', [
                'staff_id' => Auth::guard('intern')->id(),
                'message'  => $e->getMessage(),
            ]);

            return redirect()
                ->route('intern_reset_password')
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }


    public function dashboard()
    {
        return view('Intern.dashboard');
    }

    public function attendance()
    {
        $staffUserId = Auth::guard('intern')->user()->user_id;

        $now = now();

        // ---------- MONTH RANGES ----------
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();

        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // ---------- LATE LOGINS ----------
        $thisMonthLateLogin = InternAttendance::where('user_id', $staffUserId)
            ->where('type', 'late')
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->count();

        $lastMonthLateLogin = InternAttendance::where('user_id', $staffUserId)
            ->where('type', 'late')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $thisMonthLeave = Leave::where('user_id', $staffUserId)
            ->whereDate('from', '<=', $thisMonthEnd)
            ->whereDate('to', '>=', $thisMonthStart)
            ->count();

        $lastMonthLeave = Leave::where('user_id', $staffUserId)
            ->whereDate('from', '<=', $lastMonthEnd)
            ->whereDate('to', '>=', $lastMonthStart)
            ->count();

        $thisMonthPermission = Permission::where('user_id', $staffUserId)
            ->whereBetween('from', [$thisMonthStart, $thisMonthEnd])
            ->count();

        $lastMonthPermission = Permission::where('user_id', $staffUserId)
            ->whereBetween('from', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $data = [
            'thisMonthLateLogin' => $thisMonthLateLogin,
            'lastMonthLateLogin' => $lastMonthLateLogin,


            'thisMonthLeave' => $thisMonthLeave,
            'lastMonthLeave' => $lastMonthLeave,

            'thisMonthPermission' => $thisMonthPermission,
            'lastMonthPermission' => $lastMonthPermission,
        ];

        return view('Intern.attendance', compact('data'));
    }

    public function attendance_data()
    {
        $user = Auth::guard('intern')->user();

        $query = InternAttendance::where('user_id', $user->user_id)
            ->select([
                'id',
                'created_at',
                'check_in',
                'check_out',
                'image',
                'type',
                'late_reason'
            ])
            ->orderBy('created_at', 'desc');


        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })

            ->editColumn('check_in', function ($row) {
                return Carbon::parse($row->check_in)->format('h:i:s A');
            })

            ->editColumn('check_out', function ($row) {
                return $row->check_out
                    ? Carbon::parse($row->check_out)->format('h:i:s A')
                    : '-';
            })
            ->editColumn('type', function ($row) {
                $badge = $row->type === 'late'
                    ? 'bg-label-danger'
                    : 'bg-label-success';

                return '<span class="badge ' . $badge . '">'
                    . ucfirst(str_replace('_', ' ', $row->type)) .
                    '</span>';
            })

            ->addColumn('status', function () {
                return '<button class="btn btn-label-info btn-sm">Present</button>';
            })
            ->addColumn('late_reason', function ($row) {
                return $row->late_reason ?: '-';
            })


            ->rawColumns(['type', 'status', 'late_reason'])
            ->make(true);
    }

    public function intern_check_in(Request $request)
    {
        Log::info('Intern check-in started');

        $user = Auth::guard('intern')->user();

        if (!$user) {
            Log::warning('Intern check-in failed: Unauthorized access');
            return back()->with('error', 'Unauthorized');
        }

        Log::info('Intern authenticated', [
            'user_id' => $user->user_id,
            'ip'      => $request->ip()
        ]);

        $alreadyCheckedIn = InternAttendance::where('user_id', $user->user_id)
            ->whereDate('check_in', today())
            ->exists();

        if ($alreadyCheckedIn) {
            Log::warning('Intern already checked in today', [
                'user_id' => $user->user_id
            ]);
            return back()->with('error', 'You have already checked in today');
        }

        $checkInTime = now();
        $lateTime = Carbon::createFromTime(9, 10, 0);
        $isLate = $checkInTime->greaterThan($lateTime);

        Log::info('Check-in time evaluated', [
            'user_id'     => $user->user_id,
            'check_in'    => $checkInTime->toDateTimeString(),
            'late_limit'  => $lateTime->toTimeString(),
            'is_late'     => $isLate
        ]);

        if ($isLate) {
            Log::info('Late check-in detected', [
                'user_id' => $user->user_id
            ]);

            $request->validate([
                'late_reason' => 'required|string|max:255',
            ]);
        }

        try {
            InternAttendance::create([
                'user_id'     => $user->user_id,
                'check_in'    => $checkInTime,
                'type'        => $isLate ? 'late' : 'on_time',
                'late_reason' => $isLate ? $request->late_reason : null,
                'ip_address'  => $request->ip(),
            ]);

            Log::info('Intern check-in stored successfully', [
                'user_id' => $user->user_id,
                'type'    => $isLate ? 'late' : 'on_time'
            ]);

            return back()->with('success', 'Check-in recorded successfully');
        } catch (\Exception $e) {
            Log::error('Intern check-in failed', [
                'user_id' => $user->user_id,
                'error'   => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to check in');
        }
    }



    public function intern_check_out(Request $request)
    {
        $user = Auth::guard('intern')->user();

        if (!$user) {
            return back()->with('error', 'Unauthorized');
        }

        Log::info('Check out validation started');

        $request->validate([
            'remark' => 'required|string|max:255',
        ]);

        $attendance = InternAttendance::where('user_id', $user->user_id)
            ->whereDate('check_in', Carbon::today())
            ->first();

        Log::info('Today attendance fetched', $attendance?->toArray() ?? []);

        if (!$attendance) {
            return back()->with('error', 'You must check in before checking out');
        }

        if (!is_null($attendance->check_out)) {
            return back()->with('error', 'You have already checked out today');
        }

        try {
            $attendance->update([
                'check_out' => now(),
                'remark'    => $request->learnt_today,
            ]);

            Log::info('Check out updated successfully', [
                'attendance_id' => $attendance->id,
                'check_out'     => $attendance->check_out,
            ]);

            return back()->with('success', 'Check-out recorded successfully');
        } catch (\Exception $e) {
            Log::error('Check-out failed', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to check out');
        }
    }

    public function intern_task()
    {
        $studentId = Auth::guard('intern')->id();

        // New tasks
        $newTasks = StudentTask::where('student_id', $studentId)
            ->where('status', '!=', 'complete')
            ->where('status', '!=', 'hold')
            ->count();

        // Completed tasks
        $completedTasks = StudentTask::where('student_id', $studentId)
            ->where('status', 'complete')
            ->count();

        $holdTasks = StudentTask::where('student_id', $studentId)
            ->where('status', 'hold')
            ->count();
        return view('Intern.intern_task', compact('newTasks', 'completedTasks', 'holdTasks'));
    }

    public function inter_new_task()
    {
        return view("Intern.inter_new_task");
    }

    public function intern_new_task_data(Request $request)
    {
        $intern = Auth::guard('intern')->id();

        $query = StudentTask::with('chapter')
            ->where('student_id', $intern)
            ->where('status', 'new');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('chapter', function ($row) {
                return $row->chapter->chapter_name ?? '-';
            })

            ->addColumn('status', function ($row) use ($intern) {
                return '<div class="d-flex align-items-center gap-2">
                        <button class="btn btn-label-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#updateStatusModal"
                                data-task-id="' . $row->id . '"
                                data-student-id="' . $intern . '">New
                        </button>
                    </div>';
            })

            ->addColumn('action', function ($row) {
                return '<div class="d-flex align-items-center gap-2">
                <a href="' . route('intern_task_description', $row->id) . '"
                   class="btn btn-sm btn-outline-primary">
                    View
                </a>
            </div>';
            })


            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function update_student_task_status(Request $request)
    {
        Log::info('Task status update request received', $request->all());

        $request->validate([
            'task_id'    => 'required|exists:student_tasks,id',
            'student_id' => 'required|exists:users,id',
            'status'     => 'required|in:complete,hold',
            'spend_hour' => 'required|string',
            'remark'     => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            $task = StudentTask::where('id', $request->task_id)
                ->where('student_id', $request->student_id)
                ->firstOrFail();

            $task->status = $request->status;
            $task->save();

            StudentTaskHistory::create([
                'task_id'    => $request->task_id,
                'chapter_id' => $task->chapter_id,
                'course_id'  => $task->course_id,
                'student_id' => $request->student_id,
                'status'     => $request->status,
                'remark'     => $request->remark,
                'spend_hour' => $request->spend_hour,
            ]);

            DB::commit();

            return back()->with('success', 'Task status updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Task update failed', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }




    public function intern_task_description($task_id)
    {
        $internId = Auth::guard('intern')->id();

        $task = StudentTask::with(['chapter', 'history'])
            ->where('id', $task_id)
            ->where('student_id', $internId)
            ->firstOrFail();

        return view('Intern.intern_task_description', compact('task'));
    }

    public function completed_task_intern()
    {
        return view("Intern.completed_task_intern");
    }

    public function completed_task_intern_data(Request $request)
    {
        $intern = Auth::guard('intern')->id();

        $query = StudentTask::with('chapter')
            ->where('student_id', $intern)
            ->where('status', 'complete');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('chapter', function ($row) {
                return $row->chapter->chapter_name ?? '-';
            })

            ->addColumn('status', function ($row) {
                return ' <div class="d-flex align-items-center gap-2">
            <button class="btn btn-label-success">Completed</button>                          </div>';
            })

            ->addColumn('action', function ($row) {
                return '<div class="d-flex align-items-center gap-2">
                <a href="' . route('intern_task_description', $row->id) . '"
                   class="btn btn-sm btn-outline-primary">
                    View
                </a>
            </div>';
            })


            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function hold_tasks_intern()
    {
        return view("Intern.hold_tasks_intern");
    }

    public function hold_tasks_intern_data(Request $request)
    {
        $intern = Auth::guard('intern')->id();

        $query = StudentTask::with('chapter')
            ->where('student_id', $intern)
            ->where('status', 'hold');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('chapter', function ($row) {
                return $row->chapter->chapter_name ?? '-';
            })



            ->addColumn('status', function ($row) {
                return '
                    <button class="btn btn-label-danger btn-sm resumeTaskBtn"
                            data-id="' . $row->id . '"
                            data-bs-toggle="modal"
                            data-bs-target="#resumeTaskModal">
                        Hold
                    </button>
                ';
            })

            ->addColumn('action', function ($row) {
                return '<div class="d-flex align-items-center gap-2">
                <a href="' . route('intern_task_description', $row->id) . '"
                   class="btn btn-sm btn-outline-primary">
                    View
                </a>
            </div>';
            })


            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function intern_resume_task(Request $request)
    {
        Log::info('Resume task request received', [
            'all' => $request->all(),
            'intern_id' => Auth::guard('intern')->id(),
        ]);

        $request->validate([
            'task_id' => 'required|exists:student_tasks,id',
        ]);

        DB::beginTransaction();

        try {
            $studentId = Auth::guard('intern')->id();

            Log::info('Attempting to resume task', [
                'task_id'    => $request->task_id,
                'student_id' => $studentId,
            ]);

            $task = StudentTask::where('id', $request->task_id)
                ->where('student_id', $studentId)
                ->firstOrFail();

            $task->status = 'new';
            $task->save();

            StudentTaskHistory::create([
                'task_id'    => $task->id,
                'chapter_id' => $task->chapter_id,
                'course_id'  => $task->course_id,
                'student_id' => $studentId,
                'status'     => 'inprogress',
            ]);

            DB::commit();

            Log::info('Task resumed successfully', [
                'task_id'    => $task->id,
                'student_id' => $studentId,
            ]);

            return back()->with('success', 'Task resumed successfully');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Intern resume task FAILED', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'task_id' => $request->task_id ?? null,
                'student_id' => Auth::guard('intern')->id(),
            ]);

            return back()->with('error', 'Something went wrong while resuming task');
        }
    }
}
