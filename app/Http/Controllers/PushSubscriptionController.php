<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::guard('staff')->user(); // get actual authenticated user
        if (!$user) {
            Log::warning('Push subscription failed: unauthenticated request', [
                'ip' => $request->ip(),
                'payload' => $request->all()
            ]);
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $data = $request->json()->all();
        Log::info('Incoming push subscription', [
            'user_id' => $user->id,
            'payload' => $data
        ]);
        if (!isset($data['endpoint'])) {
            Log::error('Push subscription failed: missing endpoint', [
                'user_id' => $user->id,
                'payload' => $data
            ]);
            return response()->json(['error' => 'Invalid subscription'], 400);
        }
        try {
            // Save subscription using the relation
            $subscription = $user->pushSubscriptions()->updateOrCreate(
                ['endpoint' => $data['endpoint']],
                [
                    'public_key'       => $data['keys']['p256dh'] ?? null,
                    'auth_token'       => $data['keys']['auth'] ?? null,
                    'content_encoding' => 'aesgcm',
                ]
            );
            Log::info('Push subscription saved successfully', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id
            ]);
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Push subscription failed with exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to save subscription'], 500);
        }
    }
}
