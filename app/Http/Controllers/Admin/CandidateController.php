<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\CallStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
class CandidateController extends Controller
{

    public function add_candidate(Request $request)
    {
        DB::beginTransaction();

        try {

            // Validation
            $validated = $request->validate([
                'category'                 => 'required|string',
                'candidate_name'           => 'required|string|max:255',
                'technology'               => 'required|string|max:255',
                'work_status'              => 'required|string',
                'experience'               => 'nullable|integer',

                'resume'                   => 'required|file|mimes:pdf,doc,docx|max:2048',
                'notice_period'            => 'nullable|integer',
                'current_salary'           => 'nullable|numeric',
                'expected_salary'          => 'nullable|numeric',

                'phone_number'             => 'required|digits:10',
                'alternate_phone_number'  => 'nullable|digits:10',
                'email'                    => 'required|email|unique:candidates,email',

                'state'                    => 'required|string',
                'city'                     => 'required|string',

                'ready_to_reallocate'      => 'required|in:Yes,No',
                'team_management'          => 'required|in:Yes,No',
                'client_management'        => 'required|in:Yes,No',
            ]);

            // Resume Upload
            $resumePath = null;

            if ($request->hasFile('resume')) {

                $file = $request->file('resume');

                $fileName = time() . '_' . $file->getClientOriginalName();

                // storage/app/public/resume
                $resumePath = $file->storeAs('resume', $fileName, 'public');
            }

            // Insert Candidate
            Candidate::create([
                'category'                 => $request->category,
                'candidate_name'           => $request->candidate_name,
                'technology'               => $request->technology,
                'work_status'              => $request->work_status,
                'experience'               => $request->experience,

                'resume'                   => $resumePath,
                'notice_period'            => $request->notice_period,
                'current_salary'           => $request->current_salary,
                'expected_salary'          => $request->expected_salary,

                'phone_number'             => $request->phone_number,
                'alternate_phone_number'   => $request->alternate_phone_number,
                'email'                    => $request->email,

                'state'                    => $request->state,
                'city'                     => $request->city,

                'ready_to_reallocate'      => $request->ready_to_reallocate,
                'team_management'          => $request->team_management,
                'client_management'        => $request->client_management,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Candidate added successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {

            DB::rollBack();

            Log::error('Candidate Validation Error', [
                'errors' => $e->errors(),
                'input'  => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Add Candidate Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'input'   => $request->all()
            ]);

            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function candidate_list(Request $request)
    {
        if ($request->ajax()) {

            $data = Candidate::latest()->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y');
                })

                ->addColumn('count', function ($row) {
                    return 1; // dummy count
                })

                ->addColumn('status', function ($row) {

                    return '
                        <div class="d-flex justify-content-between">

                            <a href="#"
                                class="btn btn-label-info openCallModal"
                                data-id="'.$row->id.'"
                                data-bs-toggle="modal"
                                data-bs-target="#callModal">

                                <b>Last call Status</b>
                            </a>
                            <a href="' . route('view_status', $row->id) . '" class="text-underline">
                                <i class="ti tabler-eye"></i>
                            </a>
                        </div>
                    ';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <div class="d-flex justify-content-between">
                            <a href="' . route('edit_candidates', $row->id) . '"
                                class="text-underline">
                                <b>View</b>
                            </a>
                        </div>
                    ';
                })

                ->rawColumns(['status', 'action'])

                ->make(true);
        }

        return view('your_blade_file_name');
    }

    public function update_call_status(Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'candidate_id' => 'required|exists:candidates,id',
                'call_status' => 'required',
                'remarks' => 'required'
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            CallStatus::create([
                'candidate_id' => $request->candidate_id,
                'call_status' => $request->call_status,
                'remarks' => $request->remarks
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Call status added successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Update Call Status Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }


    public function view_status_data(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = CallStatus::with('candidate')
                        ->where('candidate_id', $id)
                        ->latest()
                        ->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->editColumn('created_at', function ($row) {

                    return $row->created_at->format('d/m/Y');
                })

                ->addColumn('category', function ($row) {

                    return $row->call_status ?? '-';
                })

                ->addColumn('candidate_name', function ($row) {

                    return $row->candidate->candidate_name ?? '-';
                })

                ->addColumn('phone_number', function ($row) {

                    return $row->candidate->phone_number ?? '-';
                })

                ->make(true);
        }
    }


public function update_candidate(Request $request)
{
    DB::beginTransaction();

    try {

        $validated = $request->validate([

            'candidate_id'             => 'required|exists:candidates,id',

            'category'                 => 'required|string',
            'candidate_name'           => 'required|string|max:255',
            'technology'               => 'required|string|max:255',
            'work_status'              => 'required|string',
            'experience'               => 'nullable|integer',

            'resume'                   => 'required|file|mimes:pdf,doc,docx|max:2048',
            'notice_period'            => 'nullable|integer',
            'current_salary'           => 'nullable|numeric',
            'expected_salary'          => 'nullable|numeric',

            'phone_number'             => 'required|digits:10',
            'alternate_phone_number'  => 'nullable|digits:10',

            'email' => 'required|email|unique:candidates,email,' . $request->candidate_id,

            'state'                    => 'required|string',
            'city'                     => 'required|string',

            // 'ready_to_reallocate'      => 'required|in:Yes,No',
            // 'team_management'          => 'required|in:Yes,No',
            // 'client_management'        => 'required|in:Yes,No',
        ]);

        $candidate = Candidate::findOrFail($request->candidate_id);

        // Resume Upload
        if ($request->hasFile('resume')) {

            $file = $request->file('resume');

            $fileName = time() . '_' . $file->getClientOriginalName();

            $resumePath = $file->storeAs('resume', $fileName, 'public');

            $candidate->resume = $resumePath;
        }

        $candidate->category = $request->category;
        $candidate->candidate_name = $request->candidate_name;
        $candidate->technology = $request->technology;
        $candidate->work_status = $request->work_status;
        $candidate->experience = $request->experience;

        $candidate->notice_period = $request->notice_period;
        $candidate->current_salary = $request->current_salary;
        $candidate->expected_salary = $request->expected_salary;

        $candidate->phone_number = $request->phone_number;
        $candidate->alternate_phone_number = $request->alternate_phone_number;
        $candidate->email = $request->email;

        $candidate->state = $request->state;
        $candidate->city = $request->city;

        // $candidate->ready_to_reallocate = $request->ready_to_reallocate;
        // $candidate->team_management = $request->team_management;
        // $candidate->client_management = $request->client_management;

        $candidate->save();

        DB::commit();

        return redirect()->back()
            ->with('success', 'Candidate updated successfully');

    } catch (\Illuminate\Validation\ValidationException $e) {

        DB::rollBack();

        Log::error('Candidate Update Validation Error', [

            'errors' => $e->errors(),
            'input'  => $request->all()

        ]);

        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Update Candidate Error', [

            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
            'input'   => $request->all()

        ]);

        return redirect()->back()
            ->with('error', 'Something went wrong')
            ->withInput();
    }
}
}

