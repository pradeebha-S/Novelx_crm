<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\EmailOtp;
use Illuminate\Support\Facades\Crypt;
use App\Traits\BrevoOtpTrait;

class AuthController extends Controller
{
    use BrevoOtpTrait;

    // public function login_post(Request $request)
    // {
    //     try {
    //         Log::info('Login attempt', ['email' => $request->email]);
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email|exists:users,email',
    //             'password' => 'required|string',
    //         ]);
    //         if ($validator->fails()) {
    //             Log::warning('Validation failed', [
    //                 'email' => $request->email,
    //                 'errors' => $validator->errors()->toArray(),
    //             ]);
    //             return redirect()
    //                 ->back()
    //                 ->withErrors($validator)
    //                 ->with('error', 'Validation Error')
    //                 ->withInput();
    //         }
    //         DB::beginTransaction();
    //         if (Auth::attempt($request->only('email', 'password'))) {
    //             $user = Auth::user();
    //             Log::info('Login successful', [
    //                 'user_id' => $user->id,
    //                 'role' => $user->role,
    //             ]);
    //             DB::commit();
    //             if ($user->role === 'admin') {
    //                 return redirect()->route('dashboard')
    //                     ->with('success', 'Logged in Successfully!');
    //             }
    //             Auth::logout();
    //             return redirect()->route('login')
    //                 ->with('success', 'Access Denied!');
    //         }
    //         DB::rollBack();
    //         Log::warning('Login failed - invalid credentials', [
    //             'email' => $request->email,
    //         ]);
    //         return redirect()->route('login')
    //             ->with('error', 'Oops! Invalid credentials.')
    //             ->withInput();
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::error('Login error', [
    //             'email' => $request->email,
    //             'message' => $e->getMessage(),
    //         ]);
    //         return redirect()->route('login')
    //             ->with('error', 'Something went wrong. Please try again.')
    //             ->withInput();
    //     }
    // }


    // public function login_sendotp(Request $request)
    // {
    //     try {
    //         // Validate only email & password format
    //         $request->validate([
    //             'email'    => 'required|email|exists:users,email',
    //             'password' => 'required|string',
    //         ]);

    //         // Get the admin user
    //         $user = User::where('email', $request->email)
    //             ->where('role', 'admin')
    //             ->first();

    //         if (!$user) {
    //             return response()->json(['status' => 'error', 'message' => 'Email not found']);
    //         }

    //         // Validate password using Hash::check
    //         if (!Hash::check($request->password, $user->password)) {
    //             return response()->json(['status' => 'error', 'message' => 'Invalid password']);
    //         }

    //         // Generate OTP
    //         $otp = '123456';  // For testing; replace with random later
    //         $user->otp = Crypt::encryptString($otp);
    //         $user->otp_expires_at = now()->addMinutes(2);
    //         $user->save();

    //         return response()->json(['status' => 'success', 'message' => 'OTP sent successfully']);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => 'Error sending OTP']);
    //     }
    // }
    // public function login_verifyotp(Request $request)
    // {
    //     try {
    //         Log::info("login_verifyotp: Request started", $request->all());

    //         // Validate input
    //         $request->validate([
    //             'email' => 'required|email',
    //             'otp' => 'required|string',
    //         ]);

    //         // Find the user
    //         $user = User::where('email', $request->email)
    //             ->where('role', 'admin')  // optional but recommended
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
    //         Auth::guard('admin')->login($user);
    //         // Clear OTP after successful verification
    //         $user->otp = null;
    //         $user->otp_expires_at = null;
    //         $user->save();

    //         // Redirect to change password page
    //         return redirect()
    //             ->route('admin.dashboard')
    //             ->with('success', 'OTP verified successfully');
    //     } catch (\Exception $e) {
    //         Log::error("login_verifyotp error", ['error' => $e->getMessage()]);
    //         return back()->with('error', 'Error verifying OTP')->withInput();
    //     }
    // }

  /*
    |--------------------------------------------------------------------------
    | SEND OTP
    |--------------------------------------------------------------------------
    */

    public function login_sendotp(Request $request)
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
            | FIND ADMIN USER
            |--------------------------------------------------------------------------
            */

            $user = User::where('email', $request->email)
                ->where('role', 'admin')
                ->first();

            if (!$user) {

                return response()->json([

                    'status' => 'error',

                    'message' => 'Admin account not found'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | PASSWORD CHECK
            |--------------------------------------------------------------------------
            */

            if (!Hash::check($request->password, $user->password)) {

                return response()->json([

                    'status' => 'error',

                    'message' => 'Invalid password'
                ], 401);
            }

            /*
            |--------------------------------------------------------------------------
            | SEND OTP USING BREVO
            |--------------------------------------------------------------------------
            */

            $sendOtp = $this->sendBrevoOtp(
                $request->email
            );

            /*
            |--------------------------------------------------------------------------
            | OTP SEND FAILED
            |--------------------------------------------------------------------------
            */

            if ($sendOtp['status'] != 'success') {

                return response()->json([

                    'status' => 'error',

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

                'otp_timer' => 60

            ], 200);

        } catch (\Exception $e) {

            Log::error('ADMIN SEND OTP ERROR', [

                'error' => $e->getMessage()
            ]);

            return response()->json([

                'status' => 'error',

                'message' => 'Something went wrong while sending OTP'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY OTP
    |--------------------------------------------------------------------------
    */
public function login_verifyotp(Request $request)
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
        | FIND ADMIN USER
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$user) {

            return back()->with(

                'error',
                'Admin user not found'
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
            )->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN ADMIN
        |--------------------------------------------------------------------------
        */

        Auth::guard('admin')->login($user);

        /*
        |--------------------------------------------------------------------------
        | DELETE OTP TABLE DATA
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
            ->route('admin.dashboard')
            ->with(
                'success',
                'OTP verified successfully'
            );

    } catch (\Exception $e) {

        Log::error('ADMIN VERIFY OTP ERROR', [

            'error' => $e->getMessage()
        ]);

        return back()->with(

            'error',
            'Something went wrong while verifying OTP'
        )->withInput();
    }
}
    // public function login_verifyotp(Request $request)
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
    //         | FIND ADMIN USER
    //         |--------------------------------------------------------------------------
    //         */

    //         $user = User::where('email', $request->email)
    //             ->where('role', 'admin')
    //             ->first();

    //         if (!$user) {

    //             return back()->with(

    //                 'error',
    //                 'Admin user not found'
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

    //         /*
    //         |--------------------------------------------------------------------------
    //         | INVALID OTP
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($verifyOtp['status'] != 'success') {

    //             return back()->with(

    //                 'error',
    //                 $verifyOtp['message']
    //             )->withInput();
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | LOGIN ADMIN
    //         |--------------------------------------------------------------------------
    //         */

    //         Auth::guard('admin')->login($user);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | DELETE OTP TABLE DATA
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
    //             ->route('admin.dashboard')
    //             ->with(
    //                 'success',
    //                 'OTP verified successfully'
    //             );

    //     } catch (\Exception $e) {

    //         Log::error('ADMIN VERIFY OTP ERROR', [

    //             'error' => $e->getMessage()
    //         ]);

    //         return back()->with(

    //             'error',
    //             'Something went wrong while verifying OTP'
    //         )->withInput();
    //     }
    // }
    public function resetpassword()
    {
        return view('admin.reset_password');
    }
    public function forget_password()
    {
        return view('admin.forget_password');
    }
    public function forget_sendotp(Request $request)
    {
        try {
            // Validate email + mobile properly
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'mobile' => 'required|digits:10|exists:users,mobile',
            ]);

            // Find admin user with matching email AND mobile
            $user = User::where('email', $request->email)
                ->where('mobile', $request->mobile)
                ->where('role', 'admin')
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found with this Email + Mobile'
                ]);
            }

            // Generate and save OTP
            $otp = '123456'; // Replace later with random
            $user->otp = Crypt::encryptString($otp);
            $user->otp_expires_at = now()->addMinutes(2);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error sending OTP'
            ]);
        }
    }


    public function forget_verifyotp(Request $request)
    {
        try {
            Log::info("forget_verifyotp: Request started", $request->all());

            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string',
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

            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return redirect()->route('admin.change_password', ['email' => $request->email])
                ->with('success', 'OTP verified successfully')->withInput();
        } catch (\Exception $e) {
            Log::error("forget_verifyotp error", ['error' => $e->getMessage()]);
            return back()->with('error', 'Error verifying OTP')->withInput();
        }
    }


    public function new_password(Request $request)
    {
        Log::info('update_password: Request received', $request->all());

        $request->validate([
            'email' => 'required|email',
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

            return redirect()->route('admin.login')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            Log::error('update_password: Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function logout(Request $request)
    {
        // Auth::logout();
        Auth::guard('admin')->logout();
        // $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
    
    public function reset_password_form(Request $request)
    {
        try {
            Log::info('Password reset attempt', ['user_id' => Auth::guard('admin')->id()]);
            $validator = Validator::make($request->all(), [
                'oldpass' => 'required',
                'newpass' => 'required|string',
                'conpass' => 'required|same:newpass',
            ]);
            if ($validator->fails()) {
                Log::warning('Validation failed', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors()->toArray()
                ]);
                return redirect()
                    ->route('reset_password')
                    ->withErrors($validator)
                    ->with('error', 'Validation Error')
                    ->withInput();
            }
            DB::beginTransaction();
            $user = Auth::guard('admin')->user();
            if (!Hash::check($request->oldpass, $user->password)) {
                Log::warning('Validation failed', [
                    'user_id' => Auth::guard('admin')->id(),
                ]);

                DB::rollBack();
                return redirect()->route('reset_password')
                    ->with('error', 'Old password is incorrect!')->withInput();
                ;
            }
            $newPassword = $request->newpass;
            $user->password = Hash::make($newPassword);
            $user->save();
            DB::commit();
            Log::info('Password reset successful', ['user_id' => $user->id]);
            return redirect()->route('reset_password')
                ->with('success', 'Password reset successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Password reset failed', [
                'user_id' => Auth::guard('admin')->id(),
            ]);

            return redirect()->route('reset_password')
                ->with('error', 'Something went wrong! Please try again.')->withInput();
            ;
        }
    }
}
