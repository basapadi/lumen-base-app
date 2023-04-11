<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\StaticResponseTrait;

class Cashier {

    use StaticResponseTrait;

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
                if(auth()->user()->role != 2) {
                    $resp = $this->response401();
                    return response()->json($resp, 401);
                }

                // check if invalid BSON string
                $request->merge(['user_id' => $userId]);
                return $next($request);
            }

            $resp = $this->response401();
            return response()->json($resp, 401);
        } catch (\InvalidArgumentException $e) {
            $resp = $this->response401();
            return response()->json($resp, 401);
        } catch (\Exception $e) {
            $resp = $this->response500($e->getMessage());
            return response()->json($resp, 500);
        }

        return $next($request);
    }

}
