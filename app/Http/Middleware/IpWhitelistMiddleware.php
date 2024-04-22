<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpWhitelistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $ip_address = $request->getClientIp(); // Get the client's IP address

        // Check if the IP is whitelisted
        $whitelistedIp = Setting::where('set_for', 'ip_white_list')->first();
        $list_ip = explode(',', $whitelistedIp->value);


        // if (!in_array($ip_address, $list_ip)) {
        //     return redirect('/')->with('warning', 'Access Denied: Your IP address is not authorized to access this resource.');
        // }

        return $next($request);
    }
}
