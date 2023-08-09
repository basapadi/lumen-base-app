<?php

namespace App\Http\Middleware;

use Closure;
use Btx\Http\Response;

class AuthHeader {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        try {
            //$token = $request->bearerToken();
            $payload = auth()->payload();
            $userId = $payload->get('sub');
            if ($userId) {
                // check if invalid BSON string
                $request->merge(['user_id' => $userId]);
                return $next($request);
            }

            $resp = Response::unauthorized();
            return response()->json($resp, 401);
        } catch (\InvalidArgumentException $e) {
            $resp = Response::unauthorized();
            return response()->json($resp, 401);
        } catch (\Exception $e) {
            $resp = Response::internalServerError($e->getMessage());
            return response()->json($resp, 500);
        }

        return $next($request);
    }

}
