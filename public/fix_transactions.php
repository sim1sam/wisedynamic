<?php
/**
 * Script to fix transaction data and order payment statuses
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

// HTML header
echo '<!DOCTYPE html>
<html>
<head>
    <title>Fix Transactions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .card { margin-bottom: 20px; }
        pre { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Fix Transactions</h1>';

try {
    // Connect to the database
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
        $db['username'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo '<div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Database Connection</h4>
            </div>
            <div class="card-body">
                <p><strong>Connection Status:</strong> <span class="badge bg-success">Connected</span></p>
                <p><strong>Database:</strong> ' . htmlspecialchars($db['database']) . '</p>
            </div>
        </div>';
    
    // Check if we're processing a form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['fix_transactions'])) {
            echo '<div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4>Fixing Transactions...</h4>
                    </div>
                    <div class="card-body">';
            
            // Get all transactions
            $stmt = $pdo->query("SELECT * FROM transactions");
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $fixedCount = 0;
            $errors = [];
            
            foreach ($transactions as $transaction) {
                try {
                    // Get transaction ID
                    $transactionId = $transaction['id'];
                    $sslTransactionId = $transaction['ssl_transaction_id'];
                    
                    // Check if this transaction is linked to an order
                    $orderType = null;
                    $orderId = null;
                    
                    if (!empty($transaction['package_order_id'])) {
                        $orderType = 'package';
                        $orderId = $transaction['package_order_id'];
                    } elseif (!empty($transaction['service_order_id'])) {
                        $orderType = 'service';
                        $orderId = $transaction['service_order_id'];
                    } elseif (!empty($transaction['custom_service_request_id'])) {
                        $orderType = 'custom-service';
                        $orderId = $transaction['custom_service_request_id'];
                    } elseif (!empty($transaction['fund_request_id'])) {
                        $orderType = 'fund';
                        $orderId = $transaction['fund_request_id'];
                    }
                    
                    // If no order is linked, try to find one based on the SSL transaction ID
                    if (!$orderType && !$orderId && !empty($sslTransactionId)) {
                        // Check package orders
                        $stmt = $pdo->prepare("SELECT id FROM package_orders WHERE ssl_transaction_id = :ssl_transaction_id");
                        $stmt->execute([':ssl_transaction_id' => $sslTransactionId]);
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($result) {
                            $orderType = 'package';
                            $orderId = $result['id'];
                            
                            // Update transaction with order ID
                            $stmt = $pdo->prepare("UPDATE transactions SET package_order_id = :order_id WHERE id = :transaction_id");
                            $stmt->execute([
                                ':order_id' => $orderId,
                                ':transaction_id' => $transactionId
                            ]);
                            
                            echo "<p>Linked transaction #{$transactionId} to package order #{$orderId}</p>";
                            $fixedCount++;
                        }
                        
                        // Check service orders
                        if (!$orderType) {
                            $stmt = $pdo->prepare("SELECT id FROM service_orders WHERE ssl_transaction_id = :ssl_transaction_id");
                            $stmt->execute([':ssl_transaction_id' => $sslTransactionId]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($result) {
                                $orderType = 'service';
                                $orderId = $result['id'];
                                
                                // Update transaction with order ID
                                $stmt = $pdo->prepare("UPDATE transactions SET service_order_id = :order_id WHERE id = :transaction_id");
                                $stmt->execute([
                                    ':order_id' => $orderId,
                                    ':transaction_id' => $transactionId
                                ]);
                                
                                echo "<p>Linked transaction #{$transactionId} to service order #{$orderId}</p>";
                                $fixedCount++;
                            }
                        }
                        
                        // Check custom service requests
                        if (!$orderType) {
                            $stmt = $pdo->prepare("SELECT id FROM custom_service_requests WHERE ssl_transaction_id = :ssl_transaction_id");
                            $stmt->execute([':ssl_transaction_id' => $sslTransactionId]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($result) {
                                $orderType = 'custom-service';
                                $orderId = $result['id'];
                                
                                // Update transaction with order ID
                                $stmt = $pdo->prepare("UPDATE transactions SET custom_service_request_id = :order_id WHERE id = :transaction_id");
                                $stmt->execute([
                                    ':order_id' => $orderId,
                                    ':transaction_id' => $transactionId
                                ]);
                                
                                echo "<p>Linked transaction #{$transactionId} to custom service request #{$orderId}</p>";
                                $fixedCount++;
                            }
                        }
                        
                        // Check fund requests
                        if (!$orderType) {
                            $stmt = $pdo->prepare("SELECT id FROM fund_requests WHERE ssl_transaction_id = :ssl_transaction_id");
                            $stmt->execute([':ssl_transaction_id' => $sslTransactionId]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($result) {
                                $orderType = 'fund';
                                $orderId = $result['id'];
                                
                                // Update transaction with order ID
                                $stmt = $pdo->prepare("UPDATE transactions SET fund_request_id = :order_id WHERE id = :transaction_id");
                                $stmt->execute([
                                    ':order_id' => $orderId,
                                    ':transaction_id' => $transactionId
                                ]);
                                
                                echo "<p>Linked transaction #{$transactionId} to fund request #{$orderId}</p>";
                                $fixedCount++;
                            }
                        }
                    }
                    
                    // If we found an order, update its payment status
                    if ($orderType && $orderId) {
                        $tableMap = [
                            'package' => 'package_orders',
                            'service' => 'service_orders',
                            'custom-service' => 'custom_service_requests',
                            'fund' => 'fund_requests'
                        ];
                        
                        $table = $tableMap[$orderType];
                        $status = $transaction['status'];
                        
                        // Determine payment status based on transaction status
                        $paymentStatus = ($status === 'completed') ? 'paid' : 
                                        (($status === 'failed') ? 'failed' : 
                                        (($status === 'cancelled') ? 'cancelled' : 'pending'));
                        
                        // Update order payment status
                        $stmt = $pdo->prepare("UPDATE {$table} SET payment_status = :payment_status WHERE id = :id");
                        $stmt->execute([
                            ':payment_status' => $paymentStatus,
                            ':id' => $orderId
                        ]);
                        
                        echo "<p>Updated {$orderType} #{$orderId} payment status to {$paymentStatus}</p>";
                        $fixedCount++;
                    }
                    
                    // Update customer information if missing
                    if (empty($transaction['customer_name']) || empty($transaction['customer_email'])) {
                        // Try to find customer information from the order
                        $userId = null;
                        
                        if ($orderType && $orderId) {
                            $stmt = $pdo->prepare("SELECT user_id FROM {$tableMap[$orderType]} WHERE id = :id");
                            $stmt->execute([':id' => $orderId]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($result && !empty($result['user_id'])) {
                                $userId = $result['user_id'];
                            }
                        }
                        
                        if ($userId) {
                            // Get user information
                            $stmt = $pdo->prepare("SELECT name, email, phone, address FROM users WHERE id = :id");
                            $stmt->execute([':id' => $userId]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($user) {
                                // Update transaction with customer information
                                $stmt = $pdo->prepare("UPDATE transactions SET 
                                                        customer_name = :name,
                                                        customer_email = :email,
                                                        customer_phone = :phone,
                                                        customer_address = :address
                                                      WHERE id = :transaction_id");
                                $stmt->execute([
                                    ':name' => $user['name'],
                                    ':email' => $user['email'],
                                    ':phone' => $user['phone'] ?? null,
                                    ':address' => $user['address'] ?? null,
                                    ':transaction_id' => $transactionId
                                ]);
                                
                                echo "<p>Updated customer information for transaction #{$transactionId}</p>";
                                $fixedCount++;
                            }
                        }
                    }
                    
                } catch (Exception $e) {
                    $errors[] = "Error processing transaction #{$transactionId}: " . $e->getMessage();
                }
            }
            
            echo '<div class="alert alert-success mt-3">
                    <h5>Fix Complete</h5>
                    <p>Fixed ' . $fixedCount . ' issues</p>
                  </div>';
            
            if (!empty($errors)) {
                echo '<div class="alert alert-danger mt-3">
                        <h5>Errors</h5>
                        <ul>';
                foreach ($errors as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>
                      </div>';
            }
            
            echo '</div>
                </div>';
        }
    }
    
    // Display form to fix transactions
    echo '<div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4>Fix Transaction Data</h4>
            </div>
            <div class="card-body">
                <p>Click the button below to fix transaction data and update order payment statuses:</p>
                <form method="post">
                    <button type="submit" name="fix_transactions" class="btn btn-primary">Fix Transactions</button>
                </form>
            </div>
        </div>';
    
    // Display current transaction data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM transactions");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo '<div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Current Transaction Data</h4>
            </div>
            <div class="card-body">
                <p><strong>Total Transactions:</strong> ' . $count . '</p>';
    
    if ($count > 0) {
        $stmt = $pdo->query("SELECT t.*, 
                                    u.name as user_name, u.email as user_email,
                                    CASE
                                        WHEN t.package_order_id IS NOT NULL THEN 'Package Order'
                                        WHEN t.service_order_id IS NOT NULL THEN 'Service Order'
                                        WHEN t.custom_service_request_id IS NOT NULL THEN 'Custom Service'
                                        WHEN t.fund_request_id IS NOT NULL THEN 'Fund Request'
                                        ELSE 'Unknown'
                                    END as order_type,
                                    CASE
                                        WHEN t.package_order_id IS NOT NULL THEN t.package_order_id
                                        WHEN t.service_order_id IS NOT NULL THEN t.service_order_id
                                        WHEN t.custom_service_request_id IS NOT NULL THEN t.custom_service_request_id
                                        WHEN t.fund_request_id IS NOT NULL THEN t.fund_request_id
                                        ELSE NULL
                                    END as order_id
                             FROM transactions t
                             LEFT JOIN users u ON t.customer_name = u.name OR t.customer_email = u.email
                             ORDER BY t.created_at DESC
                             LIMIT 10");
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Transaction #</th>
                            <th>Customer</th>
                            <th>Order Type</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>SSL Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($transactions as $transaction) {
            echo '<tr>
                    <td>' . htmlspecialchars($transaction['id']) . '</td>
                    <td>' . htmlspecialchars($transaction['transaction_number'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($transaction['customer_name'] ?? $transaction['user_name'] ?? 'N/A') . '<br>
                        <small>' . htmlspecialchars($transaction['customer_email'] ?? $transaction['user_email'] ?? 'N/A') . '</small></td>
                    <td>' . htmlspecialchars($transaction['order_type']) . '</td>
                    <td>' . htmlspecialchars($transaction['order_id'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($transaction['amount'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($transaction['status'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($transaction['ssl_status'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($transaction['created_at'] ?? 'N/A') . '</td>
                </tr>';
        }
        
        echo '</tbody>
                </table>
              </div>';
    }
    
    echo '</div>
        </div>';
    
    // Add links to other tools
    echo '<div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4>Other Tools</h4>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="check_transactions.php" class="btn btn-outline-primary">Check Transactions</a>
                    <a href="test_ssl_payment.php" class="btn btn-outline-primary">Test SSL Payment</a>
                    <a href="/" class="btn btn-outline-secondary">Back to Home</a>
                </div>
            </div>
        </div>';
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">
            <h4>Database Connection Error</h4>
            <p>' . htmlspecialchars($e->getMessage()) . '</p>
          </div>';
}

echo '</div>
</body>
</html>';
