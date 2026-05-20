<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\PpsTransactions;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Facades\DataTables;

class PerformanceController extends Controller

{

    public function performance_tracker()

    {

    $users = User::select('id','name')->get();

        return view('Admin.performance_tracker',compact('users'));

    }

   public function transaction_details()

{

    $all_users = User::where('role','staff')
  ->where('is_active','1')
        ->select('id','name')

        ->get();

    return view('Admin.transaction_details',

        compact('all_users'));

}

  public function create_pps_transaction(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id'          => 'required|exists:users,id',

            'transaction_type' => 'required|in:credit,debit',

            'points'           => 'required|numeric|min:1',

            'reason'      => 'nullable|string|max:500',

        ]);

        if ($validator->fails()) {

            return redirect()

                ->back()

                ->withErrors($validator)

                ->withInput();

        }

        DB::beginTransaction();

        try{

            PpsTransactions::create([

                'user_id' =>

                    $request->user_id,

                'transaction_type' =>

                    $request->transaction_type,

                'points' =>

                    $request->points,

                'reason' =>

                    $request->reason,

                'remark' =>

                    'Admin',

            ]);

            DB::commit();

             $message = $request->transaction_type == 'credit'

            ? 'You received '.$request->points.' PPS points.'

            : $request->points.' PPS points debited from your account.';



        $sent = webpushnotify(

            $request->user_id, 

            'PPS Transaction',

            $message

        );



            return back()

                ->with('success',

                'PPS Transaction Added Successfully');

        }

        catch(\Exception $e){

            DB::rollBack();

            return back()

                ->withInput()

                ->with('error',

                'Something went wrong');

        }

    }

    public function transaction_data(Request $request)

{

    if ($request->ajax()) {

        $query = PpsTransactions::with('user')

        ->select('pps_transactions.*')

        ->latest();

        // Staff Filter

        if ($request->staff) {

            $query->where('user_id',$request->staff);

        }

        // Month Filter

        if ($request->month) {

            $query->whereMonth(

                'created_at',

                $request->month

            );

        }

        return DataTables::of($query)

        ->filter(function ($query) use ($request) {

            if ($request->search['value']) {

                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {

                    $q->where('transaction_type','LIKE',"%{$search}%")

                    ->orWhere('points','LIKE',"%{$search}%")

                    ->orWhere('reason','LIKE',"%{$search}%")

                    ->orWhere('remark','LIKE',"%{$search}%")

                    ->orWhereHas('user',function($u) use ($search){

                        $u->where('name','LIKE',"%{$search}%");

                    });

                });

            }

        })

        ->addColumn('datetime',function($row){

            return $row->created_at

            ->format('Y-m-d H:i');

        })

        ->addColumn('staff',function($row){

            return $row->user->name ?? '-';

        })

        ->addColumn('type',function($row){

            if($row->transaction_type=='debit'){

                return '<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Debit</span>';

            }

            return '<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Credit</span>';

        })

        ->addColumn('points',function($row){

            if($row->transaction_type=='debit'){

                return '<span class="fw-bold text-danger">- '.$row->points.'</span>';

            }

            return '<span class="fw-bold text-success">+ '.$row->points.'</span>';

        })

        ->addColumn('description',function($row){

            return $row->reason ?? '-';

        })

        ->addColumn('added_by',function($row){

            return $row->remark ?? 'Admin';

        })

        ->rawColumns(['type','points'])

        ->make(true);

    }

}

public function pps_data(Request $request, $id)

{

    $user = User::findOrFail($id);

    $month = $request->month ?? now()->month;

    $year = $request->year ?? now()->year;

    // -------------------------

    // Monthly Transactions (Selected Month)

    // -------------------------

    $transactions = PpsTransactions::where('user_id',$id)

        ->whereYear('created_at',$year)

        ->whereMonth('created_at',$month)

        ->latest()

        ->get();

    $addedPoints = $transactions

        ->where('transaction_type','credit')

        ->sum('points');

    $reducedPoints = $transactions

        ->where('transaction_type','debit')

        ->sum('points');

    $totalPoints = 50;

    $remainingPoints = $reducedPoints;

    // =============================

    // Last 12 Months Chart (USER ONLY)

    // =============================

    $months = [];

    $credit_points = [];

    $debit_points = [];

    $currentDate = Carbon::now();

    for ($i = 11; $i >= 0; $i--) {

        $monthObj = $currentDate->copy()->subMonths($i);

        $months[] = $monthObj->format('M Y');

        // CREDIT (BLUE)

        $credit = PpsTransactions::where('user_id',$id)

            ->where('transaction_type','credit')

            ->whereYear('created_at',$monthObj->year)

            ->whereMonth('created_at',$monthObj->month)

            ->sum('points');

        // DEBIT (RED)

        $debit = PpsTransactions::where('user_id',$id)

            ->where('transaction_type','debit')

            ->whereYear('created_at',$monthObj->year)

            ->whereMonth('created_at',$monthObj->month)

            ->sum('points');

        $credit_points[] = $credit ?? 0;

        $debit_points[] = $debit ?? 0;

    }

    if ($request->ajax()) {

        return response()->json([

            'transactions'=>$transactions,

            'addedPoints'=>$addedPoints,

            'reducedPoints'=>$reducedPoints,

            'remainingPoints'=>$remainingPoints,

        ]);

    }

    return view('Admin.pps',compact(

        'user',

        'transactions',

        'addedPoints',

        'reducedPoints',

        'remainingPoints',

        'months',

        'credit_points',

        'debit_points',

        'month',

        'year'

    ));

}

}

