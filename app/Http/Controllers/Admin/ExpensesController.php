<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Expenses;
use App\Models\Project;

class ExpensesController extends Controller
{
    public function common_expenses()
    {
        return view('Admin.common_expenses');
    }

    public function project_expenses()
    {
        $projects = Project::latest()->get();
        return view('Admin.project_expenses',compact('projects'));
    }



    public function add_common_expenses(Request $request)
    {
        $validated = $request->validate([
            'month'         => 'required',
            'expense_type'  => 'required|string|max:255',
            'amount'        => 'required|numeric',
            'proof'         => 'required|file|max:2048',
            'status'        => 'required|in:paid,not_paid',
            'remarks'       => 'required|string'
        ]);

        DB::beginTransaction();

        try {

            $proofPath = null;

            if ($request->hasFile('proof')) {

                $proofPath = $request->file('proof')
                    ->store('expenses', 'public');
            }

            Expenses::create([
                'type'          => 'common_exp',
                'month'         => $request->month,
                'expense_type'  => $request->expense_type,
                'amount'        => $request->amount,
                'proof'         => $proofPath,
                'status'        => $request->status,
                'remarks'       => $request->remarks,
            ]);

            DB::commit();

            return redirect()->route('admin.common_expenses')
                ->with('success', 'Expense added successfully');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Common Expense Add Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return redirect()->back()
                ->with('error', 'Something went wrong')
                ->withInput();
        }
    }



    public function common_expenses_data(Request $request)
    {
        if ($request->ajax()) {

            $data = Expenses::where('type', 'common_exp')
                ->latest()
                ->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })

                ->editColumn('amount', function ($row) {
                    return '₹' . number_format($row->amount, 2);
                })

                ->addColumn('proof', function ($row) {

                    if ($row->proof) {

                        $url = asset('storage/' . $row->proof);

                        return '
                            <a href="' . $url . '" 
                            target="_blank"
                            class="text-decoration-underline">
                                View
                            </a>
                        ';
                    }

                    return '-';
                })

                ->editColumn('status', function ($row) {

                    if ($row->status == 'paid') {

                        return '
                            <span class="btn bg-success-subtle text-success">
                                Paid
                            </span>
                        ';
                    }

                    return '
                        <span class="btn bg-danger-subtle text-danger">
                            Not Paid
                        </span>
                    ';
                })

                ->rawColumns(['proof', 'status'])

                ->make(true);
        }
    }



    public function add_project_expenses(Request $request)
    {
        $validated = $request->validate([
            'month'         => 'required',
            'project' => 'required|exists:projects,id',
            'amount'        => 'required|numeric',
            'proof'         => 'required|file|max:2048',
            'status'        => 'required|in:paid,not_paid',
            'remarks'       => 'required|string'
        ]);

        DB::beginTransaction();

        try {

            $proofPath = null;

            if ($request->hasFile('proof')) {

                $proofPath = $request->file('proof')
                    ->store('expenses', 'public');
            }

            Expenses::create([
                'type'          => 'project_exp',
                'month'         => $request->month,
                'project'  => $request->project,
                'amount'        => $request->amount,
                'proof'         => $proofPath,
                'status'        => $request->status,
                'remarks'       => $request->remarks,
            ]);

            DB::commit();

            return redirect()->route('admin.project_expenses')
                ->with('success', 'Expense added successfully');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Common Expense Add Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return redirect()->back()
                ->with('error', 'Something went wrong')
                ->withInput();
        }
    }



    public function project_expenses_data(Request $request)
    {
        if ($request->ajax()) {

            $data = Expenses::with('projectDetails')
                ->where('type', 'project_exp')
                ->latest()
                ->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })

                ->editColumn('month', function ($row) {
                    return $row->month ?? '-';
                })

                ->addColumn('project_name', function ($row) {

                    return $row->projectDetails->project_name ?? '-';
                })

                ->editColumn('amount', function ($row) {
                    return '₹' . number_format($row->amount, 2);
                })

                ->addColumn('proof', function ($row) {

                    if ($row->proof) {

                        $url = asset('storage/' . $row->proof);

                        return '
                            <a href="' . $url . '" 
                            target="_blank"
                            class="text-decoration-underline">
                                View
                            </a>
                        ';
                    }

                    return '-';
                })

                ->editColumn('status', function ($row) {

                    if ($row->status == 'paid') {

                        return '
                            <span class="btn bg-success-subtle text-success">
                                Paid
                            </span>
                        ';
                    }

                    return '
                        <span class="btn bg-danger-subtle text-danger">
                            Not Paid
                        </span>
                    ';
                })

                ->rawColumns(['proof', 'status'])

                ->make(true);
        }
    }
}