<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ErrorException) {
            //generate file log error
            //get route which triggered the error with parameter
            $errorLog = date('Y-m-d H:i:s') .' '. $request->route()->uri .' '. $exception->getMessage() . ' ' . $exception->getFile() . ' ' . $exception->getLine() . "\n";
            error_log($errorLog, 3, storage_path('logs/ErrorException.log'));
            return response()->json([
                'message' => 'Tidak dapat terhubung ke server'
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
