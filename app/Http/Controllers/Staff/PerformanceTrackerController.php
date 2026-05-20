<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

use App\Models\PpsTransactions;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class PerformanceTrackerController extends Controller

{

public function performance_tracker()

{

    $userId = Auth::guard('staff')->id();

    $month = request()->month ?? now()->month;

    $year = request()->year ?? now()->year;

    $transactions = PpsTransactions::where('user_id', $userId)

        ->whereYear('created_at', $year)

        ->whereMonth('created_at', $month)

        ->latest()

        ->get();

    $addedPoints = $transactions->where('transaction_type', 'credit')->sum('points');

    $reducedPoints = $transactions->where('transaction_type', 'debit')->sum('points');

    $totalPoints = 50; 

    $remainingPoints = $reducedPoints;

    $earnedMonthly = [];

    $reducedMonthly = [];

    for ($i = 1; $i <= 12; $i++) {

        $earnedMonthly[] = PpsTransactions::where('user_id', $userId)

            ->where('transaction_type', 'credit')

            ->whereYear('created_at', $year)

            ->whereMonth('created_at', $i)

            ->sum('points');

        $reducedMonthly[] = PpsTransactions::where('user_id', $userId)

            ->where('transaction_type', 'debit')

            ->whereYear('created_at', $year)

            ->whereMonth('created_at', $i)

            ->sum('points');

    }

    $currentDate = Carbon::now();

    $months = [];

    $credit_points = [];

    $debit_points = [];

    for ($i = 11; $i >= 0; $i--) {

        $monthObj = $currentDate->copy()->subMonths($i);

        $months[] = $monthObj->format('M Y');

        $credit_points[] = PpsTransactions::where('transaction_type', 'credit')

            ->whereYear('created_at', $monthObj->year)

            ->whereMonth('created_at', $monthObj->month)

            ->sum('points');

        $debit_points[] = PpsTransactions::where('transaction_type', 'debit')

            ->whereYear('created_at', $monthObj->year)

            ->whereMonth('created_at', $monthObj->month)

            ->sum('points');

    }

    if (request()->ajax()) {

        return response()->json([

            'transactions' => $transactions,

            'addedPoints' => $addedPoints,

            'reducedPoints' => $reducedPoints,

            'remainingPoints' => $remainingPoints,

            'earnedMonthly' => $earnedMonthly,

            'reducedMonthly' => $reducedMonthly,

        ]);

    }

    return view('Staff.performance_tracker', compact(

        'transactions',

        'addedPoints',

        'reducedPoints',

        'remainingPoints',

        'earnedMonthly',

        'reducedMonthly',

        'months',

        'credit_points',

        'debit_points',

        'month',

        'year'

    ));

}

public function performance_tracker_data(Request $request)

{

    $request->merge(['month' => $request->month ?? now()->month, 'year' => $request->year ?? now()->year]);

    return $this->performance_tracker(); 

}

}

