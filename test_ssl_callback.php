<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Create a mock request for SSL success callback
$request = Illuminate\Http\Request::create(
    '/customer/payment/ssl/success/fund/1',
    'POST',
    [
        'status' => 'VALID',
        'tran_date' => '2024-01-15 10:30:00',
        'tran_id' => 'TEST123456789',
        'val_id' => '240115103000TEST123456',
        'amount' => '100.00',
        'store_amount' => '97.50',
        'currency' => 'BDT',
        'bank_tran_id' => '240115103000TEST',
        'card_type' => 'VISA-Dutch Bangla',
        'card_no' => '432149XXXXXX0667',
        'card_issuer' => 'STANDARD CHARTERED BANK',
        'card_brand' => 'VISA',
        'card_sub_brand' => 'Classic',
        'card_issuer_country' => 'Bangladesh',
        'card_issuer_country_code' => 'BD',
        'store_id' => 'wisedynamic',
        'verify_sign' => 'd42c3a2985f2a2e6e8b6e8b6e8b6e8b6',
        'verify_key' => 'amount,bank_tran_id,base_fair,card_brand,card_issuer,card_issuer_country,card_issuer_country_code,card_no,card_sub_brand,card_type,currency,currency_amount,currency_rate,currency_type,risk_level,risk_title,status,store_amount,store_id,tran_date,tran_id,val_id,value_a,value_b,value_c,value_d'
    ]
);

echo "Testing SSL Success Callback...\n";
echo "Request URL: " . $request->fullUrl() . "\n";
echo "Request Method: " . $request->method() . "\n";
echo "Request Data: " . json_encode($request->all(), JSON_PRETTY_PRINT) . "\n\n";

try {
    // Process the request through Laravel
    $response = $kernel->handle($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Headers: " . json_encode($response->headers->all(), JSON_PRETTY_PRINT) . "\n";
    echo "Response Content: " . $response->getContent() . "\n";
    
    // Check if fund request was updated
    $fundRequest = App\Models\FundRequest::find(1);
    if ($fundRequest) {
        echo "\nFund Request Status After Callback:\n";
        echo "ID: " . $fundRequest->id . "\n";
        echo "Status: " . $fundRequest->status . "\n";
        echo "SSL Transaction ID: " . ($fundRequest->ssl_transaction_id ?? 'null') . "\n";
        echo "SSL Response: " . ($fundRequest->ssl_response ? json_encode($fundRequest->ssl_response) : 'null') . "\n";
    }
    
    // Check if transaction was created
    $transaction = App\Models\Transaction::where('fund_request_id', 1)->latest()->first();
    if ($transaction) {
        echo "\nTransaction Created:\n";
        echo "ID: " . $transaction->id . "\n";
        echo "Transaction Number: " . $transaction->transaction_number . "\n";
        echo "Amount: " . $transaction->amount . "\n";
        echo "Status: " . $transaction->status . "\n";
        echo "Payment Method: " . $transaction->payment_method . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}

$kernel->terminate($request, $response ?? null);