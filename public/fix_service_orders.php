<?php
/**
 * Script to fix service orders payment status and display issues
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
    <title>Fix Service Orders</title>
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
        <h1 class="mb-4">Fix Service Orders</h1>';

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
        if (isset($_POST['fix_service_orders'])) {
            echo '<div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4>Fixing Service Orders...</h4>
                    </div>
                    <div class="card-body">';
            
            // 1. Fix service orders with transactions but still showing "Not Paid"
            $stmt = $pdo->query("
                SELECT so.id, so.payment_status, t.status as transaction_status, t.ssl_status
                FROM service_orders so
                JOIN transactions t ON t.service_order_id = so.id
                WHERE (t.status = 'completed' OR t.ssl_status = 'VALID' OR t.ssl_status = 'SUCCESS')
                  AND (so.payment_status != 'paid' OR so.payment_status IS NULL)
            ");
            $ordersToFix = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $fixedCount = 0;
            
            foreach ($ordersToFix as $order) {
                $stmt = $pdo->prepare("UPDATE service_orders SET payment_status = 'paid' WHERE id = :id");
                $stmt->execute([':id' => $order['id']]);
                
                echo "<p>Updated service order #{$order['id']} payment status to 'paid'</p>";
                $fixedCount++;
            }
            
            // 2. Update transactions with missing customer information
            $stmt = $pdo->query("
                SELECT t.id, t.service_order_id, t.package_order_id, t.custom_service_request_id, t.fund_request_id,
                       t.customer_name, t.customer_email
                FROM transactions t
                WHERE (t.customer_name IS NULL OR t.customer_email IS NULL)
                  AND (t.service_order_id IS NOT NULL OR t.package_order_id IS NOT NULL 
                       OR t.custom_service_request_id IS NOT NULL OR t.fund_request_id IS NOT NULL)
            ");
            $transactionsToFix = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($transactionsToFix as $transaction) {
                $userId = null;
                $orderTable = null;
                $orderId = null;
                
                if ($transaction['service_order_id']) {
                    $orderTable = 'service_orders';
                    $orderId = $transaction['service_order_id'];
                } elseif ($transaction['package_order_id']) {
                    $orderTable = 'package_orders';
                    $orderId = $transaction['package_order_id'];
                } elseif ($transaction['custom_service_request_id']) {
                    $orderTable = 'custom_service_requests';
                    $orderId = $transaction['custom_service_request_id'];
                } elseif ($transaction['fund_request_id']) {
                    $orderTable = 'fund_requests';
                    $orderId = $transaction['fund_request_id'];
                }
                
                if ($orderTable && $orderId) {
                    // Get user ID from the order
                    $stmt = $pdo->prepare("SELECT user_id FROM {$orderTable} WHERE id = :id");
                    $stmt->execute([':id' => $orderId]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result && $result['user_id']) {
                        $userId = $result['user_id'];
                        
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
                                ':transaction_id' => $transaction['id']
                            ]);
                            
                            echo "<p>Updated customer information for transaction #{$transaction['id']}</p>";
                            $fixedCount++;
                        }
                    }
                }
            }
            
            // 3. Fix service orders that should show payment buttons
            $stmt = $pdo->query("
                SELECT id, payment_status, status
                FROM service_orders
                WHERE payment_status != 'paid' AND status = 'pending'
            ");
            $pendingOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($pendingOrders as $order) {
                echo "<p>Service order #{$order['id']} should show payment button (payment_status: {$order['payment_status']}, status: {$order['status']})</p>";
            }
            
            echo '<div class="alert alert-success mt-3">
                    <h5>Fix Complete</h5>
                    <p>Fixed ' . $fixedCount . ' issues</p>
                  </div>';
            
            echo '</div>
                </div>';
        }
    }
    
    // Display form to fix service orders
    echo '<div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4>Fix Service Orders</h4>
            </div>
            <div class="card-body">
                <p>Click the button below to fix service orders payment status and display issues:</p>
                <form method="post">
                    <button type="submit" name="fix_service_orders" class="btn btn-primary">Fix Service Orders</button>
                </form>
            </div>
        </div>';
    
    // Display current service orders
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM service_orders");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo '<div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Current Service Orders</h4>
            </div>
            <div class="card-body">
                <p><strong>Total Service Orders:</strong> ' . $count . '</p>';
    
    if ($count > 0) {
        $stmt = $pdo->query("
            SELECT so.*, u.name as user_name, u.email as user_email,
                   CASE WHEN t.id IS NOT NULL THEN 'Yes' ELSE 'No' END as has_transaction
            FROM service_orders so
            LEFT JOIN users u ON so.user_id = u.id
            LEFT JOIN transactions t ON t.service_order_id = so.id
            ORDER BY so.created_at DESC
            LIMIT 10
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Service</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Has Transaction</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($orders as $order) {
            echo '<tr>
                    <td>' . htmlspecialchars($order['id']) . '</td>
                    <td>' . htmlspecialchars($order['service_name'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($order['user_name'] ?? 'N/A') . '<br>
                        <small>' . htmlspecialchars($order['user_email'] ?? 'N/A') . '</small></td>
                    <td>' . htmlspecialchars($order['amount'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($order['status'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($order['payment_status'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($order['has_transaction']) . '</td>
                    <td>' . htmlspecialchars($order['created_at'] ?? 'N/A') . '</td>
                </tr>';
        }
        
        echo '</tbody>
                </table>
              </div>';
    }
    
    echo '</div>
        </div>';
    
    // Display current transactions
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM transactions");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo '<div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Current Transactions</h4>
            </div>
            <div class="card-body">
                <p><strong>Total Transactions:</strong> ' . $count . '</p>';
    
    if ($count > 0) {
        $stmt = $pdo->query("
            SELECT t.*, 
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
            ORDER BY t.created_at DESC
            LIMIT 10
        ");
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
                    <td>' . htmlspecialchars($transaction['customer_name'] ?? 'N/A') . '<br>
                        <small>' . htmlspecialchars($transaction['customer_email'] ?? 'N/A') . '</small></td>
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
                    <a href="fix_transactions.php" class="btn btn-outline-primary">Fix Transactions</a>
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
