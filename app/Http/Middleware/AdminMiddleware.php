<?php

namespace App\Http\Middleware;

use App\Utils\Result;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth('admin')->check()) {
            return Result::unauthorized();
        }

        $user = auth('admin')->user();
        //如果没有就重新登录
        if (!$user) {
            return Result::unauthorized();
        }

        return $next($request);
    }



}
