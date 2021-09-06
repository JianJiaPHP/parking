<?php


namespace App\Http\Middleware;


use App\Models\User;
use App\Models\UserActive;
use App\Utils\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class ApiUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {


        if (!auth('api')->check()) {
            return Result::unauthorized();
        }
        $userId = auth('api')->id();
//        auth('api')->setUser(User::query()->find(3));
//        如果没有就重新登录
        if (!$userId) {
            return Result::unauthorized();
        }

        return $next($request);
    }

}
