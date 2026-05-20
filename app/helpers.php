<?php
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Notifications\SimplePushNotification;
function webpushnotify($userId, $title, $message)
{
    Log::info("Push helper started", ['user' => $userId, 'title' => $title, 'message' => $message]);
    $user = User::find($userId);
        $user_token = DB::table('push_subscriptions')->where('user_id',$userId)->latest();
    if (!$user) {
        Log::error("Push failed: user not found", ['user_id' => $userId]);
        return false;
    }
       $url ='https://novelxcrm.fuiox.com/staff/staff_task';
    try {
        $user->notify(new SimplePushNotification($title, $message,$url));
        Log::info("Push notification dispatched successfully", ['user_id' => $userId]);
        return true;
    } catch (\Throwable $e) {
        Log::error("Push error: " . $e->getMessage(), ['user_id' => $userId, 'trace' => $e->getTraceAsString()]);
        return false;
    }
}