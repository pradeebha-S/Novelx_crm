<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bugs;
use App\Models\Modules;
use App\Models\BugLogs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
class AdminBugController extends Controller
{
    public function add_bug($project_id)
    {
        $modules = Modules::where('project_id', $project_id)->get();
        $staffs = User::where('role', 'staff')
        ->where('is_active','1')
        ->get();
        return view('Admin.add_bug', compact('project_id', 'modules', 'staffs'));
    }
    public function bug_report($project_id)
    {
        $staffs = User::where('role', 'staff')
        ->where('is_active','1')
            ->get();
        $totalBugs = Bugs::where('project_id', $project_id)->count();
        $pendingBugs = Bugs::where('project_id', $project_id)
            ->where('status', 'pending')
            ->count();
        $developerCompleted = Bugs::where('project_id', $project_id)
            ->where('status', 'Developer Completed')
            ->count();
        $testingCompleted = Bugs::where('project_id', $project_id)
            ->where('status', 'Testing Completed')
            ->count();
        return view('Admin.bug_report', compact(
            'project_id',
            'staffs',
            'totalBugs',
            'pendingBugs',
            'developerCompleted',
            'testingCompleted',
            'staffs'
        ));
    }
    public function view_bug_details($id)
    {
        $bug = Bugs::findOrFail($id);
        return view('Admin.view_bug_details', compact('bug'));
    }
   public function admin_bug_report_data(Request $request, $project_id)
{
    $query = Bugs::with(['user','moduleData'])
        ->where('project_id', $project_id);
    // Filters
    if ($request->status) {
        $query->where('status', $request->status);
    }
    if ($request->type) {
        $query->where('bug_type', $request->type);
    }
    if ($request->employee) {
        $query->where('identified_by', $request->employee);
    }
    if ($request->priority) {
        $query->where('priority', $request->priority);
    }
    // Global Search
   if ($request->search) {
    $search = $request->search;
    $query->where(function ($q) use ($search) {
        $q->where('bug_title', 'like', "%{$search}%")
          ->orWhere('panel', 'like', "%{$search}%")
          ->orWhere('priority', 'like', "%{$search}%")
          ->orWhere('bug_type', 'like', "%{$search}%")
          ->orWhere('debug_by', 'like', "%{$search}%")
          ->orWhere('status', 'like', "%{$search}%");
        $q->orWhereHas('user', function ($u) use ($search) {
            $u->where('name', 'like', "%{$search}%");
        });
        $q->orWhereHas('moduleData', function ($m) use ($search) {
            $m->where('module_name', 'like', "%{$search}%");
        });
    });
}
    // Total records
    $totalRecords = Bugs::where('project_id', $project_id)->count();
    // Filtered records
    $filteredRecords = $query->count();
    // Pagination
    $bugs = $query->skip($request->start)
        ->take($request->length)
        ->latest()
        ->get();
    $data = [];
    foreach ($bugs as $bug) {
        $data[] = [
            'name'      => $bug->user->name ?? '-',
            'panel'     => $bug->panel ?? '-',
            'priority'  => $bug->priority ?? '-',
            'type'      => $bug->bug_type ?? '-',
            'title'     => $bug->bug_title ?? '-',
            'module'    => $bug->moduleData->module_name ?? '-',
            'debug'     => $bug->debug_by ?? '-',
            'sts'       => $bug->status ?? '-',
            'solved_by' => $bug->solved_by ?? '-',
            'date'      => $bug->created_at
                    ? $bug->created_at->format('d-m-Y H:i:s') 
                    : '-',
    'id'               => $bug->id,
    'testing_scenario' => $bug->testing_scenario ?? '-',
    'current_output'   => $bug->current_output ?? '-',
    'expected_output'  => $bug->expected_output ?? '-',
    'reopen_count'     => $bug->reopen_count ?? '0',
    'suggestion'       => $bug->suggestion ?? '-',
        ];
    }
    return response()->json([
        "draw" => intval($request->draw),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredRecords,
        "data" => $data
    ]);
}
public function getModuleBugs($module_id)
{
    $bugs = Bugs::where('module', $module_id)->get();
    return response()->json($bugs);
}
    public function admin_create_bug(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'project_id'       => 'required|exists:projects,id',
           'panel'            => 'required|string',
           'bug_type'         => 'required|string',
           'bug_title'        => 'required|string|max:255',
           'module'           => 'required|exists:modules,id',
           'debug_by'         => 'required|exists:users,id',
           'priority'         => 'required|in:Low,Medium,High',
           'testing_scenario' => 'required|string',
           'current_output'   => 'required|string',
           'expected_output'  => 'required|string',
           'attachment'       => 'nullable|file',
           'suggestion'      => 'required|string',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $filePaths = [];
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('bug_attachments', 'public');
                $filePaths[] = $path;
            } elseif ($request->attachment_preview) {
                $image = $request->attachment_preview;
                if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
                    $image = substr($image, strpos($image, ',') + 1);
                    $type = strtolower($type[1]); // png / jpg / jpeg
                    $image = base64_decode($image);
                    if ($image === false) {
                        throw new \Exception('Base64 decode failed');
                    }
                    $fileName = 'bug_attachments/' . uniqid() . '.' . $type;
                    Storage::disk('public')->put($fileName, $image);
                    $filePaths[] = $fileName;
                }
            }
            $debugUser = User::findOrFail($request->debug_by);
            $bug = Bugs::create([
                'project_id' => $request->project_id,
                'identified_by' => Auth::guard('admin')->id(),
                'panel' => $request->panel,
                'bug_type' => $request->bug_type,
                'bug_title' => $request->bug_title,
                'attachment' => json_encode($filePaths),
                'module' => $request->module,
                'user_id' => $debugUser->id,
                'debug_by' => $debugUser->name,
                'priority' => $request->priority,
                'testing_scenario' => $request->testing_scenario,
                'current_output' => $request->current_output,
                'expected_output' => $request->expected_output,
                 'suggestion' => $request->suggestion,
                'status' => 'Pending'
            ]);
            BugLogs::create([
                'bug_id' => $bug->id,
                'user_id' => $debugUser->id,
                'action' => 'Created',
                'status' => 'Pending',
                'comment' => 'Bug created'
            ]);
            if ($debugUser->id) {
                webpushnotify(
                    $debugUser->id,
                    'New Bug Assigned',
                    'Bug "' . $bug->bug_title . '" has been assigned to you.'
                );
            }
            DB::commit();
            return back()->with('success', 'Bug created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Bug creation failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return back()->with('error', 'Something went wrong')->withInput();
        }
    }
    public function reopenBug(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bug_id' => 'required|exists:bugs,id',
                'status' => 'required|string',
                'remark' => 'required|string',
                'attachments.*' => 'required|image|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            DB::beginTransaction();
            $bug = Bugs::findOrFail($request->bug_id);
            $bug->reopen_count = ($bug->reopen_count ?? 0) + 1;
            $oldAttachments = $bug->attachment ? json_decode($bug->attachment, true) : [];
            $newAttachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('bug_attachments', 'public');
                    $newAttachments[] = $path;
                }
            }
            $bug->attachment = json_encode(array_merge($oldAttachments, $newAttachments));
            $bug->status = $request->status;
            $bug->save();
            BugLogs::create([
                'bug_id' => $bug->id,
                'user_id' => $bug->user_id,
                'status' => $request->status,
                'comment' => $request->remark,
            ]);
            $staffId = $bug->user_id;
            if ($staffId) {
                webpushnotify(
                    $staffId,
                    'Bug Reopened',
                    'Bug "' . $bug->bug_title . '" has been reopened.'
                );
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bug reopened successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while reopening the bug.',
            ], 500);
        }
    }
    public function update_bug_status(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bug_id' => 'required|exists:bugs,id',
                'status' => 'required|string',
                'remark' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $bug = Bugs::findOrFail($request->bug_id);
            $bug->status = $request->status;
            $bug->save();
            BugLogs::create([
                'bug_id' => $bug->id,
                'status' => $request->status,
                'comment' => $request->remark,
                'user_id' => $bug->user_id,
            ]);
            $staffId = $bug->user_id;
            if ($staffId) {
                webpushnotify(
                    $staffId,
                    'Bug Status Updated',
                    'Bug "' . $bug->bug_title . '" status updated to "' . $request->status . '".'
                );
            }
            return response()->json([
                'success' => true,
                'message' => 'Bug status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating the bug status.',
            ], 500);
        }
    }
    public function edit_bug($id)
    {
        $bug = Bugs::findOrFail($id);
        $modules = Modules::where('project_id', $bug->project_id)->get();
        $users = User::where('role', 'staff')
        ->where('is_active',1)
        ->get();
        return view('Admin.edit_bug', compact('bug', 'modules', 'users'));
    }
    public function update_bug(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'panel'            => 'required|string',
            'bug_type'         => 'required|string',
            'bug_title'        => 'required|string|max:255',
            'module'           => 'required|exists:modules,id',
            'debug_by'         => 'required|exists:users,id',
            'priority'         => 'required|in:Low,Medium,High',
            'testing_scenario' => 'required|string',
            'current_output'   => 'required|string',
            'expected_output'  => 'required|string',
            'attachment'       => 'nullable',
            'suggestion'       => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $bug = Bugs::findOrFail($id);
            $filePaths = json_decode($bug->attachment ?? '[]', true);
            // upload new attachment
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('bug_attachments', 'public');
                $filePaths = [$path]; // replace old
            }
            $debugUser = User::findOrFail($request->debug_by);
            $bug->update([
                'panel' => $request->panel,
                'bug_type' => $request->bug_type,
                'bug_title' => $request->bug_title,
                'attachment' => json_encode($filePaths),
                'module' => $request->module,
                'user_id' => $debugUser->id,
                'debug_by' => $debugUser->name,
                'priority' => $request->priority,
                'testing_scenario' => $request->testing_scenario,
                'current_output' => $request->current_output,
                    'suggestion' => $request->suggestion,
                'expected_output' => $request->expected_output
            ]);
            // Bug log update
            BugLogs::create([
                'bug_id' => $bug->id,
                'user_id' => $debugUser->id,
                'action' => 'Updated',
                'status' => $bug->status,
                'comment' => 'Bug updated by admin'
            ]);
            // Notification
            if ($debugUser->id) {
                webpushnotify(
                    $debugUser->id,
                    'Bug Updated',
                    'Bug "' . $bug->bug_title . '" has been updated.'
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Bug updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Bug update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return back()->with('error', 'Something went wrong');
        }
    }
    public function delete_bug(Request $request)
    {
        try {
            Log::info('Bug delete request received', [
                'bug_id' => $request->id,
                'deleted_by' => auth()->id()
            ]);
            $bug = Bugs::findOrFail($request->id);
            Log::info('Bug found for deletion', [
                'bug_id' => $bug->id,
                'bug_title' => $bug->bug_title
            ]);
            $bug->delete();
            Log::info('Bug deleted successfully', [
                'bug_id' => $request->id
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Bug deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Bug deletion failed', [
                'bug_id' => $request->id ?? null,
                'error_message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error deleting bug'
            ], 500);
        }
    }
}
