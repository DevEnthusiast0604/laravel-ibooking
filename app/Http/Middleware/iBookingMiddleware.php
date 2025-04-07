<?php

namespace App\Http\Middleware;

use Closure;

class iBookingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!file_exists(storage_path('gmz_imported'))) {
            $arr_path = [
                'installer',
                'installer/config-database',
                'installer/check-database',
                'installer/import-data',
                'installer/not-import-data'
            ];
            if(!in_array($request->path(), $arr_path)) {
                return redirect()->route('installer');
            }
        }
        return $next($request);
    }
}
