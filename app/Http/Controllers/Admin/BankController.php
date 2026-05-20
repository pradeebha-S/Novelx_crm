<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentHistory;
use App\Models\Project;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
class BankController extends Controller
{
    public function staff_bank_details()
    {
        return view('Admin.staff_bank_details');
    }
    public function staff_bank_details_data()
    {
        $banks = UserDetails::latest()->get();
        return DataTables::of($banks)
            ->addIndexColumn()
            ->addColumn('account_number', function ($row) {
                return $row->account_number;
            })
            ->addColumn('holder_name', function ($row) {
                return $row->holder_name;
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
            ->addColumn('aadhar_number', function ($row) {
                return $row->aadhar_number;
            })
            ->addColumn('pan_number', function ($row) {
                return $row->pan_number;
            })
            ->addColumn('action', function ($row) {
                return '
        <a href  = "' . route('edit_staff_bank_details', $row->id) . '" class = "btn btn-sm btn-primary me-1">
        <i class = "fas fa-edit"></i>
        </a>
    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function edit_staff_bank_details($id)
{
    // Try to get existing bank details
    $bank = UserDetails::where('user_id', $id)->first();

    // If not exists, create empty object
    if (!$bank) {
        $bank = new UserDetails();
        $bank->id = '';
        $bank->user_id = $id;
        $bank->account_number = '';
        $bank->account_holder_name = '';
        $bank->ifsc_code = '';
        $bank->bank_name = '';
        $bank->branch_name = '';
        $bank->aadhar_number = '';
        $bank->pan_number = '';
        $bank->upi = '';
    }

    return view('Admin.edit_staff_bank_details', compact('bank'));
}
    // public function edit_staff_bank_details($id)
    // {
    //     $bank = UserDetails::findOrFail($id);
    //     return view('Admin.edit_staff_bank_details', compact('bank'));
    // }
    public function update_bank_details(Request $request)
{
    Log::info("Updating bank details");
    Log::info("Request Data: ", $request->all());

    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id', // ✅ important
        'account_number' => 'required|digits_between:9,18',
        'account_holder_name' => [
            'required',
            'string',
            'max:100',
            'regex:/^[A-Za-z\s]+$/'
        ],
        'ifsc_code' => 'required',
        'bank_name' => 'required|string|max:150',
        'branch_name' => 'required|string|max:150',
        'aadhar_number' => 'required|digits:12',
        'upi' => 'required',
        'pan_number' => 'nullable|string|max:10',
    ]);

    if ($validator->fails()) {
        Log::warning('Bank validation failed', [
            'errors' => $validator->errors()->toArray()
        ]);

        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        DB::beginTransaction();

        // ✅ Create or Update based on user_id
        UserDetails::updateOrCreate(
            ['user_id' => $request->user_id], // condition
            [
                'account_number' => $request->account_number,
                'account_holder_name' => $request->account_holder_name,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'aadhar_number' => $request->aadhar_number,
                'pan_number' => $request->pan_number,
                'upi' => $request->upi,
                'is_active' => 1
            ]
        );

        DB::commit();

        Log::info('Bank updated successfully');

        return redirect()->route('staff_table')
            ->with('success', 'Bank details updated successfully!');

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('Bank update failed', [
            'message' => $e->getMessage()
        ]);

        return redirect()->back()
            ->with('error', 'Something went wrong! Please try again.')
            ->withInput();
    }
}
    // public function update_bank_details(Request $request)
    // {
    //     $id = $request->id;
    //     Log::info("Updating project with ID: $id");
    //     Log::info("Request Data: ", $request->all());
    //     $validator = Validator::make($request->all(), [
    //         'account_number' => [
    //             'required',
    //             'digits_between:9,18',
    //         ],
    //         'account_holder_name' => [
    //             'required',
    //             'string',
    //             'max:100',
    //             'regex:/^[A-Za-z\s]+$/'
    //         ],
    //         'ifsc_code' => [
    //             'required',
    //         ],
    //         'bank_name' => [
    //             'required',
    //             'string',
    //             'max:150'
    //         ],
    //         'branch_name' => [
    //             'required',
    //             'string',
    //             'max:150'
    //         ],
    //         'aadhar_number' => 'required|digits:12',
    //         'upi' => 'required',
    //         'pan_number'    => 'nullable|string|max:10',
    //     ]);
    //     if ($validator->fails()) {
    //         Log::warning('bank validation failed', [
    //             'errors' => $validator->errors()->toArray()
    //         ]);
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     try {
    //         DB::beginTransaction();
    //         $project = UserDetails::findOrFail($id);
    //         $project->update([
    //             'account_number' => $request->account_number,
    //             'account_holder_name' => $request->account_holder_name,
    //             'ifsc_code' => strtoupper($request->ifsc_code),
    //             'bank_name' => $request->bank_name,
    //             'branch_name' => $request->branch_name,
    //             'aadhar_number' => $request->aadhar_number,
    //             'pan_number' => $request->pan_number,
    //             'upi' => $request->upi,
    //             'is_active' => 1
    //         ]);
    //         DB::commit();
    //         Log::info('bank updated successfully');
    //         return redirect()->route('staff_bank_details')->with('success', 'bank updated successfully!');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::error('bank update failed', ['message' => $e->getMessage()]);
    //         return redirect()->back()
    //             ->with('error', 'Something went wrong! Please try again.')
    //             ->withInput();
    //     }
    // }
    public function update_staff_bank_status(Request $request)
    {
        try {
            $bank = UserDetails::find($request->id);
            if (!$bank) {
                return response()->json(['status' => false, 'message' => 'Bank not found']);
            }
            $bank->is_active = $request->status;
            $bank->save();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    }
    public function store_bank(Request $request)
    {
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
            'ifsc_code' => [
                'required',
            ],
            'bank_name' => [
                'required',
                'string',
                'max:150'
            ],
            'branch_name' => [
                'required',
                'string',
                'max:150'
            ],
            'gst' => [
                'required',
                'in:Yes,No'
            ],
            'upi' => [
                'required',
                'string',
                'max:150'
            ],
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
            Bank::create([
                'account_number' => $request->account_number,
                'holder_name' => $request->holder_name,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'gst' => $request->gst,
                'upi' => $request->upi,
                'is_active' => 1
            ]);
            DB::commit();
            Log::info('bank created successfully');
            return redirect()->route('add_bank')->with('success', 'Project created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Project creation failed', ['message' => $e->getMessage()]);
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
            ->addColumn('holder_name', function ($row) {
                return $row->holder_name;
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
    public function edit_bank($id)
    {
        $bank = Bank::findOrFail($id);
        return view('Admin.edit_bank', compact('bank'));
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
    public function update_bank(Request $request)
    {
        $id = $request->id;
        Log::info("Updating project with ID: $id");
        Log::info("Request Data: ", $request->all());
        $validator = Validator::make($request->all(), [
            'account_number' => [
                'required',
                'digits_between:9,18',
            ],
            'holder_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],
            'ifsc_code' => [
                'required',
            ],
            'bank_name' => [
                'required',
                'string',
                'max:150'
            ],
            'branch_name' => [
                'required',
                'string',
                'max:150'
            ],
            'gst' => [
                'required',
                'in:Yes,No'
            ],
            'upi' => [
                'required',
                'string',
                'max:150'
            ],
        ]);
        if ($validator->fails()) {
            Log::warning('bank validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            DB::beginTransaction();
            $project = Bank::findOrFail($id);
            $project->update([
                'account_number' => $request->account_number,
                'holder_name' => $request->holder_name,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'gst' => $request->gst,
                'upi' => $request->upi,
                'is_active' => 1
            ]);
            DB::commit();
            Log::info('bank updated successfully');
            return redirect()->route('add_bank')->with('success', 'bank updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('bank update failed', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
    public function update_bank_status(Request $request)
    {
        try {
            $bank = Bank::find($request->id);
            if (!$bank) {
                return response()->json(['status' => false, 'message' => 'Bank not found']);
            }
            $bank->is_active = $request->status;
            $bank->save();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    }
    public function bill_form()
    {
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_no, 3);
            $nextNumber = 'NXB' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = 'NXB001';
        }
        $projects = Project::all();
        $banks = Bank::all();
        return view('Admin.bill_form', compact('nextNumber', 'projects', 'banks'));
    }
    public function getProject($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        return response()->json([
            'client_name' => $project->client_name,
            'mobile' => $project->client_mobile,
            'email' => $project->client_email
        ]);
    }
  

    public function bill_table(Request $request)
    {
        $project_id = $request->project_id;
        return view('Admin.bill_table', compact('project_id'));
    }
    public function invoiceData(Request $request)
    {
     $data = Invoice::with('project')
    ->where('status', 'pending')
    ->latest()
    ->get();

        if ($request->project_id) {
            $data->where('project_id', $request->project_id);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('invoice_date', function ($row) {
                return \Carbon\Carbon::parse($row->invoice_date)->format('d/m/Y');
            })
            ->addColumn('project', function ($row) {
                return $row->project->project_name ?? '-';
            })
            ->editColumn('total', function ($row) {
                return '₹ ' . $row->total;
            })
            ->addColumn('paid_amount', function ($row) {
                return '₹ ' . $row->payments->sum('amount');
            })
            ->addColumn('status', function ($row) {
                $paid = $row->payments->sum('amount');
                if ($paid == 0) {
                    return '<span class="badge bg-label-warning w-100">Pending</span>';
                } elseif ($paid < $row->total) {
                    return '<span class="badge bg-label-warning w-100">Partial</span>';
                } else {
                    return '<span class="badge bg-label-success w-100">Completed</span>';
                }
            })
            ->addColumn('mobile', function ($row) {
                return $row->mobile;
            })
            ->addColumn('update_payment', function ($row) {
                return '
        <button class="btn btn-primary btn-sm"
            onclick="openPaymentModal(' . $row->id . ', ' . $row->total . ', ' . $row->paid_amount . ')">
           Update
        </button>
    ';
            })
            ->addColumn('payment_history', function ($row) {
                return '
        <a href="' . route('payment_report', $row->id) . '" class="btn btn-info btn-sm">
            Report
        </a>
    ';
            })
            ->addColumn('view_bill', function ($row) {
                return '
                <a href="' . route('view_bill', $row->id) . '" class="btn btn-label-info">
                    Bill
                </a>
            ';
            })
            ->addColumn('view_invoice', function ($row) {
                return '
                <a href="' . route('invoice', $row->id) . '" target="_blank">
                    <i class="ti tabler-eye text-primary fs-5"></i>
                </a>
   <a href="javascript:void(0);" onclick="downloadInvoice(' . $row->id . ')">
            <i class="ti tabler-download text-primary fs-5"></i>
        </a>
            ';
            })
            ->rawColumns([
                'status',
                'update_payment',
                'payment_history',
                'view_bill',
                'view_invoice'
            ])
            ->make(true);
    }
    public function view_bill($id)
    {
        $invoice = Invoice::with(['items', 'project', 'payments', 'bank'])
            ->findOrFail($id);
        $projects = Project::all();
        $banks = Bank::all();
        return view('Admin.view_bill', compact('invoice', 'projects', 'banks'));
    }
    public function invoice($id)
    {
        $invoice = Invoice::with(['items', 'project', 'payments', 'bank'])
            ->findOrFail($id);
        return view('Admin.invoice', compact('invoice'));
    }
     public function invoice_pdf()
    {
         $invoice = Invoice::with(['items', 'project', 'payments', 'bank']);
        return view('Admin.invoice_pdf',compact('invoice'));
    }
    public function downloadInvoice($id)
{
    $invoice = Invoice::with(['items', 'project', 'payments', 'bank'])
        ->findOrFail($id);
$pdf = Pdf::loadView('Admin.invoice_pdf', compact('invoice'))
          ->setPaper('a4', 'portrait');
return $pdf->download('invoice_'.$id.'.pdf');
}
    public function getBalance($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json([
            'total' => $invoice->total,
            'paid' => $invoice->paid_amount,
            'balance' => $invoice->total - $invoice->paid_amount,
        ]);
    }
    public function payment_store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'status' => 'required',
            'receipt' => 'required|file',
        ]);
        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($request->invoice_id);
            // upload receipt
            $fileName = null;
            if ($request->hasFile('receipt')) {
                $fileName = time() . '_' . $request->file('receipt')->getClientOriginalName();
                $request->file('receipt')->move(public_path('receipts'), $fileName);
            }
            // 1. insert payment history
            PaymentHistory::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'receipt' => $fileName,
                'status' => $request->status,
            ]);
            // 2. update invoice paid amount
            $invoice->paid_amount = $invoice->paid_amount + $request->amount;
              if ($invoice->paid_amount == 0) {
            $invoice->status = 'pending';
        } elseif ($invoice->paid_amount < $invoice->total) {
            $invoice->status = 'partial';
        } else {
            $invoice->status = 'completed'; // 🔥 FULL PAID
        }
            $invoice->save();
            DB::commit();
            return redirect()->back()->with('success', 'Payment added successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function payment_report($id)
    {
        $payments = PaymentHistory::with('invoice')
            ->where('invoice_id', $id)
            ->latest()
            ->get();
        return view('Admin.payment_report', compact('id'));
    }
    public function payment_report_data(Request $request, $id)
    {
        $query = PaymentHistory::with('invoice')
            ->where('invoice_id', $id)
            ->latest();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
            })
            ->addColumn('invoice_no', function ($row) {
                return optional($row->invoice)->invoice_no ?? '-';
            })
            // 🔥 IMPORTANT: SEARCH FIX
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $keyword = $request->search['value'];
                    $query->where(function ($q) use ($keyword) {
                        $q->whereHas('invoice', function ($sub) use ($keyword) {
                            $sub->where('invoice_no', 'like', "%{$keyword}%");
                        })
                            ->orWhere('amount', 'like', "%{$keyword}%")
                            ->orWhere('status', 'like', "%{$keyword}%");
                    });
                }
            })
            ->addColumn('amount', function ($row) {
                return '₹ ' . ($row->amount ?? 0);
            })
            ->addColumn('receipt', function ($row) {
                if (!$row->receipt) return '-';
                return '
                <button class="btn btn-sm btn-primary viewReceiptBtn"
                    data-img="' . asset('receipts/' . $row->receipt) . '">
                    View
                </button>
            ';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'Pending') {
                    return '<span class="badge bg-danger">Pending</span>';
                } elseif ($row->status == 'Completed') {
                    return '<span class="badge bg-success">Completed</span>';
                }
                return '-';
            })
            ->rawColumns(['receipt', 'status'])
            ->make(true);
    }
    public function create_invoice(Request $request)
    {
        Log::info('Invoice creation started', $request->all());
        $validator = Validator::make($request->all(), [
            'invoice_no'    => 'required|unique:invoices,invoice_no',
            'invoice_date'  => 'required|date',
            'project_id'    => 'required|exists:projects,id',
            'client_name'   => 'required|string|max:100',
            'mobile'        => 'required',
            'address'       => 'required|string|max:255',
            'module'        => 'required|array|min:1',
            'module.*'      => 'required|string',
            'description.*' => 'required|string',
            'type.*'        => 'required|string',
            'rate'          => 'required|array|min:1',
            'rate.*'        => 'required|numeric|min:0',
            'tax'           => 'required|numeric|min:0',
            'discount'      => 'required|numeric|min:0',
            'bank_id'       => 'required|exists:banks,id',
            'remarks'       => 'required',
        ]);
        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }
        Log::info('Validation passed');
        DB::beginTransaction();
        Log::info('DB transaction started');
        try {
            $modules = $request->module ?? [];
            $descriptions = $request->description ?? [];
            $types = $request->type ?? [];
            $rates = $request->rate ?? [];
            Log::info('Input arrays fetched', [
                'modules' => $modules,
                'rates' => $rates
            ]);
            $subtotal = 0;
            $validItems = [];
            foreach ($modules as $key => $module) {
                $rate = $rates[$key] ?? 0;
                Log::info("Processing item index {$key}", [
                    'module' => $module,
                    'rate'   => $rate
                ]);
                if (empty($module) && empty($rate)) {
                    Log::info("Skipping empty row at index {$key}");
                    continue;
                }
                $validItems[] = [
                    'module' => $module,
                    'description' => $descriptions[$key] ?? null,
                    'type' => $types[$key] ?? null,
                    'rate' => $rate,
                ];
                $subtotal += $rate;
            }
            Log::info('Items processed', [
                'valid_items' => $validItems,
                'subtotal' => $subtotal
            ]);
            if (count($validItems) == 0) {
                Log::warning('No valid invoice items found');
                return back()->with('error', 'Please add at least one item')->withInput();
            }
            $taxPercent      = $request->tax ?? 0;
            $discountPercent = $request->discount ?? 0;
            Log::info('Tax & Discount values', [
                'tax_percent' => $taxPercent,
                'discount_percent' => $discountPercent
            ]);
            $taxAmount      = ($subtotal * $taxPercent) / 100;
            $discountAmount = ($subtotal * $discountPercent) / 100;
            $total          = $subtotal + $taxAmount - $discountAmount;
            Log::info('Final calculation done', [
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total' => $total
            ]);
            $invoice = Invoice::create([
                'invoice_no'          => $request->invoice_no,
                'invoice_date'        => $request->invoice_date,
                'project_id'          => $request->project_id,
                'client_name'         => $request->client_name,
                'mobile'              => $request->mobile,
                'address'             => $request->address,
                'subtotal'            => $subtotal,
                'tax_percentage'      => $taxPercent,
                'tax'                 => $taxAmount,
                'discount_percentage' => $discountPercent,
                'discount'            => $discountAmount,
                'total'               => $total,
                'remarks'             => $request->remarks,
                'bank_id'             => $request->bank_id,
            ]);
            Log::info('Invoice created', ['invoice_id' => $invoice->id]);
            foreach ($validItems as $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'module'      => $item['module'],
                    'description' => $item['description'],
                    'type'        => $item['type'],
                    'rate'        => $item['rate'],
                ]);
                Log::info('Invoice item inserted', $item);
            }
            DB::commit();
            Log::info('DB committed successfully');
            return redirect()->route('bill_table')
                ->with('success', 'Invoice Created Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Invoice creation failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function invoice_update(Request $request, $id)
    {
        Log::info('🔵 Invoice Update Started', [
            'invoice_id' => $id,
            'request' => $request->all()
        ]);
        $validator = Validator::make($request->all(), [
            'invoice_no'    => "required|unique:invoices,invoice_no,$id",
            'invoice_date'  => 'required|date',
            'project_id'    => 'required|exists:projects,id',
            'client_name'   => 'required|string|max:100',
            'mobile'        => 'required',
            'email'         => 'required|string|max:255',
            'module'        => 'required|array|min:1',
            'module.*'      => 'required|string',
            'description.*' => 'required|string',
            'type.*'        => 'required|string',
            'rate.*'        => 'required|numeric|min:0',
            'tax'           => 'required|numeric|min:0',
            'discount'      => 'required|numeric|min:0',
            'bank_id'       => 'required|exists:banks,id',
        ]);
        if ($validator->fails()) {
            Log::error('❌ Validation Failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }
        Log::info('✅ Validation Passed');
        DB::beginTransaction();
        Log::info('🟡 DB Transaction Started');
        try {
            $invoice = Invoice::findOrFail($id);
            Log::info('📄 Invoice Found', ['invoice' => $invoice]);
            $modules      = $request->module;
            $descriptions = $request->description;
            $types        = $request->type;
            $rates        = $request->rate;
            Log::info('📦 Input Arrays Loaded', [
                'modules' => $modules,
                'rates' => $rates
            ]);
            $subtotal = 0;
            $items = [];
            foreach ($modules as $key => $module) {
                $rate = $rates[$key] ?? 0;
                Log::info('🔁 Processing Item', [
                    'index' => $key,
                    'module' => $module,
                    'rate' => $rate
                ]);
                if (empty($module) && empty($rate)) {
                    Log::info('⏭️ Skipping Empty Row', ['index' => $key]);
                    continue;
                }
                $items[] = [
                    'module'      => $module,
                    'description' => $descriptions[$key],
                    'type'        => $types[$key],
                    'rate'        => $rate,
                ];
                $subtotal += $rate;
                Log::info('➕ Subtotal Updated', ['subtotal' => $subtotal]);
            }
            Log::info('💰 Final Subtotal Calculated', ['subtotal' => $subtotal]);
            $taxPercent      = $request->tax;        // % value
            $discountPercent = $request->discount;   // % value
            $taxAmount      = ($subtotal * $taxPercent) / 100;
            $discountAmount = ($subtotal * $discountPercent) / 100;
            $total = $subtotal + $taxAmount - $discountAmount;
            $invoice->update([
                'invoice_no'   => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'project_id'   => $request->project_id,
                'client_name'  => $request->client_name,
                'mobile'       => $request->mobile,
                'address'      => $request->email,
                // ✅ amounts
                'subtotal' => $subtotal,
                'tax'      => $taxAmount,
                'discount' => $discountAmount,
                'total'    => $total,
                // ✅ percentages (THIS IS WHAT YOU MISSED)
                'tax_percentage'      => $taxPercent,
                'discount_percentage' => $discountPercent,
                'remarks' => $request->remarks,
                'bank_id' => $request->bank_id,
            ]);
            Log::info('📝 Invoice Updated Successfully');
            InvoiceItem::where('invoice_id', $invoice->id)->delete();
            Log::info('🗑️ Old Invoice Items Deleted');
            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'module' => $item['module'],
                    'description' => $item['description'],
                    'type' => $item['type'],
                    'rate' => $item['rate'],
                ]);
                Log::info('📌 Item Inserted', $item);
            }
            DB::commit();
            Log::info('🟢 Transaction Committed Successfully');
            return redirect()->route('bill_table')
                ->with('success', 'Invoice Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('🔥 Invoice Update Failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function completed_invoice()
    {
        return view('Admin.completed_invoice');
    }
   public function completed_invoice_data(Request $request)
{
    $data = Invoice::with(['project', 'payments'])
        ->whereHas('payments', function ($q) {
            $q->where('status', 'completed'); // ✅ only completed payments
        })
         ->latest();
    if ($request->project_id) {
        $data->where('project_id', $request->project_id);
    }
    return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('invoice_date', function ($row) {
            return \Carbon\Carbon::parse($row->invoice_date)->format('d/m/Y');
        })
        ->addColumn('project', function ($row) {
            return $row->project->project_name ?? '-';
        })
        ->editColumn('total', function ($row) {
            return '₹ ' . $row->total;
        })
        ->addColumn('paid_amount', function ($row) {
            return '₹ ' . $row->payments
                ->where('status', 'completed') // ✅ only completed payments
                ->sum('amount');
        })
        ->addColumn('status', function ($row) {
            return $row->status;
        })
        ->addColumn('mobile', function ($row) {
            return $row->mobile;
        })
        ->addColumn('update_payment', function ($row) {
            $paid = $row->payments
                ->where('status', 'completed')
                ->sum('amount');
            return '
                <button class="btn btn-primary btn-sm"
                    onclick="openPaymentModal(' . $row->id . ', ' . $row->total . ', ' . $paid . ')">
                   Update
                </button>
            ';
        })
        ->addColumn('payment_history', function ($row) {
            return '
                <a href="' . route('payment_report', $row->id) . '" class="btn btn-info btn-sm">
                    Report
                </a>
            ';
        })
        ->addColumn('view_bill', function ($row) {
            return '
                <a href="' . route('view_bill', $row->id) . '" class="btn btn-label-info">
                    Bill
                </a>
            ';
        })
       ->addColumn('view_invoice', function ($row) {
    return '
        <a href="' . route('invoice', $row->id) . '" target="_blank">
            <i class="ti tabler-eye text-primary fs-5"></i>
        </a>
        <a href="javascript:void(0);" onclick="downloadInvoice(' . $row->id . ')">
            <i class="ti tabler-download text-primary fs-5"></i>
        </a>
    ';
})
        ->rawColumns([
            'status',
            'update_payment',
            'payment_history',
            'view_bill',
            'view_invoice'
        ])
        ->make(true);
}
}
