<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Traits\BrevoOtpTrait;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
class AuthenticationController extends Controller
{
    use BrevoOtpTrait;
    // public function staff_login_sendotp(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email'    => 'required|email',
    //             'password' => 'required|string',
    //         ]);
    //         $user = User::where('email', $request->email)
    //             ->where('role', 'staff')
    //             ->first();
    //         if (!$user) {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'Staff account not found'
    //             ], 404);
    //         }
    //         if ($user->is_active != 1) {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'User account is inactive'
    //             ], 403);
    //         }
    //         if (!Hash::check($request->password, $user->password)) {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'Invalid password'
    //             ], 401);
    //         }
    //         // $otp = rand(100000, 999999);
    //         $otp = '123456';
    //         $user->otp            = Crypt::encryptString($otp);
    //         $user->otp_expires_at = now()->addMinutes(2);
    //         $user->save();
    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'OTP sent successfully'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong while sending OTP'
    //         ], 500);
    //     }
    // }
    // public function staff_login_verifyotp(Request $request)
    // {
    //     try {
    //         Log::info("Staff_login_verifyotp: Request started", $request->all());
    //         // Validate input
    //         $request->validate([
    //             'email' => 'required|email',
    //             'otp' => 'required|string',
    //         ]);
    //         // Find the user
    //         $user = User::where('email', $request->email)
    //             ->where('role', 'staff')  // optional but recommended
    //             ->first();
    //         if (!$user) {
    //             return back()->with('error', 'User not found')->withInput();
    //         }
    //         // Check OTP expiry
    //         if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
    //             return back()->with('error', 'OTP expired')->withInput();
    //         }
    //         // Check OTP match
    //         $storedOtp = Crypt::decryptString($user->otp);
    //         if ($storedOtp !== $request->otp) {
    //             return back()->with('error', 'Invalid OTP')->withInput();
    //         }
    //         // Auth::login($user);
    //         Auth::guard('staff')->login($user);
    //         // Clear OTP after successful verification
    //         $user->otp = null;
    //         $user->otp_expires_at = null;
    //         $user->save();
    //         // Redirect to change password page
    //         return redirect()
    //             ->route('staff.dashboard')
    //             ->with('success', 'OTP verified successfully');
    //     } catch (\Exception $e) {
    //         Log::error("login_verifyotp error", ['error' => $e->getMessage()]);
    //         return back()->with('error', 'Error verifying OTP')->withInput();
    //     }
    // }
    public function staff_login_sendotp(Request $request)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */

            $request->validate([

                'email'    => 'required|email',

                'password' => 'required|string',
            ]);

            /*
            |--------------------------------------------------------------------------
            | FIND USER
            |--------------------------------------------------------------------------
            */

            $user = User::where('email', $request->email)
                ->where('role', 'staff')
                ->first();

            if (!$user) {

                return response()->json([

                    'status'  => false,

                    'message' => 'Staff account not found'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT ACTIVE CHECK
            |--------------------------------------------------------------------------
            */

            if ($user->is_active != 1) {

                return response()->json([

                    'status'  => error,

                    'message' => 'User account inactive'
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | PASSWORD CHECK
            |--------------------------------------------------------------------------
            */

            if (!Hash::check($request->password, $user->password)) {

                return response()->json([

                    'status'  => 'error',

                    'message' => 'Invalid password'
                ], 401);
            }

            /*
            |--------------------------------------------------------------------------
            | SEND OTP MAIL
            |--------------------------------------------------------------------------
            */

            $sendOtp = $this->sendBrevoOtp($request->email);

            if (!$sendOtp['status']) {

                return response()->json([

                    'status' => error,

                    'message' => $sendOtp['message']
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | SUCCESS RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'status' => 'success',

                'message' => 'OTP sent successfully',

                // FRONTEND TIMER
                'otp_timer' => 60

            ], 200);

        } catch (\Exception $e) {

            Log::error('SEND OTP ERROR', [

                'error' => $e->getMessage()
            ]);

            return response()->json([

                'status' => false,

                'message' => 'Something went wrong while sending OTP'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY OTP
    |--------------------------------------------------------------------------
    */

    // public function staff_login_verifyotp(Request $request)
    // {
    //     try {

    //         /*
    //         |--------------------------------------------------------------------------
    //         | VALIDATION
    //         |--------------------------------------------------------------------------
    //         */

    //         $request->validate([

    //             'email' => 'required|email',

    //             'otp'   => 'required'
    //         ]);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | FIND USER
    //         |--------------------------------------------------------------------------
    //         */

    //         $user = User::where('email', $request->email)
    //             ->where('role', 'staff')
    //             ->first();

    //         if (!$user) {

    //             return back()->with(

    //                 'error',
    //                 'User not found'
    //             );
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | VERIFY OTP
    //         |--------------------------------------------------------------------------
    //         */

    //         $verifyOtp = $this->verifyBrevoOtp(

    //             $request->email,
    //             $request->otp
    //         );

    //         if (!$verifyOtp['status']) {

    //             return back()->with(

    //                 'error',
    //                 $verifyOtp['message']
    //             );
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | LOGIN USER
    //         |--------------------------------------------------------------------------
    //         */

    //         Auth::guard('staff')->login($user);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | CLEAR OTP TABLE
    //         |--------------------------------------------------------------------------
    //         */

    //         EmailOtp::where(
    //             'email',
    //             $request->email
    //         )->delete();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | OPTIONAL USER OTP CLEAR
    //         |--------------------------------------------------------------------------
    //         */

    //         $user->update([

    //             'otp' => null,

    //             'otp_expires_at' => null
    //         ]);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | REDIRECT DASHBOARD
    //         |--------------------------------------------------------------------------
    //         */

    //         return redirect()
    //             ->route('staff.dashboard')
    //             ->with(
    //                 'success',
    //                 'OTP verified successfully'
    //             );

    //     } catch (\Exception $e) {

    //         Log::error('VERIFY OTP ERROR', [

    //             'error' => $e->getMessage()
    //         ]);

    //         return back()->with(

    //             'error',
    //             'Something went wrong while verifying OTP'
    //         );
    //     }
    // }
    public function staff_login_verifyotp(Request $request)
{
    try {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'email' => 'required|email',

            'otp'   => 'required'
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIND USER
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $request->email)
            ->where('role', 'staff')
            ->first();

        if (!$user) {

            return back()->with(

                'error',
                'User not found'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFY OTP
        |--------------------------------------------------------------------------
        */

        $verifyOtp = $this->verifyBrevoOtp(

            $request->email,
            $request->otp
        );

        /*
        |--------------------------------------------------------------------------
        | INVALID OTP CHECK
        |--------------------------------------------------------------------------
        */

        if ($verifyOtp['status'] !== 'success') {

            return back()->with(

                'error',
                $verifyOtp['message']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN USER
        |--------------------------------------------------------------------------
        */

        Auth::guard('staff')->login($user);

        /*
        |--------------------------------------------------------------------------
        | CLEAR OTP TABLE
        |--------------------------------------------------------------------------
        */

        EmailOtp::where(
            'email',
            $request->email
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | OPTIONAL USER OTP CLEAR
        |--------------------------------------------------------------------------
        */

        $user->update([

            'otp' => null,

            'otp_expires_at' => null
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT DASHBOARD
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('staff.dashboard')
            ->with(
                'success',
                'OTP verified successfully'
            );

    } catch (\Exception $e) {

        Log::error('VERIFY OTP ERROR', [

            'error' => $e->getMessage()
        ]);

        return back()->with(

            'error',
            'Something went wrong while verifying OTP'
        );
    }
}

    public function staff_reset_password()
    {
        return view('Staff.staff_reset_password');
    }
    public function forget_password()
    {
        return view('Staff.forget_password');
    }
    public function staff_forget_sendotp(Request $request)
    {
        try {
            $request->validate([
                'email'  => 'required|email|exists:users,email',
                'mobile' => 'required|digits:10|exists:users,mobile',
            ]);
            $user = User::where('email', $request->email)
                ->where('mobile', $request->mobile)
                ->where('role', 'staff')
                ->first();
            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found with this Email + Mobile'
                ]);
            }
            $otp                  = '123456';
            $user->otp            = Crypt::encryptString($otp);
            $user->otp_expires_at = now()->addMinutes(2);
            $user->save();
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error sending OTP'
            ]);
        }
    }
    public function staff_forget_verifyotp(Request $request)
    {
        try {
            Log::info("staff_forget_verifyotp: Request started", $request->all());
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|string',
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->with('error', 'User not found')->withInput();  // add ->withInput()
            }
            if (!$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
                return back()->with('error', 'OTP expired')->withInput();
            }
            if (Crypt::decryptString($user->otp) != $request->otp) {
                return back()->with('error', 'Invalid OTP')->withInput();
            }
            $user->otp            = null;
            $user->otp_expires_at = null;
            $user->save();
            return redirect()->route('change_password', ['email' => $request->email])
                ->with('success', 'OTP verified successfully')->withInput();
        } catch (\Exception $e) {
            Log::error("staff_forget_verifyotp error", ['error' => $e->getMessage()]);
            return back()->with('error', 'Error verifying OTP')->withInput();
        }
    }
    public function new_password(Request $request)
    {
        Log::info('update_password: Request received', $request->all());
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->with('error', 'User not found.');
            }
            $user->password = bcrypt($request->password);
            $user->save();
            Log::info('update_password: Password updated successfully', ['email' => $request->email]);
            return redirect()->route('login')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            Log::error('update_password: Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong.');
        }
    }
    public function staff_logout(Request $request)
    {
        // Auth::logout();
        Auth::guard('staff')->logout();
        // $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
    public function staff_reset_password_form(Request $request)
    {
        try {
            $user = Auth::guard('staff')->user();
            Log::info('Password reset attempt', ['staff_id' => $user->id]);
            $validator = Validator::make($request->all(), [
                'oldpass' => 'required',
                'newpass' => 'required|string|min:6',
                'conpass' => 'required|same:newpass',
            ]);
            if ($validator->fails()) {
                Log::warning('Password reset validation failed', [
                    'staff_id' => $user->id,
                    'errors'   => $validator->errors()->toArray(),
                ]);
                return redirect()
                    ->route('staff_reset_password')
                    ->withErrors($validator)
                    ->withInput();
            }
            // Check old password
            if (!Hash::check($request->oldpass, $user->password)) {
                Log::warning('Old password mismatch', ['staff_id' => $user->id]);
                return redirect()
                    ->route('staff_reset_password')
                    ->with('error', 'Old password is incorrect!')
                    ->withInput();
            }
            DB::beginTransaction();
            $user->password = Hash::make($request->newpass);
            $user->save();
            DB::commit();
            Log::info('Password reset successful', ['staff_id' => $user->id]);
            return redirect()
                ->route('staff_reset_password')
                ->with('success', 'Password reset successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Password reset failed', [
                'staff_id' => Auth::guard('staff')->id(),
                'message'  => $e->getMessage(),
            ]);
            return redirect()
                ->route('staff_reset_password')
                ->with('error', 'Something went wrong! Please try again.')
                ->withInput();
        }
    }
}
