<?php
/**
 * Script to create a test transaction in the database
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

// Get form data
$amount = $_POST['amount'] ?? 1000;
$paymentMethod = $_POST['payment_method'] ?? 'SSL Payment';
$status = $_POST['status'] ?? 'pending';
$sslTransactionId = $_POST['ssl_transaction_id'] ?? 'TEST_TRAN_' . time();
$sslStatus = $_POST['ssl_status'] ?? 'VALID';

// Generate a transaction number
function generateTransactionNumber() {
    return 'TXN' . strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
}

// HTML header
echo '<!DOCTYPE html>
<html>
<head>
    <title>Create Test Transaction</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 50px; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Create Test Transaction</h1>';

try {
    // Connect to the database
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}",
        $db['username'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Insert transaction
    $transactionNumber = generateTransactionNumber();
    $now = date('Y-m-d H:i:s');
    
    $query = "INSERT INTO transactions (
                transaction_number, 
                amount, 
                payment_method, 
                status, 
                ssl_transaction_id, 
                ssl_status, 
                notes,
                created_at,
                updated_at
              ) VALUES (
                :transaction_number,
                :amount,
                :payment_method,
                :status,
                :ssl_transaction_id,
                :ssl_status,
                :notes,
                :created_at,
                :updated_at
              )";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':transaction_number' => $transactionNumber,
        ':amount' => $amount,
        ':payment_method' => $paymentMethod,
        ':status' => $status,
        ':ssl_transaction_id' => $sslTransactionId,
        ':ssl_status' => $sslStatus,
        ':notes' => 'Test transaction created via create_test_transaction.php',
        ':created_at' => $now,
        ':updated_at' => $now
    ]);
    
    $transactionId = $pdo->lastInsertId();
    
    echo '<div class="alert alert-success">
            <h4>Transaction Created Successfully</h4>
            <p>Transaction ID: ' . $transactionId . '</p>
            <p>Transaction Number: ' . htmlspecialchars($transactionNumber) . '</p>
            <p>SSL Transaction ID: ' . htmlspecialchars($sslTransactionId) . '</p>
          </div>';
    
    echo '<div class="mt-4">
            <a href="check_transactions.php" class="btn btn-primary">Back to Transaction Check</a>
          </div>';
    
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">
            <h4>Database Error</h4>
            <p>' . htmlspecialchars($e->getMessage()) . '</p>
          </div>';
    
    echo '<div class="mt-4">
            <a href="check_transactions.php" class="btn btn-primary">Back to Transaction Check</a>
          </div>';
}

echo '</div>
</body>
</html>';
