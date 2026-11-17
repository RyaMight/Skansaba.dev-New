<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceSessionTimeout
{
    /**
     * Logout otomatis setelah tidak ada aktivitas selama batas waktu tertentu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        $timeoutMinutes = max(1, (int) config('session.idle_timeout', 30));
        $lastActivity = $request->session()->get('last_activity_at');

        if ($lastActivity && (now()->timestamp - (int) $lastActivity) >= ($timeoutMinutes * 60)) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => "Sesi Anda telah berakhir karena tidak ada aktivitas selama {$timeoutMinutes} menit. Silakan login kembali.",
                ]);
        }

        $request->session()->put('last_activity_at', now()->timestamp);

        return $next($request);
    }
}
