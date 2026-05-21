<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Popup;
use Yajra\DataTables\Facades\DataTables;

class PopupController extends Controller
{

public function add_popup_manager(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'employees'   => 'required|array|min:1',
                'employees.*' => 'exists:users,id',
                'message'     => 'required|string'
            ]);

            foreach ($request->employees as $employeeId) {

                Popup::create([
                    'user_id'      => $employeeId,
                    'message'      => $request->message,
                    'popup_status' => 'active',
                    'done_status'  => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->back()->with([
                'success' => 'Popup created successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            DB::rollBack();

            Log::error('Popup Validation Error', [
                'errors' => $e->errors(),
                'input'  => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Popup Store Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'input'   => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with([
                    'error' => 'Something went wrong'
                ]);
        }
    }


    public function popupNoted($id)
    {
        DB::beginTransaction();

        try {

            $popup = Popup::find($id);

            if (!$popup) {

                return response()->json([
                    'success' => false,
                    'message' => 'Popup not found'
                ], 404);
            }

            // count increase
            $popup->increment('count');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Popup noted successfully',
                'count'   => $popup->count
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Popup Noted Error', [
                'popup_id' => $id,
                'error'    => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function popupDone($id)
    {
        DB::beginTransaction();

        try {

            $popup = Popup::find($id);

            if (!$popup) {

                return response()->json([
                    'success' => false,
                    'message' => 'Popup not found'
                ], 404);
            }

            // mark completed
            $popup->done_status = 'done';

            // optional but recommended
            // $popup->popup_status = 'inactive';

            $popup->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Popup marked as done'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Popup Done Error', [
                'popup_id' => $id,
                'error'    => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }


     public function getPopups(Request $request)
    {
        $data = Popup::select([
            'id',
            'user_id',
            'title',
            'message',
            'count',
            'popup_status',
            'done_status',
            'created_at'
        ]);

        return DataTables::of($data)

            // S.No
            ->addIndexColumn()

            // Date Time
            ->editColumn('datetime', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('d M Y h:i A')
                    : '';
            })

            // Username (you can change relation later)
            ->addColumn('username', function ($row) {
                return $row->user->name ?? 'N/A';
            })

            // Description
            ->editColumn('description', function ($row) {
                return $row->message;
            })

            // Popup Status badge
            ->editColumn('popup_status', function ($row) {
                return $row->popup_status;
            })

            // Noted Count
            ->editColumn('noted_count', function ($row) {
                return $row->count;
            })

            // Done Status
            ->editColumn('done_status', function ($row) {
                return $row->done_status;
            })

            ->rawColumns(['popup_status', 'noted_count', 'done_status'])
            ->make(true);
    }


public function update_popup_status(Request $request)
{
    // 🔹 Input validation (important)
    $request->validate([
        'id' => 'required|exists:popups,id',
    ]);

    DB::beginTransaction();

    try {

        $popup = Popup::findOrFail($request->id);

        // toggle status
        $popup->popup_status = $popup->popup_status === 'active'
            ? 'inactive'
            : 'active';

        $popup->save();

        DB::commit();

        return response()->json([
            'status' => true,
            'new_status' => $popup->popup_status,
            'message' => 'Status updated successfully'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        // log full error
        Log::error('Popup Status Update Failed', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'request' => $request->all()
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong'
        ], 500);
    }
}
}
