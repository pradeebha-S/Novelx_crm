<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Popup;

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
}
