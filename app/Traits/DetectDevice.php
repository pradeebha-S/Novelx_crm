<?php
namespace App\Traits;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

trait DetectDevice
{

public function isMobileDevice($request)
{
    $agent = new Agent();
    $userAgent = strtolower($request->header('User-Agent'));

    $agent->setUserAgent($userAgent);

    $isMobile = $agent->isMobile() || 
                $agent->isTablet() ||
                str_contains($userAgent, 'android') ||
                str_contains($userAgent, 'iphone');

    Log::info('Device Detection', [
        'user_agent' => $userAgent,
        'is_mobile' => $isMobile ? 'YES' : 'NO',
        'ip' => $request->ip()
    ]);

    return $isMobile;
}
}