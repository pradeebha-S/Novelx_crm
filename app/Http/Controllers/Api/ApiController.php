<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Project;

use App\Models\TaskHistory;

use App\Models\Task;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Wfh;
use App\Models\Leave;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;











class ApiController extends Controller

{

    public function getAllProjects()

    {

        try {



            $projects = Project::all();



            return response()->json([

                'status' => true,

                'message' => 'Project list fetched successfully',

                'data' => $projects

            ], 200);

        } catch (\Exception $e) {



            return response()->json([

                'status' => false,

                'message' => 'Failed to fetch projects',

                'error' => $e->getMessage()

            ], 500);

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

    // 🔥 CHANGE HERE ONLY (RETURN JSON)
    return response()->json([
        'report' => $report,
        'wfhStaff' => $wfhStaff,
        'leaveStaff' => $leaveStaff,
        'permissionStaff' => $permissionStaff
    ]);
}

}

