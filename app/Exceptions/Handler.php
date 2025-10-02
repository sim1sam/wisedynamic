<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\View\ViewException;
use Throwable;
use Illuminate\Http\Response;

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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle ViewException specifically to avoid the problematic markdown renderer
        if ($exception instanceof ViewException) {
            return $this->renderViewException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render a ViewException with a simple error page
     */
    protected function renderViewException($request, ViewException $exception)
    {
        $errorMessage = $exception->getMessage();
        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        // Create a simple HTML error response
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>View Error</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .error-container { background: #f8f9fa; padding: 20px; border-left: 4px solid #dc3545; }
                .error-title { color: #dc3545; font-size: 24px; margin-bottom: 10px; }
                .error-message { font-size: 16px; margin-bottom: 15px; }
                .error-details { font-size: 14px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-title">View Compilation Error</div>
                <div class="error-message">' . htmlspecialchars($errorMessage) . '</div>
                <div class="error-details">
                    <strong>File:</strong> ' . htmlspecialchars($errorFile) . '<br>
                    <strong>Line:</strong> ' . $errorLine . '
                </div>
            </div>
        </body>
        </html>';

        return new Response($html, 500);
    }
}