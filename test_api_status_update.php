<?php

/**
 * Test script for SSL Gateway Transaction Status Update API
 * 
 * This script demonstrates how to use the API endpoints to update
 * transaction statuses from SSL gateway callbacks.
 */

require_once 'vendor/autoload.php';

// Configuration
$baseUrl = 'http://localhost:8000'; // Your Laravel app URL
$apiKey = 'your_ssl_gateway_api_key_here'; // Set this in your .env file

// Test data for successful transaction
$successData = [
    'transaction_id' => 'WD123456789', // Your internal transaction ID
    'ssl_transaction_id' => 'SSL240115103000TEST123456',
    'status' => 'success',
    'gateway_response' => [
        'status' => 'VALID',
        'tran_date' => '2024-01-15 10:30:00',
        'tran_id' => 'SSL240115103000TEST123456',
        'val_id' => '240115103000TEST123456',
        'amount' => '100.00',
        'store_amount' => '97.50',
        'currency' => 'BDT',
        'bank_tran_id' => '240115103000TEST',
        'card_type' => 'VISA-Dutch Bangla',
        'card_no' => '432149XXXXXX0667',
        'card_issuer' => 'STANDARD CHARTERED BANK',
        'card_brand' => 'VISA',
        'verify_sign' => 'd42c3a2985f2a2e6e8b6e8b6e8b6e8b6',
    ],
    'bank_transaction_id' => '240115103000TEST',
    'card_type' => 'VISA-Dutch Bangla',
    'card_no' => '432149XXXXXX0667',
    'card_issuer' => 'STANDARD CHARTERED BANK',
    'currency_type' => 'BDT',
    'currency_amount' => 100.00,
];

// Test data for failed transaction
$failedData = [
    'transaction_id' => 'WD123456790',
    'ssl_transaction_id' => 'SSL240115103001FAIL123456',
    'status' => 'failed',
    'fail_reason' => 'Insufficient funds in customer account',
    'gateway_response' => [
        'status' => 'FAILED',
        'tran_date' => '2024-01-15 10:31:00',
        'tran_id' => 'SSL240115103001FAIL123456',
        'fail_reason' => 'Insufficient funds',
        'amount' => '100.00',
        'currency' => 'BDT',
    ],
];

/**
 * Send API request to update transaction status
 */
function updateTransactionStatus($url, $data, $apiKey) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-API-Key: ' . $apiKey,
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false, // For local testing only
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        return ['error' => 'cURL Error: ' . $error];
    }
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

/**
 * Get transaction status
 */
function getTransactionStatus($url, $sslTransactionId, $apiKey) {
    $ch = curl_init();
    
    $queryUrl = $url . '?' . http_build_query(['ssl_transaction_id' => $sslTransactionId]);
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $queryUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'X-API-Key: ' . $apiKey,
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false, // For local testing only
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        return ['error' => 'cURL Error: ' . $error];
    }
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

echo "=== SSL Gateway Transaction Status Update API Test ===\n\n";

// Test 1: Update transaction status to success
echo "1. Testing successful transaction status update...\n";
$result = updateTransactionStatus(
    $baseUrl . '/api/ssl-gateway/transaction/status',
    $successData,
    $apiKey
);

echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Update transaction status to failed
echo "2. Testing failed transaction status update...\n";
$result = updateTransactionStatus(
    $baseUrl . '/api/ssl-gateway/transaction/status',
    $failedData,
    $apiKey
);

echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Get transaction status
echo "3. Testing get transaction status...\n";
$result = getTransactionStatus(
    $baseUrl . '/api/ssl-gateway/transaction/status',
    'SSL240115103000TEST123456',
    $apiKey
);

echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 4: Test with invalid API key
echo "4. Testing with invalid API key...\n";
$result = updateTransactionStatus(
    $baseUrl . '/api/ssl-gateway/transaction/status',
    $successData,
    'invalid_api_key'
);

echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== Test completed ===\n";
echo "\nNOTE: Make sure to:\n";
echo "1. Set SSL_GATEWAY_API_KEY in your .env file\n";
echo "2. Have actual transaction records in your database\n";
echo "3. Update the transaction IDs in this script to match your data\n";
echo "4. Run your Laravel server (php artisan serve)\n";