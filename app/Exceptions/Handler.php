<?php

namespace App\Exceptions;

use http\Client\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        $this->renderable(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('*')) {
                return response()->json([
                    'message' => $e->getMessage() . $e->getCode() . $e->getFile() . $e->getTraceAsString()
                ], 500);
            }
        });
    }
}
