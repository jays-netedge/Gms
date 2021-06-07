<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSession;


class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_UPPER);
        if (isset($headers['SESSION-TOKEN']) && !empty($headers['SESSION-TOKEN'])) {
            $row = UserSession::where('session_token', $headers['SESSION-TOKEN'])->where('is_active', 1)->first();
            if ($row != null) {
                session()->put('session_token', $row);
                return $next($request);
            } else {
                $response['code'] = 401;
                $response['message'] = "Invalid Session Token!!";
                return response()->json($response, $response['code']);
            }
        } else {
            $response['code'] = 422;
            $response['message'] = "Invalid Request!!";
            return response()->json($response, $response['code']);
        }
    }
}
