<?php
/**
 * Script to check and display transaction data in the database
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
    <title>Transaction Data Check</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        pre { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Transaction Data Check</h1>';

try {
    // Connect to the database
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
        $db['username'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Check if transactions table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'transactions'");
    $tableExists = $stmt->rowCount() > 0;
    
    echo '<div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Database Connection</h4>
            </div>
            <div class="card-body">
                <p><strong>Connection Status:</strong> <span class="badge bg-success">Connected</span></p>
                <p><strong>Database:</strong> ' . htmlspecialchars($db['database']) . '</p>
                <p><strong>Transactions Table Exists:</strong> ' . ($tableExists ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>') . '</p>
            </div>
        </div>';
    
    if ($tableExists) {
        // Get table structure
        $stmt = $pdo->query("DESCRIBE transactions");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4>Transactions Table Structure</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th>Null</th>
                                <th>Key</th>
                                <th>Default</th>
                                <th>Extra</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($columns as $column) {
            echo '<tr>';
            foreach ($column as $key => $value) {
                echo '<td>' . htmlspecialchars($value ?? 'NULL') . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</tbody>
                    </table>
                </div>
            </div>';
        
        // Count transactions
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM transactions");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo '<div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4>Transaction Count</h4>
                </div>
                <div class="card-body">
                    <p class="display-4 text-center">' . $count . '</p>
                </div>
            </div>';
        
        // Get latest transactions
        if ($count > 0) {
            $stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC LIMIT 10");
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4>Latest Transactions</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Transaction Number</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>SSL Transaction ID</th>
                                        <th>SSL Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>';
            
            foreach ($transactions as $transaction) {
                echo '<tr>
                        <td>' . htmlspecialchars($transaction['id']) . '</td>
                        <td>' . htmlspecialchars($transaction['transaction_number'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($transaction['amount'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($transaction['payment_method'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($transaction['status'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($transaction['ssl_transaction_id'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($transaction['ssl_status'] ?? 'N/A') . '</td>
                        <td>' . htmlspecialchars($transaction['created_at'] ?? 'N/A') . '</td>
                    </tr>';
            }
            
            echo '</tbody>
                            </table>
                        </div>
                    </div>
                </div>';
        }
    }
    
    // Add a form to create a test transaction
    echo '<div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Create Test Transaction</h4>
            </div>
            <div class="card-body">
                <form method="post" action="create_test_transaction.php">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="1000" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="SSL Payment">SSL Payment</option>
                            <option value="Manual Bank Transfer">Manual Bank Transfer</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ssl_transaction_id" class="form-label">SSL Transaction ID</label>
                        <input type="text" class="form-control" id="ssl_transaction_id" name="ssl_transaction_id" value="TEST_TRAN_' . time() . '">
                    </div>
                    <div class="mb-3">
                        <label for="ssl_status" class="form-label">SSL Status</label>
                        <select class="form-control" id="ssl_status" name="ssl_status">
                            <option value="VALID">VALID</option>
                            <option value="FAILED">FAILED</option>
                            <option value="CANCELLED">CANCELLED</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Test Transaction</button>
                </form>
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
