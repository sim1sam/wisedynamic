<?php
/**
 * Direct API endpoint to update transaction status without going through Laravel's routing.
 */

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

// Get transaction data from request
$transactionId = $_REQUEST['tran_id'] ?? null;
$status = $_REQUEST['status'] ?? null;
$orderType = $_REQUEST['value_a'] ?? $_REQUEST['type'] ?? null;
$orderId = $_REQUEST['value_b'] ?? $_REQUEST['id'] ?? null;

// Validate required parameters
if (!$transactionId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
    exit;
}

// Connect to the database
try {
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
        $db['username'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Update transaction status
    $query = "UPDATE transactions SET ssl_status = :status, updated_at = NOW() WHERE ssl_transaction_id = :transaction_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':status' => $status,
        ':transaction_id' => $transactionId
    ]);
    
    // Check if any rows were affected
    $rowCount = $stmt->rowCount();
    
    // Update order status if order type and ID are provided
    $orderUpdated = false;
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
                      
            $paymentStatus = (strtoupper($status) === 'VALID' || strtoupper($status) === 'SUCCESS') ? 'paid' : 'pending';
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':payment_status' => $paymentStatus,
                ':id' => $orderId
            ]);
            
            $orderUpdated = $stmt->rowCount() > 0;
        }
    }
    
    // Return response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => $rowCount > 0 ? 'Transaction status updated successfully' : 'No transaction found with the provided ID',
        'rows_affected' => $rowCount,
        'order_updated' => $orderUpdated,
        'order_type' => $orderType,
        'order_id' => $orderId
    ]);
    
} catch (PDOException $e) {
    // Log error
    error_log('Database error: ' . $e->getMessage());
    
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
