<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\UserDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Brevo\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use GuzzleHttp\Client;
use App\Traits\BrevoOtpTrait;

class UserDetailsController extends Controller
{
     use BrevoOtpTrait;
   public function testBrevo()
    {
        return $this->testBrevoMail(
            'novelxansalna@gmail.com'
        );
    }
    public function add_kyc_details(Request $request)
    {
        $staffId = Auth::guard('staff')->id();
        Log::info('🔵 KYC Submission Started', [
            'staff_id' => $staffId,
            'input' => $request->except(['account_number'])
        ]);
        $validator = Validator::make($request->all(), [
            'account_number' => [
                'required',
                'digits_between:9,18',
                'unique:banks,account_number'
            ],
            'holder_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],
            'ifsc_code'     => 'required',
            'bank_name'     => 'required|string|max:150',
            'branch_name'   => 'required|string|max:150',
            'aadhar_number' => 'required|digits:12',
            'pan_number'    => 'nullable|string|max:10',
            'upi' => 'required',
        ]);
        // ❌ VALIDATION FAIL
        if ($validator->fails()) {
            Log::warning('❌ KYC Validation Failed', [
                'staff_id' => $staffId,
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            Log::info('✅ Validation Passed', ['staff_id' => $staffId]);
            // Optional: Check duplicate (one-time setup)
            $exists = UserDetails::where('user_id', $staffId)->exists();
            if ($exists) {
                Log::warning('⚠️ KYC Already Exists', ['staff_id' => $staffId]);
                return redirect()->back()->with('error', 'KYC already submitted');
            }
            $kyc = UserDetails::create([
                'user_id'             => $staffId,
                'account_number'      => $request->account_number,
                'account_holder_name' => $request->holder_name,
                'ifsc_code'           => strtoupper($request->ifsc_code),
                'bank_name'           => $request->bank_name,
                'branch_name'         => $request->branch_name,
                'aadhar_number'       => $request->aadhar_number,
                'pan_number'          => $request->pan_number,
                'upi'          => $request->upi,
                'is_active'           => 1
            ]);
            Log::info('💾 KYC Saved Successfully', [
                'staff_id' => $staffId,
                'kyc_id' => $kyc->id
            ]);
            DB::commit();
            Log::info('🎉 KYC Transaction Committed', ['staff_id' => $staffId]);
            return redirect()->route('bank_details')
                ->with('success', 'KYC details created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('🔥 KYC Save Failed', [
                'staff_id' => $staffId,
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function bank_data()
    {
        $banks = Bank::latest()->get();
        return DataTables::of($banks)
            ->addIndexColumn()
            ->addColumn('account_number', function ($row) {
                return $row->account_number;
            })
            ->addColumn('account_holder_name', function ($row) {
                return $row->account_holder_name;
            })
            ->addColumn('ifsc_code', function ($row) {
                return $row->ifsc_code;
            })
            ->addColumn('bank_name', function ($row) {
                return $row->bank_name;
            })
            ->addColumn('branch_name', function ($row) {
                return $row->branch_name;
            })
            ->addColumn('gst', function ($row) {
                return $row->gst;
            })
            ->addColumn('upi', function ($row) {
                return $row->upi;
            })
            ->addColumn('status', function ($row) {
                return '
        <button class       = "btn btn-sm ' . ($row->is_active ? 'btn-success' : 'btn-danger') . ' changeStatus"
                data-id     = "' . $row->id . '"
                data-status = "' . ($row->is_active ? 0 : 1) . '">
            ' . ($row->is_active ? 'Active' : 'Inactive') . '
        </button>
    ';
            })
            ->addColumn('action', function ($row) {
                return '
        <a href  = "' . route('edit_bank', $row->id) . '" class = "btn btn-sm btn-primary me-1">
        <i class = "fas fa-edit"></i>
        </a>
        <button class          = "btn btn-sm btn-danger deleteBtn"
                data-id        = "' . $row->id . '"
                data-bs-toggle = "modal"
                data-bs-target = "#delete">
        <i      class          = "fas fa-trash"></i>
        </button>
    ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
    public function delete_bank(Request $request)
    {
        try {
            Log::info('Project delete request received', [
                'project_id' => $request->id,
                'requested_by' => auth()->id()
            ]);
            $project = Bank::find($request->id);
            if (!$project) {
                Log::warning('Project not found while deleting', [
                    'project_id' => $request->id
                ]);
                return redirect()->back()
                    ->with('error', 'Project not found');
            }
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
}
