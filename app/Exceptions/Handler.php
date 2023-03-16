<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use App\Traits\StaticResponseTrait;
use App\Libraries\ApiResponse;

class Handler extends ExceptionHandler
{
    
    use StaticResponseTrait;
    
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $httpCode = $exception->getStatusCode();
            // start custom code
            if($httpCode == 404) {
                $resp = $this->response404('API yang anda cari tidak ditemukan!');
                return response()->json($resp, $httpCode);
            } else if ($httpCode == 405) {
                ApiResponse::setStatusCode($httpCode);
                $resp = ApiResponse::make(false, $exception->getMessage());
                
                return response()->json($resp, $exception->getStatusCode());
            }
        } 
        
        if(config('app.env') === 'local') return $this->response500($exception);
        else return parent::render($request, $exception);
    }
}
