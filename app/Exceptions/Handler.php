<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\View\ViewException;
use Throwable;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        // Handle payment-related exceptions for payment routes
        if ($request->is('*/payment/*') || $request->is('api/ssl-gateway/*')) {
            $paymentHandler = new PaymentExceptionHandler();
            return $paymentHandler->handle($exception, $request);
        }
        
        // Handle ViewException specifically to avoid the problematic markdown renderer
        if ($exception instanceof ViewException) {
            return $this->renderViewException($request, $exception);
        }

        // Convert CSRF token mismatch (419) into a friendly redirect
        if ($exception instanceof TokenMismatchException || ($exception instanceof HttpException && $exception->getStatusCode() === 419)) {
            // Check if this is an SSL payment callback
            $path = $request->path();
            $isSSLCallback = false;
            
            // Check if this is related to SSL payment
            if (strpos($path, 'ssl') !== false || 
                strpos($path, 'payment') !== false || 
                $request->has('tran_id')) {
                $isSSLCallback = true;
            }
            
            // Store transaction ID in session if available
            if ($request->has('tran_id')) {
                session(['ssl_transaction_id' => $request->get('tran_id')]);
            }
            
            // If it's an SSL callback, redirect to a more specific page
            if ($isSSLCallback) {
                // Log the error for debugging
                \Illuminate\Support\Facades\Log::warning('SSL Payment 419 Error', [
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'request_data' => $request->all()
                ]);
                
                // Extract type and ID if available
                $type = $request->get('value_a');
                $id = $request->get('value_b');
                
                if ($type && $id) {
                    // If we have type and ID, redirect to payment options
                    return redirect()->route('customer.payment.options', ['type' => $type, 'id' => $id])
                        ->with('warning', 'Your payment session expired. Please check your transaction status or try again.');
                }
                
                // Fallback to dashboard with warning
                return redirect()->route('customer.dashboard')
                    ->with('warning', 'Your payment process was completed, but we encountered a session issue. Please check your transaction status or contact support.');
            }
            
            // For non-payment related CSRF issues, use the general message
            return redirect()->guest(url('/'))
                ->with('error', 'Your session has expired. Please try again.');
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