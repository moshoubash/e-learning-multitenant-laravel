<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class BlockVPN
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return $next($request);
        }

        try {
            $response = Http::timeout(3)
                ->withHeaders(['X-Key' => config('services.vpn_api_key')])
                ->get("https://iphub.info{$ip}");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['block']) && $data['block'] == 1) {
                    return response()->json([
                        'error' => 'Access denied. VPN or Proxy connections are restricted.'
                    ], 403);
                }
            }
        } catch (\Exception $e) {
            logger()->error('VPN Detection Failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
