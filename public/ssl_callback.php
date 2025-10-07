<?php
/**
 * This is a special handler for SSL payment callbacks that completely bypasses Laravel's
 * CSRF protection and session handling.
 * 
 * It directly processes the callback and shows a success page without redirecting to Laravel routes.
 */

// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the request method and parameters
$method = $_SERVER['REQUEST_METHOD'];
$params = $_REQUEST;

// Get the transaction ID and status
$transactionId = $params['tran_id'] ?? 'Unknown';
$status = $params['status'] ?? 'Unknown';
$valueA = $params['value_a'] ?? null; // Order type
$valueB = $params['value_b'] ?? null; // Order ID

// Store the transaction data in the session
$_SESSION['ssl_transaction_id'] = $transactionId;
$_SESSION['ssl_status'] = $status;
$_SESSION['ssl_value_a'] = $valueA;
$_SESSION['ssl_value_b'] = $valueB;
$_SESSION['ssl_callback_data'] = $params;

// Set a cookie with the transaction ID (as a backup)
setcookie('ssl_transaction_id', $transactionId, time() + 3600, '/');

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

// Update transaction status in the database directly
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
            $amount = isset($params['amount']) ? $params['amount'] : 0;
            
            // Normalize SSL status - convert VALID to SUCCESS for consistency
            if (strtoupper($status) === 'VALID') {
                $status = 'SUCCESS';
            }
            
            $internalStatus = (strtoupper($status) === 'SUCCESS') ? 'completed' : 
                             (strtoupper($status) === 'FAILED' ? 'failed' : 
                             (strtoupper($status) === 'CANCELLED' ? 'cancelled' : 'pending'));
            
            // Check if we have order type and ID information
            $orderType = $valueA;
            $orderId = $valueB;
            
            // Prepare order-specific fields
            $orderFields = '';
            $orderValues = [];
            
            if ($orderType && $orderId) {
                switch ($orderType) {
                    case 'package':
                        $orderFields = ', package_order_id';
                        $orderValues = [':package_order_id' => $orderId];
                        break;
                    case 'service':
                        $orderFields = ', service_order_id';
                        $orderValues = [':service_order_id' => $orderId];
                        break;
                    case 'custom-service':
                        $orderFields = ', custom_service_request_id';
                        $orderValues = [':custom_service_request_id' => $orderId];
                        break;
                    case 'fund':
                        $orderFields = ', fund_request_id';
                        $orderValues = [':fund_request_id' => $orderId];
                        break;
                }
                
                // Also update the order's payment status
                $tableMap = [
                    'package' => 'package_orders',
                    'service' => 'service_orders',
                    'custom-service' => 'custom_service_requests',
                    'fund' => 'fund_requests'
                ];
                
                if (isset($tableMap[$orderType])) {
                    $table = $tableMap[$orderType];
                    $paymentStatus = ($internalStatus === 'completed') ? 'paid' : 'pending';
                    
                    try {
                        $orderQuery = "UPDATE {$table} SET 
                                        payment_status = :payment_status,
                                        updated_at = :updated_at
                                      WHERE id = :id";
                                      
                        $orderStmt = $pdo->prepare($orderQuery);
                        $orderStmt->execute([
                            ':payment_status' => $paymentStatus,
                            ':updated_at' => $now,
                            ':id' => $orderId
                        ]);
                        
                        error_log("Updated {$orderType} #{$orderId} payment status to {$paymentStatus}");
                        
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
                        error_log("Error updating order: " . $e->getMessage());
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
            
            $stmt = $pdo->prepare($query);
            
            // Prepare the execute parameters
            $executeParams = [
                ':transaction_number' => $transactionNumber,
                ':amount' => $amount,
                ':payment_method' => 'SSL Payment',
                ':status' => $internalStatus,
                ':ssl_transaction_id' => $transactionId,
                ':ssl_status' => $status,
                ':notes' => 'Transaction created from SSL callback',
                ':created_at' => $now,
                ':updated_at' => $now
            ];
            
            // Merge with order values if they exist
            if (!empty($orderValues)) {
                $executeParams = array_merge($executeParams, $orderValues);
            }
            
            $stmt->execute($executeParams);
            
            error_log("Created new transaction: {$transactionId} with status: {$status}");
        }
        
    } catch (PDOException $e) {
        error_log("Database error in SSL callback: " . $e->getMessage());
    }
}

// Determine the message based on the status
$message = '';
$alertClass = '';

if (strtoupper($status) === 'VALID' || strtoupper($status) === 'SUCCESS') {
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

// Output a simple success page
echo '<!DOCTYPE html>
<html>
<head>
    <title>Payment Processed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .payment-container { max-width: 600px; margin: 0 auto; padding: 20px; }
    </style>
</head>
<body>
    <div class="container payment-container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Payment Processed</h4>
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
                        </tr>
                    </table>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="/" class="btn btn-primary">Return to Home</a>
                    <a href="/customer/dashboard" class="btn btn-outline-primary">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Automatically redirect to our direct payment success page after 3 seconds
    setTimeout(function() {
        window.location.href = "/payment_success.php?tran_id=' . urlencode($transactionId) . '&status=' . urlencode($status) . '&type=' . urlencode($valueA ?? '') . '&id=' . urlencode($valueB ?? '') . '";
    }, 3000);
    </script>
</body>
</html>';

// End the script
exit;
