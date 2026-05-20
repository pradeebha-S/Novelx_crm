<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Feedback;
use App\Models\Loginentries;
use App\Models\InternAttendance;
use App\Models\Modules;
use App\Models\Personal;
use App\Models\Project;
use App\Models\Reminder;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use App\Models\Leave;
use App\Models\Wfh;
use App\Models\Permission;
use App\Models\TaskHistory;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Chapter;
use App\Models\StudentTask;
use App\Models\BreakTime;
use App\Models\Invoice;
use App\Models\StudentTaskHistory;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Communication;
use App\Models\CommunicationAttachment;
use App\Models\ProjectDocuments;
use App\Models\Document;
use App\Models\Popup;
use Hash;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Storage;

use App\Models\CommunicationReply;
use Yajra\DataTables\Facades\DataTables;
use Carbon\CarbonPeriod;
use App\Traits\BrevoMailTrait;
class AdminController extends Controller
{
    use BrevoMailTrait;
    public function checkIdleUsers()
    {
        $now = Carbon::now();
        $day = $now->format('l');
        $time = $now->format('H:i');
        // 🔴 Sunday - No notifications
        if ($day == 'Sunday') {
            return response()->json([
                'status' => true,
                'message' => 'Sunday - Notifications disabled'
            ]);
        }
        // --------------------------------------------------
        // 🔹 Working Time Rules
        // --------------------------------------------------
        if ($day == 'Saturday') {
            if ($time < '10:00') {
                return response()->json([
                    'status' => true,
                    'message' => 'Notifications allowed after 10 AM on Saturday'
                ]);
            }
        } else {
            if ($time <= '09:00') {
                return response()->json([
                    'status' => true,
                    'message' => 'Notifications allowed after 9 AM'
                ]);
            }
        }
        // --------------------------------------------------
        // 🔹 Get Active Users
        // --------------------------------------------------
        $users = User::where('is_active', 1)->get();
        foreach ($users as $user) {
            // --------------------------------------------------
            // 🔹 Skip if user is on break
            // --------------------------------------------------
            if ($user->is_break == 1) {
                continue;
            }
            // --------------------------------------------------
            // 🔹 Leave Check
            // --------------------------------------------------
            $isLeave = Leave::where('user_id', $user->id)
                ->whereDate('from', '<=', Carbon::today())
                ->whereDate('to', '>=', Carbon::today())
                ->exists();
            if ($isLeave) {
                continue;
            }
            // --------------------------------------------------
            // 🔹 Morning Permission Check
            // --------------------------------------------------
            $hasMorningPermission = Permission::where('user_id', $user->id)
                ->where('from', '<=', Carbon::now())
                ->where('to', '>=', Carbon::now())
                ->exists();
            if ($hasMorningPermission) {
                continue;
            }
            // --------------------------------------------------
            // 🔹 Check-in Status
            // --------------------------------------------------
            $checkin = Loginentries::where('user_id', $user->user_id)
                ->whereDate('check_in', Carbon::today())
                ->first();
            // --------------------------------------------------
            // 🔹 Check-out Status
            // --------------------------------------------------
            $checkout = Loginentries::where('user_id', $user->user_id)
                ->whereDate('check_in', Carbon::today())
                ->whereNotNull('check_out')
                ->exists();
            // --------------------------------------------------
            // 🔹 Task Status
            // --------------------------------------------------
            $taskInProgress = Task::where('assign_to', $user->id)
                ->where('task_status', 'inprogress')
                ->exists();
            // --------------------------------------------------
            // 🔹 1️⃣ Check-in Reminder
            // From 9 AM notify users to check in
            // --------------------------------------------------
            if (!$checkin && $time >= '09:00') {
                webpushnotify(
                    $user->id,
                    'Check In Reminder',
                    'Please check in to start your work day.'
                );
                continue;
            }
            // --------------------------------------------------
            // 🔹 2️⃣ Task In Progress Reminder
            // -------------------------------------------------
            if ($checkin && !$taskInProgress && $time < '18:00') {
                webpushnotify(
                    $user->id,
                    'Task Reminder',
                    'Make your task In Progress to maintain 8 productive hours.'
                );
            }
            if ($checkin && $taskInProgress && $time >= '13:00' && $time <= '13:15') {
                webpushnotify(
                    $user->id,
                    'Task Reminder',
                    'Take your Break for lunch Break in before 2.'
                );
            }
            // --------------------------------------------------
            // 🔹 3️⃣ Checkout Reminder
            // --------------------------------------------------
            if ($time >= '18:05' && $time <= '19:00') {
                if ($checkin && !$checkout) {
                    webpushnotify(
                        $user->id,
                        'Checkout Reminder',
                        'Please check out and continue your pending task as In Progress if needed.'
                    );
                }
            }
            // --------------------------------------------------
            // 🔹 4️⃣ Restart Task Reminder (6:30 PM - 6:40 PM)
            // --------------------------------------------------
            if ($time >= '18:30' && $time <= '18:40') {
                if ($checkin && $taskInProgress) {
                    // 🔔 Notification
                    webpushnotify(
                        $user->id,
                        'Task Update',
                        'Please restart your task.'
                    );
                    // 🔄 Update task status to HOLD
                    $tasks = Task::where('assign_to', $user->id)
                        ->where('task_status', 'inprogress')
                        ->get();
                    foreach ($tasks as $task) {
                        $task->update([
                            'task_status' => 'hold'
                        ]);
                        TaskHistory::create([
                            'task_id' => $task->id,
                            'project_id' => $task->project_id,
                            'staff_id' => $user->id,
                            'status' => 'hold',
                            'remark' => 'Auto hold after 6:30 PM'
                        ]);
                    }
                }
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Notifications processed successfully',
            'user_count' => $users->count()
        ]);
    }
    public function hold_tasks()
    {
        return view('Admin.hold_tasks');
    }
    public function view_doc($project_id = null)
    {
        \Log::info('Project ID:', ['id' => $project_id]);
        $project = $project_id ? Project::find($project_id) : null;
        $documents = $project_id
            ? Document::where('project_id', $project_id)->get()
            : Document::all();
        \Log::info('Documents Count:', ['count' => $documents->count()]);
        return view('Admin.view_doc', compact('project', 'documents', 'project_id'));
    }
    public function view_credentials($project_id = null)
    {
        if ($project_id) {
            $project = Project::find($project_id); // ✅ ADD THIS
            $credentials = ProjectDocuments::where('project_id', $project_id)->get();
        } else {
            $project = null; // ✅ IMPORTANT
            $credentials = ProjectDocuments::all();
        }
        return view('Admin.view_credentials', compact('credentials', 'project_id', 'project'));
    }
    // public function view_credentials($project_id = null)
    // {
    //     if ($project_id) {
    //         // Specific project credentials
    //         $credentials = ProjectDocuments::where('project_id', $project_id)->get();
    //     } else {
    //         // All credentials
    //         $credentials = ProjectDocuments::all();
    //     }
    //     return view('Admin.view_credentials', compact('credentials', 'project_id'));
    // }
    public function add_document($id = null)
    {
        $project = $id ? Project::find($id) : null;
        $projects = Project::all();
        return view('Admin.add_document', compact('project', 'projects'));
    }
    // public function view_credentials($id)
    // {
    //     return view('Admin.view_credentials', compact('id'));
    // }
    public function upload_credentials($id = null)
    {
        if ($id) {
            $project = Project::find($id);
        } else {
            $project = null;
        }
        // ✅ ADD THIS
        $projects = Project::all();
        return view('Admin.upload_credentials', compact('project', 'projects'));
    }
    public function admin_hold_tasks_data()
    {
        $tasks = Task::with([
            'project',
            'module',
            'assignedStaff'
        ])
            ->where('task_status', 'hold')
            ->latest()
            ->get();
        $data = [];
        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->id,
                'project' => optional($task->project)->project_name ?? '-',
                'module' => optional($task->module)->module_name ?? '-',
                'task' => $task->task_name,
                'employee' => optional($task->assignedStaff)->name ?? '-',
                'status' => 'On Hold'
            ];
        }
        return response()->json($data);
    }
    public function weekly_report(Request $request, $staff_id)
    {
        $staff = User::findOrFail($staff_id);
        $userCode = $staff->user_id;
        $entries = Loginentries::where('user_id', $userCode)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($row) {
                return Carbon::parse($row->created_at)->toDateString();
            });
        $taskHistories = Task::where('assign_to', $staff->id)
            ->with('histories')
            ->get()
            ->flatMap(function ($task) {
                return $task->histories->map(function ($history) use ($task) {
                    return [
                        'task_id' => $task->id,
                        'project_id' => $task->project_id,
                        'date' => Carbon::parse($history->created_at)->toDateString(),
                        'status' => $history->status,
                        'time' => Carbon::parse($history->created_at),
                    ];
                });
            })
            ->groupBy('date');
        $loginHistories = collect();
        foreach ($entries->keys()->sortDesc() as $dateKey) {
            $entry = $entries[$dateKey]->first();
            $projectCount = 0;
            $totalMinutes = 0;
            $projectNameList = '-';
            if (isset($taskHistories[$dateKey])) {
                $dayHistories = collect($taskHistories[$dateKey])
                    ->sortBy('time');
                /* ✅ Correct Project Count */
                $workedProjects = $dayHistories
                    ->filter(function ($history) {
                        return in_array(strtolower($history['status']), ['start', 'inprogress', 'complete']);
                    })
                    ->pluck('project_id')
                    ->filter()
                    ->unique();
                $projectNameList = Project::whereIn('id', $workedProjects)
                    ->pluck('project_name')
                    ->implode(', ');
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
                            $totalMinutes += $workingStart
                                ->diffInMinutes($history['time']);
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
            $workedHours = "{$hours}h {$minutes}m";
            $loginHistories->push([
                'date' => Carbon::parse($dateKey)->format('d-m-Y'),
                'check_in' => $entry->check_in
                    ? Carbon::parse($entry->check_in)->format('h:i A')
                    : '-',
                'check_out' => $entry->check_out
                    ? Carbon::parse($entry->check_out)->format('h:i A')
                    : '-',
                'status' => $entry->type ?? 'Present',
                'project_count' => $projectCount,
                'project_names' => $projectNameList ?: '-',
                'worked_hours' => $workedHours,
                'action' => '<a href="' .
                    route('view_weekly_report', [
                        'staff_id' => $staff->id,
                        'date' => $dateKey
                    ]) .
                    '" class="btn btn-sm btn-warning mt-1">View</a>',
            ]);
        }
        return view('Admin.weekly_report', compact('staff', 'loginHistories'));
    }
    public function view_weekly_report($staff_id, $date)
    {
        $startDay = Carbon::parse($date)->startOfDay();
        $endDay = Carbon::parse($date)->endOfDay();
        $employee = User::find($staff_id);
        $userCode = $employee->user_id;
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
                        $estimatedHours = (int) filter_var($task->estimated_time, FILTER_SANITIZE_NUMBER_INT);
                        $estimatedMinutes = $estimatedHours * 60;
                        $extraMinutes = $minutes - $estimatedMinutes;
                        $extraHours = floor(abs($extraMinutes) / 60);
                        $extraMins = abs($extraMinutes) % 60;
                        $report->push([
                            'task_id' => $task->id,
                            'project' => $task->project->project_name ?? '-',
                            'task' => $task->task_name,
                            'estimated_time' => $task->estimated_time,
                            'start_time' => $workStart->format('h:i A'),
                            'end_time' => $workEnd->format('h:i A'),
                            'working_hours' => "{$hours}h {$mins}m",
                            'extra_time' => $extraMinutes > 0
                                ? "{$extraHours}h {$extraMins}m"
                                : "0h 0m",
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
                $estimatedMinutes = ($task->estimated_time ?? 0) * 60;
                $extraMinutes = $minutes - $estimatedMinutes;
                $extraHours = floor(abs($extraMinutes) / 60);
                $extraMins = abs($extraMinutes) % 60;
                $report->push([
                    'task_id' => $task->id,
                    'project' => $task->project->project_name ?? '-',
                    'task' => $task->task_name,
                    'estimated_time' => $task->estimated_time,
                    'start_time' => $workingStart->format('h:i A'),
                    'end_time' => '-',
                    'working_hours' => "{$hours}h {$mins}m",
                    'extra_time' => $extraMinutes > 0
                        ? "{$extraHours}h {$extraMins}m"
                        : "0h 0m",
                    'status' => 'inprogress',
                    'sort_time' => $workingStart->timestamp,
                ]);
            }
        }
        $report = $report
            ->sortBy('sort_time')
            ->values();
        $productiveHours = sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60);
        $today = Carbon::today();
        $breaks = BreakTime::where('user_id', $employee->id)
            ->whereDate('created_at', $today)
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
        return view('Admin.view_weekly_report', compact(
            'report',
            'date',
            'employee',
            'productiveHours',
            'checkin',
            'checkOut',
            'totalMinutes',
            'totalBreakHours',
            'breaks'
        ));
    }
    // public function staff_report(Request $request)
    // {
    //     $tasks = Task::with(['histories', 'assignedStaff'])
    //         ->get()
    //         ->groupBy('assign_to');
    //     $loginHistories = collect();
    //     foreach ($tasks as $staffId => $staffTasks) {
    //         $staff = User::where('id', $staffId)
    //             ->where('is_active', 1)
    //             ->first();
    //         if (!$staff)
    //             continue;
    //         $userCode = $staff->user_id;
    //         $entries = Loginentries::where('user_id', $userCode)
    //             ->orderBy('created_at', 'desc')
    //             ->get()
    //             ->groupBy(function ($row) {
    //                 return Carbon::parse($row->created_at)->toDateString();
    //             });
    //         $today = Carbon::today()->toDateString();
    //         if (!isset($entries[$today])) {
    //             $loginHistories->push([
    //                 'user_id' => $staff->id,
    //                 'date_raw' => $today,
    //                 'date' => Carbon::parse($today)->format('d-m-Y'),
    //                 'staff_name' => $staff->name,
    //                 'check_in' => '-',
    //                 'check_out' => '-',
    //                 'status' => 'Absent',
    //                 'project_count' => 0,
    //                 'project_names' => '-',
    //                 'worked_hours' => '0h 0m',
    //                 'action' => '<span class="text-muted">No Activity</span>',
    //             ]);
    //         }
    //         $taskHistories = $staffTasks
    //             ->flatMap(function ($task) {
    //                 return $task->histories->map(function ($history) use ($task) {
    //                     return [
    //                         'task_id' => $task->id,
    //                         'project_id' => $task->project_id,
    //                         'date' => Carbon::parse($history->created_at)->toDateString(),
    //                         'status' => strtolower($history->status),
    //                         'time' => Carbon::parse($history->created_at),
    //                     ];
    //                 });
    //             })
    //             ->groupBy('date');
    //         foreach ($entries->keys()->sortDesc() as $dateKey) {
    //             $date = $dateKey;
    //             $entry = $entries[$dateKey]->first();
    //             $projectCount = 0;
    //             $totalMinutes = 0;
    //             $projectNameList = '-';
    //             if (isset($taskHistories[$dateKey])) {
    //                 $dayHistories = collect($taskHistories[$dateKey])
    //                     ->sortBy('time');
    //                 $workedProjects = $dayHistories
    //                     ->filter(function ($history) {
    //                         return in_array($history['status'], ['start', 'inprogress', 'complete']);
    //                     })
    //                     ->pluck('project_id')
    //                     ->filter()
    //                     ->unique();
    //                 $projectCount = $workedProjects->count();
    //                 $projectNameList = Project::whereIn('id', $workedProjects)
    //                     ->pluck('project_name')
    //                     ->implode(', ');
    //                 $tasksGrouped = $dayHistories->groupBy('task_id');
    //                 foreach ($tasksGrouped as $histories) {
    //                     $workingStart = null;
    //                     foreach ($histories as $history) {
    //                         if (in_array($history['status'], ['start', 'inprogress'])) {
    //                             $workingStart = $history['time'];
    //                         }
    //                         if (in_array($history['status'], ['hold', 'complete', 'reassign']) && $workingStart) {
    //                             $totalMinutes += $workingStart->diffInMinutes($history['time']);
    //                             $workingStart = null;
    //                         }
    //                     }
    //                     if ($workingStart) {
    //                         $endTime = $entry->check_out
    //                             ? Carbon::parse($entry->check_out)
    //                             : now();
    //                         $totalMinutes += $workingStart->diffInMinutes($endTime);
    //                     }
    //                 }
    //             }
    //             $hours = floor($totalMinutes / 60);
    //             $minutes = $totalMinutes % 60;
    //             $loginHistories->push([
    //                 'user_id' => $staff->id,
    //                 'date_raw' => $dateKey,
    //                 'date' => Carbon::parse($dateKey)->format('d-m-Y'),
    //                 'staff_name' => $staff->name,
    //                 'check_in' => $entry->check_in
    //                     ? Carbon::parse($entry->check_in)->format('h:i A')
    //                     : '-',
    //                 'check_out' => $entry->check_out
    //                     ? Carbon::parse($entry->check_out)->format('h:i A')
    //                     : '-',
    //                 // ✅ If check_in missing → Absent
    //                 'status' => $entry->check_in ? ($entry->type ?? 'Present') : 'Absent',
    //                 'project_count' => $projectCount,
    //                 'project_names' => $projectNameList ?: '-',
    //                 'worked_hours' => "{$hours}h {$minutes}m",
    //                 'action' => '<a href="' .
    //                     route('view_weekly_report', [
    //                         'staff_id' => $staff->id,
    //                         'date' => $dateKey
    //                     ]) .
    //                     '" class="btn btn-sm btn-warning">View</a>',
    //             ]);
    //         }
    //     }
    //     $loginHistories = $loginHistories
    //         ->sortByDesc('date_raw')
    //         ->values();
    //     $staffList = User::where('is_active', 1)->where('role', 'staff')->get();
    //     $wfhStaff = Wfh::where('is_replied', 1)
    //         ->select('user_id', 'from', 'to')
    //         ->get();
    //     $leaveStaff = Leave::join('users', 'users.id', '=', 'leaves.user_id')
    //         ->leftJoin('loginentries', function ($join) {
    //             $join->on('loginentries.user_id', '=', 'users.user_id')
    //                 ->whereRaw('DATE(loginentries.created_at) = DATE(leaves.from)');
    //         })
    //         ->where('leaves.is_replied', 1)
    //         ->whereNull('loginentries.check_in')
    //         ->select('users.id as user_id', 'leaves.from')
    //         ->get();
    //     $permissionStaff = Permission::where('reply', 'approved')
    //         ->select('user_id', 'created_at')
    //         ->get();
    //     return view('Admin.staff_report', compact('loginHistories', 'staffList', 'wfhStaff', 'leaveStaff', 'permissionStaff'));
    // }
    public function staff_report(Request $request)
    {
        $tasks = Task::with(['histories', 'assignedStaff'])
            ->get()
            ->groupBy('assign_to');
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
                    'user_id' => $staff->id,
                    'date_raw' => $today,
                    'date' => Carbon::parse($today)->format('d-m-Y'),
                    'staff_name' => $staff->name,
                    'check_in' => '-',
                    'check_out' => '-',
                    'status' => 'Absent',
                    'project_count' => 0,
                    'project_names' => '-',
                    'worked_hours' => '0h 0m',
                    'estimate_time' => '0h 0m',
                    'extra_hours' => '0h 0m',
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
                $taskIds = TaskHistory::whereIn('status', ['inprogress', 'complete', 'hold'])
                    ->where('staff_id', $staffId)
                    ->whereDate('created_at', $dateKey)
                    ->pluck('task_id')
                    ->unique();
                $estimateMinutes = 0;
                $tasksEstimated = Task::whereIn('id', $taskIds)->get();
                foreach ($tasksEstimated as $task) {
                    $hours = (int) filter_var($task->estimated_time, FILTER_SANITIZE_NUMBER_INT);
                    $estimateMinutes += $hours * 60;
                }
                $estimateHours = floor($estimateMinutes / 60);
                $estimateMin = $estimateMinutes % 60;
                $extraMinutes = $totalMinutes - $estimateMinutes;
                if ($extraMinutes > 0) {
                    $extraHours = floor($extraMinutes / 60);
                    $extraMin = $extraMinutes % 60;
                } else {
                    $extraHours = 0;
                    $extraMin = 0;
                }
                $loginHistories->push([
                    'user_id' => $staff->id,
                    'date_raw' => $dateKey,
                    'date' => Carbon::parse($dateKey)->format('d-m-Y'),
                    'staff_name' => $staff->name,
                    'check_in' => $entry->check_in ? Carbon::parse($entry->check_in)->format('h:i A') : '-',
                    'check_out' => $entry->check_out ? Carbon::parse($entry->check_out)->format('h:i A') : '-',
                    'status' => $entry->check_in ? ($entry->type ?? 'Present') : 'Absent',
                    'project_count' => $projectCount,
                    'project_names' => $projectNameList ?: '-',
                    'estimate_time' => "{$estimateHours}h {$estimateMin}m",
                    'extra_hours' => $extraMinutes > 0
                        ? "{$extraHours}h {$extraMin}m"
                        : "0h 0m",
                    'worked_hours' => "{$hours}h {$minutes}m",
                    'action' => '<a href="' .
                        route('view_weekly_report', [
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
        $staffList = User::where('is_active', 1)->where('role', 'staff')->get();
        $wfhStaff = Wfh::where('reply', 'approved')
            ->select('user_id', 'from', 'to')
            ->get();
        $leaveStaff = Leave::join('users', 'users.id', '=', 'leaves.user_id')
            ->leftJoin('loginentries', function ($join) {
                $join->on('loginentries.user_id', '=', 'users.user_id')
                    ->whereRaw('DATE(loginentries.created_at) BETWEEN DATE(leaves.from) AND DATE(leaves.to)');
            })
            ->where('leaves.reply', 'approved')
            ->whereNull('loginentries.check_in')
            ->select(
                'users.id as user_id',
                'leaves.from',
                'leaves.to'
            )
            ->get();
        $permissionStaff = Permission::where('reply', 'approved')
            ->select('user_id', 'created_at')
            ->get();
        return view('Admin.staff_report', compact(
            'loginHistories',
            'staffList',
            'wfhStaff',
            'leaveStaff',
            'permissionStaff'
        ));
    }
    public function login()
    {
        return view('Admin.login');
    }
    public function forget_password()
    {
        return view('Admin.forget_password');
    }
    public function change_password(Request $request)
    {
        if (!$request->has('email')) {
            return redirect()->route('login')->with('error', 'Reset link is missing.');
        }
        $email = $request->email;
        return view('Admin.change_password', compact('email'));
    }
    // public function dashboard()
    // {
    //     //staff
    //     $staffCount          = User::where('role', 'staff')->count();
    //     $todaytask           = Task::whereDate('start_date', Carbon::today())->count();
    //     $todayCompletedCount = Task::where('task_status', 'complete')
    //         ->whereDate('updated_at', Carbon::today())->count();
    //     $projectCount = Project::count();
    //     $presentCount    = Loginentries::whereDate('created_at', Carbon::today())->whereNotNull('check_in')->count();
    //     $pendingCount    = Task::where('task_status', '!=', 'complete')->count();
    //     $inprogressCount = Task::where('task_status', 'inprogress')->count();
    //     //student
    //     $studentPresentCount    = InternAttendance::whereDate('created_at', Carbon::today())->whereNotNull('check_in')->count();
    //     $studentCount          = User::where('role', 'intern')->count();
    //     //project status
    //     $projects = Project::withCount([
    //         'tasks as total_tasks',
    //         'tasks as completed_tasks' => function ($q) {
    //             $q->where('task_status', 'complete');
    //         }
    //     ])->get()->map(function ($project) {
    //         $percentage = $project->total_tasks > 0
    //             ? round(($project->completed_tasks / $project->total_tasks) * 100)
    //             : 0;
    //         return [
    //             'id' => $project->id,
    //             'name' => $project->project_name,
    //             'total' => $project->total_tasks,
    //             'completed' => $project->completed_tasks,
    //             'percentage' => $percentage,
    //         ];
    //     })
    //         ->sortByDesc('percentage')
    //         ->values();
    //     //staff status
    //     $staffs = User::where('role', 'staff')->where('is_active', 1)->get();
    //     $staffTaskStatus = $staffs->map(function ($staff) {
    //         $newTasks = Task::where('assign_to', $staff->id)
    //             ->where('task_status', '!=', 'complete')
    //             ->where('due_date', Carbon::today())
    //             ->count();
    //         $pendingTasks = Task::where('assign_to', $staff->id)
    //             ->whereDate('start_date', '<', Carbon::today())
    //             ->where('task_status', '!=', 'complete')
    //             ->where('task_status', '!=', 'hold')
    //             ->count();
    //         $holdTasks = Task::where('assign_to', $staff->id)
    //             ->where('task_status', 'hold')
    //             ->count();
    //         $completedTasks = Task::where('assign_to', $staff->id)
    //             ->where('task_status', 'complete')
    //             ->count();
    //         $totalTasks = Task::where('assign_to', $staff->id)->count();
    //         $inprogressTasks = Task::where('assign_to', $staff->id)
    //             ->where('task_status', 'inprogress')
    //             ->count();
    //         $reopenCount = DB::table('task_histories')
    //             ->join('tasks', 'tasks.id', '=', 'task_histories.task_id')
    //             ->where('tasks.assign_to', $staff->id)
    //             ->where('task_histories.status', 'reopen')
    //             ->count();
    //         $percentage = $totalTasks > 0
    //             ? round(($completedTasks / $totalTasks) * 100)
    //             : 0;
    //         return [
    //             'id'            => $staff->id,
    //             'name'          => $staff->name,
    //             'new'           => $newTasks,
    //             'pending'       => $pendingTasks,
    //             'hold'          => $holdTasks,
    //             'completed'     => $completedTasks,
    //             'total'         => $totalTasks,
    //             'percentage'    => $percentage,
    //             'inprogress' => $inprogressTasks,
    //             'reopen' => $reopenCount,
    //         ];
    //     })
    //         ->sortByDesc('percentage')
    //         ->values();
    //     $today_reminders = Reminder::where('is_active', 1)
    //         ->whereDate('date', '<=', Carbon::today())
    //         ->orderBy('date', 'desc')
    //         ->get();
    //     return view('Admin.dashboard', compact(
    //         'staffCount',
    //         'projectCount',
    //         'pendingCount',
    //         'inprogressCount',
    //         'presentCount',
    //         'todaytask',
    //         'todayCompletedCount',
    //         'studentPresentCount',
    //         'studentCount',
    //         'staffTaskStatus',
    //         'projects',
    //         'today_reminders'
    //     ));
    // }
    public function projectSearch(Request $request)
    {
        $search = $request->search;
        $projects = Project::where('project_name', 'LIKE', "%$search%")
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => function ($q) {
                    $q->where('task_status', 'complete');
                },
                'modules as total_modules'
            ])
            ->get()
            ->map(function ($project) {
                $percentage = $project->total_tasks > 0
                    ? round(($project->completed_tasks / $project->total_tasks) * 100)
                    : 0;
                return [
                    'id' => $project->id,
                    'name' => $project->project_name,
                    'total' => $project->total_tasks,
                    'completed' => $project->completed_tasks,
                    'modules' => $project->total_modules,
                    'percentage' => $percentage
                ];
            });
        return response()->json($projects);
    }
    public function dashboard()
    {
        // ================= STAFF COUNTS =================
        $staffCount = User::where('role', 'staff')->count();
        // INACTIVE STAFF
        $inactiveCount = User::where('role', 'staff')
            ->where('is_active', 0)
            ->count();
        $todaytask = Task::whereDate('start_date', Carbon::today())->count();
        $todayCompletedCount = Task::where('task_status', 'complete')
            ->whereDate('updated_at', Carbon::today())
            ->count();
        $projectCount = Project::count();
        $pendingCount = Task::where('task_status', '!=', 'complete')->count();
        $holdcount = Task::where('task_status', 'hold')->count();
        $presentCount = Loginentries::whereDate('created_at', Carbon::today())
            ->whereNotNull('check_in')
            ->count();
        $inprogressCount = Task::where('task_status', 'inprogress')->count();
        $notinprogress = $presentCount - $inprogressCount;
        // ================= WFH STAFF =================
        $wfhCount = Wfh::whereDate('from', '<=', Carbon::today())
            ->whereDate('to', '>=', Carbon::today())
            ->count();
        // ================= ABSENT STAFF =================
        $absentCount = Leave::whereDate('from', '<=', Carbon::today())
            ->whereDate('to', '>=', Carbon::today())
            ->count();
        // NOT IN PROGRESS
        // ================= STUDENT COUNTS =================
        $studentPresentCount = InternAttendance::whereDate('created_at', Carbon::today())
            ->whereNotNull('check_in')
            ->count();
        $studentCount = User::where('role', 'intern')->count();
        // ==================================================
        // ✅ GET REOPEN COUNT PROJECT-WISE (ONLY ONE QUERY)
        // ==================================================
        $reopenData = DB::table('task_histories')
            ->join('tasks', 'tasks.id', '=', 'task_histories.task_id')
            ->where('task_histories.status', 'reopen')
            ->select('tasks.project_id', DB::raw('COUNT(*) as reopen_count'))
            ->groupBy('tasks.project_id')
            ->pluck('reopen_count', 'tasks.project_id');
        // ==================================================
        // BUG COUNT PROJECT-WISE
        // ==================================================
        $bugData = DB::table('bugs')
            ->select(
                'project_id',
                DB::raw('COUNT(*) as total_bugs'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bugs")
            )
            ->groupBy('project_id')
            ->get()
            ->keyBy('project_id');
        // ==================================================
        // ✅ PROJECT STATUS (WITH MODULE COUNT)
        // ==================================================
        $projects = Project::withCount([
            // Total Tasks
            'tasks as total_tasks',
            // Completed Tasks
            'tasks as completed_tasks' => function ($q) {
                $q->where('task_status', 'complete');
            },
            // Module Count
            'modules as total_modules',
        ])
            ->get()
            ->map(function ($project) use ($reopenData, $bugData) {
                // Get reopen count (NO EXTRA QUERY)
                $reopenCount = $reopenData[$project->id] ?? 0;
                $total_bugs = $bugData[$project->id]->total_bugs ?? 0;
                $pending_bugs = $bugData[$project->id]->pending_bugs ?? 0;
                $percentage = $project->total_tasks > 0
                    ? round(($project->completed_tasks / $project->total_tasks) * 100)
                    : 0;
                return [
                    'id' => $project->id,
                    'name' => $project->project_name,
                    'total' => $project->total_tasks,
                    'completed' => $project->completed_tasks,
                    'modules' => $project->total_modules,
                    'reopen' => $reopenCount,
                    'percentage' => $percentage,
                    'total_bugs' => $total_bugs,
                    'pending_bugs' => $pending_bugs,
                ];
            })
            ->sortByDesc('percentage')
            ->values();
        // ==================================================
        // STAFF STATUS
        // ==================================================
        $staffs = User::where('role', 'staff')
            ->where('is_active', 1)
            ->get();
        $staffTaskStatus = $staffs->map(function ($staff) {
            $newTasks = Task::where('assign_to', $staff->id)
                ->where('task_status', '!=', 'complete')
                ->where('due_date', Carbon::today())
                ->count();
            $pendingTasks = Task::where('assign_to', $staff->id)
                ->whereDate('start_date', '<', Carbon::today())
                ->where('task_status', '!=', 'complete')
                ->where('task_status', '!=', 'hold')
                ->count();
            $holdTasks = Task::where('assign_to', $staff->id)
                ->where('task_status', 'hold')
                ->count();
            $completedTasks = Task::where('assign_to', $staff->id)
                ->where('task_status', 'complete')
                ->count();
            $totalTasks = Task::where('assign_to', $staff->id)->count();
            $inprogressTasks = Task::where('assign_to', $staff->id)
                ->where('task_status', 'inprogress')
                ->count();
            $reopenCount = DB::table('task_histories')
                ->join('tasks', 'tasks.id', '=', 'task_histories.task_id')
                ->where('tasks.assign_to', $staff->id)
                ->where('task_histories.status', 'reopen')
                ->count();
            $percentage = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'new' => $newTasks,
                'pending' => $pendingTasks,
                'hold' => $holdTasks,
                'completed' => $completedTasks,
                'total' => $totalTasks,
                'percentage' => $percentage,
                'inprogress' => $inprogressTasks,
                'reopen' => $reopenCount,
            ];
        })
            ->sortByDesc('percentage')
            ->values();
        // ==================================================
        // TODAY REMINDERS
        // ==================================================
        $today_reminders = Reminder::where('is_active', 1)
            ->whereDate('date', '<=', Carbon::today())
            ->where('added_by', 'admin')
            ->orderBy('date', 'desc')
            ->get();
        return view('Admin.dashboard', compact(
            'staffCount',
            'inactiveCount',
            'wfhCount',
            'projectCount',
            'absentCount',
            'pendingCount',
            'inprogressCount',
            'notinprogress',
            'presentCount',
            'todaytask',
            'todayCompletedCount',
            'studentPresentCount',
            'studentCount',
            'staffTaskStatus',
            'projects',
            'today_reminders',
            'holdcount'
        ));
    }
    public function staff_table()
    {
        return view("Admin.staff_table");
    }
    public function staff_table_data()
    {
        $query = User::where('role', 'staff')
            ->orderBy('created_at', 'asc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('task', function ($row) {
                return '<a href="' . route('task_view', ['staff_id' => $row->id]) . '"
                    class="btn btn-outline-danger btn-sm">
                    Task
                </a>';
            })
            ->addColumn('report', function ($row) {
                return '<a href="' . route('weekly_report', ['staff_id' => $row->id]) . '"
                    class="btn btn-outline-success btn-sm">
                    Report
                </a>';
            })
            ->addColumn('tasks_count', function ($row) {
                $totalTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->count();
                $completedTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->where('task_status', 'complete')
                    ->count();
                return '<span class="text-nowrap">
            <span class="text-success">' . $completedTasks . '</span> |
            <span class="text-muted">' . $totalTasks . '</span>
        </span>';
            })
            ->editColumn('is_active', function ($user) {
                return $user->is_active
                    ? '<span class="badge bg-label-success">Active</span>'
                    : '<span class="badge bg-label-danger">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = '
                <a class="action-btn" href="' . route('edit_staff', $row->id) . '">
                    <img src="' . asset('assets/img/edit.png') . '" alt="Edit">
                </a>';
                $delete = '
                <a class="action-btn-danger deleteBtn"
                   data-id="' . $row->id . '"
                   data-bs-toggle="modal"
                   data-bs-target="#delete_staff"
                   onclick="setDeleteId(this)">
                    <img src="' . asset('assets/img/trash.png') . '" alt="Delete">
                </a>';
                $toggleStatus = '
                <a class="action-btn"
                   data-id="' . $row->id . '"
                   data-active="' . ($row->is_active ? 1 : 0) . '"
                   data-bs-toggle="modal"
                   data-bs-target="#toggle_status"
                   onclick="setToggleId(this)">
                    <img src="' . asset('assets/img/block.png') . '" width="18" height="17" alt="Block">
                </a>';
                return '
                <div class="d-flex justify-content-evenly gap-2">
                    ' . $edit . $delete . $toggleStatus . '
                </div>';
            })
            ->addColumn('bank', function ($row) {
                return '<a href="' . route('edit_staff_bank_details', ['id' => $row->id]) . '"
        class="btn btn-outline-success btn-sm">
         Bank
    </a>';
            })
            ->rawColumns([
                'task',
                'report',
                'tasks_count',
                'is_active',
                'actions',
                'bank'
            ])
            ->make(true);
    }
    //toggle status-> block/unblock
    public function toggle_status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);
        try {
            $staff = User::findOrFail($request->id);
            $staff->is_active = !$staff->is_active;
            // if blocked store date
            if (!$staff->is_active) {
                $staff->blocked_at = now();
            } else {
                $staff->blocked_at = null;
            }
            $staff->save();
            $status = $staff->is_active ? 'Active' : 'Inactive';
            Log::info("Staff status changed to {$status}: ID {$staff->id}");
            return redirect()->back()->with('success', "Staff {$status} successfully!");
        } catch (\Exception $e) {
            Log::error("Failed to toggle staff status", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function create_role()
    {
        $data = Role::all();
        return view("Admin.create_role", compact("data"));
    }
    public function delete_role(Request $request)
    {
        $user = Role::findOrFail($request->id);
        $user->delete();
        return redirect()->back()->with('success', 'Role deleted successfully!');
    }
    public function add_role(Request $request)
    {
        Log::info("Role Validation Started");
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            Log::warning('Role validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            Role::create([
                'role' => $request->role,
            ]);
            DB::commit();
            Log::info('Role created successfully');
            return back()->with('success', 'Role created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Role creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function create_staff()
    {
        $roles = Role::all();
        $lastUser = User::whereNotNull('user_id')
            ->orderBy('id', 'desc')
            ->first();
        if ($lastUser && preg_match('/NX(\d+)/', $lastUser->user_id, $matches)) {
            $number = (int)$matches[1] + 1;
        } else {
            $number = 1;
        }
        $nextUserId = 'NX' . str_pad($number, 4, '0', STR_PAD_LEFT);
        return view('Admin.create_staff', compact('roles', 'nextUserId'));
    }
    public function edit_staff($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('Admin.edit_staff', compact('user', 'roles'));
    }
    public function project_table()
    {
        $testers = User::where('role', 'staff')
            ->where('designation', 'Tester')
            ->where('is_active', '1')
            ->get();
        return view('Admin.project_table', compact('testers'));
    }
    public function assignTester(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'tester_id' => 'required'
        ]);
        Project::where('id', $request->project_id)
            ->update([
                'tester_id' => $request->tester_id
            ]);
        return back()->with('success', 'Tester assigned successfully');
    }
    public function project_table_data()
    {
        $projects = Project::with([
            'tester'
        ])->withCount([
            'tasks',
            'tasks as pending_tasks' => function ($q) {
                $q->where('task_status', '!=', 'complete');
            },
            // Add a new count for pending bugs
            'bugs as pending_bugs' => function ($q) {
                $q->where('status', 'Pending');
            }
        ])->get();
        return DataTables::of($projects)
            ->addIndexColumn()
            ->addColumn('bugs', function ($row) {
                $url = route('admin.bug_report', ['project_id' => $row->id]);
                return '
                <a href="' . $url . '" class="badge bg-label-danger">
                    ' . $row->pending_bugs . '
                    <i class="ti tabler-eye ms-1"></i>
                </a>
            ';
            })
            ->addColumn('tester', function ($row) {
                // tester assigned iruntha name show
                if ($row->tester) {
                    return '
        <div>
            <span class="badge bg-label-success mb-1">
                ' . $row->tester->name . '
            </span>
        </div>
        ';
                }
                // assign illa na button show
                return '
    <button class="btn btn-sm btn-info openTesterModal"
        data-id="' . $row->id . '"
        data-bs-toggle="modal"
        data-bs-target="#testerModal">
        Assign Tester
    </button>
    ';
            })
            ->addColumn('modules', function ($row) {
                return '<a href="' . route('modules', $row->id) . '"><b>View</b></a>';
            })
            ->addColumn('tasks', function ($row) {
                return '<a href="' . route('task', $row->id) . '"><b>View</b></a>';
            })
            ->addColumn('links', function ($row) {
                $doc = $row->document_link
                    ? 'D : <a class="link" href="' . $row->document_link . '" target="_blank">link</a>'
                    : 'D : null';
                $figma = $row->figma_link
                    ? 'F : <a class="link" href="' . $row->figma_link . '" target="_blank">link</a>'
                    : 'F : null';
                $sheet = $row->sheet_link
                    ? 'S : <a class="link" href="' . $row->sheet_link . '" target="_blank">link</a>'
                    : 'S : null';
                return $doc . '<br>' . $figma . '<br>' . $sheet;
            })
            ->addColumn('action', function ($row) {
                $edit = '<a class="action-btn me-2" href="' . route('edit_project', $row->id) . '"><img src="' . asset('assets/img/edit.png') . '" alt="Edit"></a>';
                $delete = '<a class="action-btn-danger deleteBtn" data-bs-target="#delete" data-id="' . $row->id . '" data-bs-toggle="modal"><img src="' . asset('assets/img/trash.png') . '" alt="Delete"></a>';
                return $edit . $delete;
            })
            ->addColumn('doc', function ($row) {
                return '<a href="' . route('view_doc', $row->id) . '" class="badge bg-label-info"><b>Document</b></a>';
            })
            // ->addColumn('invoice', function ($row) {
            //     return '<a href="' . route('bill_table') . '" class="badge bg-label-success"><b>Invoice</b></a>';
            // })
            // ->addColumn('invoice', function ($row) {
            //     return '<a href="' . route('bill_table', $row->id) . '" class="badge bg-label-success"><b>Invoice</b></a>';
            // })
            ->addColumn('invoice', function ($row) {
                return '<a href="' . route('bill_table', ['project_id' => $row->id]) . '" class="badge bg-label-success"><b>Invoice</b></a>';
            })
            ->addColumn('credentials', function ($row) {
                return '<a href="' . route('view_credentials', $row->id) . '"
        class="badge bg-label-warning"><b>Credentials</b></a>';
            })
            ->rawColumns(['bugs', 'modules', 'tasks', 'action', 'links', 'credentials', 'doc', 'invoice', 'tester'])
            ->make(true);
    }
    public function create_project()
    {
        return view('Admin.create_project');
    }
    public function edit_project($id)
    {
        $project = Project::findOrFail($id);
        return view('Admin.edit_project', compact('project'));
    }
    public function modules($id)
    {
        $project = Project::findOrFail($id);
        return view('Admin.modules', compact('project'));
    }
    public function modules_data($project_id)
    {
        $modules = Modules::where('project_id', $project_id)->orderBy('id', 'DESC');
        return DataTables::of($modules)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y') : '-';
            })
            ->addColumn('actions', function ($row) {
                $edit = '<a href="' . route('edit_module', ['id' => $row->id]) . '">
                        <img src="' . asset("assets/img/edit.png") . '" alt="">
                    </a>';
                $delete = '<a class="action-btn-danger deleteBtn" data-id="' . $row->id . '" data-bs-target="#delete" data-bs-toggle="modal">
                        <img src="' . asset("assets/img/trash.png") . '" alt="">
                    </a>';
                return '<div class="dropdown">' . $edit . $delete . '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function edit_module($id)
    {
        $module = Modules::findOrFail($id);
        $project = Project::findOrFail($module->project_id);
        return view('Admin.edit_module', compact('project', 'module'));
    }
    public function task($project_id)
    {
        $project = Project::findOrFail($project_id);
        $modules = Modules::where('project_id', $project_id)->get()->unique('module_type');
        $staffs = User::all();
        return view('Admin.task', compact('project', 'modules', 'staffs', 'project_id'));
    }
    public function task_data(Request $request, $project_id)
    {
        $data = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])->where('project_id', $project_id)
            ->orderBy('id', 'desc');;
        if ($request->has('status') && $request->status != '') {
            $statusMap = [
                'Not Assigned' => 'not_assigned',
                'Not Started' => 'new',
                'In Progress' => 'inprogress',
                'Completed' => 'complete',
                'Hold' => 'hold'
            ];
            $status = $statusMap[$request->status] ?? null;
            if ($status) {
                $data->where('task_status', $status);
            }
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $data->whereBetween('start_date', [
                $request->from_date,
                $request->to_date
            ]);
        }
        // If only From Date
        if ($request->filled('from_date') && !$request->filled('to_date')) {
            $data->whereDate('start_date', '>=', $request->from_date);
        }
        // If only To Date
        if (!$request->filled('from_date') && $request->filled('to_date')) {
            $data->whereDate('start_date', '<=', $request->to_date);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('project', fn($row) => $row->project->project_name ?? 'N/A')
            ->addColumn(
                'module',
                fn($row) =>
                '<strong>' . $row->module_type . '</strong>
            <br><small>' . $row->module->module_name . '</small>'
            )
            ->addColumn(
                'task',
                fn($row) =>
                '<strong>Task Title:</strong> ' . $row->task_name .
                    '<br>'
            )
            ->addColumn('assigned_staff', fn($row) =>
            $row->assignedStaff->name ?? 'Not Assigned')
            ->addColumn('status', function ($row) {
                return match ($row->task_status) {
                    'not_assigned' => '<button class="btn btn-label-primary btn-sm text-nowrap AssignTaskBtn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#AssignedTaskModal">Not Assigned</button>',
                    'new' => '<span class="badge bg-label-info btn-sm">Not Started</span>',
                    'inprogress' => '<span class="badge bg-label-warning">In Progress</span>',
                    'complete' => '<span class="badge bg-label-success">Completed</span>',
                    'hold' => '<span class="badge bg-label-danger">Hold</span>',
                    default => '-',
                };
            })
            ->addColumn('view', function ($row) {
                return
                    ' <a href="' . route('task_description', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route('edit_task', $row->id) . '" class="action-btn me-2">
    <img src="' . asset('assets/img/edit.png') . '" alt="Edit">
    </a>';
                $delete = '<a class="action-btn-danger deleteBtn"
    data-id="' . $row->id . '"
    data-bs-toggle="modal"
    data-bs-target="#delete">
    <img src="' . asset('assets/img/trash.png') . '" alt="Delete">
    </a>';
                return $edit . $delete;
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('task_name', 'like', "%{$search}%")
                            ->orWhereHas('project', fn($q2) => $q2->where('project_name', 'like', "%{$search}%"))
                            ->orWhereHas('module', fn($q2) => $q2->where('module_name', 'like', "%{$search}%"))
                            ->orWhereHas('assignedStaff', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
                    });
                }
            })
            ->rawColumns(['start_date', 'due_date', 'module', 'task', 'status', 'action', 'view'])
            ->make(true);
    }
    public function edit_task($id)
    {
        $task = Task::findOrFail($id);
        $project = Project::find($task->project_id);
        $modules = Modules::where('project_id', $task->project_id)
            ->get()
            ->unique('module_type');
        $staffs = User::all();
        $testers = User::where('role', 'staff')
            ->where('designation', 'Tester')
            ->where('is_active', '1')
            ->get();
        return view('Admin.edit_task', compact('task', 'project', 'modules', 'staffs', 'testers'));
    }
    public function in_progress($project_id)
    {
        $project = Project::findOrFail($project_id);
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('project_id', $project_id)
            ->where('task_status', 'inprogress')
            ->get();
        return view('Admin.in_progress', compact('project', 'tasks'));
    }
    public function completed($project_id)
    {
        $project = Project::findOrFail($project_id);
        $tasks = $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('project_id', $project_id)
            ->where('task_status', 'complete')
            ->get();
        return view('Admin.completed', compact('project', 'tasks'));
    }
    public function hold($project_id)
    {
        $project = Project::findOrFail($project_id);
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('project_id', $project_id)
            ->where('task_status', 'hold')
            ->get();
        return view('Admin.hold', compact('project', 'tasks'));
    }
    public function reset_password()
    {
        return view('Admin.reset_password');
    }
    public function common_request_table()
    {
        return view('Admin.common_request_table');
    }
    public function common_request_table_data()
    {
        $data = Common::where('is_replied', 0);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('reply', function ($row) {
                return '<button  class="btn btn-primary open-reply"
                        data-id="' . $row->id . '"
                        data-bs-toggle="modal"
                        data-bs-target="#form">
                        Reply
                    </button>';
            })
            ->rawColumns(['reply'])
            ->make(true);
    }
    public function personal_request_table()
    {
        return view('Admin.personal_request_table');
    }
    public function personal_request_table_data()
    {
        $data = Personal::where('is_replied', 0);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('reply', function ($row) {
                return '
                <button class="btn btn-primary open-reply"
                    data-id="' . $row->id . '"
                    data-bs-toggle="modal"
                    data-bs-target="#form">
                    Reply
                </button>';
            })
            ->rawColumns(['reply'])
            ->make(true);
    }
    public function profile()
    {
        $user = Auth::guard('admin')->user();
        return view('Admin.profile', compact('user'));
    }
    public function add_staff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:users,user_id',
            'name' => 'required|string|max:50',
            'mobile' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email',
            'personal_email' => 'required|email|unique:users,personal_email',
            'password' => 'required|string|min:6',
            'designation' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'dob' => 'required|date',
            'doj' => 'required|date|before_or_equal:today',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            Log::warning('Staff validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            User::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'personal_email' => $request->personal_email,
                'password' => Hash::make($request->password),
                'password_hint' => $request->password,
                'designation' => $request->designation,
                'address' => $request->address,
                'dob' => $request->dob,
                'doj' => $request->doj,
                'type' => $request->type,
            ]);
            DB::commit();
            Log::info('Staff created successfully');
            return back()->with('success', 'Staff created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Staff creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function update_staff(Request $request)
    {
        $id = $request->input('id');
        $user = User::findOrFail($id);
        Log::info("Updating customer with ID: $id");
        Log::info("Request Data: ", $request->all());
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:users,user_id,' . $user->id,
            'name' => 'required|string|max:50',
            'mobile' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'personal_email' => 'required|email|unique:users,personal_email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'designation' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'dob' => 'required|date',
            'doj' => 'required|date|before_or_equal:today',
            'type' => 'nullable',
        ]);
        if ($validator->fails()) {
            Log::warning('Staff validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            $user->user_id = $request->user_id;
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->personal_email = $request->personal_email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->designation = $request->designation;
            $user->address = $request->address;
            $user->dob = $request->dob;
            $user->password_hint = $request->password;
            $user->doj = $request->doj;
            $user->type = $request->type;
            $user->save();
            DB::commit();
            Log::info('Staff updated successfully');
            return back()->with('success', 'Staff updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Staff update failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function add_project(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name'  => 'required|string|max:255',
            'type'          => 'required|string|max:255',
            'figma_link'    => 'nullable|string',
            'document_link' => 'nullable|string',
            'sheet_link'    => 'nullable|string',
            'client_mobile' => 'required|string',
            'client_email'  => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'client_name'   => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            Log::warning('Project validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            Project::create([
                'project_name'  => $request->project_name,
                'type'          => $request->type,
                'figma_link'    => $request->figma_link,
                'sheet_link'    => $request->sheet_link,
                'document_link' => $request->document_link,
                'client_mobile' => $request->client_mobile,
                'client_email'  => $request->client_email,
                'client_name'   => $request->client_name,
                'address'       => $request->address,
            ]);
            DB::commit();
            Log::info('Project created successfully');
            return redirect()->route('project_table')->with('success', 'Project created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Project creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function update_project(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'project_name'  => 'required|string|max:255',
            'type'          => 'required|string|max:255',
            'figma_link'    => 'nullable|string',
            'sheet_link'    => 'nullable|string',
            'document_link' => 'nullable|string',
            'client_mobile' => 'required|string',
            'client_email'  => 'required|string|max:255',
            'client_name'   => 'required|string|max:255',
            'address'       => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $project = Project::findOrFail($id);
            $project->update([
                'project_name'  => $request->project_name,
                'type'          => $request->type,
                'figma_link'    => $request->figma_link,
                'sheet_link'    => $request->sheet_link,
                'document_link' => $request->document_link,
                'client_mobile' => $request->client_mobile,
                'client_email'  => $request->client_email,
                'client_name'   => $request->client_name,
                'address'       => $request->address,
            ]);
            DB::commit();
            return redirect()->route('project_table')
                ->with('success', 'Project updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function delete_staff(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();
        return redirect()->back()->with('success', 'Staff deleted successfully!');
    }
    public function delete_project(Request $request)
    {
        try {
            Log::info('Project delete request received', [
                'project_id' => $request->id,
                'requested_by' => auth()->id()
            ]);
            $project = Project::find($request->id);
            if (!$project) {
                Log::warning('Project not found while deleting', [
                    'project_id' => $request->id
                ]);
                return redirect()->back()
                    ->with('error', 'Project not found');
            }
            // Delete related histories
            TaskHistory::where('project_id', $project->id)->delete();
            // Delete project
            $project->delete();
            Log::info('Project deleted successfully', [
                'project_id' => $request->id
            ]);
            return redirect()->back()
                ->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error while deleting project', [
                'project_id' => $request->id,
                'error_message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong');
        }
    }
    public function delete_module(Request $request)
    {
        $data = Modules::findOrFail($request->id);
        $data->delete();
        return redirect()->back()->with('success', 'Module deleted successfully!');
    }
    public function add_module(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'module_type' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            Log::warning('Project validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            Modules::create([
                'module_name' => $request->module_name,
                'project_id' => $request->project_id,
                'module_type' => $request->module_type,
            ]);
            DB::commit();
            Log::info('Module created successfully');
            return back()->with('success', 'Module created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Module creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function add_task(Request $request)
    {
        Log::info('Validation Started');
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'module_type' => 'required',
            'module_id' => 'required|exists:modules,id',
            'task_name' => 'required|string',
            'due_date' => 'required|date',
            'estimated_time' => 'nullable| string',
            'task_type' => 'required|string',
            'assign_to' => 'nullable|string|max:255',
            'priority' => 'required|string',
            'start_date' => 'required|date',
            'task_description' => 'required|string',
            'tester_id' => 'nullable',
        ]);
        if ($validator->fails()) {
            Log::warning('Task validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            $task_status = $request->assign_to ? 'new' : 'not_assigned';
            $estimated_time = $request->assign_to ? $request->estimated_time : null;
            $assign_to = $request->assign_to ?? null;
            $task = Task::create([
                "module_type" => $request->module_type,
                'module_name' => $request->module_name,
                'project_id' => $request->project_id,
                'module_id' => $request->module_id,
                'task_name' => $request->task_name,
                'estimated_time' => $estimated_time,
                'due_date' => $request->due_date,
                'assign_to' => $assign_to,
                'task_type' => $request->task_type,
                'priority' => $request->priority,
                'start_date' => $request->start_date,
                'task_description' => $request->task_description,
                'tester_id' => $request->tester_id,
                'task_status' => $task_status,
            ]);
            if ($assign_to) {
                $sent = webpushnotify(
                    $assign_to,
                    'New Task Assigned',
                    'You have a new task : ' . $task->task_name
                );
                Log::info('Push notification status', [
                    'user_id' => $assign_to,
                    'sent' => $sent
                ]);
            }
            TaskHistory::create([
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'staff_id' => null,
                'status' => $task_status == 'new' ? 'assigned' : 'created',
                'remark' => null,
                'spending_hour' => null,
            ]);
            DB::commit();
            Log::info('Task created successfully');
            return back()->with('success', 'Task created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Task creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function assigned_task(Request $request)
    {
        Log::info('Assigned task update started');
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'assign_to' => 'required|exists:users,id',
            'estimated_time' => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $task = Task::findOrFail($request->task_id);
            $task->update([
                'assign_to' => $request->assign_to,
                'estimated_time' => $request->estimated_time,
                'task_status' => 'new',
            ]);
            TaskHistory::create([
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'staff_id' => $request->assign_to,
                'status' => 'assigned',
                'spending_hour' => null,
            ]);
            DB::commit();
            Log::info("Task {$task->id} assigned successfully");
            return redirect()->back()->with('success', 'Task assigned successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Assigned task update failed', [
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong')
                ->withInput();
        }
    }
    public function getModuleName(Request $request)
    {
        $modules = Modules::where('module_type', $request->module_type)
            ->where('project_id', $request->project_id)
            ->get();
        return response()->json($modules);
    }
    public function update_task(Request $request)
    {
        $id = $request->id;
        Log::info("Updating task with ID: $id");
        Log::info("Request Data: ", $request->all());
        Log::info("Updating task with ID from URL: $id");
        $task = Task::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'module_type' => 'required',
            'module_id' => 'required|exists:modules,id',
            'task_name' => 'required|string',
            'estimated_time' => 'required|string',
            'due_date' => 'required|date',
            'assign_to' => 'required|string|max:255',
            'start_date' => 'required|date',
            'priority' => 'required|string|max:255',
            'task_type' => 'required|string|max:255',
            'task_description' => 'required|string',
            'tester_id' => 'nullable',
        ]);
        if ($validator->fails()) {
            Log::warning('Task validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $task = Task::findOrFail($id);
            $task->update([
                "module_type" => $request->module_type,
                'module_name' => $request->module_name,
                'project_id' => $request->project_id,
                'module_id' => $request->module_id,
                'task_name' => $request->task_name,
                'estimated_time' => $request->estimated_time,
                'due_date' => $request->due_date,
                'assign_to' => $request->assign_to,
                'task_type' => $request->task_type,
                'priority' => $request->priority,
                'start_date' => $request->start_date,
                'task_description' => $request->task_description,
                'tester_id' => $request->tester_id,
            ]);
            DB::commit();
            Log::info('Task updated successfully');
            return back()->with('success', 'Task updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Task update failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function reminder()
    {
        return view("Admin.reminder");
    }
    public function reminder_data()
    {
        $query = Reminder::where('added_by', 'admin');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('reminder_type', function ($row) {
                return $row->reminder_type ? ucfirst(str_replace('_', ' ', $row->reminder_type)) : '-';
            })
            ->editColumn('date', function ($row) {
                return $row->date ? Carbon::parse($row->date)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                if ($row->is_active == 0) {
                    $statusHtml = '<span class="badge bg-secondary me-1">Completed</span>';
                } else {
                    $statusHtml = '<button type="button" class="btn btn-sm btn-success statusBtn me-1"
                        data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#completeModal">
                        Active</button>';
                }
                $deleteBtn = '
        <button type="button" class="btn btn-sm deleteBtn" data-id="' . $row->id . '"
                data-bs-toggle="modal" data-bs-target="#delete">
            <i class="fa fa-trash text-danger"></i>
        </button>';
                return $statusHtml . $deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create_reminder()
    {
        $all_users = User::whereIn('role', ['admin', 'staff'])
            ->where('is_active', '1')
            ->get(['id', 'name']);
        return view('Admin.create_reminder', compact('all_users'));
    }
    public function add_reminder(Request $request)
    {
        Log::info('Reminder validation started');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'reminder_type' => 'required|string|min:6',
            'date' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $user = User::find($request->user_id);
            Reminder::create([
                'title' => $request->title,
                'user_id' => $user->id,
                'remind_to' => $user->name,
                'description' => $request->description,
                'reminder_type' => $request->reminder_type,
                'date' => $request->date,
                'added_by' => 'admin',
            ]);
            DB::commit();
            Log::info('Reminder created successfully with Name and ID');
            return back()->with('success', 'Reminder created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reminder creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong!')->withInput();
        }
    }
    //    public function edit_reminder($id)
    //     {
    //         $date = Reminder::findOrFail($id);
    //         return view('Admin.edit_reminder', compact('data'));
    //     }
    //    public function update_reminder(Request $request)
    // {
    //     $id = $request->id;
    //     Log::info("Updating reminder with ID: $id");
    //     Log::info("Request Data: ", $request->all());
    //     // Validate input
    //     $validator = Validator::make($request->all(), [
    //         'title'         => 'required|string|max:50',
    //         'remind_to'     => 'required|string|max:50',
    //         'description'   => 'required|string',
    //         'reminder_type' => 'required|string|min:6',
    //         'date'          => 'nullable|date',
    //     ]);
    //     if ($validator->fails()) {
    //         Log::warning('Reminder validation failed', [
    //             'errors' => $validator->errors()->toArray()
    //         ]);
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     try {
    //         DB::beginTransaction();
    //         $reminder = Reminder::findOrFail($id);
    //         // Update fields
    //         $reminder->update([
    //             'title'         => $request->title,
    //             'remind_to'     => $request->remind_to,
    //             'description'   => $request->description,
    //             'reminder_type' => $request->reminder_type,
    //             'date'          => $request->date,
    //         ]);
    //         DB::commit();
    //         Log::info('Reminder updated successfully');
    //         return back()->with('success', 'Reminder updated successfully!');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::error('Reminder update failed', ['message' => $e->getMessage()]);
    //         return redirect()->back()
    //             ->with('error', 'Something went wrong! Please try again.')
    //             ->withInput();
    //     }
    // }
    public function complete_reminder(Request $request)
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
    public function remark_form(Request $request)
    {
        Log::info('Reply ID:', ['id' => $request->id]);
        $validator = Validator::make($request->all(), [
            'remark' => 'required|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Reply validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $data = Common::findOrFail($request->id);
            Log::info("Validation passed");
            $data->update([
                'remark' => $request->remark,
                'is_replied' => 1,
            ]);
            DB::commit();
            Log::info('Reply Sent Successfully');
            return back()->with('success', 'Reply Sent Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reply failed', ['message' => $e->getMessage()]);
            return back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function remark_personal(Request $request)
    {
        Log::info('Reply Validation Started');
        $validator = Validator::make($request->all(), [
            'remark' => 'required|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Reply validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $data = Personal::findOrFail($request->id);
            Log::info("Validation passed");
            $data->update([
                'remark' => $request->remark,
                'is_replied' => 1,
            ]);
            DB::commit();
            Log::info('Reply Sent Successfully');
            return back()->with('success', 'Reply Sent Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reply failed', ['message' => $e->getMessage()]);
            return back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function create_task($project_id)
    {
        $project = Project::findOrFail($project_id);
        $modules = Modules::where('project_id', $project_id)
            ->get()
            ->unique('module_type');
        $staffs = User::where('role', 'staff')
            ->where('is_active', '1')
            ->get();
        $testers = User::where('role', 'staff')
            ->where('designation', 'Tester')
            ->where('is_active', '1')
            ->get();
        return view('Admin.create_task', compact('project', 'modules', 'staffs', 'testers'));
    }
    public function delete_task(Request $request)
    {
        try {
            $task = Task::find($request->id);
            if (!$task) {
                return redirect()->back()
                    ->with('error', 'Task not found');
            }
            if (method_exists($task, 'histories')) {
                $task->histories()->delete();
            }
            $task->delete();
            return redirect()->back()
                ->with('success', 'Task deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong');
        }
    }
    public function leave_request_table()
    {
        return view("Admin.leave_request_table");
    }
    public function leave_request_table_data()
    {
        $data = Leave::with('user')->where('is_replied', 0);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('staff_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                return '<button
                        class="btn btn-label-info open-reply"
                        data-id="' . $row->id . '"
                        data-bs-toggle="modal"
                        data-bs-target="#reply">
                        Reply
                    </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function leave_reply(Request $request)
    {
        Log::info('Reply Validation Started');
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
            'remark' => 'required|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Reply validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $data = Leave::findOrFail($request->id);
            Log::info("Validation passed");
            $data->update([
                'reply' => $request->reply,
                'remark' => $request->remark,
                'is_replied' => 1,
            ]);
            DB::commit();
            Log::info('Reply Sent Successfully');
            return back()->with('success', 'Reply Sent Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reply failed', ['message' => $e->getMessage()]);
            return back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function leave_request_history()
    {
        return view("Admin.leave_request_history");
    }
    public function leave_request_history_data()
    {
        $data = Leave::with('user')
            ->where('is_replied', 1)
            ->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d ');
            })
            ->addColumn('staff_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                return '<button class="btn btn-label-success open-reply"
                    data-id="' . $row->id . '">
                    Approved
                </button>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function wfh_table()
    {
        return view("Admin.wfh_table");
    }
    public function wfh_table_data()
    {
        $data = Wfh::with('user')->where('is_replied', 0);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('staff_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                return '<button
                        class="btn btn-label-info open-reply"
                        data-id="' . $row->id . '"
                        data-bs-toggle="modal"
                        data-bs-target="#reply">
                        Reply
                    </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function wfh_reply(Request $request)
    {
        Log::info('Reply Validation Started');
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
            'remark' => 'required|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Reply validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $data = Wfh::findOrFail($request->id);
            Log::info("Validation passed");
            $data->update([
                'reply' => $request->reply,
                'remark' => $request->remark,
                'is_replied' => 1,
            ]);
            DB::commit();
            Log::info('Reply Sent Successfully');
            return back()->with('success', 'Reply Sent Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reply failed', ['message' => $e->getMessage()]);
            return back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function wfh_history()
    {
        $data = Wfh::where('is_replied', '1')->get();
        return view("Admin.wfh_history", compact("data"));
    }
    public function wfh_history_data()
    {
        $data = wfh::with('user')
            ->where('is_replied', 1)
            ->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d ');
            })
            ->addColumn('staff_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                return '<button class="btn btn-label-success open-reply"
                    data-id="' . $row->id . '">
                    Approved
                </button>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function permission_table()
    {
        return view("Admin.permission_table");
    }
    public function permission_table_data()
    {
        $permissions = Permission::where('is_replied', '0')->get();
        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('reply', function ($row) {
                return '<button class="btn btn-label-info open-reply"
                        data-id="' . $row->id . '"
                        data-bs-toggle="modal"
                        data-bs-target="#reply">
                        Reply
                    </button>';
            })
            ->rawColumns(['reply'])
            ->make(true);
    }
    public function permission_history()
    {
        return view("Admin.permission_history");
    }
    public function permission_history_data()
    {
        $permissions = Permission::where('is_replied', '1')->latest();
        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d ');
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('reply', function ($row) {
                return '<button class="btn btn-label-success open-reply"
                        data-id="' . $row->id . '">Approved</button>';
            })
            ->rawColumns(['reply'])
            ->make(true);
    }
    public function permission_reply(Request $request)
    {
        Log::info('Reply Validation Started');
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
            'remark' => 'required|string'
        ]);
        if ($validator->fails()) {
            Log::warning('Reply validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $data = Permission::findOrFail($request->id);
            Log::info("Validation passed");
            $data->update([
                'reply' => $request->reply,
                'remark' => $request->remark,
                'is_replied' => 1,
            ]);
            DB::commit();
            Log::info('Reply Sent Successfully');
            return back()->with('success', 'Reply Sent Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reply failed', ['message' => $e->getMessage()]);
            return back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function personal_request_history_table()
    {
        return view("Admin.personal_request_history_table");
    }
    public function personal_request_history_data()
    {
        $query = Personal::where('is_replied', 1)->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->make(true);
    }
    public function common_request_history_table()
    {
        return view("Admin.common_request_history_table");
    }
    public function common_request_history_data()
    {
        $data = Common::where('is_replied', '1')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->make(true);
    }
    public function attendance_history()
    {
        return view('Admin.attendance_history');
    }
    public function attendance_history_data()
    {
        $query = Loginentries::with('user')->latest()->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->editColumn('check_in', function ($row) {
                return $row->check_in
                    ? Carbon::parse($row->check_in)->format('h:i:s A')
                    : '-';
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
            ->rawColumns(['image', 'type', 'late_reason'])
            ->make(true);
    }
    public function task_history()
    {
        return view('Admin.task_history');
    }
    public function task_history_data()
    {
        $query = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('task_status', 'inprogress');
        $query->withCount([
            'histories as reopen_count' => function ($q) {
                $q->where('status', 'reopen');
            },
            'histories as hold_count' => function ($q) {
                $q->where('status', 'hold');
            }
        ]);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('project', function ($row) {
                return ($row->project->project_name ?? 'N/A')
                    . '<br><small>Start: ' . ($row->start_date ? Carbon::parse($row->start_date)->format('d M Y') : '-')
                    . '<br>End: ' . ($row->due_date ? Carbon::parse($row->due_date)->format('d M Y') : '-')
                    . '</small>';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>'
                    . '<small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>Task Title:</strong> ' . ($row->task_name ?? '-')
                    . '<br><small>' . strip_tags($row->task_description ?? '') . '</small>';
            })
            ->addColumn('assigned_staff', function ($row) {
                return $row->assignedStaff->name ?? 'Not Assigned';
            })
            ->addColumn('reopen', function ($row) {
                return $row->reopen_count
                    . ' <a href="' . route('view_reopen_history', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->addColumn('hold', function ($row) {
                return $row->hold_count
                    . ' <a href="' . route('view_hold_history', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->addColumn('status', function ($row) {
                if ($row->task_status === 'inprogress') {
                    return '<span class="btn btn-primary btn-sm disabled">In Progress</span>';
                } else {
                    return '—';
                }
            })
            ->rawColumns(['project', 'module', 'task', 'reopen', 'hold', 'status'])
            ->make(true);
    }
    public function completed_task_history()
    {
        $data = Task::where('task_status', 'completed')
            ->get();
        return view("Admin.completed_task_history", compact("data"));
    }
    public function update_module(Request $request)
    {
        $id = $request->input('id');
        $module = Modules::findOrFail($id);
        Log::info("Updating module with ID: $id");
        Log::info("Request Data: ", $request->all());
        $validator = Validator::make($request->all(), [
            'module_name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'module_type' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            Log::warning('Module validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info("Validation passed");
            $module->module_name = $request->module_name;
            $module->project_id = $request->project_id;
            $module->module_type = $request->module_type;
            $module->save();
            DB::commit();
            Log::info('Module updated successfully');
            return back()->with('success', 'Module updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Module update failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function today_tasks()
    {
        return view('Admin.today_tasks');
    }
    public function today_tasks_data(Request $request)
    {
        $query = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])->whereDate('start_date', Carbon::today());
        if ($request->has('status') && $request->status != '') {
            $statusMap = [
                'Not Started' => 'new',
                'In Progress' => 'inprogress',
                'Completed' => 'complete',
                'Hold' => 'hold'
            ];
            $status = $statusMap[$request->status] ?? null;
            if ($status) {
                $query->where('task_status', $status);
            }
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('start_date', function ($row) {
                return $row->start_date
                    ? Carbon::parse($row->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date
                    ? Carbon::parse($row->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? 'N/A';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>'
                    . '<small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>Task Title:</strong> ' . $row->task_name;
            })
            ->addColumn('assigned_staff', function ($row) {
                return $row->assignedStaff->name ?? 'Not Assigned';
            })
            ->addColumn('status', function ($row) {
                if ($row->task_status === 'new') {
                    return '<button class="btn btn-label-danger btn-sm startTaskBtn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#startTaskModal">Not Started</button>';
                } elseif ($row->task_status === 'inprogress') {
                    return '<span class="btn btn-label-info btn-sm text-nowrap">In Progress</span>';
                } elseif ($row->task_status === 'hold') {
                    return '<span class="btn btn-label-warning btn-sm">Hold</span>';
                } elseif ($row->task_status === 'complete') {
                    return '<span class="btn btn-label-success btn-sm">Complete</span>';
                } else {
                    return '';
                }
            })
            ->addColumn('action', function ($row) {
                return
                    ' <a href="' . route('task_description', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->filter(function ($query) {
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        // Task name
                        $q->where('task_name', 'like', "%{$search}%")
                            // Project name
                            ->orWhereHas('project', function ($q2) use ($search) {
                                $q2->where('project_name', 'like', "%{$search}%");
                            })
                            // Module name
                            ->orWhereHas('module', function ($q2) use ($search) {
                                $q2->where('module_name', 'like', "%{$search}%");
                            })
                            // Assigned staff name
                            ->orWhereHas('assignedStaff', function ($q2) use ($search) {
                                $q2->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->rawColumns(['start_date', 'due_date', 'project', 'module', 'task', 'status', 'action'])
            ->make(true);
    }
    public function pending_task()
    {
        return view('Admin.pending_task');
    }
    public function pending_task_data(Request $request)
    {
        $query = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])->where('task_status', '!=', 'complete')
            ->orderBy('created_at', 'desc');;
        $query->withCount([
            'histories as reopen_count' => function ($q) {
                $q->where('status', 'reopen');
            },
            'histories as hold_count' => function ($q) {
                $q->where('status', 'hold');
            }
        ]);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? 'N/A'
                    . '<br><small>Start: ' . ($row->start_date ? Carbon::parse($row->start_date)->format('d M Y') : '-')
                    . '<br>End: ' . ($row->due_date ? Carbon::parse($row->due_date)->format('d M Y') : '-')
                    . '</small>';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>'
                    . '<small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>Task Title:</strong> ' . ($row->task_name ?? '-');
            })
            ->addColumn('assigned_staff', function ($row) {
                return $row->assignedStaff->name ?? 'Not Assigned';
            })
            ->addColumn('status', function ($row) {
                if ($row->task_status === 'new') {
                    return '<button class="btn btn-label-danger btn-sm startTaskBtn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#startTaskModal">Not Started</button>';
                } elseif ($row->task_status === 'inprogress') {
                    return '<span class="btn btn-label-warning btn-sm ">InProgress</span>';
                } elseif ($row->task_status === 'hold') {
                    return '<span class="btn btn-label-info btn-sm ">Hold</span>';
                } else {
                    return '—';
                }
            })
            ->addColumn('action', function ($row) {
                return
                    ' <a href="' . route('task_description', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where(function ($q) use ($search) {
                        $q->where('task_name', 'like', "%{$search}%")
                            ->orWhereHas('project', function ($q2) use ($search) {
                                $q2->where('project_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('module', function ($q2) use ($search) {
                                $q2->where('module_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('assignedStaff', function ($q2) use ($search) {
                                $q2->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->rawColumns(['project', 'module', 'task', 'status', 'action'])
            ->make(true);
    }
    public function task_view($staff_id)
    {
        $staffName = User::where('id', $staff_id)->value('name');
        $completed_tasks_count = Task::where('assign_to', $staff_id)
            ->where('task_status', 'complete')
            ->count();
        $inprogressTasks = Task::where('assign_to', $staff_id)
            ->whereDate('start_date', '<', Carbon::today())
            ->where('task_status', 'inprogress')
            ->count();
        $pending_tasks_count = Task::where('assign_to', $staff_id)
            ->whereDate('start_date', '<', Carbon::today())
            ->where('task_status', '!=', 'complete')
            ->where('task_status', '!=', 'hold')
            ->count();
        $hold_tasks_count = Task::where('assign_to', $staff_id)
            ->where('task_status', 'hold')
            ->count();
        $reopen_tasks_count = TaskHistory::whereHas('task', function ($q) use ($staff_id) {
            $q->where('assign_to', $staff_id);
        })
            ->where('status', 'reopen')
            ->count();
        return view('Admin.task_view', [
            'staffId' => $staff_id,
            'staffName' => $staffName,
            'completed_tasks_count' => $completed_tasks_count,
            'inprogressTasks' => $inprogressTasks,
            'pending_tasks_count' => $pending_tasks_count,
            'hold_tasks_count' => $hold_tasks_count,
            'reopen_tasks_count' => $reopen_tasks_count,
        ]);
    }
    public function task_view_data(Request $request, $staff_id)
    {
        $tasks = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])->orderBy('id', 'desc')
            ->where('assign_to', $staff_id)
            ->withCount([
                'histories as reopen_count' => function ($q) {
                    $q->where('status', 'reopen');
                },
                'histories as hold_count' => function ($q) {
                    $q->where('status', 'hold');
                }
            ]);
        if ($request->has('status') && $request->status != '') {
            $statusMap = [
                'Not Started' => 'new',
                'In Progress' => 'inprogress',
                'Completed' => 'complete',
                'Hold' => 'hold'
            ];
            $status = $statusMap[$request->status] ?? null;
            if ($status) {
                $tasks->where('task_status', $status);
            }
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $tasks->whereBetween('start_date', [
                $request->from_date,
                $request->to_date
            ]);
        }
        // Only From Date
        if ($request->filled('from_date') && !$request->filled('to_date')) {
            $tasks->whereDate('start_date', '>=', $request->from_date);
        }
        // Only To Date
        if (!$request->filled('from_date') && $request->filled('to_date')) {
            $tasks->whereDate('start_date', '<=', $request->to_date);
        }
        if ($search = $request->input('search.value')) {
            $tasks->where(function ($q) use ($search) {
                $q->where('task_name', 'like', "%{$search}%")
                    ->orWhereHas('project', fn($p) => $p->where('project_name', 'like', "%{$search}%"))
                    ->orWhereHas('module', fn($m) => $m->where('module_name', 'like', "%{$search}%"));
            });
        }
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('start_date', function ($row) {
                return $row->start_date
                    ? Carbon::parse($row->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date
                    ? Carbon::parse($row->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? 'N/A';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>
                    <small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>' . e($row->task_name) . '</strong><br>';
            })
            ->addColumn('estimated_time', function ($row) {
                return '<strong>' . e($row->estimated_time) . '</strong><br>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->task_status) {
                    'new' => '<button class="btn btn-label-info btn-sm text-nowrap startTaskBtn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#startTaskModal">Not Started</button>',
                    'inprogress' => '<span class="badge bg-label-warning text-nowrap">In Progress</span>',
                    'complete' => '<span class="badge bg-label-success">Completed</span>',
                    'hold' => '<span class="badge bg-label-danger">Hold</span>',
                    default => '-',
                };
            })
            ->addColumn('action', function ($row) {
                return
                    ' <a href="' . route('task_description', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->rawColumns(['start_date', 'due_date', 'project', 'module', 'task', 'estimated_time', 'status', 'action'])
            ->make(true);
    }
    public function task_description($task_id)
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
        return view('Admin.task_description', compact('task'));
    }
    public function verify_test_status(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'test_status' => 'required'
        ]);
        Task::where('id', $request->task_id)
            ->update(['test_status' => 'complete']);
        return back()->with('success', 'Task marked as complete');
    }
    public function view_hold_history($task_id)
    {
        return view('Admin.view_hold_history', compact('task_id'));
    }
    public function view_hold_history_data($task_id)
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
    public function view_reopen_history($task_id)
    {
        return view('Admin.view_reopen_history', compact('task_id'));
    }
    public function view_reopen_history_data($task_id)
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
    public function today_complete()
    {
        return view('Admin.today_complete');
    }
    public function today_complete_data(Request $request)
    {
        $query = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('task_status', 'complete')
            ->whereDate('updated_at', Carbon::today());
        $query->withCount([
            'histories as reopen_count' => function ($q) {
                $q->where('status', 'reopen');
            }
        ]);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('start_date', function ($row) {
                return $row->start_date
                    ? Carbon::parse($row->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date
                    ? Carbon::parse($row->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? 'N/A';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>'
                    . '<small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>Task Title:</strong> ' . ($row->task_name ?? '-');
            })
            ->addColumn('assigned_staff', function ($row) {
                return $row->assignedStaff->name ?? 'Not Assigned';
            })
            ->addColumn('status', function ($row) {
                return '<span class="badge bg-success">Complete</span>';
            })
            ->addColumn('action', function ($row) {
                return
                    ' <a href="' . route('task_description', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where(function ($q) use ($search) {
                        $q->where('task_name', 'like', "%{$search}%")
                            ->orWhereHas('project', function ($q2) use ($search) {
                                $q2->where('project_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('module', function ($q2) use ($search) {
                                $q2->where('module_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('assignedStaff', function ($q2) use ($search) {
                                $q2->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->rawColumns(['start_date', 'due_date', 'project', 'module', 'task', 'reopen', 'status', 'action'])
            ->make(true);
    }
    public function today_present()
    {
        $data = Loginentries::with('user')
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('Admin.today_present', compact('data'));
    }
    public function update_profile(Request $request)
    {
        Log::info('Admin profile update started');
        Log::info('Request data:', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::guard('admin')->id(),
            'mobile' => 'required|digits:10',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:800',
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
            $user = Auth::guard('admin')->user();
            if (!$user) {
                Log::error('Admin not authenticated');
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
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ]);
            DB::commit();
            Log::info('Admin profile updated successfully', [
                'admin_id' => $user->id
            ]);
            return redirect()
                ->back()
                ->with('success', 'Profile updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin profile update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()
                ->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function today_in_progress()
    {
        return view('Admin.today_in_progress');
    }
    public function today_in_progress_data(Request $request)
    {
        $query = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('task_status', 'inprogress');
        $query->withCount([
            'histories as reopen_count' => function ($q) {
                $q->where('status', 'reopen');
            }
        ]);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('start_date', function ($row) {
                return $row->start_date
                    ? Carbon::parse($row->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date
                    ? Carbon::parse($row->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? 'N/A';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>'
                    . '<small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>Task Title:</strong> ' . ($row->task_name ?? '-');
            })
            ->addColumn('assigned_staff', function ($row) {
                return $row->assignedStaff->name ?? 'Not Assigned';
            })
            ->addColumn('status', function ($row) {
                return '<span class="badge bg-info text-nowrap">In Progress</span>';
            })
            ->addColumn('action', function ($row) {
                return
                    ' <a href="' . route('task_description', ['task_id' => $row->id]) . '" class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where(function ($q) use ($search) {
                        $q->where('task_name', 'like', "%{$search}%")
                            ->orWhereHas('project', function ($q2) use ($search) {
                                $q2->where('project_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('module', function ($q2) use ($search) {
                                $q2->where('module_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('assignedStaff', function ($q2) use ($search) {
                                $q2->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->rawColumns(['start_date', 'due_date', 'project', 'module', 'task', 'reopen', 'status', 'action'])
            ->make(true);
    }
    //intern
    public function student_dashboard()
    {
        //staff
        $staffCount = User::where('role', 'staff')->count();
        $todaytask = Task::whereDate('start_date', Carbon::today())->count();
        $todayCompletedCount = Task::where('task_status', 'complete')
            ->whereDate('updated_at', Carbon::today())->count();
        $projectCount = Project::count();
        $presentCount = Loginentries::whereDate('created_at', Carbon::today())->whereNotNull('check_in')->count();
        $pendingCount = Task::where('task_status', '!=', 'complete')->count();
        $inprogressCount = Task::where('task_status', 'inprogress')->count();
        //student
        $studentPresentCount = InternAttendance::whereDate('created_at', Carbon::today())->whereNotNull('check_in')->count();
        $studentCount = User::where('role', 'intern')->count();
        //project status
        $projects = Project::withCount([
            'tasks as total_tasks',
            'tasks as completed_tasks' => function ($q) {
                $q->where('task_status', 'complete');
            }
        ])->get()->map(function ($project) {
            $percentage = $project->total_tasks > 0
                ? round(($project->completed_tasks / $project->total_tasks) * 100)
                : 0;
            return [
                'id' => $project->id,
                'name' => $project->project_name,
                'total' => $project->total_tasks,
                'completed' => $project->completed_tasks,
                'percentage' => $percentage,
            ];
        })
            ->sortByDesc('percentage')
            ->values();
        //staff status
        $staffs = User::where('role', 'staff')->where('is_active', 1)->get();
        $staffTaskStatus = $staffs->map(function ($staff) {
            $totalTasks = Task::where('assign_to', $staff->id)->count();
            $completedTasks = Task::where('assign_to', $staff->id)
                ->where('task_status', 'complete')
                ->count();
            $percentage = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'total' => $totalTasks,
                'completed' => $completedTasks,
                'percentage' => $percentage,
            ];
        })
            ->sortByDesc('percentage')
            ->values();
        return view('Admin.student_dashboard', compact(
            'staffCount',
            'projectCount',
            'pendingCount',
            'inprogressCount',
            'presentCount',
            'todaytask',
            'todayCompletedCount',
            'studentPresentCount',
            'studentCount',
            'staffTaskStatus',
            'projects',
        ));
    }
    public function create_intern()
    {
        $roles = Role::all();
        return view("Admin.create_intern", compact('roles'));
    }
    public function create_intern_form(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'mobile' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'designation' => 'required|string|max:50',
            'intern_period' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);
        if ($validator->fails()) {
            Log::warning('Intern validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            do {
                $user_id = 'INT' . rand(1000, 9999);
            } while (User::where('user_id', $user_id)->exists());
            Log::info("Validation passed");
            User::create([
                'user_id' => $user_id,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'designation' => $request->designation,
                'intern_period' => $request->intern_period,
                'address' => $request->address,
                'dob' => $request->dob,
                'role' => 'intern'
            ]);
            DB::commit();
            Log::info('Intern created successfully');
            return redirect()->route('intern_table')->with('success', 'Intern created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Intern creation failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function intern_table()
    {
        return view("Admin.intern_table");
    }
    public function intern_table_data()
    {
        $query = User::where('role', 'intern');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('task', function ($row) {
                return '<a href="' . route('student_task_view', ['student_id' => $row->id]) . '" class="btn btn-outline-danger btn-sm">Task</a>';
            })
            ->editColumn('is_active', function ($user) {
                return $user->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($row) {
                $edit = '<a class="action-btn " href="' . route('edit_intern', $row->id) . '">
                        <img src="' . asset("assets/img/edit.png") . '" alt="">
                    </a>';
                $delete = '<a class="action-btn-danger deleteBtn"
                             data-bs-toggle = "modal"
                             data-bs-target = "#delete_intern"
                             data-id="' . $row->id . '"
                             onclick="setDeleteId(this)">
                        <img src            = "' . asset('assets/img/trash.png') . '" alt = "">
                    </a>';
                $toggleStatus = '<a class="action-btn "
                               data-id="' . $row->id . '"
                               data-active="' . ($row->is_active ? 1 : 0) . '"
                               data-bs-target="#toggle_status"
                               data-bs-toggle="modal"
                               onclick="setToggleId(this)">
                               <img src="' . asset("assets/img/block.png") . '" alt="" width="18px" height="17px">
                             </a>';
                return '<div class="dropdown d-flex justify-evenly gap-1">' . $edit . $delete . $toggleStatus . '</div>';
            })
            ->rawColumns(['actions', 'task'])
            ->make(true);
    }
    public function student_task_view($student_id)
    {
        $studentName = User::where('id', $student_id)->value('name');
        return view('Admin.student_task_view', [
            'studentId' => $student_id,
            'studentName' => $studentName
        ]);
    }
    public function student_task_view_data(Request $request, $studentId)
    {
        $query = StudentTask::with(['course', 'topic', 'chapter'])
            ->where('student_id', $studentId);
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('course', function ($row) {
                return $row->course->course_name ?? '-';
            })
            ->addColumn('topic', function ($row) {
                return $row->topic->topic_name ?? '-';
            })
            ->addColumn('chapter', function ($row) {
                return $row->chapter->chapter_name ?? '-';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'new' => '<span class="badge bg-label-info btn-sm">Not Started</span>',
                    'complete' => '<span class="badge bg-label-success">Completed</span>',
                    'hold' => '<span class="badge bg-label-danger">Hold</span>',
                    default => '-',
                };
            })
            ->rawColumns(['status',])
            ->make(true);
    }
    public function edit_intern($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('Admin.edit_intern', compact('user', 'roles'));
    }
    public function update_intern(Request $request)
    {
        Log::info('Intern update request received', [
            'intern_id' => $request->id,
            'data' => $request->all()
        ]);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'mobile' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'designation' => 'required|string|max:50',
            'intern_period' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);
        if ($validator->fails()) {
            Log::warning('Intern update validation failed', [
                'intern_id' => $request->id,
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info('Intern update transaction started', ['intern_id' => $request->id]);
            $user = User::findOrFail($request->id);
            $user->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'designation' => $request->designation,
                'intern_period' => $request->intern_period,
                'address' => $request->address,
                'dob' => $request->dob,
            ]);
            DB::commit();
            Log::info('Intern updated successfully', ['intern_id' => $user->id]);
            return redirect()->route('intern_table')
                ->with('success', 'Intern updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Intern update failed', [
                'intern_id' => $request->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    //delete intern
    public function delete_intern(Request $request)
    {
        Log::info('Intern delete request received', ['intern_id' => $request->id]);
        $user = User::where('id', $request->id)
            ->where('role', 'intern')
            ->firstOrFail();
        $user->delete();
        return redirect()->route('intern_table')
            ->with('success', 'Intern deleted successfully!');
    }
    //toggle status-> block/unblock
    public function intern_toggle_status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);
        try {
            $intern = User::findOrFail($request->id);
            $intern->is_active = !$intern->is_active;
            $intern->save();
            $status = $intern->is_active ? 'Active' : 'Inactive';
            Log::info("Intern status changed to {$status}: ID {$intern->id}");
            return redirect()->back()->with('success', "Intern {$status} successfully!");
        } catch (\Exception $e) {
            Log::error("Failed to toggle staff Intern", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function intern_attendance()
    {
        return view('Admin.intern_attendance');
    }
    public function intern_attendance_data()
    {
        $query = InternAttendance::with('user')->latest()->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->editColumn('check_in', function ($row) {
                return $row->check_in
                    ? Carbon::parse($row->check_in)->format('h:i:s A')
                    : '-';
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
            ->rawColumns(['type', 'late_reason'])
            ->make(true);
    }
    public function submit_course(Request $request)
    {
        DB::beginTransaction();
        try {
            // validation
            $request->validate([
                'course_name' => 'required|string|max:255',
            ]);
            // create course
            Course::create([
                'course_name' => $request->course_name,
            ]);
            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Course created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // log error
            Log::error('Course create failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function course()
    {
        return view('Admin.course');
    }
    public function course_data(Request $request)
    {
        $courses = Course::select('id', 'course_name', 'created_at')
            ->withCount(['topics', 'chapters']);
        return DataTables::of($courses)
            ->addIndexColumn()
            ->addColumn('topics', function ($row) {
                return '
                <div class="d-flex align-items-center gap-2">
                    <a href="' . route('topic', $row->id) . '" >
                        View
                    </a>
                </div>
            ';
            })
            ->addColumn('chapters', function ($row) {
                return '
                <div class="d-flex align-items-center gap-2">
                    <a href="' . route('chapter', $row->id) . '" >
                        View
                    </a>
                </div>
            ';
            })
            ->addColumn('total_topic', function ($row) {
                return $row->topics_count;
            })
            ->addColumn('total_chapter', function ($row) {
                return $row->chapters_count;
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="d-flex align-items-center gap-2"
                 data-bs-toggle="modal"
                 data-bs-target="#delete"
                 data-id="' . $row->id . '"
                             onclick="setDeleteId(this)">
                    <i class="icon-base ti tabler-trash text-danger"
                       style="cursor:pointer"
                      ></i>
                </div>
            ';
            })
            ->rawColumns(['topics', 'chapters', 'action'])
            ->make(true);
    }
    public function delete_course(Request $request)
    {
        $data = Course::findOrFail($request->id);
        $data->delete();
        return redirect()->back()->with('success', 'Course deleted successfully!');
    }
    public function topic($id)
    {
        $course = Course::findOrFail($id);
        return view('Admin.topic', compact('course'));
    }
    public function topic_data($id)
    {
        $topics = Topic::where('course_id', $id);
        return DataTables::of($topics)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
            <div class="d-flex align-items-center gap-2"
                 data-bs-toggle="modal"
                 data-bs-target="#delete"
                 data-id="' . $row->id . '"
                 onclick="setDeleteId(this)">
                <i class="icon-base ti tabler-trash text-danger" style="cursor:pointer"></i>
            </div>';
            })
            ->rawColumns(['chapters', 'action'])
            ->make(true);
    }
    public function submit_topic(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'topic_name' => 'required|string|max:255',
            ]);
            Topic::create([
                'topic_name' => $request->topic_name,
                'course_id' => $request->course_id,
            ]);
            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Topic created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Topic create failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function delete_topic(Request $request)
    {
        $data = Topic::findOrFail($request->id);
        $data->delete();
        return redirect()->back()->with('success', 'Topic deleted successfully!');
    }
    public function chapter($id)
    {
        $course = Course::findOrFail($id);
        $topics = Topic::where('course_id', $course->id)->get();
        $students = User::where('role', 'intern')->get();
        return view('Admin.chapter', compact('course', 'topics', 'students'));
    }
    public function chapter_data($id)
    {
        $chapters = Chapter::where('course_id', $id)
            ->orderBy('id', 'DESC');
        return DataTables::of($chapters)
            ->addIndexColumn()
            ->addColumn('chapter', function ($row) {
                return $row->chapter_name;
            })
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('assign_to', function ($row) {
                return '
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-label-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#task"
                    data-course-id="' . $row->course_id . '"
                    data-topic-id="' . $row->topic_id . '"
                    data-chapter-id="' . $row->id . '"
                    type="button">
                    Assign
                </button>
            </div>';
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="d-flex align-items-center gap-2"
                     data-bs-toggle="modal"
                     data-bs-target="#delete"
                     data-id="' . $row->id . '"
                     onclick="setDeleteId(this)">
                    <i class="icon-base ti tabler-trash text-danger" style="cursor:pointer"></i>
                </div>';
            })
            ->rawColumns(['action', 'assign_to'])
            ->make(true);
    }
    public function submit_chapter(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic_id' => 'required|exists:topics,id',
            'chapter_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            Chapter::create([
                'course_id' => $request->course_id,
                'topic_id' => $request->topic_id,
                'chapter_name' => $request->chapter_name,
                'description' => $request->description,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Chapter created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Chapter create failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function assign_chapter(Request $request)
    {
        Log::info('Assign chapter request received', [
            'request_data' => $request->all()
        ]);
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:users,id',
                'course_id' => 'required|exists:courses,id',
                'topic_id' => 'required|exists:topics,id',
                'chapter_id' => 'required|exists:chapters,id',
            ]);
            Log::info('Assign chapter validation passed', [
                'validated_data' => $validated
            ]);
            $student_id = (int) $request->student_id;
            $chapter_id = (int) $request->chapter_id;
            $alreadyAssigned = StudentTask::where('student_id', $student_id)
                ->where('chapter_id', $chapter_id)
                ->exists();
            Log::info('Already assigned check', [
                'student_id' => $student_id,
                'chapter_id' => $chapter_id,
                'exists' => $alreadyAssigned
            ]);
            if ($alreadyAssigned) {
                DB::rollBack();
                return back()->with('error', 'Chapter already assigned to this student');
            }
            $task = StudentTask::create([
                'student_id' => $student_id,
                'course_id' => $request->course_id,
                'topic_id' => $request->topic_id,
                'chapter_id' => $chapter_id,
                'status' => 'new',
            ]);
            Log::info('Chapter assigned successfully', [
                'task_id' => $task->id
            ]);
            StudentTaskHistory::create([
                'task_id' => $task->id,
                'chapter_id' => $task->chapter_id,
                'course_id' => $task->course_id,
                'student_id' => $task->student_id,
                'status' => $task->status,
                'remark' => null,
                'spend_hour' => null,
            ]);
            Log::info('StudentTaskHistory created');
            DB::commit();
            return back()->with('success', 'Chapter assigned successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Chapter assignment failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'request_data' => $request->all(),
            ]);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function delete_chapter(Request $request)
    {
        $data = Chapter::findOrFail($request->id);
        $data->delete();
        return redirect()->back()->with('success', 'Chapter deleted successfully!');
    }
    public function student_tasks()
    {
        return view("Admin.student_tasks");
    }
    public function student_tasks_data()
    {
        $tasks = StudentTask::with(['chapter:id,chapter_name,description', 'assignedStaff:id,name'])
            ->orderBy('id', 'desc');
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('chapter_name', function ($row) {
                return $row->chapter ? $row->chapter->chapter_name : 'N/A';
            })
            ->addColumn('description', function ($row) {
                return $row->chapter->description;
            })
            ->addColumn('assign_to', function ($row) {
                if ($row->assignedStaff) {
                    return '<div class="d-flex align-items-center gap-2">
                            <span class="text-nowrap">' . $row->assignedStaff->name . '</span>
                        </div>';
                }
                return '<div class="d-flex align-items-center gap-2">
                        <button class="btn btn-label-info text-nowrap" style="cursor:pointer">Not Assigned</button>
                    </div>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'new' => '<span class="badge bg-label-info btn-sm">Not Started</span>',
                    'complete' => '<span class="badge bg-label-success">Completed</span>',
                    'hold' => '<span class="badge bg-label-danger">Hold</span>',
                    default => '-',
                };
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex align-items-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#delete"
                        data-id="' . $row->id . '"
                        onclick="setDeleteId(this)">
                        <i class="icon-base ti tabler-trash text-danger" style="cursor:pointer"></i>
                    </div>';
            })
            ->rawColumns(['action', 'assign_to', 'status'])
            ->make(true);
    }
    public function delete_student_task(Request $request)
    {
        DB::beginTransaction();
        try {
            $task = StudentTask::findOrFail($request->id);
            StudentTaskHistory::where('chapter_id', $task->chapter_id)
                ->where('student_id', $task->student_id)
                ->delete();
            $task->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Task and history deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task delete failed', [
                'error' => $e->getMessage(),
                'task_id' => $request->id
            ]);
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function reopen($staff_id)
    {
        return view('Admin.reopen', compact('staff_id'));
    }
    public function reopen_data(Request $request, $staff_id)
    {
        if ($request->ajax()) {
            $data = DB::table('task_histories')
                ->join('tasks', 'tasks.id', '=', 'task_histories.task_id')
                ->join('projects', 'projects.id', '=', 'tasks.project_id') // ✅ join project
                ->where('tasks.assign_to', $staff_id)
                ->where('task_histories.status', 'reopen')
                ->select(
                    'projects.project_name',
                    'tasks.task_name',
                    'task_histories.remark',
                    'task_histories.reopen_type',
                    'task_histories.created_at'
                )
                ->orderByDesc('task_histories.created_at');
            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i');
                })
                ->make(true);
        }
    }
    public function project_reopen($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('Admin.project_reopen', compact('project'));
    }
    public function projectReopenData(Request $request, $projectId)
    {
        if ($request->ajax()) {
            $data = DB::table('task_histories')
                ->join('tasks', 'tasks.id', '=', 'task_histories.task_id')
                ->join('projects', 'projects.id', '=', 'tasks.project_id')
                ->join('users', 'users.id', '=', 'tasks.assign_to')
                ->where('tasks.project_id', $projectId)
                ->where('task_histories.status', 'reopen')
                ->select(
                    'projects.project_name',
                    'tasks.task_name',
                    'users.name as staff_name',
                    'task_histories.reopen_type',
                    'task_histories.remark',
                    'task_histories.created_at'
                )
                ->orderByDesc('task_histories.created_at');
            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A');
                })
                ->make(true);
        }
    }
    public function monthly_report()
    {
        $tasks = Task::with(['histories', 'assignedStaff', 'project'])
            ->get()
            ->groupBy('assign_to');
        $report = [];
        $uniqueCheck = [];
        foreach ($tasks as $staffId => $staffTasks) {
            $staff = User::where('id', $staffId)
                ->where('is_active', 1)
                ->first();
            if (!$staff) {
                continue;
            }
            if ($staff->designation == 'Project Coordinator' && $staff->name == 'Sterbin S R') {
                $staffTasks = Task::with(['histories', 'project'])->get();
            }
            foreach ($staffTasks as $task) {
                foreach ($task->histories as $history) {
                    // Only include worked statuses
                    if (!in_array(strtolower($history->status), ['start', 'inprogress', 'hold', 'complete', 'reassign'])) {
                        continue;
                    }
                    $date = Carbon::parse($history->created_at)->toDateString();
                    $project = $task->project->project_name ?? 'Project';
                    $key = $staff->id . '_' . $date . '_' . $project;
                    if (!isset($uniqueCheck[$key])) {
                        $report[] = [
                            'staff_id' => $staff->id,
                            'staff_name' => $staff->name,
                            'project_name' => $project,
                            'work_date' => $date
                        ];
                        $uniqueCheck[$key] = true;
                    }
                }
            }
        }
        /* ===== STAFF STATUS ===== */
        $today = Carbon::today();
        $wfhStaff = Wfh::where('reply', 'approved')
            ->select('user_id', 'from', 'to')
            ->get();
        $leaveStaff = Leave::join('users', 'users.id', '=', 'leaves.user_id')
            ->leftJoin('loginentries', function ($join) {
                $join->on('loginentries.user_id', '=', 'users.id')
                    ->whereRaw('DATE(loginentries.created_at) BETWEEN DATE(leaves.from) AND DATE(leaves.to)');
            })
            ->where('leaves.reply', 'approved')
            ->whereNull('loginentries.check_in')
            ->select(
                'users.id as user_id',
                DB::raw('DATE(leaves.from) as from_date'),
                DB::raw('DATE(leaves.to) as to_date')
            )
            ->get();
        $permissionStaff = Permission::where('reply', 'approved')
            ->select('user_id', DB::raw('DATE(created_at) as date'))
            ->get();
        return view('Admin.monthly_report', compact(
            'report',
            'wfhStaff',
            'leaveStaff',
            'permissionStaff'
        ));
    }
    public function monthly_project_report(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $tasks = Task::with(['project', 'assignedStaff', 'histories'])->get();
        $projects = [];
        foreach ($tasks as $task) {
            foreach ($task->histories as $history) {
                // ✅ only worked status include
                if (!in_array(strtolower($history->status), ['start', 'inprogress', 'hold', 'complete', 'reassign'])) {
                    continue;
                }
                $date = Carbon::parse($history->created_at)->toDateString();
                // ✅ date filter
                if ($from && $to) {
                    if ($date < $from || $date > $to) {
                        continue;
                    }
                }
                $projectName = $task->project->project_name ?? 'Project';
                $staffName = $task->assignedStaff->name ?? '';
                if (!isset($projects[$projectName])) {
                    $projects[$projectName] = [
                        'project_name' => $projectName,
                        'staff' => []
                    ];
                }
                // ✅ prevent duplicate staff
                if (!in_array($staffName, $projects[$projectName]['staff'])) {
                    $projects[$projectName]['staff'][] = $staffName;
                }
            }
        }
        $projects = array_values($projects);
        return view('Admin.monthly_project_report', compact('projects'));
    }
    public function feed_back()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('Admin.feed_back', compact('staffs'));
    }
    public function seen_feedbacks()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('Admin.seen_feedbacks', compact('staffs'));
    }
    public function mark_feedback_seen(Request $request)
    {
        Feedback::where('id', $request->feedback_id)
            ->update(
                ['status' => 'seen']
            );
        return back()->with('success', 'Feedback marked as seen');
    }
    public function feed_back_data(Request $request)
    {
        try {
            $feedback = Feedback::with('user')
                ->where('status', 'pending')
                ->select('feedback.*')
                ->latest();
            // Search Filter
            if ($request->has('search') && $request->search['value'] != '') {
                $keyword = $request->search['value'];
                $feedback->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhereHas('user', function ($u) use ($keyword) {
                            $u->where('name', 'like', "%{$keyword}%");
                        });
                });
            }
            // Month Filter
            if ($request->has('month') && $request->month != '') {
                $monthNumber = date('m', strtotime($request->month));
                $feedback->whereMonth('created_at', $monthNumber);
            }
            // Employee Filter
            if ($request->has('employee_id') && $request->employee_id != '') {
                $feedback->where('user_id', $request->employee_id);
            }
            return DataTables::of($feedback)
                ->addIndexColumn()
                // Employee Name
                ->addColumn('emp', function ($row) {
                    return optional($row->user)->name ?? '-';
                })
                // Month
                ->addColumn('month', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)
                        ->format('F');
                })
                ->addColumn('description', function ($row) {
                    $url = route('view_feedback', $row->id);
                    return '
        <div class="d-flex align-items-center gap-3">
            <a href="' . $url . '" title="View Feedback">
                <i class="ti tabler-eye text-danger fs-5"></i>
            </a>
            <a href="javascript:void(0);"
                class="seenBtn"
                data-id="' . $row->id . '"
                data-bs-toggle="modal"
                data-bs-target="#seenModal"
                title="Mark as Seen">
                <i class="ti tabler-circle-check text-success fs-5"></i>
            </a>
        </div>
    ';
                })
                ->rawColumns([
                    'description',
                ])
                ->make(true);
        } catch (\Throwable $e) {
            Log::error('Feedback Error', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'data' => []
            ]);
        }
    }
    public function seen_feed_back_data(Request $request)
    {
        try {
            $feedback = Feedback::with('user')
                ->where('status', 'seen')
                ->select('feedback.*')
                ->latest();
            // Search Filter
            if ($request->has('search') && $request->search['value'] != '') {
                $keyword = $request->search['value'];
                $feedback->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhereHas('user', function ($u) use ($keyword) {
                            $u->where('name', 'like', "%{$keyword}%");
                        });
                });
            }
            // Month Filter
            if ($request->has('month') && $request->month != '') {
                $monthNumber = date('m', strtotime($request->month));
                $feedback->whereMonth('created_at', $monthNumber);
            }
            // Employee Filter
            if ($request->has('employee_id') && $request->employee_id != '') {
                $feedback->where('user_id', $request->employee_id);
            }
            return DataTables::of($feedback)
                ->addIndexColumn()
                // Employee Name
                ->addColumn('emp', function ($row) {
                    return optional($row->user)->name ?? '-';
                })
                // Month
                ->addColumn('month', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)
                        ->format('F');
                })
                ->addColumn('description', function ($row) {
                    $url = route('view_feedback', $row->id);
                    return '
        <a href="' . $url . '" title="View Feedback">
            <i class="ti tabler-eye text-danger fs-5"></i>
        </a>
    ';
                })
                ->rawColumns([
                    'description',
                ])
                ->make(true);
        } catch (\Throwable $e) {
            Log::error('Feedback Error', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'data' => []
            ]);
        }
    }
    // public function feed_back_data(Request $request)
    // {
    //     try {
    //         $feedback = Feedback::with('user')->select('feedback.*')->latest();
    //         // $feedback = Null;
    //         // Filter by search
    //         if ($request->has('search') && $request->search['value'] != '') {
    //             $keyword = $request->search['value'];
    //             $feedback->where(function ($q) use ($keyword) {
    //                 $q->where('title', 'like', "%{$keyword}%")
    //                     ->orWhereHas('user', function ($u) use ($keyword) {
    //                         $u->where('name', 'like', "%{$keyword}%");
    //                     });
    //             });
    //         }
    //         // Filter by month
    //         if ($request->has('month') && $request->month != '') {
    //             $monthNumber = date('m', strtotime($request->month));
    //             $feedback->whereMonth('created_at', $monthNumber);
    //         }
    //         // Filter by employee
    //         if ($request->has('employee_id') && $request->employee_id != '') {
    //             $feedback->where('user_id', $request->employee_id);
    //         }
    //         return DataTables::of($feedback)
    //             ->addIndexColumn()
    //             ->addColumn('emp', function ($row) {
    //                 return optional($row->user)->name ?? '-';
    //             })
    //             ->addColumn('month', function ($row) {
    //                 return \Carbon\Carbon::parse($row->created_at)->format('F');
    //             })
    //             ->addColumn('description', function ($row) {
    //                 $url = route('view_feedback', $row->id);
    //                 return '<a href="' . $url . '">
    //                 <i class="ti tabler-eye text-danger"></i>
    //             </a>';
    //             })
    //             ->rawColumns(['description'])
    //             ->make(true);
    //     } catch (\Throwable $e) {
    //         Log::error('Feedback Error', ['error' => $e->getMessage()]);
    //         return response()->json(['data' => []]);
    //     }
    // }
    public function view_feedback($id)
    {
        $feedback = Feedback::with('user')->findOrFail($id);
        return view('Admin.view_feedback', compact('feedback'));
    }
    public function wfh_staff()
    {
        return view('Admin.wfh_staff');
    }
    public function today_wfh_employee_data()
    {
        $today = Carbon::today();
        $query = User::where('role', 'staff')
            ->whereIn('id', function ($q) use ($today) {
                $q->select('user_id')
                    ->from('wfhs')
                    ->whereDate('from', '<=', $today)
                    ->whereDate('to', '>=', $today);
            });
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('task', function ($row) {
                return '<a href="' . route('task_view', ['staff_id' => $row->id]) . '"
                class="btn btn-outline-danger btn-sm">
                Task
            </a>';
            })
            ->addColumn('report', function ($row) {
                return '<a href="' . route('weekly_report', ['staff_id' => $row->id]) . '"
                class="btn btn-outline-success btn-sm">
                Report
            </a>';
            })
            ->addColumn('tasks_count', function ($row) {
                $totalTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->count();
                $completedTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->where('task_status', 'complete')
                    ->count();
                return '<span class="text-nowrap">
                <span class="text-success">' . $completedTasks . '</span> |
                <span class="text-muted">' . $totalTasks . '</span>
            </span>';
            })
            ->editColumn('is_active', function () {
                return '<span class="badge bg-label-info">WFH</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = '
            <a class="action-btn" href="' . route('edit_staff', $row->id) . '">
                <img src="' . asset('assets/img/edit.png') . '" alt="Edit">
            </a>';
                $delete = '
            <a class="action-btn-danger deleteBtn"
               data-id="' . $row->id . '"
               data-bs-toggle="modal"
               data-bs-target="#delete_staff"
               onclick="setDeleteId(this)">
                <img src="' . asset('assets/img/trash.png') . '" alt="Delete">
            </a>';
                return '
            <div class="d-flex justify-content-evenly gap-2">
                ' . $edit . $delete . '
            </div>';
            })
            ->rawColumns([
                'task',
                'report',
                'tasks_count',
                'is_active',
                'actions'
            ])
            ->make(true);
    }
    public function absent_staff()
    {
        return view('Admin.absent_staff');
    }
    public function today_absent_employee_data()
    {
        $today = Carbon::today();
        $query = User::where('role', 'staff')
            ->whereIn('id', function ($q) use ($today) {
                $q->select('user_id')
                    ->from('leaves')
                    ->whereDate('from', '<=', $today)
                    ->whereDate('to', '>=', $today);
            });
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('task', function ($row) {
                return '<a href="' . route('task_view', ['staff_id' => $row->id]) . '"
                class="btn btn-outline-danger btn-sm">
                Task
            </a>';
            })
            ->addColumn('report', function ($row) {
                return '<a href="' . route('weekly_report', ['staff_id' => $row->id]) . '"
                class="btn btn-outline-success btn-sm">
                Report
            </a>';
            })
            ->addColumn('tasks_count', function ($row) {
                $totalTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->count();
                $completedTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->where('task_status', 'complete')
                    ->count();
                return '<span class="text-nowrap">
                <span class="text-success">' . $completedTasks . '</span> |
                <span class="text-muted">' . $totalTasks . '</span>
            </span>';
            })
            ->editColumn('is_active', function () {
                return '<span class="badge bg-label-warning">Absent</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = '
            <a class="action-btn" href="' . route('edit_staff', $row->id) . '">
                <img src="' . asset('assets/img/edit.png') . '" alt="Edit">
            </a>';
                $delete = '
            <a class="action-btn-danger deleteBtn"
               data-id="' . $row->id . '"
               data-bs-toggle="modal"
               data-bs-target="#delete_staff"
               onclick="setDeleteId(this)">
                <img src="' . asset('assets/img/trash.png') . '" alt="Delete">
            </a>';
                return '
            <div class="d-flex justify-content-evenly gap-2">
                ' . $edit . $delete . '
            </div>';
            })
            ->rawColumns([
                'task',
                'report',
                'tasks_count',
                'is_active',
                'actions'
            ])
            ->make(true);
    }
    public function inactive_employees()
    {
        return view('Admin.inactive_employees');
    }
    public function inactive_employee_data()
    {
        $query = User::where('role', 'staff')
            ->where('is_active', 0); // only inactive employees
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('task', function ($row) {
                return '<a href="' . route('task_view', ['staff_id' => $row->id]) . '"
                class="btn btn-outline-danger btn-sm">
                Task
            </a>';
            })
            ->addColumn('report', function ($row) {
                return '<a href="' . route('weekly_report', ['staff_id' => $row->id]) . '"
                class="btn btn-outline-success btn-sm">
                Report
            </a>';
            })
            ->addColumn('tasks_count', function ($row) {
                $totalTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->count();
                $completedTasks = DB::table('tasks')
                    ->where('assign_to', $row->id)
                    ->where('task_status', 'complete')
                    ->count();
                return '<span class="text-nowrap">
                <span class="text-success">' . $completedTasks . '</span> |
                <span class="text-muted">' . $totalTasks . '</span>
            </span>';
            })
            ->editColumn('is_active', function ($user) {
                return '<span class="badge bg-label-danger">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = '
            <a class="action-btn" href="' . route('edit_staff', $row->id) . '">
                <img src="' . asset('assets/img/edit.png') . '" alt="Edit">
            </a>';
                $delete = '
            <a class="action-btn-danger deleteBtn"
               data-id="' . $row->id . '"
               data-bs-toggle="modal"
               data-bs-target="#delete_staff"
               onclick="setDeleteId(this)">
                <img src="' . asset('assets/img/trash.png') . '" alt="Delete">
            </a>';
                $toggleStatus = '
            <a class="action-btn"
               data-id="' . $row->id . '"
               data-active="0"
               data-bs-toggle="modal"
               data-bs-target="#toggle_status"
               onclick="setToggleId(this)">
                <img src="' . asset('assets/img/block.png') . '" width="18" height="17" alt="Block">
            </a>';
                return '
            <div class="d-flex justify-content-evenly gap-2">
                ' . $edit . $delete . $toggleStatus . '
            </div>';
            })
            ->rawColumns([
                'task',
                'report',
                'tasks_count',
                'is_active',
                'actions'
            ])
            ->make(true);
    }
    public function not_inprogress()
    {
        return view('Admin.not_inprogress');
    }
    public function admin_not_inprogress_tasks_data()
    {
        $userCodes = Loginentries::whereDate('created_at', Carbon::today())
            ->whereNotNull('check_in')
            ->pluck('user_id');
        $users = User::whereIn('user_id', $userCodes)
            ->whereDoesntHave('tasks', function ($query) {
                $query->where('task_status', 'inprogress');
            })
            ->get();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('employee', function ($user) {
                return $user->name ?? '-';
            })
            ->addColumn('view', function ($user) {
                return '
        <a href="' . route('task_view', $user->id) . '"
           class="btn btn-sm btn-warning">
            <i class="fa fa-eye me-1"></i> View
        </a>
    ';
            })
            ->addColumn('status', function ($user) {
                return 'Not In Progress';
            })
            ->addColumn('action', function ($user) {
                return '
                <button class="btn btn-sm btn-warning sendNotification" data-id="' . $user->id . '">send
                    <i class="fa fa-bell"></i>
                </button>
            ';
            })
            ->rawColumns(['view', 'action'])
            ->make(true);
    }
    public function inprogress_notification(Request $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['status' => false]);
        }
        webpushnotify(
            $user->id,
            'Task Pending',
            'You have not started any task yet. Please start your task and move it to In Progress.'
        );
        return response()->json([
            'status' => true,
            'message' => 'Notification sent successfully!'
        ]);
    }
    public function admin_not_inprogress_tasks_dataxx()
    {
        // ✅ Get today present user IDs
        $userIds = Loginentries::whereDate('created_at', Carbon::today())
            ->pluck('user_id');
        // ✅ Get tasks for those users which are NOT in progress
        $tasks = Task::with('assignedStaff')
            ->whereIn('user_id', $userIds)
            ->where('task_status', '!=', 'inprogress')
            ->get();
        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('employee', function ($task) {
                return optional($task->assignedStaff)->name ?? '-';
            })
            ->addColumn('status', function ($task) {
                return 'Not In Progress';
            })
            ->make(true);
    }
    public function pending_task_employee()
    {
        return view('Admin.pending_task_employee');
    }
    public function hold_task_staff()
    {
        return view('Admin.hold_task_staff');
    }
    public function completed_staff($staff_id)
    {
        return view('Admin.completed_staff', compact('staff_id'));
    }
    public function completed_staff_data(Request $request, $staff_id)
    {
        $query = Task::with([
            'project:id,project_name',
            'module:id,module_name',
            'assignedStaff:id,name'
        ])
            ->where('task_status', 'complete')
            ->where('assign_to', $staff_id);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('start_date', function ($row) {
                return $row->start_date
                    ? Carbon::parse($row->start_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date
                    ? Carbon::parse($row->due_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? 'N/A';
            })
            ->addColumn('module', function ($row) {
                return '<strong>' . ($row->module_type ?? '-') . '</strong><br>
                    <small>' . ($row->module->module_name ?? '-') . '</small>';
            })
            ->addColumn('task', function ($row) {
                return '<strong>Task Title:</strong> ' . ($row->task_name ?? '-');
            })
            ->addColumn('assigned_staff', function ($row) {
                return $row->assignedStaff->name ?? 'Not Assigned';
            })
            ->addColumn('status', function ($row) {
                return '<span class="badge bg-success text-nowrap">Completed</span>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('task_description', ['task_id' => $row->id]) . '"
                    class="btn btn-sm btn-warning mt-1">View</a>';
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where(function ($q) use ($search) {
                        $q->where('task_name', 'like', "%{$search}%")
                            ->orWhereHas('project', function ($q2) use ($search) {
                                $q2->where('project_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('module', function ($q2) use ($search) {
                                $q2->where('module_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('assignedStaff', function ($q2) use ($search) {
                                $q2->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->rawColumns(['module', 'task', 'status', 'action'])
            ->make(true);
    }
    public function payment_report()
    {
        return view('Admin.payment_report');
    }
    public function add_bank()
    {
        return view('Admin.add_bank');
    }
  public function view_mail($id)
{
    $communication = Communication::with([
        'attachments',
        'user'
    ])->findOrFail($id);

    return view(
        'Admin.view_mail',
        compact('communication')
    );
}
public function reply($id)
{

    /*
    |--------------------------------------------------------------------------
    | UPDATE UNREAD REPLIES AS READ
    |--------------------------------------------------------------------------
    */
    CommunicationReply::where('communication_id', $id)
        ->where('reply_from', 'staff')
        ->where('is_read', 0)
        ->update([
            'is_read' => 1
        ]);

    /*
    |--------------------------------------------------------------------------
    | GET COMMUNICATION WITH REPLIES
    |--------------------------------------------------------------------------
    */
    $communication = Communication::with([
        'replies.user'
    ])->findOrFail($id);

    return view(
        'Admin.reply',
        compact('communication')
    );
}

  public function create_doc($project_id = null)
{
    $project = $project_id ? Project::find($project_id) : null;
    $projects = Project::all();

    return view('Admin.create_doc', compact('project', 'projects', 'project_id'));
}
    public function sent_mail()
    {
        return view('Admin.sent_mail');
    }
    public function create_communication()
    {
        $employees = User::where('role', 'staff')
            ->where('is_active', 1)
            ->latest()
            ->get();
        return view('Admin.create_communication', compact('employees'));
    }



     public function mail_table()
    {
        return view('Admin.mail_table');
    }
    public function mail_report_data(Request $request)
{
    $communications = Communication::with('user')
        ->latest();
    return DataTables::of($communications)
        ->addIndexColumn()
        ->editColumn('created_at', function ($row) {
            return $row->created_at
                ? $row->created_at->format('d M Y h:i A')
                : '-';
        })
        ->addColumn('employee_name', function ($row) {
            return $row->user->name ?? '-';
        })
        ->addColumn('communication_type', function ($row) {
            return $row->communication_type ?? '-';
        })
        ->addColumn('priority', function ($row) {
            $class = '';
            if ($row->priority_level == 'High') {
                $class = 'bg-label-danger';
            } elseif ($row->priority_level == 'Medium') {
                $class = 'bg-label-warning';
            } else {
                $class = 'bg-label-success';
            }
            return '
                <span class="badge '.$class.'">
                    '.$row->priority_level.'
                </span>
            ';
        })
->addColumn('reply_needed', function ($row) {

    if ($row->reply_needed == 'Yes') {

        return '
            <div class="d-flex align-items-center gap-2">

                <span class="badge bg-label-primary">
                    Yes
                </span>

               

            </div>
        ';
    }

    return '
        <span class="badge bg-label-secondary">
            No
        </span>
    ';
})
     ->addColumn('is_replied', function ($row) {

    if ($row->is_replied == 1) {

        $replyUrl = url('admin/reply/' . $row->id);

        return '
            <div class="d-flex align-items-center gap-2">

                <span class="badge bg-label-success">
                    Yes
                </span>

                <a href="'.$replyUrl.'"
                    class="btn btn-sm btn-icon btn-text-secondary"
                    title="View Reply"
                >
                    <i class="ti tabler-eye"></i>
                </a>

            </div>
        ';
    }

    return '
        <span class="badge bg-label-secondary">
            No
        </span>
    ';
})
        ->addColumn('is_viewed', function ($row) {
            if ($row->is_viewed == 1) {
                return '
                    <span class="badge bg-label-success">
                        Viewed
                    </span>
                ';
            }
            return '
                <span class="badge bg-label-warning">
                    Pending
                </span>
            ';
        })
       ->addColumn('action', function ($row) {

    $url = route('view_mail', $row->id);

    return '
        <a href="' . $url . '"
            class="text-primary text-decoration-underline">
            View
        </a>
    ';
})
        ->rawColumns([
            'priority',
            'reply_needed',
            'is_replied',
            'is_viewed',
            'action'
        ])
        ->make(true);
}
public function popup_manager_form()
{
    $employees = User::where('role', 'staff')->where('is_active', 1)->get();
    return view('Admin.popup_manager_form', compact('employees'));

}
public function popup_manager()
{    
    $popups = Popup::latest()->get();

    return view('Admin.popup_manager',compact('popups'));
}

public function store_communication(Request $request)
    {
        Log::info('================ STORE COMMUNICATION START ================');
        DB::beginTransaction();
        try {
            /*
            |--------------------------------------------------------------------------
            | REQUEST LOG
            |--------------------------------------------------------------------------
            */
            Log::info('Request Data Received', [
                'request' => $request->all()
            ]);
            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */
            $validator = Validator::make($request->all(), [
                'employee_ids'         => 'required|array|min:1',
                'employee_ids.*'       => 'exists:users,id',
                'communication_type'   => 'required|string',
                'priority_level'       => 'required|string',
                'reply_needed'         => 'required|string',
                'subject'              => 'required|string|max:255',
                'content'              => 'required|string',
                'attachments.*'        => 'nullable|file|max:5120',
            ]);
            /*
            |--------------------------------------------------------------------------
            | VALIDATION FAILED
            |--------------------------------------------------------------------------
            */
            if ($validator->fails()) {
                Log::error('Validation Failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            Log::info('Validation Passed Successfully');
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE LOOP
            |--------------------------------------------------------------------------
            */
            foreach ($request->employee_ids as $employeeId) {
                Log::info('Processing Employee', [
                    'employee_id' => $employeeId
                ]);
                /*
                |--------------------------------------------------------------------------
                | GET USER
                |--------------------------------------------------------------------------
                */
                $user = User::where('id', $employeeId)
                    ->where('role', 'staff')
                    ->where('is_active', 1)
                    ->first();
                if (!$user) {
                    Log::warning('Employee Not Found Or Inactive', [
                        'employee_id' => $employeeId
                    ]);
                    continue;
                }
                Log::info('Employee Found', [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]);
                /*
                |--------------------------------------------------------------------------
                | CREATE COMMUNICATION
                |--------------------------------------------------------------------------
                */
                $communication = Communication::create([
                    'user_id'             => $employeeId,
                    'communication_type'  => $request->communication_type,
                    'priority_level'      => $request->priority_level,
                    'reply_needed'        => $request->reply_needed,
                    'subject'             => $request->subject,
                    'content'             => $request->content,
                    'status'              => 'pending',
                     'is_replied'          => 0,
    'is_viewed'           => 0,
                ]);
                Log::info('Communication Created Successfully', [
                    'communication_id' => $communication->id
                ]);
                /*
                |--------------------------------------------------------------------------
                | FILE UPLOAD
                |--------------------------------------------------------------------------
                */
                if ($request->hasFile('attachments')) {
                    Log::info('Attachments Found');
                    foreach ($request->file('attachments') as $file) {
                        Log::info('Uploading Attachment', [
                            'file_name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType()
                        ]);
                        $path = $file->store(
                            'communication_files',
                            'public'
                        );
                        Log::info('File Uploaded Successfully', [
                            'path' => $path
                        ]);
                        $attachmentData = CommunicationAttachment::create([
                            'communication_id' => $communication->id,
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                        ]);
                        Log::info('Attachment Saved In Database', [
                            'attachment_id' => $attachmentData->id
                        ]);
                    }
                } else {
                    Log::info('No Attachments Uploaded');
                }
                /*
                |--------------------------------------------------------------------------
                | LOAD ATTACHMENTS
                |--------------------------------------------------------------------------
                */
                $communication->load('attachments');
                Log::info('Attachments Loaded', [
                    'count' => $communication->attachments->count()
                ]);
                /*
                |--------------------------------------------------------------------------
                | PREPARE ATTACHMENTS
                |--------------------------------------------------------------------------
                */
                $attachments = [];
                if ($communication->attachments->count() > 0) {
                    foreach ($communication->attachments as $attachment) {
                        $filePath = storage_path(
                            'app/public/' . $attachment->file_path
                        );
                        Log::info('Preparing Attachment For Mail', [
                            'file_path' => $filePath
                        ]);
                        if (file_exists($filePath)) {
                            Log::info('Attachment File Exists');
                            $attachments[] = [
                                'name' => $attachment->file_name,
                                'content' => base64_encode(
                                    file_get_contents($filePath)
                                )
                            ];
                        } else {
                            Log::error('Attachment File Missing', [
                                'file_path' => $filePath
                            ]);
                        }
                    }
                }
                Log::info('Attachment Array Prepared', [
                    'total_attachments' => count($attachments)
                ]);
                /*
                |--------------------------------------------------------------------------
                | HTML CONTENT
                |--------------------------------------------------------------------------
                */
                $htmlContent = '
                    <div style="font-family:Arial;padding:20px;">
                        <h2 style="color:#333;">
                            ' . $communication->subject . '
                        </h2>
                        <p style="
                            font-size:15px;
                            line-height:24px;
                            color:#555;
                        ">
                            ' . nl2br($communication->content) . '
                        </p>
                        <br>
                        <p>
                            Regards,<br>
                            ' . env('BREVO_SENDER_NAME') . '
                        </p>
                    </div>
                ';
                Log::info('HTML Content Prepared');
                /*
                |--------------------------------------------------------------------------
                | SEND MAIL
                |--------------------------------------------------------------------------
                */
                Log::info('Sending Mail Started', [
                    'to_email' => $user->email,
                    'subject' => $communication->subject
                ]);
                $mailResponse = $this->sendBrevoMail(
                    $user->email,
                    $user->name,
                    $communication->subject,
                    $htmlContent,
                    $attachments
                );
                Log::info('Mail Response Received', [
                    'response' => $mailResponse
                ]);
                /*
                |--------------------------------------------------------------------------
                | UPDATE STATUS
                |--------------------------------------------------------------------------
                */
                if ($mailResponse) {
                    $communication->update([
                        'status' => 'sent'
                    ]);
                    Log::info('Communication Status Updated To SENT', [
                        'communication_id' => $communication->id
                    ]);
                } else {
                    $communication->update([
                        'status' => 'failed'
                    ]);
                    Log::error('Communication Status Updated To FAILED', [
                        'communication_id' => $communication->id
                    ]);
                }
            }
            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */
            DB::commit();
            Log::info('Database Transaction Committed Successfully');
            Log::info('================ STORE COMMUNICATION END ================');
            return redirect()->back()->with(
                'success',
                'Communication sent successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('================ STORE COMMUNICATION ERROR ================', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
