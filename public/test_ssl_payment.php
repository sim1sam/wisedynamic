<?php
/**
 * Test script to simulate SSL payment flow
 */

// Generate a test transaction ID
$transactionId = 'TEST_TRAN_' . time();
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// HTML header
echo '<!DOCTYPE html>
<html>
<head>
    <title>SSL Payment Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { margin-bottom: 20px; }
        .btn-group { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">SSL Payment Test</h1>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Test Transaction</h4>
            </div>
            <div class="card-body">
                <p><strong>Transaction ID:</strong> ' . $transactionId . '</p>
                <p><strong>Current URL:</strong> ' . $currentUrl . '</p>
                
                <h5 class="mt-4">Test Payment Flow</h5>
                <p>Click one of the buttons below to simulate a payment:</p>
                
                <div class="btn-group">
                    <a href="' . $currentUrl . '/ssl_callback.php?tran_id=' . $transactionId . '&status=VALID&amount=1000&value_a=package&value_b=1" class="btn btn-success">Simulate Successful Payment</a>
                    <a href="' . $currentUrl . '/ssl_callback.php?tran_id=' . $transactionId . '&status=FAILED&amount=1000&value_a=package&value_b=1" class="btn btn-danger">Simulate Failed Payment</a>
                    <a href="' . $currentUrl . '/ssl_callback.php?tran_id=' . $transactionId . '&status=CANCELLED&amount=1000&value_a=package&value_b=1" class="btn btn-warning">Simulate Cancelled Payment</a>
                </div>
                
                <h5 class="mt-4">Test Direct Success Page</h5>
                <div class="btn-group">
                    <a href="' . $currentUrl . '/payment_success.php?tran_id=' . $transactionId . '&status=VALID&amount=1000&type=package&id=1" class="btn btn-outline-success">Test Success Page</a>
                </div>
                
                <h5 class="mt-4">Check Transaction Status</h5>
                <div class="btn-group">
                    <a href="' . $currentUrl . '/check_transactions.php" class="btn btn-outline-primary">Check Transactions</a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4>Test POST Requests</h4>
            </div>
            <div class="card-body">
                <p>Use these forms to test POST requests:</p>
                
                <form action="' . $currentUrl . '/ssl_callback.php" method="post" class="mb-3">
                    <input type="hidden" name="tran_id" value="' . $transactionId . '_POST">
                    <input type="hidden" name="status" value="VALID">
                    <input type="hidden" name="amount" value="2000">
                    <input type="hidden" name="value_a" value="service">
                    <input type="hidden" name="value_b" value="2">
                    <button type="submit" class="btn btn-primary">Test POST to SSL Callback</button>
                </form>
                
                <form action="' . $currentUrl . '/payment_success.php" method="post" class="mb-3">
                    <input type="hidden" name="tran_id" value="' . $transactionId . '_POST_SUCCESS">
                    <input type="hidden" name="status" value="VALID">
                    <input type="hidden" name="amount" value="3000">
                    <input type="hidden" name="type" value="service">
                    <input type="hidden" name="id" value="3">
                    <button type="submit" class="btn btn-primary">Test POST to Success Page</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>';
