<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Throwable  $exception
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'API endpoint not found.'
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'HTTP method not allowed.'
            ], 405);
        }      

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated. Please provide a valid token.'
        ], 401);
    }   
}
