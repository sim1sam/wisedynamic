<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find a customer user
$user = App\Models\User::where('role', 'customer')->first();

if (!$user) {
    echo "No customer user found. Creating one...\n";
    $user = App\Models\User::create([
        'name' => 'Test Customer',
        'email' => 'test@customer.com',
        'password' => bcrypt('password'),
        'role' => 'customer',
        'email_verified_at' => now(),
    ]);
    echo "Created customer user with ID: {$user->id}\n";
}

// Create a test fund request
$fundRequest = App\Models\FundRequest::create([
    'user_id' => $user->id,
    'amount' => 100.00,
    'service_info' => 'Test fund request for SSL payment testing',
    'payment_method' => 'ssl',
    'status' => 'pending'
]);

echo "Created Fund Request ID: {$fundRequest->id}\n";
echo "User ID: {$user->id}\n";
echo "Amount: {$fundRequest->amount}\n";
echo "Status: {$fundRequest->status}\n";