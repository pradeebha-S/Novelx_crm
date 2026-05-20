<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\HoldDetails;
use App\Models\Loginentries;
use App\Models\Modules;
use App\Models\Personal;
use App\Models\PpsTransactions;
use App\Models\Project;
use App\Models\Popup;

use App\Models\User;
use App\Models\Task;
use App\Models\Common;
use App\Models\Wfh;
use App\Models\Reminder;
use App\Models\Leave;
use App\Models\Permission;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\BreakTime;
use App\Models\Bugs;
use App\Models\UserDetails;
use App\Traits\DetectDevice;
use Illuminate\Support\Str;
use App\Models\Communication;
use App\Models\CommunicationAttachment;
use App\Models\CommunicationReply;
use Spatie\Permission\Models\Permission as ModelsPermission;
use App\Traits\BrevoMailTrait;

class StaffController extends Controller
{
    use DetectDevice;
    use BrevoMailTrait;

    public function daily_login_form(Request $request)
    {
        if ($this->isMobileDevice($request)) {
            Log::warning('Mobile login attempt blocked', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);
            return back()->with('error', 'Mobile check-in is not allowed.');
        }
        Log::info('Desktop login attempt', [
            'ip' => $request->ip()
        ]);
        $user = Auth::guard('staff')->user();
        if (!$user) {
            return back()->with('error', 'Unauthorized');
        }
        $alreadyCheckedIn = Loginentries::where('user_id', $user->user_id)
            ->whereDate('check_in', Carbon::today('Asia/Kolkata'))
            ->exists();
        if ($alreadyCheckedIn) {
            return back()->with('error', 'You have already checked in today');
        }
        $checkInTime = Carbon::now('Asia/Kolkata');
        $lateTime    = Carbon::createFromTime(9, 10, 0, 'Asia/Kolkata');
        $isLate      = $checkInTime->greaterThanOrEqualTo($lateTime);
        if ($isLate) {
            $request->validate([
                'late_reason' => [
                    'required',
                    'string',
                    'min:20',
                    'regex:/^[\pL\pN\s\pP]+$/u'
                ],
            ]);
        }
        try {
            Loginentries::create([
                'user_id'     => $user->user_id,
                'check_in'    => $checkInTime,
                'type'        => $isLate ? 'late' : 'on_time',
                'late_reason' => $isLate ? $request->late_reason : null,
                'ip_address'  => $request->ip(),
            ]);
            return back()->with(
                'success',
                $isLate
                    ? 'Late check-in recorded successfully'
                    : 'Check-in recorded successfully'
            );
        } catch (\Exception $e) {
            Log::error('Check-in error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to check in');
        }
    }
    public function view_bug_detail($id)
    {
        $bug = Bugs::findOrFail($id);
        return view('Staff.view_bug_detail', compact('bug'));
    }
    public function add_bug($project_id)
    {
        $modules = Modules::where('project_id', $project_id)->get();
        $staffs = User::where('role', 'staff')
            ->where('is_active', '1')
            ->get();
        return view('Staff.add_bug', compact('project_id', 'modules', 'staffs'));
    }
    public function bank_details()
    {
        $staffId = Auth::guard('staff')->id();
        $details = UserDetails::where('user_id', $staffId)->first();
        return view('Staff.bank_details', compact('details'));
    }
    public function login()
    {
        return view('Staff.login');
    }
    public function break_report()
    {
        return view('Staff.break_report');
    }
    public function break_report_data(Request $request)
    {
        $userId = Auth::guard('staff')->id();

        $breaks = BreakTime::where('user_id', $userId);

        // FROM DATE FILTER
        if ($request->from_date) {

            $breaks->whereDate('created_at', '>=', $request->from_date);
        }

        // TO DATE FILTER
        if ($request->to_date) {

            $breaks->whereDate('created_at', '<=', $request->to_date);
        }

        // SEARCH FILTER
        if ($request->search_value) {

            $search = $request->search_value;

            $breaks->where(function ($query) use ($search) {

                $query->where('break_start_time', 'like', "%{$search}%")
                    ->orWhere('break_end_time', 'like', "%{$search}%")
                    ->orWhereDate('created_at', $search);
            });
        }

        $breaks = $breaks->latest();

        return DataTables::of($breaks)

            ->addIndexColumn()

            ->addColumn('date', function ($row) {

                if (!$row->created_at) {
                    return '-';
                }

                return date('d-m-Y', strtotime($row->created_at));
            })

            ->addColumn('start_time', function ($row) {

                if (!$row->break_start_time) {
                    return '-';
                }

                return date('h:i A', strtotime($row->break_start_time));
            })

            ->addColumn('end_time', function ($row) {

                if (!$row->break_end_time) {
                    return '-';
                }

                return date('h:i A', strtotime($row->break_end_time));
            })

            ->addColumn('hours', function ($row) {

                if (!$row->break_start_time || !$row->break_end_time) {
                    return '-';
                }

                $start = strtotime($row->break_start_time);
                $end = strtotime($row->break_end_time);

                $diff = abs($end - $start);

                $hours = floor($diff / 3600);
                $minutes = floor(($diff % 3600) / 60);

                return $hours . ' Hr ' . $minutes . ' Min';
            })

            ->addColumn('status', function ($row) {

                if (!$row->break_start_time || !$row->break_end_time) {

                    return '<span class="badge bg-warning">Active</span>';
                }

                $start = strtotime($row->break_start_time);
                $end = strtotime($row->break_end_time);

                $diffMinutes = floor(abs($end - $start) / 60);

                if ($diffMinutes <= 60) {

                    return '<span class="badge bg-success">On Time</span>';
                }

                return '<span class="badge bg-danger">Exceeded</span>';
            })

            ->rawColumns(['status'])

            ->make(true);
    }

    public function dashboard()
    {
        $staffId = Auth::guard('staff')->id();
        $staffUserId = Auth::guard('staff')->user()->user_id;
        $now = now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $thisMonthCompleted = Task::where('assign_to', $staffId)
            ->where('task_status', 'complete')
            ->whereBetween('start_date', [
                $thisMonthStart,
                $thisMonthEnd
            ])
            ->count();
        $thisMonthPending = Task::where('assign_to', $staffId)
            ->where('task_status', '!=', 'complete')
            ->whereBetween('start_date', [
                $thisMonthStart,
                $thisMonthEnd
            ])
            ->count();

        // ---------- LAST MONTH ----------
        $lastMonthCompleted = Task::where('assign_to', $staffId)
            ->where('task_status', 'complete')
            ->whereBetween('start_date', [
                $lastMonthStart,
                $lastMonthEnd
            ])
            ->count();
        $lastMonthPending = Task::where('assign_to', $staffId)
            ->where('task_status', '!=', 'complete')
            ->whereBetween('start_date', [
                $lastMonthStart,
                $lastMonthEnd
            ])
            ->count();
        // ---------- LATE LOGINS ----------
        $thisMonthLateLogin = Loginentries::where('user_id', $staffUserId)
            ->where('type', 'late')
            ->whereBetween('created_at', [
                $thisMonthStart,
                $thisMonthEnd
            ])
            ->count();
        // ---------- LATE LOGINS ----------
        $thisMonthontimeLogin = Loginentries::where('user_id', $staffUserId)
            ->where('type', 'on_time')
            ->whereBetween('created_at', [
                $thisMonthStart,
                $thisMonthEnd
            ])
            ->count();
        // ---------- WFH DAYS COUNT ----------
        $thisMonthWfh = Wfh::where('user_id', $staffId)
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->get()
            ->sum(function ($wfh) {

                $from = Carbon::parse($wfh->from);
                $to = Carbon::parse($wfh->to);

                return $from->diffInDays($to) + 1;
            });
              $completed_tasks_count = Task::where('assign_to', $staffId)
            ->where('task_status', 'complete')
            ->count();
    $inprogressTasks = Task::where('assign_to', $staffId)
    ->where('task_status', 'inprogress')
    ->count();
     $pending_tasks_count = Task::where('assign_to', $staffId)
            //  ->whereDate('start_date', '<', Carbon::today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            // ->where('task_status', '!=', 'inprogress')
            ->count();

                $hold_tasks_count = Task::where('assign_to', $staffId)
            ->where('task_status', 'hold')
            ->count();

        // ---------- LEAVE DAYS COUNT ----------
        $thisMonthLeave = Leave::where('user_id', $staffId)
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->get()
            ->sum(function ($leave) {

                $from = Carbon::parse($leave->from);
                $to = Carbon::parse($leave->to);

                return $from->diffInDays($to) + 1;
            });

        // ---------- PERMISSION COUNT ----------
        $thisMonthPermission = Permission::where('user_id', $staffId)
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->count();
        $break = BreakTime::where('user_id', $staffId)
            ->whereDate('created_at', Carbon::today())
            ->whereNull('break_end_time')
            ->first();
        $breakStartTime = $break ? $break->break_start_time : null;
        $lastMonthLateLogin = Loginentries::where('user_id', $staffUserId)
            ->where('type', 'late')
            ->whereBetween('created_at', [
                $lastMonthStart,
                $lastMonthEnd
            ])
            ->count();
        // For last 12 months
        $currentDate = Carbon::now();
        $months = [];
        $earnedPoints = [];
        $reducedPoints = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $currentDate->copy()->subMonths($i);
            $months[] = $month->format('M Y');
            $earnedPoints[] = PpsTransactions::where('user_id', $staffId)
                ->where('transaction_type', 'credit')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('points');
            $reducedPoints[] = PpsTransactions::where('user_id', $staffId)
                ->where('transaction_type', 'debit')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('points');
        }
        $data = [
            'thisMonthCompleted' => $thisMonthCompleted,
            'thisMonthPending' => $thisMonthPending,
            'lastMonthCompleted' => $lastMonthCompleted,
            'lastMonthPending' => $lastMonthPending,
            'thisMonthLateLogin' => $thisMonthLateLogin,
            'lastMonthLateLogin' => $lastMonthLateLogin,
            'thisMonthontimeLogin' => $thisMonthontimeLogin,
'pending_tasks_count' => $pending_tasks_count,
'hold_tasks_count' => $hold_tasks_count,
            // NEW COUNTS
            'thisMonthWfh' => $thisMonthWfh,
            'thisMonthLeave' => $thisMonthLeave,
              'inprogressTasks' => $inprogressTasks,
            'thisMonthPermission' => $thisMonthPermission,
            'completed_tasks_count' => $completed_tasks_count,
        ];
        $today_reminders = Reminder::where('user_id', $staffId)
            ->where('is_active', 1)
            ->whereDate('date', '<=', Carbon::today())
            ->orderBy('date', 'desc')
            ->get();
            $unreadMails = Communication::where('user_id', Auth::guard('staff')->id())
    ->where('is_viewed', 0)
    ->latest()
    ->get();
$popup = Popup::where('popup_status', 'active')
    ->where('done_status', 'pending')
    ->where(function ($q) {

        $q->whereNull('user_id')
          ->orWhere('user_id', Auth::guard('staff')->id());

    })
    ->latest()
    ->first();
        return view('Staff.dashboard', compact(
            'data',
            'today_reminders',
            'months',
            'earnedPoints',
            'reducedPoints',
            'break',
            'breakStartTime',
            'unreadMails',
            'popup'
        ));
    }
    public function staff_task()
    {
        $staffId = Auth::guard('staff')->id();
        // Pending tasks
        $pending_tasks = Task::where('assign_to', $staffId)
            // ->whereDate('due_date', '<', today())
            ->whereDate('start_date', '<', Carbon::today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        // New tasks
        $new_tasks = Task::where('assign_to', $staffId)
            ->whereDate('start_date', '>=', today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        // Completed tasks
        $completed_tasks = Task::where('assign_to', $staffId)
            ->where('task_status', 'complete')
            ->count();
        // Hold tasks
        $hold_tasks = Task::where('assign_to', $staffId)
            ->where('task_status', 'hold')
            ->count();
        $staffId = Auth::guard('staff')->id();
        // Get all projects
        $projects = Project::whereHas('tasks', function ($q) use ($staffId) {
            $q->where('assign_to', $staffId);
        })->with([
            'tasks' => function ($q) use ($staffId) {
                $q->where('assign_to', $staffId);
            }
        ])->get();
        $projectData = [];
        foreach ($projects as $project) {
            $tasks = $project->tasks;
            $completedTasks = $tasks->where('task_status', 'complete')->count();
            $pendingTasks = $tasks->where('task_status', '!=', 'complete')
                ->where('task_status', '!=', 'hold')
                ->count();
            $holdTasks = $tasks->where('task_status', 'hold')->count();
            $totalProgressTasks = $completedTasks + $pendingTasks + $holdTasks;
            $progressPercent = $totalProgressTasks > 0
                ? round(($completedTasks / $totalProgressTasks) * 100)
                : 0;
            $totalBugs = Bugs::where('project_id', $project->id)->count();
            $pendingBugs = Bugs::where('project_id', $project->id)
                ->where('status', 'Pending')
                ->count();
            $projectData[] = [
                'project' => $project,
                'completedTasks' => $completedTasks,
                'pendingTasks' => $pendingTasks,
                'holdTasks' => $holdTasks,
                'progressPercent' => $progressPercent,
                'totalTasks' => $totalProgressTasks,
                'totalBugs' => $totalBugs,
                'pendingBugs' => $pendingBugs
            ];
        }
        return view('Staff.staff_task', compact('pending_tasks', 'new_tasks', 'completed_tasks', 'hold_tasks', 'projectData'));
    }
    public function task_inprogress()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('Staff.task_inprogress', compact('staffs'));
    }
    public function pending_task_table()
    {
        // $staffId = Auth::id();
        $staffs = User::where('role', 'staff')->get();
        return view('Staff.pending_task_table', compact('staffs'));
    }
    public function pending_task_table_data()
    {
        $staffId = Auth::guard('staff')->id();
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name'
        ])
            ->where('assign_to', $staffId)
            ->whereDate('start_date', '<', Carbon::today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold');
            //  ->where('task_status', '!=', 'inprogress');
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . e($task->module_type) . '</strong><br>
                <small class = "text-muted">' . e(optional($task->module)->module_name) . '</small>
            ';
            })
            ->addColumn('task', function ($task) {
                return '<strong>Task Title:</strong> ' . e($task->task_name) . '<br><br>';
            })
            ->addColumn('estimated_time', function ($task) {
                return '<strong>' . e($task->estimated_time) . '</strong>';
            })
            ->addColumn('status', function ($task) {
                if ($task->task_status === 'completed') {
                    return '<span class="badge bg-success">Completed</span>';
                }
                if ($task->task_status === 'new') {
                    return '
            <button
                class          = "btn btn-info btn-sm startTaskBtn"
                data-id        = "' . $task->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#startTaskModal">
                New
            </button>
        ';
                }
                if ($task->task_status === 'inprogress') {
                    return '
            <button
                class          = "btn btn-primary btn-sm text-nowrap openStatusModal"
                data-id        = "' . $task->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#updateStatusModal">
                In Progress
            </button>
        ';
                }
                if ($task->task_status === 'hold') {
                    return '
            <button
                class          = "btn btn-danger btn-sm openStatusModal"
                data-id        = "' . $task->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#updateStatusModal">
                Hold
            </button>
        ';
                }
                return '
        <button
            class          = "btn btn-secondary btn-sm openStatusModal"
            data-id        = "' . $task->id . '"
            data-bs-toggle = "modal"
            data-bs-target = "#updateStatusModal">
            ' . ucfirst($task->task_status) . '
        </button>
        ';
            })
            ->addColumn('action', function ($task) {
                return '
                <a href  = "' . route('task_descriptions', $task->id) . '"
                   class = "btn btn-sm btn-warning">
                   View
                </a>
            ';
            })
            ->rawColumns(['project', 'module', 'task', 'estimated_time', 'status', 'action'])
            ->make(true);
    }
    public function task_inprogress_data()
    {
        $staffId = Auth::guard('staff')->id();
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name'
        ])
            ->where('assign_to', $staffId)
            // ->whereDate('start_date', '<', Carbon::today())
            ->where('task_status', 'inprogress');
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . e($task->module_type) . '</strong><br>
                <small class = "text-muted">' . e(optional($task->module)->module_name) . '</small>
            ';
            })
            ->addColumn('task', function ($task) {
                return '<strong>Task Title:</strong> ' . e($task->task_name) . '<br><br>';
            })
            ->addColumn('estimated_time', function ($task) {
                return '<strong>' . e($task->estimated_time) . '</strong>';
            })
            ->addColumn('status', function ($task) {
                if ($task->task_status === 'completed') {
                    return '<span class="badge bg-success">Completed</span>';
                }
                if ($task->task_status === 'new') {
                    return '
            <button
                class          = "btn btn-info btn-sm startTaskBtn"
                data-id        = "' . $task->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#startTaskModal">
                New
            </button>
        ';
                }
                if ($task->task_status === 'inprogress') {
                    return '
            <button
                class          = "btn btn-primary btn-sm text-nowrap openStatusModal"
                data-id        = "' . $task->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#updateStatusModal">
                In Progress
            </button>
        ';
                }
                if ($task->task_status === 'hold') {
                    return '
            <button
                class          = "btn btn-danger btn-sm openStatusModal"
                data-id        = "' . $task->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#updateStatusModal">
                Hold
            </button>
        ';
                }
                return '
        <button
            class          = "btn btn-secondary btn-sm openStatusModal"
            data-id        = "' . $task->id . '"
            data-bs-toggle = "modal"
            data-bs-target = "#updateStatusModal">
            ' . ucfirst($task->task_status) . '
        </button>
        ';
            })
            ->addColumn('action', function ($task) {
                return '
                <a href  = "' . route('task_descriptions', $task->id) . '"
                   class = "btn btn-sm btn-warning">
                   View
                </a>
            ';
            })
            ->rawColumns(['project', 'module', 'task', 'estimated_time', 'status', 'action'])
            ->make(true);
    }
    public function view_staff_hold_history($task_id)
    {
        return view('Staff.view_staff_hold_history', compact('task_id'));
    }
    public function view_staff_hold_history_data($task_id)
    {
        $data = TaskHistory::where('task_id', $task_id)
            ->where('status', 'hold')
            ->orderBy('created_at', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('remark', function ($row) {
                return $row->remark ?? '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('d M Y H:i')
                    : '-';
            })
            ->make(true);
    }
    public function view_staff_reopen_history($task_id)
    {
        return view('Staff.view_staff_reopen_history', compact('task_id'));
    }
    public function view_staff_reopen_history_data($task_id)
    {
        $data = TaskHistory::where('task_id', $task_id)
            ->where('status', 'reopen')
            ->orderBy('created_at', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('reopen_type', function ($row) {
                return $row->reopen_type ?? '-';
            })
            ->editColumn('remark', function ($row) {
                return $row->remark ?? '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('d M Y H:i')
                    : '-';
            })
            ->make(true);
    }
    public function today_task()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('Staff.today_task', compact('staffs'));
    }
    public function today_task_data()
    {
        $staffId = Auth::guard('staff')->id();
        $tasks = Task::with(['project:id,project_name', 'module:id,module_name'])
            ->where('assign_to', $staffId)
            ->whereDate('start_date', '>=', today())
            ->whereNotIn('task_status', ['complete', 'hold']);
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . e($task->module_type) . '</strong><br>
                <small class = "text-muted">' . e(optional($task->module)->module_name) . '</small>
            ';
            })
            ->addColumn('task', function ($task) {
                return '
                <strong>' . e($task->task_name) . '</strong><br>
            ';
            })
            ->addColumn('estimated_time', function ($task) {
                return '
                <strong>' . e($task->estimated_time) . '</strong><br>
            ';
            })
            ->addColumn('status', function ($task) {
                return match ($task->task_status) {
                    'new' => '
                    <button class          = "btn btn-info btn-sm startTaskBtn"
                            data-id        = "' . $task->id . '"
                            data-bs-toggle = "modal"
                            data-bs-target = "#startTaskModal">New</button>
                ',
                    'inprogress' => '
                    <button class          = "btn btn-primary btn-sm openStatusModal"
                            data-id        = "' . $task->id . '"
                            data-bs-toggle = "modal"
                            data-bs-target = "#updateStatusModal">In Progress</button>
                ',
                    default => '
                    <button class          = "btn btn-secondary btn-sm openStatusModal"
                            data-id        = "' . $task->id . '"
                            data-bs-toggle = "modal"
                            data-bs-target = "#updateStatusModal">'
                        . ucfirst($task->task_status) .
                        '</button>
                '
                };
            })
            ->addColumn('action', function ($task) {
                return '
                <a href  = "' . route('task_descriptions', $task->id) . '"
                   class = "btn btn-sm btn-warning">
                   View
                </a>
            ';
            })
            ->rawColumns(['project', 'module', 'task', 'estimated_time', 'status', 'action'])
            ->make(true);
    }
    public function completed_task()
    {
        return view('Staff.completed_task');
    }
    public function developer_completed_task()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('Staff.developer_completed_task', compact('staffs'));
    }
    public function testing_completed_tasks()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('Staff.testing_completed_tasks', compact('staffs'));
    }
    public function developer_task_description($task_id)
    {
        $task = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name',
            'histories'
        ])->findOrFail($task_id);
        $task->reopen_count = $task->histories->where('status', 'reopen')->count();
        $task->hold_count = $task->histories->where('status', 'hold')->count();
        $task->histories->transform(function ($history) {
            if ($history->status === 'hold') {
                $history->display_remark = $history->remark ?: '-';
            } elseif ($history->status === 'complete') {
                $history->display_remark = $history->remark ?: '-';
            } else {
                $history->display_remark = trim(($history->remark ?? '') . ' ' . ($history->new_status ?? '')) ?: '-';
            }
            return $history;
        });
        return view('Staff.developer_task_description', compact('task'));
    }
    public function developer_task_table_data(Request $request)
    {
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('task_status', 'complete')
            ->where('test_status', 'incomplete')
            ->whereDate('start_date', '<', Carbon::today());

        // ✅ SEARCH FIX (IMPORTANT PART)
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];

            $tasks->where(function ($q) use ($search) {
                $q->where('task_name', 'like', "%$search%")
                    ->orWhere('module_type', 'like', "%$search%")
                    ->orWhere('start_date', 'like', "%$search%")
                    ->orWhere('due_date', 'like', "%$search%")
                    ->orWhereHas('project', function ($p) use ($search) {
                        $p->where('project_name', 'like', "%$search%");
                    })
                    ->orWhereHas('assignedStaff', function ($u) use ($search) {
                        $u->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('module', function ($m) use ($search) {
                        $m->where('module_name', 'like', "%$search%");
                    });
            });
        }

        return DataTables::of($tasks)
            ->addIndexColumn()

            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })

            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })

            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })

            ->addColumn('module', function ($task) {
                return '
            <strong>' . e($task->module_type) . '</strong><br>
            <small class="text-muted">' . e(optional($task->module)->module_name) . '</small>
        ';
            })

            ->addColumn('task', function ($task) {
                return '<strong>Task Title:</strong> ' . e($task->task_name);
            })

            ->addColumn('developer_name', function ($task) {
                return $task->assignedStaff
                    ? '<strong>' . e($task->assignedStaff->name) . '</strong>'
                    : '<span class="text-muted">N/A</span>';
            })

            ->addColumn('status', function ($task) {
                return '<span class="badge bg-warning text-dark">Testing Pending</span>';
            })

            ->addColumn('action', function ($task) {
                return '
            <a href="' . route('developer_task_description', ['task_id' => $task->id]) . '"
               class="btn btn-sm btn-warning">
               View
            </a>';
            })

            ->rawColumns(['project', 'module', 'task', 'developer_name', 'status', 'action'])
            ->make(true);
    }

    public function testing_task_table_data(Request $request)
    {
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name'
        ])
            ->where('task_status', 'complete')
            ->where('test_status', 'complete')
            ->whereDate('start_date', '<', Carbon::today());

        // ✅ SEARCH FIX
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];

            $tasks->where(function ($q) use ($search) {
                $q->where('task_name', 'like', "%$search%")
                    ->orWhere('module_type', 'like', "%$search%")
                    ->orWhere('start_date', 'like', "%$search%")
                    ->orWhere('due_date', 'like', "%$search%")
                    ->orWhereHas('assignedStaff', function ($u) use ($search) {
                        $u->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('project', function ($p) use ($search) {
                        $p->where('project_name', 'like', "%$search%");
                    })
                    ->orWhereHas('module', function ($m) use ($search) {
                        $m->where('module_name', 'like', "%$search%");
                    });
            });
        }

        return DataTables::of($tasks)
            ->addIndexColumn()

            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })

            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })

            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })

            ->addColumn('module', function ($task) {
                return '
            <strong>' . e($task->module_type) . '</strong><br>
            <small class="text-muted">' . e(optional($task->module)->module_name) . '</small>
        ';
            })

            ->addColumn('task', function ($task) {
                return '<strong>Task Title:</strong> ' . e($task->task_name);
            })

            ->addColumn('developer_name', function ($task) {
                return $task->assignedStaff
                    ? '<strong>' . e($task->assignedStaff->name) . '</strong>'
                    : '<span class="text-muted">N/A</span>';
            })

            ->addColumn('status', function ($task) {
                return '<span class="badge bg-success text-dark">Testing Completed</span>';
            })

            ->addColumn('action', function ($task) {
                return '
            <a href="' . route('developer_task_description', ['task_id' => $task->id]) . '"
               class="btn btn-sm btn-warning">
               View
            </a>';
            })

            ->rawColumns(['project', 'module', 'task', 'developer_name', 'status', 'action'])
            ->make(true);
    }

    public function verify_test_status(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'test_status' => 'required',
            'remark' => 'nullable|string'
        ]);

        $staffId = Auth::guard('staff')->id();

        // ✅ First get task
        $task = Task::findOrFail($request->task_id);

        // ✅ Update task
        $task->update([
            'test_status' => 'complete',
            'task_status' => 'complete',
            'tested_by'   => $staffId
        ]);

        // ✅ Insert history
        TaskHistory::create([
            'task_id'    => $task->id,
            'project_id' => $task->project_id,
            'staff_id'   => $staffId,
            'status'     => 'testing completed',
            'remark'    => $request->remark
        ]);

        return back()->with('success', 'Task marked as complete');
    }
    public function submit_reopen_status(Request $request)
    {
        Log::info('Reopen request received', [
            'reopen' => $request->all(),
            // 'staff_id' => Auth::guard('staff')->id(),
        ]);
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'reopen_type' => 'required|in:bug,update,cr',
            'remark' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $task = Task::findOrFail($request->task_id);
            if (empty($task->assign_to)) {
                DB::rollBack(); // ✅ FIX
                return back()->with('error', 'Please assign the task first before reopening.');
            }
            $staffId = $task->assign_to;
            Log::info('Creating reopen history', [
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'staff_id' => $staffId,
                'reopen_type' => $request->reopen_type,
            ]);
            TaskHistory::create([
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'staff_id' => $staffId,
                'status' => 'reopen',
                'reopen_type' => $request->reopen_type,
                'remark' => $request->remark,
            ]);
            $task->update([
                'task_status' => 'new',
                'test_status' => 'incomplete',
            ]);
            Log::info('Task status updated after reopen', [
                'task_id' => $task->id,
                'task_status' => 'new',
                'test_status' => 'incomplete',
            ]);
            DB::commit();
            if ($staffId) {
                $sent = webpushnotify(
                    $staffId,
                    'Task Reopened',
                    'Task "' . $task->task_name . '" has been reopened.'
                );
                Log::info('Reopen Push Notification Sent', [
                    'staff_id' => $staffId,
                    'sent_status' => $sent
                ]);
            }
            Log::info('Reopen completed successfully', [
                'task_id' => $task->id,
                'staff_id' => $staffId,
            ]);
            return back()->with('success', 'Task reopened successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reopen failed', [
                'task_id' => $request->task_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Something went wrong');
        }
    }
    public function completed_task_data()
    {
        $staffId = Auth::guard('staff')->id();
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name'
        ])
            ->where('assign_to', $staffId)
            ->orderBy('updated_at', 'desc')
            ->where('task_status', 'complete');
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . e($task->module_type) . '</strong><br>
                <small class = "text-muted">' . e(optional($task->module)->module_name) . '</small>
            ';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . ($task->module_type ?? '-') . '</strong><br>
                <small class = "text-muted">
                    ' . ($task->module->module_name ?? '-') . '
                </small>
            ';
            })
            ->addColumn('task', function ($task) {
                return '
                <strong>Task Title: </strong> ' . ($task->task_name) . '<br><br>
            ';
            })
            ->addColumn('status', function ($task) {
                return '<span class="badge bg-success">Complete</span>';
            })
            ->addColumn('action', function ($task) {
                return '
                <a href  = "' . route('task_descriptions', $task->id) . '"
                   class = "btn btn-sm btn-warning">
                   View
                </a>
            ';
            })
            ->rawColumns(['project', 'module', 'task', 'status', 'action'])
            ->make(true);
    }
    public function hold_tasks()
    {
        return view('Staff.hold_tasks');
    }
    public function hold_tasks_data()
    {
        $staffId = Auth::guard('staff')->id();
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name'
        ])
            ->where('assign_to', $staffId)
            ->where('task_status', 'hold');
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('start_date', function ($task) {
                return $task->start_date
                    ? Carbon::parse($task->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($task) {
                return $task->due_date
                    ? Carbon::parse($task->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($task) {
                return $task->project
                    ? $task->project->project_name
                    : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . e($task->module_type) . '</strong><br>
                <small class = "text-muted">' . e(optional($task->module)->module_name) . '</small>
            ';
            })
            ->addColumn('module', function ($task) {
                return '
                <strong>' . ($task->module_type ?? '-') . '</strong><br>
                <small class = "text-muted">
                    ' . ($task->module->module_name ?? '-') . '
                </small>
            ';
            })
            ->addColumn('task', function ($task) {
                return '
                <strong>Task Title: </strong> ' . $task->task_name . '<br><br>
            ';
            })
            ->addColumn('status', function ($task) {
                return '
                <button class          = "btn btn-danger btn-sm resumeTaskBtn"
                        data-id        = "' . $task->id . '"
                        data-bs-toggle = "modal"
                        data-bs-target = "#resumeTaskModal">
                    Hold
                </button>
            ';
            })
            ->addColumn('action', function ($task) {
                return '
                <a href  = "' . route('task_descriptions', $task->id) . '"
                   class = "btn btn-sm btn-warning">
                   View
                </a>
            ';
            })
            ->rawColumns(['project', 'module', 'task', 'status', 'action'])
            ->make(true);
    }
    public function attendance_dashboard()
    {
        $staffUserId = Auth::guard('staff')->user()->user_id;
        $now = now();
        // ---------- MONTH RANGES ----------
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        // ---------- LATE LOGINS ----------
        $thisMonthLateLogin = Loginentries::where('user_id', $staffUserId)
            ->where('type', 'late')
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->count();
        $lastMonthLateLogin = Loginentries::where('user_id', $staffUserId)
            ->where('type', 'late')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
        $thisMonthWFH = Wfh::where('user_id', $staffUserId)
            ->where('reply', 'approved')
            ->whereDate('from', '<=', $thisMonthEnd)
            ->whereDate('to', '>=', $thisMonthStart)
            ->count();
        $lastMonthWFH = Wfh::where('user_id', $staffUserId)
            ->where('reply', 'approved')
            ->whereDate('from', '<=', $lastMonthEnd)
            ->whereDate('to', '>=', $lastMonthStart)
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
            'thisMonthWFH' => $thisMonthWFH,
            'lastMonthWFH' => $lastMonthWFH,
            'thisMonthLeave' => $thisMonthLeave,
            'lastMonthLeave' => $lastMonthLeave,
            'thisMonthPermission' => $thisMonthPermission,
            'lastMonthPermission' => $lastMonthPermission,
        ];
        return view('Staff.attendance_dashboard', compact('data'));
    }
    public function attendance_dashboard_data()
    {
        $user = Auth::guard('staff')->user();
        $query = Loginentries::where('user_id', $user->user_id)
            ->select([
                'id',
                'created_at',
                'check_in',
                'check_out',
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
    public function profile()
    {
        $staff = Auth::guard('staff')->user();
        return view('Staff.profile', compact('staff'));
    }
    public function attendance()
    {
        return view('Staff.attendance');
    }
    public function forget_password()
    {
        return view('Staff.forget_password');
    }
    public function change_password()
    {
        return view('Staff.change_password');
    }
    public function staff_reset_password()
    {
        return view('Staff.staff_reset_password');
    }
    public function common_support()
    {
        return view('Staff.common_support');
    }
    public function common_support_data()
    {
        $data = Common::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('created_date', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('remark', function ($row) {
                return !empty($row->remark) ? $row->remark : 'Not Replied';
            })
            ->rawColumns(['created_date', 'remark'])
            ->make(true);
    }
    public function personal_request()
    {
        return view('Staff.personal_request');
    }
    public function personal_request_data()
    {
        $data = Personal::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('remark', function ($row) {
                return !empty($row->remark) ? $row->remark : 'Not Replied';
            })
            ->addColumn('is_replied', function ($row) {
                return $row->is_replied == 1 ? 'Yes' : 'No';
            })
            ->rawColumns(['date', 'is_replied', 'action'])
            ->make(true);
    }
    public function add_request(Request $request)
    {
        Log::info('Request Validation Started');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'description' => 'required|string|max:500',
            'remark' => 'nullable|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Request validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            personal::create([
                'title' => $request->title,
                'description' => $request->description,
                'remark' => $request->remark,
            ]);
            DB::commit();
            Log::info('Request Send Successfully');
            return back()->with('success', 'Request Sent Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Request creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function add_support(Request $request)
    {
        Log::info('Support Validation Started');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'description' => 'required|string|max:500',
            'remark' => 'nullable|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Support validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            Common::create([
                'title' => $request->title,
                'description' => $request->description,
                'remark' => $request->remark
            ]);
            DB::commit();
            Log::info('Support Created Successfully');
            return back()->with('success', 'Support Created Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Support creation failed', [
                'message' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    // public function update_task_status(Request $request)
    // {
    //     $request->validate([
    //         'task_id'       => 'required|exists:tasks,id',
    //         'action'        => 'required|in:start,update',
    //         'task_status'   => 'required_if:action,update|in:complete,hold',
    //         'spending_hour' => 'required_if:action,update|string',
    //         'remark'        => 'required_if:action,update|string',
    //     ]);
    //     DB::beginTransaction();
    //     try {
    //         $task    = Task::findOrFail($request->task_id);
    //         $staffId = Auth::guard('staff')->id();
    //         $historyStatus       = null;
    //         $historyRemark       = null;
    //         $historySpendingHour = null;
    //         if ($request->action === 'start') {
    //             $alreadyInProgress = Task::where('assign_to', $staffId)
    //                 ->where('task_status', 'inprogress')
    //                 ->where('id', '!=', $task->id)
    //                 ->exists();
    //             if ($alreadyInProgress) {
    //                 DB::rollBack();
    //                 return back()->with('error', 'Already one task is in progress.');
    //             }
    //             $task->task_status = 'inprogress';
    //             $historyStatus     = 'start';
    //         }
    //         if ($request->action === 'update') {
    //             $task->task_status   = $request->task_status;
    //             $task->remark        = $request->remark;
    //             $task->spending_hour = $request->spending_hour;
    //             $historyStatus       = $request->task_status;
    //             $historyRemark       = $request->remark;
    //             $historySpendingHour = $request->spending_hour;
    //         }
    //         $task->save();
    //         if ($historyStatus) {
    //             TaskHistory::create([
    //                 'task_id'       => $task->id,
    //                 'project_id'    => $task->project_id,
    //                 'staff_id'      => $staffId,
    //                 'status'        => $historyStatus,
    //                 'remark'        => $historyRemark,
    //                 'spending_hour' => $historySpendingHour,
    //             ]);
    //         }
    //         DB::commit();
    //         return back()->with('success', 'Task status updated successfully');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Task status update failed', [
    //             'error' => $e->getMessage()
    //         ]);
    //         return back()->with('error', 'Something went wrong. Please try again.');
    //     }
    // }
    public function update_task_status(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'action' => 'required|in:start,update',
            'task_status' => 'required_if:action,update|in:complete,hold,reassign',
            // 'spending_hour' => 'required_if:action,update|string',
            'remark' => [
                'required_if:action,update',
                'string',
                'min:50',
                'regex:/^[\pL\pN\s\pP]+$/u'
            ],
            'assign_to' => 'required_if:task_status,reassign|exists:users,id',
        ]);
        DB::beginTransaction();
        try {
            $task = Task::findOrFail($request->task_id);

            $staff = Auth::guard('staff')->user();
            $staffId = $staff->id;
            $userCode = $staff->user_id;
            $historyStatus = null;
            $historyRemark = null;
            $historySpendingHour = null;
            $reassignTo = null;
            if ($request->action === 'start') {
                $alreadyCheckedIn = Loginentries::where('user_id', $userCode)
                    ->whereDate('check_in', Carbon::today('Asia/Kolkata'))
                    ->exists();
                if (!$alreadyCheckedIn) {
                    DB::rollBack();
                    return back()->with('error', 'Please check-in before starting the task.');
                }
                $alreadyInProgress = Task::where('assign_to', $staffId)
                    ->where('task_status', 'inprogress')
                    ->where('id', '!=', $task->id)
                    ->exists();
                if ($alreadyInProgress) {
                    DB::rollBack();
                    return back()->with('error', 'Already one task is in progress.');
                }
                $task->task_status = 'inprogress';
                $historyStatus = 'start';
            }
            //         if ($request->action === 'start') {
            // //              $alreadyCheckedIn = Loginentries::where('user_id', $user_id)
            // //     ->whereDate('check_in', Carbon::today('Asia/Kolkata'))
            // //     ->exists();
            // // if (!$alreadyCheckedIn) {
            // //     DB::rollBack();
            // //     return back()->with('error', 'Please check-in before starting the task.');
            // // }
            //             $alreadyInProgress = Task::where('assign_to', $staffId)
            //                 ->where('task_status', 'inprogress')
            //                 ->where('id', '!=', $task->id)
            //                 ->exists();
            //             if ($alreadyInProgress) {
            //                 DB::rollBack();
            //                 return back()->with('error', 'Already one task is in progress.');
            //             }
            //             $task->task_status = 'inprogress';
            //             $historyStatus = 'start';
            //         }
            if ($request->action === 'update') {
                if ($request->task_status === 'reassign') {
                    $task->assign_to = $request->assign_to;
                    $task->task_status = 'new';
                    $reassignTo = $request->assign_to;
                    $historyStatus = 'reassign';
                } else {
                    $task->task_status = $request->task_status;
                    $historyStatus = $request->task_status;
                }
                // These go ONLY to history
                $historyRemark = $request->remark;
                $historySpendingHour = $request->spending_hour;
            }
            $task->save();
            if ($historyStatus) {
                TaskHistory::create([
                    'task_id' => $task->id,
                    'project_id' => $task->project_id,
                    'staff_id' => $staffId,
                    'status' => $historyStatus,
                    'remark' => $historyRemark,
                    'spending_hour' => $historySpendingHour,
                    'reassign_to' => $reassignTo,
                ]);
            }
            DB::commit();
            return back()->with('success', 'Task status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task status update failed', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function wfh()
    {
        return view('Staff.wfh');
    }
    public function wfh_data()
    {
        $staff = Auth::guard('staff')->id();
        $data = Wfh::where('user_id', $staff);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('status', function ($row) {
                if ($row->reply == 'approved') {
                    return '<button class="btn btn-label-success " >Approved</button>';
                } else {
                    return '<button class="btn btn-label-danger " >Not Approved</button>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function request_wfh(Request $request)
    {
        Log::info('WFH Request Validation Started');
        $userId = Auth::guard('staff')->id();
        $validator = Validator::make($request->all(), [
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'reason' => 'required|string',
            'informed_to' => 'required|string',
            'mailed' => 'required|in:yes,no',
        ]);
        if ($validator->fails()) {
            Log::warning('WFH validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Wfh::create([
                'user_id' => $userId,
                'from' => $request->from,
                'to' => $request->to,
                'reason' => $request->reason,
                'informed_to' => $request->informed_to,
                'mailed' => $request->mailed,
            ]);
            DB::commit();
            return back()->with('success', 'WFH request submitted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('WFH request failed', [
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Something went wrong')->withInput();
        }
    }
    public function leave_request()
    {
        return view('Staff.leave_request');
    }
    public function leave_request_data()
    {
        $staff = Auth::guard('staff')->id();
        $data = Leave::where('user_id', $staff);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('status', function ($row) {
                if ($row->reply == 'approved') {
                    return '<button class="btn btn-label-success btn-sm ">Approved</button>';
                } else {
                    return '<button class="btn btn-label-danger btn-sm">Not Approved</button>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function request_leave(Request $request)
    {
        Log::info('Leave Request Validation Started');
        // $userId = Auth::id();
        $userId = Auth::guard('staff')->id();
        $validator = Validator::make($request->all(), [
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'reason' => 'required|string',
            'informed_to' => 'required|string',
            'mailed' => 'required|in:yes,no',
        ]);
        if ($validator->fails()) {
            Log::warning('Leave validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Leave::create([
                'user_id' => $userId,
                'from' => $request->from,
                'to' => $request->to,
                'reason' => $request->reason,
                'informed_to' => $request->informed_to,
                'mailed' => $request->mailed,
            ]);
            DB::commit();
            return back()->with('success', 'Leave request submitted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Leave request failed', [
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Something went wrong')->withInput();
        }
    }
    public function permission()
    {
        return view('Staff.permission');
    }
    public function permission_data()
    {
        $staff = Auth::guard('staff')->id();
        $data = Permission::where('user_id', $staff);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('status', function ($row) {
                if ($row->reply == 'approved') {
                    return '<button class="btn btn-label-success btn-sm">Approved</button>';
                } else {
                    return '<button class="btn btn-label-danger btn-sm">Not Approved</button>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function request_permission(Request $request)
    {
        Log::info('Permission Request Validation Started');
        $userId = Auth::guard('staff')->id();
        $validator = Validator::make($request->all(), [
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'reason' => 'nullable|string',
            'date' => 'required|date',
            'informed_to' => 'required|string',
            'mailed' => 'required|in:yes,no',
        ]);
        if ($validator->fails()) {
            Log::warning('Permission validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Permission::create([
                'user_id' => $userId,
                'from' => $request->from,
                'to' => $request->to,
                'reason' => $request->reason,
                'date' => $request->date,
                'informed_to' => $request->informed_to,
                'mailed' => $request->mailed,
            ]);
            DB::commit();
            return back()->with('success', 'Permission request submitted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Permission request failed', [
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Something went wrong')->withInput();
        }
    }
    public function final_logout_form(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remark' => 'nullable|string|max:255',
        ]);
        $user = Auth::guard('staff')->user();
        if (!$user) {
            return back()->with('error', 'Unauthorized access');
        }
        $userId = $user->id;
        if (
            Task::where('assign_to', $userId)
            ->where('task_status', 'inprogress')
            ->exists()
        ) {
            return back()->with(
                'error',
                'Task is in progress. Please hold or complete it before logout.'
            );
        }
        DB::beginTransaction();
        try {
            $today = Carbon::now()->toDateString();
            $loginEntry = Loginentries::where('user_id', $user->user_id)
                ->whereDate('created_at', $today)
                ->first();
            if (!$loginEntry) {
                DB::rollBack();
                return back()->with('error', 'Login entry not found for today');
            }
            if ($loginEntry->check_out) {
                DB::rollBack();
                return back()->with('error', 'Already Checked Out');
            }
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = 'logout_' . $userId . '_' . time() . '.' . $image->extension();
                $image->move(public_path('assets/logout'), $fileName);
                $imagePath = 'assets/logout/' . $fileName;
            }
            $loginEntry->update([
                'check_out' => now(),
                'image' => $imagePath,
                'remark' => $request->remark,
            ]);
            DB::commit();
            return back()->with('success', 'Check Out Successful');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Check Out failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Something went wrong');
        }
    }
    //Task Resume Function
    public function resume_task(Request $request)
    {
        DB::beginTransaction();
        try {
            $task = Task::findOrFail($request->task_id);
            $staffId = Auth::guard('staff')->id();
            Log::info('Resume Task Request', [
                'task_id' => $request->task_id,
                'staff_id' => $staffId
            ]);
             /*
        |---------------------------------------
        | CHECK-IN VALIDATION
        |---------------------------------------
        */

        $staff = Auth::guard('staff')->user();

        $userCode = $staff->user_id; // or employee_id based on your table

        $alreadyCheckedIn = Loginentries::where('user_id', $userCode)
            ->whereDate('check_in', Carbon::today('Asia/Kolkata'))
            ->exists();

        if (!$alreadyCheckedIn) {

            DB::rollBack();

            return back()->with('error', 'Please check-in before resuming the task.');
        }

            /*
            |---------------------------------------
            | AUTO END BREAK IF ACTIVE
            |---------------------------------------
            */
            BreakTime::where('user_id', $staffId)
                ->whereNull('break_end_time')
                ->update([
                    'break_end_time' => now()
                ]);
            /*
            |---------------------------------------
            | CHECK IF ANOTHER TASK IS IN PROGRESS
            |---------------------------------------
            */
            $alreadyInProgress = Task::where('assign_to', $staffId)
                ->where('task_status', 'inprogress')
                ->where('id', '!=', $task->id)
                ->exists();
            if ($alreadyInProgress) {
                DB::rollBack();
                Log::warning('Another task already in progress', [
                    'staff_id' => $staffId
                ]);
                return back()->with('error', 'Already one task is in progress.');
            }
            /*
            |---------------------------------------
            | UPDATE TASK STATUS
            |---------------------------------------
            */
            $task->task_status = 'inprogress';
            $task->save();
            /*
            |---------------------------------------
            | SAVE TASK HISTORY
            |---------------------------------------
            */
            TaskHistory::create([
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'staff_id' => $staffId,
                'status' => 'inprogress',
                'remark' => $request->remark,
            ]);
            DB::commit();
            Log::info('Task resumed successfully', [
                'task_id' => $task->id
            ]);
            return back()->with('success', 'Task resumed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Resume task error', [
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Something went wrong');
        }
    }
    // public function resume_task(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $task        = Task::findOrFail($request->task_id);
    //         $staffId     = Auth::guard('staff')->id();
    //         $breakActive = BreakTime::where('user_id', $staffId)
    //             ->whereNull('break_end_time')
    //             ->exists();
    //         if ($breakActive) {
    //             DB::rollBack();
    //             return back()->with('error', 'Please end your break before resuming the task.');
    //         }
    //         $alreadyInProgress = Task::where('assign_to', $staffId)
    //             ->where('task_status', 'inprogress')
    //             ->where('id', '!=', $task->id)
    //             ->exists();
    //         if ($alreadyInProgress) {
    //             DB::rollBack();
    //             return back()->with('error', 'Already one task is in progress.');
    //         }
    //         $task->task_status = 'inprogress';
    //         $task->save();
    //         $historyStatus = ($request->action === 'start')
    //             ? 'start'
    //             :  'inprogress';
    //         TaskHistory::create([
    //             'task_id'    => $task->id,
    //             'project_id' => $task->project_id,
    //             'staff_id'   => $staffId,
    //             'status'     => $historyStatus,
    //             'remark'     => $request->remark,
    //         ]);
    //         DB::commit();
    //         return back()->with('success', 'Task resumed successfully');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error($e->getMessage());
    //         return back()->with('error', 'Something went wrong');
    //     }
    // }
    public function final_logout()
    {
        $staffId    = Auth::guard('staff')->id();
        $staff      = Auth::guard('staff')->user();
        $staffCode  = $staff->user_id;
        $loginEntry = Loginentries::where('user_id', $staffCode)
            ->whereDate('check_in', today())
            ->first();
        $pendingTasks = Task::where('assign_to', $staffId)
            ->whereDate('due_date', '<', today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        Log::info('Check out validation started' . $loginEntry);
        $newTasks = Task::where('assign_to', $staffId)
            ->whereDate('start_date', '>=', today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        $completedTasks = Task::where('assign_to', $staffId)
            ->where('task_status', 'complete')
            ->whereDate('updated_at', today())
            ->count();
        $holdTasks = Task::where('assign_to', $staffId)
            ->where('task_status', 'hold')
            ->count();
        $staffCode = Auth::guard('staff')->user()->id;
        $breakHistory = BreakTime::where('user_id', $staffCode)
            ->whereDate('created_at', today())
            ->orderBy('break_start_time', 'asc')
            ->get();
        $totalBreakMinutes = 0;
        foreach ($breakHistory as $break) {
            if ($break->break_start_time && $break->break_end_time) {
                $start              = Carbon::parse($break->created_at->toDateString() . ' ' . $break->break_start_time);
                $end                = Carbon::parse($break->created_at->toDateString() . ' ' . $break->break_end_time);
                $totalBreakMinutes += $start->diffInMinutes($end);
            }
        }
        $breakHours          = floor($totalBreakMinutes / 60);
        $breakMins           = $totalBreakMinutes % 60;
        $totalBreakTime      = "{$breakHours}h {$breakMins}m";
        $completedTaskList   = collect();
        $totalWorkingMinutes = 0;
        $tasks               = Task::where('assign_to', $staffId)
            ->with([
                'project',
                'histories' => function ($q) {
                    $q->whereDate('created_at', today())
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->get();
        $startDay = today()->startOfDay();
        $endDay   = today()->endOfDay();
        foreach ($tasks as $task) {
            $workingStart = null;
            foreach ($task->histories as $history) {
                $historyTime = Carbon::parse($history->created_at);
                if (in_array($history->status, ['start', 'inprogress'])) {
                    $workingStart = $historyTime;
                }
                if (in_array($history->status, ['hold', 'complete', 'reassign']) && $workingStart) {
                    $workStart = $workingStart->copy();
                    $workEnd   = $historyTime->copy();
                    if ($workStart->lt($startDay))
                        $workStart = $startDay->copy();
                    if ($workEnd->gt($endDay))
                        $workEnd = $endDay->copy();
                    if ($workEnd->gt($workStart)) {
                        $minutes = $workStart->diffInMinutes($workEnd);
                        $totalWorkingMinutes += $minutes;
                        $hours = floor($minutes / 60);
                        $mins = $minutes % 60;
                        $completedTaskList->push([
                            'project'        => $task->project->project_name ?? '-',
                            'task'           => $task->task_name,
                            'estimated_time' => $task->estimated_time ?? '-',
                            'start_time'     => $workStart->format('h:i A'),
                            'end_time'       => $workEnd->format('h:i A'),
                            'working_hours'  => "{$hours}h {$mins}m",
                            'status'         => $history->status,
                            'sort_time'      => $workStart->timestamp,
                        ]);
                    }
                    $workingStart = null;
                }
            }
            if ($workingStart && $workingStart->between($startDay, $endDay)) {
                $now                  = now()->lt($endDay) ? now() : $endDay;
                $minutes              = $workingStart->diffInMinutes($now);
                $totalWorkingMinutes += $minutes;
                $hours                = floor($minutes / 60);
                $mins                 = $minutes % 60;
                $completedTaskList->push([
                    'project'       => $task->project->project_name ?? '-',
                    'task'          => $task->task_name,
                    'start_time'    => $workingStart->format('h:i A'),
                    'end_time'      => '-',
                    'working_hours' => "{$hours}h {$mins}m",
                    'status'        => 'inprogress',
                    'sort_time'     => $workingStart->timestamp,
                ]);
            }
        }
        $completedTaskList = $completedTaskList
            ->sortBy('sort_time')
            ->values();
        $totalHours           = floor($totalWorkingMinutes / 60);
        $totalMins            = $totalWorkingMinutes % 60;
        $totalProductiveHours = "{$totalHours}h {$totalMins}m";
        return view('Staff.final_logout', compact(
            'pendingTasks',
            'newTasks',
            'completedTasks',
            'holdTasks',
            'completedTaskList',
            'loginEntry',
            'totalProductiveHours',
            'breakHistory',
            'totalBreakTime'
        ));
    }
    public function update_profile_staff(Request $request)
    {
        Log::info('Staff profile update started');
        Log::info('Request data:', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::guard('staff')->id(),
            'mobile' => 'required|digits:10',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:800',
            'personal_email'  => 'nullable|email',
            'dob'             => 'nullable|date',
            'doj'             => 'nullable|date',
            'designation'     => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:500',


        ]);
        if ($validator->fails()) {
            Log::warning('Profile validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $user = Auth::guard('staff')->user();
            if (!$user) {
                Log::error('Staff not authenticated');
                return redirect()->back()->with('error', 'Unauthorized access');
            }
            if ($request->hasFile('profile_image')) {
                if (
                    $user->profile_image &&
                    Storage::disk('public')->exists($user->profile_image)
                ) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $path = $request->file('profile_image')
                    ->store('profile_images', 'public');
                $user->profile_image = $path;
            }
            // 4️⃣ Update staff profile
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                 'personal_email' => $request->personal_email,
                'dob' => $request->dob,
                'doj' => $request->doj,
                  'designation' => $request->designation,
                 'address' => $request->address,

            ]);
            DB::commit();
            Log::info('staff profile updated successfully', [
                'staff_id' => $user->id
            ]);
            return redirect()
                ->back()
                ->with('success', 'Profile updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Staff profile update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()
                ->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    // public function check_in(Request $request)
    // {
    //     try {
    //         $user = Auth::guard('staff')->user();
    //         if (!$user) {
    //             return back()->with('error', 'Unauthorized');
    //         }
    //         $alreadyCheckedIn = Loginentries::where('user_id', $user->user_id)
    //             ->whereDate('check_in', today())
    //             ->exists();
    //         if ($alreadyCheckedIn) {
    //             return back()->with('error', 'You have already checked in today');
    //         }
    //         $checkInTime = now();
    //         // 9:00 AM cutoff
    //         $isLate = $checkInTime->greaterThan(Carbon::createFromTime(9, 10, 0));
    //         $ipAddress = $request->ip();
    //         Loginentries::create([
    //             'user_id' => $user->user_id,
    //             'check_in' => $checkInTime,
    //             'type' => $isLate ? 'late' : 'on_time',
    //             'late_reason' => $isLate ? $request->late_reason : null,
    //             'latitude' => $request->latitude,
    //             'longitude' => $request->longitude,
    //             'ip_address' => $ipAddress,
    //         ]);
    //         return back()->with('success', 'Check-in recorded successfully');
    //     } catch (\Exception $e) {
    //         Log::error('check_in error', ['error' => $e->getMessage()]);
    //         return back()->with('error', 'Failed to check in');
    //     }
    // }
    public function check_in(Request $request)
    {
        $user = Auth::guard('staff')->user();
        if (!$user) {
            return back()->with('error', 'Unauthorized');
        }
        $alreadyCheckedIn = Loginentries::where('user_id', $user->user_id)
            ->whereDate('check_in', today())
            ->exists();
        if ($alreadyCheckedIn) {
            return back()->with('error', 'You have already checked in today');
        }
        $checkInTime = now();
        $isLate = $checkInTime->greaterThan(Carbon::createFromTime(9, 10, 0));
        if ($isLate) {
            $request->validate([
                'late_reason' => [
                    'required',
                    'string',
                    'min:5',
                    'regex:/^[A-Za-z\s]+$/'
                ],
            ]);
        }
        try {
            Loginentries::create([
                'user_id' => $user->user_id,
                'check_in' => $checkInTime,
                'type' => $isLate ? 'late' : 'on_time',
                'late_reason' => $isLate ? $request->late_reason : null,
                'ip_address' => $request->ip(),
            ]);
            return back()->with('success', 'Check-in recorded successfully');
        } catch (\Exception $e) {
            Log::error('check_in error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to check in');
        }
    }
    public function task_descriptions($task_id)
    {
        $staffId = Auth::guard('staff')->id();
        $task = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name',
            'histories' => function ($q) {}
        ])
            ->where('assign_to', $staffId)
            ->findOrFail($task_id);
        $task->reopen_count = $task->histories
            ->where('status', 'reopen')
            ->count();
        $task->hold_count = $task->histories
            ->where('status', 'hold')
            ->count();
        $task->histories->transform(function ($history) {
            if (in_array($history->status, ['hold', 'complete'])) {
                $history->display_remark = $history->remark ?: '-';
            } else {
                $history->display_remark = trim(
                    ($history->remark ?? '') . ' ' . ($history->new_status ?? '')
                ) ?: '-';
            }
            return $history;
        });
        return view('Staff.task_descriptions', compact('task'));
    }
    public function daily_login()
    {
        $staffId = Auth::guard('staff')->id();
        // $staffId = Auth::guard('staff')->user_id();
        // Log::info('User id' .$staffId);
        $staff = Auth::guard('staff')->user();
        $staffCode = $staff->user_id;
        $loginEntry = Loginentries::where('user_id', $staffCode)
            ->whereDate('check_in', today())
            ->first();
        $pendingTasks = Task::where('assign_to', $staffId)
            ->whereDate('due_date', '<', today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        // $loginEntry = Loginentries::where('user_id', $staffId)
        //     ->whereDate('check_in', today())
        //     ->first();
        Log::info('Check out validation started' . $loginEntry);
        $newTasks = Task::where('assign_to', $staffId)
            ->whereDate('start_date', '>=', today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        $completedTasks = Task::where('assign_to', $staffId)
            ->where('task_status', 'complete')
            ->whereDate('updated_at', today())
            ->count();
        $holdTasks = Task::where('assign_to', $staffId)
            ->where('task_status', 'hold')
            ->count();
        $newTaskList = Task::with('project')
            ->where('assign_to', $staffId)
            // ->whereDate('start_date', '>=', today())
            ->whereNotIn('task_status', ['complete'])
            ->get();
        return view('Staff.daily_login', compact('pendingTasks', 'newTasks', 'completedTasks', 'holdTasks', 'newTaskList', 'loginEntry'));
    }
    public function reminder()
    {
        $user = Auth::guard('staff')->user();
        $reminders = Reminder::where('user_id', $user->id)->get();
        return view("Staff.reminder", compact('reminders'));
    }
    public function reminder_data()
    {
        $user = Auth::guard('staff')->user();
        $query = Reminder::where('user_id', $user->id)->latest();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('title', fn($row) => $row->title ?? '-')
            ->editColumn('remind_to', fn($row) => $row->remind_to ?? '-')
            ->editColumn('description', fn($row) => $row->description ?? '-')
            ->editColumn('reminder_type', function ($row) {
                return $row->reminder_type
                    ? ucfirst(str_replace('_', ' ', $row->reminder_type))
                    : '-';
            })
            ->editColumn('date', function ($row) {
                return $row->date
                    ? \Carbon\Carbon::parse($row->date)->format('d-m-Y')
                    : '-';
            })
            ->editColumn('added_by', fn($row) => $row->added_by ?? '-')
            ->addColumn('action', function ($row) {
                $statusBadge = '-';
                if ($row->date) {
                    $today = now()->startOfDay();
                    $reminderDate = \Carbon\Carbon::parse($row->date)->startOfDay();
                    if ($row->is_active == 0) {
                        $statusBadge = '<span class="badge bg-label-secondary me-1">Completed</span>';
                    } else {
                        $statusBadge = '<span class="badge bg-label-success me-1 completeBtn"
        style          = "cursor:pointer"
        data-id        = "' . $row->id . '"
        data-bs-toggle = "modal"
        data-bs-target = "#completeModal">
        Active
    </span>';
                    }
                }
                $deleteBtn = '
        <button type           = "button"
                class          = "btn btn-sm deleteBtn"
                data-id        = "' . $row->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#delete">
        <i      class          = "fa fa-trash text-danger"></i>
        </button>';
                return $statusBadge . $deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create_reminder()
    {
        return view('Staff.create_reminder');
    }
    public function add_reminder(Request $request)
    {
        Log::info('Reminder validation started');
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'panel' => 'required|string',
            'bug_type' => 'required|string',
            'bug_title' => 'required|string|max:255',
            'module' => 'required|exists:modules,id',
            'debug_by' => 'required|exists:users,id',
            'priority' => 'required|in:Low,Medium,High',
            'testing_scenario' => 'required|string',
            'current_output' => 'required|string',
            'expected_output' => 'required|string',
            'attachment' => 'required|file'
        ]);
        if ($validator->fails()) {
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $base64 = base64_encode(file_get_contents($file));
                $mime = $file->getMimeType();
                session()->flash('old_attachment', 'data:' . $mime . ';base64,' . $base64);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            Reminder::create([
                'title' => $request->title,
                'remind_to' => $request->remind_to,
                'description' => $request->description,
                'reminder_type' => $request->reminder_type,
                'date' => $request->date,
                'user_id' => Auth::guard('staff')->id(),
                'added_by' => Auth::guard('staff')->user()->name,
            ]);
            DB::commit();
            Log::info('Reminder created successfully');
            return back()->with('success', 'Reminder created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reminder creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function staff_complete_reminder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reminders,id'
        ]);
        Log::info('Reminder status update process started', ['reminder_id' => $request->id]);
        try {
            DB::beginTransaction();
            $reminder = Reminder::findOrFail($request->id);
            $reminder->update([
                'is_active' => 0
            ]);
            DB::commit();
            Log::info('Reminder marked as completed successfully', ['reminder_id' => $request->id]);
            return back()->with('success', 'Reminder marked as completed!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to complete reminder', [
                'reminder_id' => $request->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function delete_reminder(Request $request)
    {
        try {
            Log::info('Delete Reminder Request Received', $request->all());
            if (!$request->id) {
                Log::warning('Reminder ID Missing');
                return redirect()->back()->with('error', 'Reminder ID missing');
            }
            $data = Reminder::find($request->id);
            if (!$data) {
                Log::warning('Reminder Not Found', ['id' => $request->id]);
                return redirect()->back()->with('error', 'Reminder not found');
            }
            $data->delete();
            Log::info('Reminder Deleted Successfully', ['id' => $request->id]);
            return redirect()->back()->with('success', 'Reminder deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Delete Reminder Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    // public function report()
    // {
    //     return view('Staff.report');
    // }
    public function report(Request $request)
    {
        $staff = auth()->guard('staff')->user();
        $tasks = Task::with(['histories', 'assignedStaff'])
            ->where('assign_to', $staff->id)
            ->get()
            ->groupBy('assign_to');
        $entries = Loginentries::where('user_id', $staff->user_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($row) {
                return Carbon::parse($row->created_at)->toDateString();
            });
        $loginHistories = collect();
        foreach ($tasks as $staffId => $staffTasks) {
            $staff = User::where('id', $staffId)
                ->where('is_active', 1)
                ->first();
            if (!$staff)
                continue;
            $userCode = $staff->user_id;
            $entries = Loginentries::where('user_id', $userCode)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy(function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                });
            $today = Carbon::today()->toDateString();
            if (!isset($entries[$today])) {
                $loginHistories->push([
                    'date_raw' => $today,
                    'date' => Carbon::parse($today)->format('d-m-Y'),
                    'staff_name' => $staff->name,
                    'check_in' => '-',
                    'check_out' => '-',
                    'status' => 'Absent',
                    'project_count' => 0,
                    'project_names' => '-',
                    'worked_hours' => '0h 0m',
                    'action' => '<span class="text-muted">No Activity</span>',
                ]);
            }
            $taskHistories = $staffTasks
                ->flatMap(function ($task) {
                    return $task->histories->map(function ($history) use ($task) {
                        return [
                            'task_id' => $task->id,
                            'project_id' => $task->project_id,
                            'date' => Carbon::parse($history->created_at)->toDateString(),
                            'status' => strtolower($history->status),
                            'time' => Carbon::parse($history->created_at),
                        ];
                    });
                })
                ->groupBy('date');
            foreach ($entries->keys()->sortDesc() as $dateKey) {
                $entry = $entries[$dateKey]->first();
                $projectCount = 0;
                $totalMinutes = 0;
                $projectNameList = '-';
                if (isset($taskHistories[$dateKey])) {
                    $dayHistories = collect($taskHistories[$dateKey])
                        ->sortBy('time');
                    $workedProjects = $dayHistories
                        ->filter(function ($history) {
                            return in_array($history['status'], ['start', 'inprogress', 'complete']);
                        })
                        ->pluck('project_id')
                        ->filter()
                        ->unique();
                    $projectCount = $workedProjects->count();
                    $projectNameList = Project::whereIn('id', $workedProjects)
                        ->pluck('project_name')
                        ->implode(', ');
                    $tasksGrouped = $dayHistories->groupBy('task_id');
                    foreach ($tasksGrouped as $histories) {
                        $workingStart = null;
                        foreach ($histories as $history) {
                            if (in_array($history['status'], ['start', 'inprogress'])) {
                                $workingStart = $history['time'];
                            }
                            if (in_array($history['status'], ['hold', 'complete', 'reassign']) && $workingStart) {
                                $totalMinutes += $workingStart->diffInMinutes($history['time']);
                                $workingStart = null;
                            }
                        }
                        if ($workingStart) {
                            $endTime = $entry->check_out
                                ? Carbon::parse($entry->check_out)
                                : now();
                            $totalMinutes += $workingStart->diffInMinutes($endTime);
                        }
                    }
                }
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                $loginHistories->push([
                    'date_raw' => $dateKey,
                    'date' => Carbon::parse($dateKey)->format('d-m-Y'),
                    'staff_name' => $staff->name,
                    'check_in' => $entry->check_in
                        ? Carbon::parse($entry->check_in)->format('h:i A')
                        : '-',
                    'check_out' => $entry->check_out
                        ? Carbon::parse($entry->check_out)->format('h:i A')
                        : '-',
                    // ✅ If check_in missing → Absent
                    'status' => $entry->check_in ? ($entry->type ?? 'Present') : 'Absent',
                    'project_count' => $projectCount,
                    'project_names' => $projectNameList ?: '-',
                    'worked_hours' => "{$hours}h {$minutes}m",
                    'action' => '<a href="' .
                        route('view_report', [
                            'staff_id' => $staff->id,
                            'date' => $dateKey
                        ]) .
                        '" class="btn btn-sm btn-warning">View</a>',
                ]);
            }
        }
        $loginHistories = $loginHistories
            ->sortByDesc('date_raw')
            ->values();
        return view('Staff.report', compact('loginHistories'));
    }
    public function view_report($staff_id, $date)
    {
        $startDay = Carbon::parse($date)->startOfDay();
        $endDay = Carbon::parse($date)->endOfDay();
        $employee = User::find($staff_id);
        $userCode = $employee->user_id;
        $userId = $employee->id;
        $loginEntry = Loginentries::where('user_id', $userCode)
            ->whereDate('created_at', $startDay)
            ->first();
        $checkin = $loginEntry && $loginEntry->check_in
            ? Carbon::parse($loginEntry->check_in)->format('h:i A')
            : null;
        $checkOut = $loginEntry && $loginEntry->check_out
            ? Carbon::parse($loginEntry->check_out)->format('h:i A')
            : null;
        $tasks = Task::where('assign_to', $staff_id)
            ->with([
                'project',
                'histories' => function ($q) use ($endDay) {
                    $q->where('created_at', '<=', $endDay)
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->get();
        $report = collect();
        $totalMinutes = 0;
        foreach ($tasks as $task) {
            $workingStart = null;
            foreach ($task->histories as $history) {
                $historyTime = Carbon::parse($history->created_at);
                if (in_array($history->status, ['start', 'inprogress']) && !$workingStart) {
                    $workingStart = $historyTime;
                }
                if (in_array($history->status, ['hold', 'complete', 'reassign']) && $workingStart) {
                    $workStart = $workingStart->copy();
                    $workEnd = $historyTime->copy();
                    if ($workStart->lt($startDay)) {
                        $workStart = $startDay->copy();
                    }
                    if ($workEnd->gt($endDay)) {
                        $workEnd = $endDay->copy();
                    }
                    if ($workEnd->gt($workStart)) {
                        $minutes = $workStart->diffInMinutes($workEnd);
                        $totalMinutes += $minutes;
                        $hours = floor($minutes / 60);
                        $mins = $minutes % 60;
                        $report->push([
                            'task_id' => $task->id,
                            'project' => $task->project->project_name ?? '-',
                            'task' => $task->task_name,
                            'start_time' => $workStart->format('h:i A'),
                            'end_time' => $workEnd->format('h:i A'),
                            'working_hours' => "{$hours}h {$mins}m",
                            'status' => $history->status,
                            'sort_time' => $workStart->timestamp,
                        ]);
                    }
                    $workingStart = null;
                }
            }
            if ($workingStart && $workingStart->between($startDay, $endDay)) {
                $now = now()->lt($endDay) ? now() : $endDay;
                $minutes = $workingStart->diffInMinutes($now);
                $totalMinutes += $minutes;
                $hours = floor($minutes / 60);
                $mins = $minutes % 60;
                $report->push([
                    'task_id' => $task->id,
                    'project' => $task->project->project_name ?? '-',
                    'task' => $task->task_name,
                    'start_time' => $workingStart->format('h:i A'),
                    'end_time' => '-',
                    'working_hours' => "{$hours}h {$mins}m",
                    'status' => 'inprogress',
                    'sort_time' => $workingStart->timestamp,
                ]);
            }
        }
        $report = $report
            ->sortBy('sort_time')
            ->values();
        $productiveHours = sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60);
        $breaks = BreakTime::where('user_id', $userId)
            ->whereDate('created_at', $startDay)
            ->get();
        $totalBreakSeconds = 0;
        foreach ($breaks as $break) {
            if ($break->break_start_time && $break->break_end_time) {
                $start = Carbon::parse($break->created_at->toDateString() . ' ' . $break->break_start_time);
                $end = Carbon::parse($break->created_at->toDateString() . ' ' . $break->break_end_time);
                $totalBreakSeconds += $start->diffInSeconds($end);
            }
        }
        $totalBreakHours = gmdate('H:i:s', $totalBreakSeconds);
        return view('Staff.view_report', compact(
            'report',
            'date',
            'employee',
            'productiveHours',
            'checkin',
            'checkOut',
            'totalMinutes',
            'totalBreakHours'
        ));
    }
    // public function break_start()
    // {
    //     DB::beginTransaction();
    //     try {
    //         $userId        = Auth::guard('staff')->user()->id;     // NX0011
    //         $staffId       = Auth::guard('staff')->id();
    //         $today         = Carbon::today();
    //         $existingBreak = BreakTime::where('user_id', $userId)
    //             ->whereDate('created_at', $today)
    //             ->whereNull('break_end_time')
    //             ->first();
    //         if (!$existingBreak) {
    //             BreakTime::create([
    //                 'user_id'          => $userId,
    //                 'break_start_time' => Carbon::now()->format('H:i:s'),
    //                 'created_at'       => now()
    //             ]);
    //             User::where('id', $userId)->update([
    //                 'is_break' => 1
    //             ]);
    //             $tasks = Task::where('assign_to', $staffId)
    //                 ->where('task_status', 'inprogress')
    //                 ->get();
    //             foreach ($tasks as $task) {
    //                 TaskHistory::create([
    //                     'task_id'    => $task->id,
    //                     'project_id' => $task->project_id,
    //                     'staff_id'   => $staffId,
    //                     'status'     => 'hold',
    //                     'remark'     => 'Break Started'
    //                 ]);
    //             }
    //         }
    //         DB::commit();
    //         return back()->with('success', 'Break Started');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('Break Start Error: ' . $e->getMessage());
    //         return back()->with('error', 'Something went wrong');
    //     }
    // }
    public function break_start()
    {
        DB::beginTransaction();
        try {
            $userId = Auth::guard('staff')->user()->id;
            $staffId = Auth::guard('staff')->id();
            $today = Carbon::today();
            $existingBreak = BreakTime::where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->whereNull('break_end_time')
                ->first();
            if ($existingBreak) {
                DB::commit();
                return back()->with('error', 'Break already started');
            }
            $inProgressTask = Task::where('assign_to', $staffId)
                ->where('task_status', 'inprogress')
                ->exists();
            if ($inProgressTask) {
                DB::rollback();
                return back()->with('error', 'Need to hold the task and start the break');
            }
            BreakTime::create([
                'user_id' => $userId,
                'break_start_time' => Carbon::now()->format('H:i:s'),
                'created_at' => now()
            ]);
            User::where('id', $userId)->update([
                'is_break' => 1
            ]);
            DB::commit();
            return back()->with('success', 'Break Started');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Break Start Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong');
        }
    }
    public function break_end()
    {
        DB::beginTransaction();
        try {
            $userId = Auth::guard('staff')->user()->id;
            $today = Carbon::today();
            $break = BreakTime::where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->whereNull('break_end_time')
                ->latest()
                ->first();
            if ($break) {
                $break->update([
                    'break_end_time' => Carbon::now()->format('H:i:s')
                ]);
                User::where('id', $userId)->update([
                    'is_break' => 0
                ]);
            }
            DB::commit();
            return back()->with('success', 'Break Ended');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Break End Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong');
        }
    }
    public function feed_back_submit()
    {
        return view('Staff.feed_back_submit');
    }

    public function add_feedback(Request $request)
    {
        Log::info('Feedback validation started');

        $validator = Validator::make($request->all(), [
            'positive_feedback'   => 'required|string|min:200',
            'negative_feedback'   => 'required|string|min:200',
            'suggestions'         => 'required|string|min:200',
            'additional_feedback'  => 'nullable|string|min:200',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            Feedback::create([
                'user_id'            => Auth::guard('staff')->id(),
                'positive_feedback'  => $request->positive_feedback,
                'negative_feedback'  => $request->negative_feedback,
                'suggestions'        => $request->suggestions,
                'additional_feedback' => $request->additional_feedback,
                'status'        => 'pending',
            ]);

            DB::commit();

            return back()->with('success', 'Feedback submitted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function feedback_list()
    {
        return view('Staff.feedback_list');
    }
    public function feedback_data(Request $request)
    {
        try {

            $staffId = Auth::guard('staff')->id();

            if (!$staffId) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $data = Feedback::where('user_id', $staffId)
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
                })
                ->editColumn('positive_feedback', function ($row) {
                    return '<span class="text-nowrap">' . Str::limit($row->positive_feedback ?? '-', 50) . '</span>';
                })

                ->editColumn('negative_feedback', function ($row) {
                    return '<span class="text-nowrap">' . Str::limit($row->negative_feedback ?? '-', 50) . '</span>';
                })




                ->addColumn('action', function ($row) {
                    return '<a href="' . route('view_feedbacks', $row->id) . '">
                            <i class="ti tabler-eye menu-icon"></i>
                        </a>';
                })


                ->rawColumns(['positive_feedback', 'negative_feedback', 'suggestions', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function view_feedbacks($id)
    {
        $feedback = Feedback::findOrFail($id);
        return view('Staff.view_feedbacks', compact('feedback'));
    }
    public function edit_bug()
    {
        return view('Staff.edit_bug');
    }
     public function table_mail()
    {
        return view('Staff.table_mail');
    }
    public function mail_report_list(Request $request)
{
    if ($request->ajax()) {

      $data = Communication::where(
        'user_id',
        Auth::guard('staff')->id()
    )
    ->latest()
    ->get();

        return DataTables::of($data)

            ->addIndexColumn()

            ->editColumn('created_at', function ($row) {

                return $row->created_at
                    ? $row->created_at->format('d M Y h:i A')
                    : '-';
            })

            ->editColumn('priority_level', function ($row) {

                if ($row->priority_level == 'High') {

                    return '
                        <span class="badge bg-label-danger">
                            High
                        </span>
                    ';
                }

                elseif ($row->priority_level == 'Medium') {

                    return '
                        <span class="badge bg-label-warning">
                            Medium
                        </span>
                    ';
                }

                else {

                    return '
                        <span class="badge bg-label-success">
                            Low
                        </span>
                    ';
                }
            })

            ->editColumn('reply_needed', function ($row) {

                return $row->reply_needed == 'Yes'
                    ? '<span class="badge bg-label-success">Yes</span>'
                    : '<span class="badge bg-label-secondary">No</span>';
            })

            ->editColumn('is_replied', function ($row) {

                return $row->is_replied == 1
                    ? '<span class="badge bg-label-success">Yes</span>'
                    : '<span class="badge bg-label-danger">No</span>';
            })

            ->editColumn('is_viewed', function ($row) {

                return $row->is_viewed == 1
                    ? '<span class="badge bg-label-success">Viewed</span>'
                    : '<span class="badge bg-label-warning">Pending</span>';
            })

            ->addColumn('action', function ($row) {

    return '
        <a href="' . route('read_mail', ['id' => $row->id]) . '"
            class="text-primary text-decoration-underline">
            View
        </a>
    ';
})

            ->rawColumns([
                'priority_level',
                'reply_needed',
                'is_replied',
                'is_viewed',
                'action'
            ])

            ->make(true);
    }
}
  
public function read_mail($id)
{
    $communication = Communication::with([
            'attachments',
            'replies.user'
        ])
        ->where('user_id', Auth::guard('staff')->id())
        ->findOrFail($id);

    $communication->update([
        'is_viewed' => 1
    ]);

    return view(
        'Staff.read_mail',
        compact('communication')
    );
}

public function store_communication_reply(Request $request, $id)
{
    Log::info('================ STORE COMMUNICATION REPLY START ================');

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | REQUEST LOG
        |--------------------------------------------------------------------------
        */
        Log::info('Reply Request Data', [
            'request' => $request->all(),
            'communication_id' => $id
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $validator = Validator::make($request->all(), [

            'subject'        => 'required|string|max:255',

            'content'        => 'required|string',

            'attachments.*'  => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip',

            'is_replied'     => 'required|in:Yes,No',

        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDATION FAILED
        |--------------------------------------------------------------------------
        */
        if ($validator->fails()) {

            Log::error('Reply Validation Failed', [
                'errors' => $validator->errors()->toArray()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Log::info('Reply Validation Passed');

        /*
        |--------------------------------------------------------------------------
        | GET COMMUNICATION
        |--------------------------------------------------------------------------
        */
        $communication = Communication::with('attachments')
            ->find($id);

        if (!$communication) {

            Log::error('Communication Not Found', [
                'communication_id' => $id
            ]);

            return redirect()->back()->with(
                'error',
                'Communication not found'
            );
        }

        Log::info('Communication Found', [
            'communication_id' => $communication->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | STORE ATTACHMENT
        |--------------------------------------------------------------------------
        */
        $uploadedAttachment = null;

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                Log::info('Uploading Reply Attachment', [
                    'file_name' => $file->getClientOriginalName(),
                    'size'      => $file->getSize(),
                ]);

                $path = $file->store(
                    'communication_reply_files',
                    'public'
                );

                Log::info('Reply Attachment Uploaded', [
                    'path' => $path
                ]);

                $uploadedAttachment = $path;

                /*
                |--------------------------------------------------------------------------
                | SAVE ATTACHMENT TABLE
                |--------------------------------------------------------------------------
                */
                CommunicationAttachment::create([

                    'communication_id' => $communication->id,

                    'file_name'        => $file->getClientOriginalName(),

                    'file_path'        => $path,

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STORE REPLY
        |--------------------------------------------------------------------------
        */
        $staff = Auth::guard('staff')->user();

        $reply = CommunicationReply::create([

            'communication_id' => $communication->id,

            'user_id'          => $staff->id,

            'reply_from'       => 'staff',

            'message'          => $request->content,

            'attachment'       => $uploadedAttachment,

            'is_read'          => 0,

        ]);

        Log::info('Communication Reply Stored', [
            'reply_id' => $reply->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE COMMUNICATION
        |--------------------------------------------------------------------------
        */
        $communication->update([

            'is_replied' => $request->is_replied == 'Yes' ? 1 : 0,

            'status'     => 'replied',

        ]);

        Log::info('Communication Updated', [
            'communication_id' => $communication->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET ADMIN USER
        |--------------------------------------------------------------------------
        */
        $admin = User::where('role', 'admin')->first();

        if ($admin) {

            /*
            |--------------------------------------------------------------------------
            | PREPARE MAIL ATTACHMENTS
            |--------------------------------------------------------------------------
            */
            $attachments = [];

            if ($request->hasFile('attachments')) {

                foreach ($request->file('attachments') as $file) {

                    $attachments[] = [

                        'name' => $file->getClientOriginalName(),

                        'content' => base64_encode(
                            file_get_contents($file->getRealPath())
                        )

                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | HTML MAIL CONTENT
            |--------------------------------------------------------------------------
            */
            $htmlContent = '

                <div style="font-family:Arial;padding:20px;">

                    <h2 style="color:#333;">
                        ' . $request->subject . '
                    </h2>

                    <p style="
                        font-size:15px;
                        line-height:24px;
                        color:#555;
                    ">
                        ' . nl2br($request->content) . '
                    </p>

                    <br>

                    <p>
                        Regards,<br>
                        ' . env('BREVO_SENDER_NAME') . '
                    </p>

                </div>
            ';

            /*
            |--------------------------------------------------------------------------
            | SEND MAIL TO ADMIN
            |--------------------------------------------------------------------------
            */
            Log::info('Sending Reply Mail To Admin', [
                'to_email' => $admin->email
            ]);

            $mailResponse = $this->sendBrevoMail(

                $admin->email,

                $admin->name,

                $request->subject,

                $htmlContent,

                $attachments

            );

            Log::info('Reply Mail Response', [
                'response' => $mailResponse
            ]);
        } else {

            Log::warning('No Admin Found In Users Table');
        }

        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */
        DB::commit();

        Log::info('================ STORE COMMUNICATION REPLY END ================');

        return redirect()->back()->with(
            'success',
            'Reply sent successfully'
        );

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('================ STORE COMMUNICATION REPLY ERROR ================', [

            'message' => $e->getMessage(),

            'line'    => $e->getLine(),

            'file'    => $e->getFile(),

            'trace'   => $e->getTraceAsString()

        ]);

        return redirect()->back()->with(
            'error',
            $e->getMessage()
        );
    }
}
}
