<?php
/**
 * Test script to simulate SSL payment callbacks.
 * This file should be placed in the public directory.
 */

// Include the CSRF bypass script
if (file_exists(__DIR__ . '/force_no_csrf.php')) {
    include __DIR__ . '/force_no_csrf.php';
}

// Generate a test transaction ID
$transactionId = 'TEST_TRAN_' . time();
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// Create HTML form
echo '<!DOCTYPE html>
<html>
<head>
    <title>SSL Payment Callback Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .container { background: #f9f9f9; border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        h1 { color: #333; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; }
        .btn.fail { background: #f44336; }
        .btn.cancel { background: #ff9800; }
        pre { background: #eee; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>SSL Payment Callback Test</h1>
        <p>This page simulates SSL payment gateway callbacks. Click one of the buttons below to test different callback scenarios.</p>
        
        <h2>Test Transaction ID: ' . $transactionId . '</h2>
        
        <h3>Test Generic Callbacks (No Type/ID)</h3>
        <a href="' . $currentUrl . '/customer/payment/ssl/success?tran_id=' . $transactionId . '&status=VALID" class="btn">Test Success</a>
        <a href="' . $currentUrl . '/customer/payment/ssl/fail?tran_id=' . $transactionId . '&status=FAILED" class="btn fail">Test Fail</a>
        <a href="' . $currentUrl . '/customer/payment/ssl/cancel?tran_id=' . $transactionId . '&status=CANCELLED" class="btn cancel">Test Cancel</a>
        
        <h3>Test With Type/ID Parameters</h3>
        <a href="' . $currentUrl . '/customer/payment/ssl/success/package/1?tran_id=' . $transactionId . '&status=VALID&value_a=package&value_b=1" class="btn">Test Success with Params</a>
        <a href="' . $currentUrl . '/customer/payment/ssl/fail/package/1?tran_id=' . $transactionId . '&status=FAILED&value_a=package&value_b=1" class="btn fail">Test Fail with Params</a>
        <a href="' . $currentUrl . '/customer/payment/ssl/cancel/package/1?tran_id=' . $transactionId . '&status=CANCELLED&value_a=package&value_b=1" class="btn cancel">Test Cancel with Params</a>
        
        <h3>Test Fallback Route</h3>
        <a href="' . $currentUrl . '/ssl-callback?tran_id=' . $transactionId . '&status=VALID" class="btn">Test Fallback</a>
        
        <h3>Test Direct Success Page</h3>
        <a href="' . $currentUrl . '/payment/success?message=Test+message&tran_id=' . $transactionId . '&status=success" class="btn">Test Success Page</a>
        
        <h3>Test POST Requests (Form Submission)</h3>
        <form action="' . $currentUrl . '/customer/payment/ssl/success" method="post" style="margin-bottom: 10px;">
            <input type="hidden" name="tran_id" value="' . $transactionId . '">
            <input type="hidden" name="status" value="VALID">
            <input type="hidden" name="val_id" value="val_' . $transactionId . '">
            <input type="hidden" name="amount" value="1000">
            <input type="hidden" name="value_a" value="package">
            <input type="hidden" name="value_b" value="1">
            <button type="submit" class="btn">Test POST Success</button>
        </form>
        
        <form action="' . $currentUrl . '/ssl-callback" method="post" style="margin-bottom: 10px;">
            <input type="hidden" name="tran_id" value="' . $transactionId . '">
            <input type="hidden" name="status" value="VALID">
            <input type="hidden" name="val_id" value="val_' . $transactionId . '">
            <input type="hidden" name="amount" value="1000">
            <button type="submit" class="btn">Test POST Fallback</button>
        </form>
    </div>
</body>
</html>';
