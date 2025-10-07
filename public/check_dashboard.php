<?php
/**
 * This script checks if the customer dashboard is accessible.
 */

// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Output HTML header
echo '<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Check</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Dashboard Access Check</h1>';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['auth']) && $_SESSION['auth'] === true;
$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? 'Unknown User') : 'Not Logged In';

echo '<div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Authentication Status</h4>
        </div>
        <div class="card-body">
            <p><strong>Login Status:</strong> ' . ($isLoggedIn ? 'Logged In' : 'Not Logged In') . '</p>
            <p><strong>User:</strong> ' . htmlspecialchars($userName) . '</p>
        </div>
    </div>';

// Check routes
echo '<div class="card">
        <div class="card-header bg-info text-white">
            <h4>Route Tests</h4>
        </div>
        <div class="card-body">
            <p>Click the links below to test different routes:</p>
            <ul>
                <li><a href="/test-route" target="_blank">Test Basic Route</a></li>
                <li><a href="/test-customer-dashboard" target="_blank">Test Customer Dashboard Authentication</a></li>
                <li><a href="/customer" target="_blank">Customer Dashboard</a></li>
                <li><a href="/customer/dashboard" target="_blank">Customer Dashboard (Alternate URL)</a></li>
            </ul>
        </div>
    </div>';

// Check Laravel environment
$laravelEnv = file_exists('../.env');
$storageWritable = is_writable('../storage');
$bootstrapCacheWritable = is_writable('../bootstrap/cache');

echo '<div class="card">
        <div class="card-header bg-success text-white">
            <h4>Laravel Environment Check</h4>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    .env file exists
                    <span class="badge ' . ($laravelEnv ? 'bg-success' : 'bg-danger') . '">' . ($laravelEnv ? 'Yes' : 'No') . '</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    storage directory is writable
                    <span class="badge ' . ($storageWritable ? 'bg-success' : 'bg-danger') . '">' . ($storageWritable ? 'Yes' : 'No') . '</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    bootstrap/cache directory is writable
                    <span class="badge ' . ($bootstrapCacheWritable ? 'bg-success' : 'bg-danger') . '">' . ($bootstrapCacheWritable ? 'Yes' : 'No') . '</span>
                </li>
            </ul>
        </div>
    </div>';

// Check server information
echo '<div class="card">
        <div class="card-header bg-warning text-dark">
            <h4>Server Information</h4>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    PHP Version
                    <span class="badge bg-info">' . phpversion() . '</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Server Software
                    <span class="badge bg-info">' . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . '</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Document Root
                    <span class="badge bg-info">' . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . '</span>
                </li>
            </ul>
        </div>
    </div>';

echo '</div>
</body>
</html>';
