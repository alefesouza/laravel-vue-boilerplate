<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        return response()->json([
            'message' => __('errors.unauthorized'),
            'errors' => [
                'message' => [
                    __('errors.unauthorized'),
                ],
            ],
        ], 401);
    }
}
