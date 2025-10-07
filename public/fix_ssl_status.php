<?php
/**
 * Script to fix SSL status and payment status issues
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
    <title>Fix SSL Status and Payment Status</title>
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
        <h1 class="mb-4">Fix SSL Status and Payment Status</h1>';

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
        if (isset($_POST['fix_ssl_status'])) {
            echo '<div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4>Fixing SSL Status...</h4>
                    </div>
                    <div class="card-body">';
            
            // 1. Fix SSL status from "VALID" to "SUCCESS"
            $stmt = $pdo->query("
                UPDATE transactions 
                SET ssl_status = 'SUCCESS', status = 'completed' 
                WHERE ssl_status = 'VALID'
            ");
            $sslStatusUpdated = $stmt->rowCount();
            
            echo "<p>Updated {$sslStatusUpdated} transactions from SSL status 'VALID' to 'SUCCESS'</p>";
            
            // 2. Fix payment status for service orders with completed transactions
            $stmt = $pdo->query("
                UPDATE service_orders so
                JOIN transactions t ON t.service_order_id = so.id
                SET so.payment_status = 'paid',
                    so.paid_amount = so.amount,
                    so.due_amount = 0
                WHERE t.status = 'completed' OR t.ssl_status = 'SUCCESS'
            ");
            $serviceOrdersUpdated = $stmt->rowCount();
            
            echo "<p>Updated {$serviceOrdersUpdated} service orders payment status to 'paid'</p>";
            
            // 3. Fix payment status for package orders with completed transactions
            $stmt = $pdo->query("
                UPDATE package_orders po
                JOIN transactions t ON t.package_order_id = po.id
                SET po.payment_status = 'paid',
                    po.paid_amount = po.amount,
                    po.due_amount = 0
                WHERE t.status = 'completed' OR t.ssl_status = 'SUCCESS'
            ");
            $packageOrdersUpdated = $stmt->rowCount();
            
            echo "<p>Updated {$packageOrdersUpdated} package orders payment status to 'paid'</p>";
            
            // 4. Fix payment status for custom service requests with completed transactions
            $stmt = $pdo->query("
                UPDATE custom_service_requests csr
                JOIN transactions t ON t.custom_service_request_id = csr.id
                SET csr.payment_status = 'paid',
                    csr.paid_amount = csr.amount,
                    csr.due_amount = 0
                WHERE t.status = 'completed' OR t.ssl_status = 'SUCCESS'
            ");
            $customServiceRequestsUpdated = $stmt->rowCount();
            
            echo "<p>Updated {$customServiceRequestsUpdated} custom service requests payment status to 'paid'</p>";
            
            // 5. Fix payment status for fund requests with completed transactions
            $stmt = $pdo->query("
                UPDATE fund_requests fr
                JOIN transactions t ON t.fund_request_id = fr.id
                SET fr.payment_status = 'paid',
                    fr.paid_amount = fr.amount,
                    fr.due_amount = 0
                WHERE t.status = 'completed' OR t.ssl_status = 'SUCCESS'
            ");
            $fundRequestsUpdated = $stmt->rowCount();
            
            echo "<p>Updated {$fundRequestsUpdated} fund requests payment status to 'paid'</p>";
            
            // 6. Add payment_status field to custom_service_requests table if it doesn't exist
            try {
                $stmt = $pdo->query("SHOW COLUMNS FROM custom_service_requests LIKE 'payment_status'");
                $columnExists = $stmt->rowCount() > 0;
                
                if (!$columnExists) {
                    $stmt = $pdo->query("ALTER TABLE custom_service_requests ADD COLUMN payment_status VARCHAR(20) DEFAULT NULL AFTER ssl_transaction_id");
                    echo "<p>Added payment_status column to custom_service_requests table</p>";
                }
            } catch (Exception $e) {
                echo "<p class='text-danger'>Error checking/adding payment_status column: " . $e->getMessage() . "</p>";
            }
            
            // 7. Update payment_status for custom service requests with completed transactions
            $stmt = $pdo->query("
                UPDATE custom_service_requests csr
                JOIN transactions t ON t.custom_service_request_id = csr.id
                SET csr.payment_status = 'paid'
                WHERE t.status = 'completed' OR t.ssl_status = 'SUCCESS'
            ");
            $customServiceRequestsUpdated = $stmt->rowCount();
            
            echo "<p>Updated {$customServiceRequestsUpdated} custom service requests payment status to 'paid'</p>";
            
            // 8. Fix transactions with missing customer information
            $stmt = $pdo->query("
                SELECT t.id, t.service_order_id, t.package_order_id, t.custom_service_request_id, t.fund_request_id,
                       t.customer_name, t.customer_email
                FROM transactions t
                WHERE (t.customer_name IS NULL OR t.customer_email IS NULL)
                  AND (t.service_order_id IS NOT NULL OR t.package_order_id IS NOT NULL 
                       OR t.custom_service_request_id IS NOT NULL OR t.fund_request_id IS NOT NULL)
            ");
            $transactionsToFix = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $customerInfoUpdated = 0;
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
                            
                            $customerInfoUpdated++;
                        }
                    }
                }
            }
            
            echo "<p>Updated customer information for {$customerInfoUpdated} transactions</p>";
            
            echo '<div class="alert alert-success mt-3">
                    <h5>Fix Complete</h5>
                    <p>Fixed SSL status and payment status issues</p>
                  </div>';
            
            echo '</div>
                </div>';
        }
    }
    
    // Display form to fix SSL status
    echo '<div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4>Fix SSL Status and Payment Status</h4>
            </div>
            <div class="card-body">
                <p>Click the button below to fix SSL status and payment status issues:</p>
                <form method="post">
                    <button type="submit" name="fix_ssl_status" class="btn btn-primary">Fix SSL Status and Payment Status</button>
                </form>
            </div>
        </div>';
    
    // Display current SSL status distribution
    $stmt = $pdo->query("
        SELECT ssl_status, COUNT(*) as count
        FROM transactions
        WHERE ssl_status IS NOT NULL
        GROUP BY ssl_status
    ");
    $sslStatusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Current SSL Status Distribution</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SSL Status</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>';
    
    foreach ($sslStatusCounts as $statusCount) {
        echo '<tr>
                <td>' . htmlspecialchars($statusCount['ssl_status']) . '</td>
                <td>' . htmlspecialchars($statusCount['count']) . '</td>
              </tr>';
    }
    
    echo '</tbody>
                    </table>
                </div>
            </div>
        </div>';
    
    // Display current payment status distribution for service orders
    $stmt = $pdo->query("
        SELECT payment_status, COUNT(*) as count
        FROM service_orders
        GROUP BY payment_status
    ");
    $paymentStatusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Current Service Order Payment Status Distribution</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment Status</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>';
    
    foreach ($paymentStatusCounts as $statusCount) {
        echo '<tr>
                <td>' . htmlspecialchars($statusCount['payment_status'] ?? 'NULL') . '</td>
                <td>' . htmlspecialchars($statusCount['count']) . '</td>
              </tr>';
    }
    
    echo '</tbody>
                    </table>
                </div>
            </div>
        </div>';
    
    // Display recent transactions
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
    
    echo '<div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4>Recent Transactions</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                </div>
            </div>
        </div>';
    
    // Add links to other tools
    echo '<div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4>Other Tools</h4>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="fix_service_orders.php" class="btn btn-outline-primary">Fix Service Orders</a>
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
