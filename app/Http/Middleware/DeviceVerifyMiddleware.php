<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeviceVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $device = $request->header('User-Agent');
        $device = 'Dart/2.17 (dart:io)';
        $device = explode("/", $device);
        if(env('APP_MODE') == LIVE && $device[0] != 'Dart') {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            abort(response()->json(['errors' => $errors], 401));
        }
        return $next($request);
    }
}
