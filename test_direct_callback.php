<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create a mock request
$request = new Illuminate\Http\Request([
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
]);

echo "Testing Direct Controller Method Call...\n";
echo "Request Data: " . json_encode($request->all(), JSON_PRETTY_PRINT) . "\n\n";

try {
    // Get fund request before processing
    $fundRequestBefore = App\Models\FundRequest::find(1);
    echo "Fund Request Before Processing:\n";
    echo "ID: " . $fundRequestBefore->id . "\n";
    echo "Status: " . $fundRequestBefore->status . "\n";
    echo "Amount: " . $fundRequestBefore->amount . "\n\n";
    
    // Directly call the controller method
    $controller = new App\Http\Controllers\Customer\PaymentController();
    $response = $controller->sslSuccess($request, 'fund', 1);
    
    echo "Controller Response Type: " . get_class($response) . "\n";
    
    if ($response instanceof Illuminate\Http\RedirectResponse) {
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
        echo "Session Flash Messages: " . json_encode(session()->all(), JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "Response Content: " . $response->getContent() . "\n";
    }
    
    // Check fund request after processing
    $fundRequestAfter = App\Models\FundRequest::find(1);
    echo "\nFund Request After Processing:\n";
    echo "ID: " . $fundRequestAfter->id . "\n";
    echo "Status: " . $fundRequestAfter->status . "\n";
    echo "SSL Transaction ID: " . ($fundRequestAfter->ssl_transaction_id ?? 'null') . "\n";
    echo "SSL Response: " . ($fundRequestAfter->ssl_response ? json_encode($fundRequestAfter->ssl_response) : 'null') . "\n";
    
    // Check if transaction was created
    $transaction = App\Models\Transaction::where('fund_request_id', 1)->latest()->first();
    if ($transaction) {
        echo "\nTransaction Created:\n";
        echo "ID: " . $transaction->id . "\n";
        echo "Transaction Number: " . $transaction->transaction_number . "\n";
        echo "Amount: " . $transaction->amount . "\n";
        echo "Status: " . $transaction->status . "\n";
        echo "Payment Method: " . $transaction->payment_method . "\n";
    } else {
        echo "\nNo transaction found.\n";
    }
    
    // Check user balance
    $user = App\Models\User::find($fundRequestAfter->user_id);
    echo "\nUser Balance: " . $user->balance . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}