<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\StaticResponseTrait;
use App\Statics\RoleStatic;
use App\Models\{
    Role,
    RoleModule
};

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
                $rm = RoleModule::with('module','role')->where('role_id', auth()->user()->role)->first();
                if(empty($rm)) {
                    $resp = $this->response401();
                    return response()->json($resp, 401);
                }
                /**
                 * Validasi ACL berdasarkan RoleModule dan URI di module
                 */
                
                $resp = $this->response401();
                return response()->json($resp, 401);

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
