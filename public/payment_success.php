<?php
/**
 * Direct payment success page that doesn't rely on Laravel's routing.
 * This avoids CSRF issues and redirect loops.
 */

// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get transaction data from session or query parameters
$transactionId = $_GET['tran_id'] ?? $_SESSION['ssl_transaction_id'] ?? $_COOKIE['ssl_transaction_id'] ?? 'Unknown';
$status = $_GET['status'] ?? $_SESSION['ssl_status'] ?? 'Unknown';
$orderType = $_GET['type'] ?? $_SESSION['ssl_value_a'] ?? null;
$orderId = $_GET['id'] ?? $_SESSION['ssl_value_b'] ?? null;

// Database connection details (get from Laravel's .env file)
$envFile = __DIR__ . '/../.env';
$db = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
                $value = substr($value, 1, -1);
            }
            
            $_ENV[$key] = $value;
        }
    }
    
    $db = [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'database' => $_ENV['DB_DATABASE'] ?? 'laravel',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
    ];
}

// Generate a transaction number
function generateTransactionNumber() {
    return 'TXN' . strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
}

// Update transaction status in the database
if ($transactionId && $status) {
    try {
        // Connect to the database
        $pdo = new PDO(
            "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
            $db['username'],
            $db['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // First check if a transaction with this SSL transaction ID already exists
        $stmt = $pdo->prepare("SELECT id FROM transactions WHERE ssl_transaction_id = :transaction_id");
        $stmt->execute([':transaction_id' => $transactionId]);
        $existingTransaction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingTransaction) {
            // Update existing transaction
            $query = "UPDATE transactions SET 
                        ssl_status = :status, 
                        status = :internal_status, 
                        updated_at = NOW() 
                      WHERE ssl_transaction_id = :transaction_id";
                      
            // Normalize SSL status - convert VALID to SUCCESS for consistency
            if (strtoupper($status) === 'VALID') {
                $status = 'SUCCESS';
            }
            
            $internalStatus = (strtoupper($status) === 'SUCCESS') ? 'completed' : 
                             (strtoupper($status) === 'FAILED' ? 'failed' : 
                             (strtoupper($status) === 'CANCELLED' ? 'cancelled' : 'pending'));
                             
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':status' => $status,
                ':internal_status' => $internalStatus,
                ':transaction_id' => $transactionId
            ]);
            
            error_log("Updated existing transaction: {$transactionId} with status: {$status}");
        } else {
            // Create new transaction
            $transactionNumber = generateTransactionNumber();
            $now = date('Y-m-d H:i:s');
            $amount = $_GET['amount'] ?? 0;
            
            // Normalize SSL status - convert VALID to SUCCESS for consistency
            if (strtoupper($status) === 'VALID') {
                $status = 'SUCCESS';
            }
            
            $internalStatus = (strtoupper($status) === 'SUCCESS') ? 'completed' : 
                             (strtoupper($status) === 'FAILED' ? 'failed' : 
                             (strtoupper($status) === 'CANCELLED' ? 'cancelled' : 'pending'));
            
            // Check if we have order type and ID information
            $orderFields = '';
            $orderValues = [];
            
            if ($orderType && $orderId) {
                switch ($orderType) {
                    case 'package':
                        $orderFields = ', package_order_id';
                        $orderValues[':package_order_id'] = $orderId;
                        break;
                    case 'service':
                        $orderFields = ', service_order_id';
                        $orderValues[':service_order_id'] = $orderId;
                        break;
                    case 'custom-service':
                        $orderFields = ', custom_service_request_id';
                        $orderValues[':custom_service_request_id'] = $orderId;
                        break;
                    case 'fund':
                        $orderFields = ', fund_request_id';
                        $orderValues[':fund_request_id'] = $orderId;
                        break;
                }
                
                // Try to get customer information from the order
                $tableMap = [
                    'package' => 'package_orders',
                    'service' => 'service_orders',
                    'custom-service' => 'custom_service_requests',
                    'fund' => 'fund_requests'
                ];
                
                if (isset($tableMap[$orderType])) {
                    $table = $tableMap[$orderType];
                    
                    try {
                        // Get customer information from the order
                        $userQuery = "SELECT u.* FROM {$table} o 
                                      JOIN users u ON o.user_id = u.id 
                                      WHERE o.id = :id";
                                      
                        $userStmt = $pdo->prepare($userQuery);
                        $userStmt->execute([':id' => $orderId]);
                        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($user) {
                            $orderFields .= ', customer_name, customer_email, customer_phone, customer_address';
                            $orderValues[':customer_name'] = $user['name'];
                            $orderValues[':customer_email'] = $user['email'];
                            $orderValues[':customer_phone'] = $user['phone'] ?? null;
                            $orderValues[':customer_address'] = $user['address'] ?? null;
                        }
                    } catch (Exception $e) {
                        error_log("Error getting customer info: " . $e->getMessage());
                    }
                }
            }
            
            // Build the query dynamically based on whether we have order fields
            $baseFields = "transaction_number, amount, payment_method, status, ssl_transaction_id, ssl_status, notes, created_at, updated_at";
            $baseValues = ":transaction_number, :amount, :payment_method, :status, :ssl_transaction_id, :ssl_status, :notes, :created_at, :updated_at";
            
            $finalFields = $baseFields;
            $finalValues = $baseValues;
            
            if (!empty($orderFields)) {
                $finalFields .= $orderFields;
                $finalValues .= ', ' . implode(', ', array_keys($orderValues));
            }
            
            $query = "INSERT INTO transactions ({$finalFields}) VALUES ({$finalValues})";
            
            // Prepare the execute parameters
            $executeParams = [
                ':transaction_number' => $transactionNumber,
                ':amount' => $amount,
                ':payment_method' => 'SSL Payment',
                ':status' => $internalStatus,
                ':ssl_transaction_id' => $transactionId,
                ':ssl_status' => $status,
                ':notes' => 'Transaction created from payment success page',
                ':created_at' => $now,
                ':updated_at' => $now
            ];
            
            // Merge with order values if they exist
            if (!empty($orderValues)) {
                $executeParams = array_merge($executeParams, $orderValues);
            }
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($executeParams);
            
            error_log("Created new transaction: {$transactionId} with status: {$status}");
        }
        
        // Update order status if order type and ID are provided
        if ($orderType && $orderId) {
            $tableMap = [
                'package' => 'package_orders',
                'service' => 'service_orders',
                'custom-service' => 'custom_service_requests',
                'fund' => 'fund_requests'
            ];
            
            if (isset($tableMap[$orderType])) {
                $table = $tableMap[$orderType];
                
                // Update payment status
                $query = "UPDATE {$table} SET 
                            payment_status = :payment_status,
                            updated_at = NOW()
                          WHERE id = :id";
                          
                // Normalize SSL status - convert VALID to SUCCESS for consistency
                if (strtoupper($status) === 'VALID') {
                    $status = 'SUCCESS';
                }
                
                $paymentStatus = (strtoupper($status) === 'SUCCESS') ? 'paid' : 'pending';
                
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':payment_status' => $paymentStatus,
                    ':id' => $orderId
                ]);
                
                error_log("Updated {$orderType} order {$orderId} payment status to {$paymentStatus}");
            }
        }
        
    } catch (PDOException $e) {
        error_log("Database error in payment success: " . $e->getMessage());
    }
}

// Determine the message based on the status
$message = '';
$alertClass = '';

// Normalize SSL status - convert VALID to SUCCESS for consistency
if (strtoupper($status) === 'VALID') {
    $status = 'SUCCESS';
}

if (strtoupper($status) === 'SUCCESS') {
    $message = 'Your payment was successful! Transaction ID: ' . $transactionId;
    $alertClass = 'alert-success';
} elseif (strtoupper($status) === 'FAILED') {
    $message = 'Your payment failed. Transaction ID: ' . $transactionId;
    $alertClass = 'alert-danger';
} elseif (strtoupper($status) === 'CANCELLED') {
    $message = 'Your payment was cancelled. Transaction ID: ' . $transactionId;
    $alertClass = 'alert-warning';
} else {
    $message = 'Payment status: ' . $status . '. Transaction ID: ' . $transactionId;
    $alertClass = 'alert-info';
}

// Get website settings for logo
$websiteLogo = '';
$logoAlt = 'Wise Dynamic';
$siteName = 'Wise Dynamic';

try {
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
        $db['username'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $pdo->query("SELECT site_logo, logo_alt_text, site_name FROM website_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        if (!empty($settings['site_logo'])) {
            $websiteLogo = '/storage/' . $settings['site_logo'];
        }
        if (!empty($settings['logo_alt_text'])) {
            $logoAlt = $settings['logo_alt_text'];
        }
        if (!empty($settings['site_name'])) {
            $siteName = $settings['site_name'];
        }
    }
} catch (Exception $e) {
    error_log("Error getting website settings: " . $e->getMessage());
}

// Output the success page
echo '<!DOCTYPE html>
<html>
<head>
    <title>Payment Status - ' . htmlspecialchars($siteName) . '</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .payment-container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo img { max-height: 60px; }
    </style>
</head>
<body>
    <div class="container payment-container">
        <div class="logo">';

// Display the website logo if available, otherwise show site name
if (!empty($websiteLogo)) {
    echo '<img src="' . htmlspecialchars($websiteLogo) . '" alt="' . htmlspecialchars($logoAlt) . '" class="img-fluid">';
} else {
    echo '<h2>' . htmlspecialchars($siteName) . '</h2>';
}

echo '
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Payment Status</h4>
            </div>
            <div class="card-body">
                <div class="alert ' . $alertClass . '">' . htmlspecialchars($message) . '</div>
                
                <div class="mb-4">
                    <h5>Transaction Details:</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Transaction ID:</th>
                            <td>' . htmlspecialchars($transactionId) . '</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>' . htmlspecialchars($status) . '</td>
                        </tr>';

// Add order details if available
if ($orderType && $orderId) {
    echo '
                        <tr>
                            <th>Order Type:</th>
                            <td>' . htmlspecialchars($orderType) . '</td>
                        </tr>
                        <tr>
                            <th>Order ID:</th>
                            <td>' . htmlspecialchars($orderId) . '</td>
                        </tr>';
}

echo '
                    </table>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="/" class="btn btn-primary">Return to Home</a>
                    <a href="/customer/dashboard" class="btn btn-outline-primary">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
