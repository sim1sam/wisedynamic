<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin: 10px 0;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .transaction-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #6c757d;
        }
        .message-box {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">WiseDynamic</div>
            <h2>Payment Status Update</h2>
        </div>

        <p>Dear {{ $customer_name }},</p>

        <p>We're writing to inform you about an update to your payment status.</p>

        <div class="transaction-details">
            <h3>Transaction Details</h3>
            <div class="detail-row">
                <span class="detail-label">Transaction Number:</span>
                <span class="detail-value">#{{ $transaction_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Type:</span>
                <span class="detail-value">{{ $order_type }}</span>
            </div>
            @if(isset($order_details['name']))
            <div class="detail-row">
                <span class="detail-label">Order:</span>
                <span class="detail-value">{{ $order_details['name'] }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <span class="detail-value">{{ $amount }} {{ $currency }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    @if($new_status === 'success' || $new_status === 'completed')
                        <span class="status-badge status-success">Payment Confirmed</span>
                    @elseif($new_status === 'failed')
                        <span class="status-badge status-failed">Payment Failed</span>
                    @elseif($new_status === 'pending')
                        <span class="status-badge status-pending">Payment Processing</span>
                    @else
                        <span class="status-badge">{{ ucfirst($new_status) }}</span>
                    @endif
                </span>
            </div>
        </div>

        <div class="message-box">
            <strong>
                @if($new_status === 'success' || $new_status === 'completed')
                    🎉 Great news!
                @elseif($new_status === 'failed')
                    ⚠️ Payment Issue
                @elseif($new_status === 'pending')
                    ⏳ Processing
                @else
                    📋 Status Update
                @endif
            </strong>
            <p>{{ $status_message }}</p>
        </div>

        @if($new_status === 'success' || $new_status === 'completed')
            <div style="text-align: center;">
                <a href="{{ url('/customer/dashboard') }}" class="button">View Your Dashboard</a>
            </div>
        @elseif($new_status === 'failed')
            <div style="text-align: center;">
                <a href="{{ url('/contact') }}" class="button">Contact Support</a>
            </div>
        @endif

        @if(isset($order_details['description']) && $order_details['description'])
        <div class="message-box">
            <strong>Order Description:</strong>
            <p>{{ $order_details['description'] }}</p>
        </div>
        @endif

        @if(isset($order_details['duration']) && $order_details['duration'])
        <div class="message-box">
            <strong>Service Duration:</strong>
            <p>{{ $order_details['duration'] }} days</p>
        </div>
        @endif

        <p>If you have any questions or concerns about this transaction, please don't hesitate to contact our support team.</p>

        <div class="footer">
            <p>Thank you for choosing WiseDynamic!</p>
            <p>
                <strong>WiseDynamic Support Team</strong><br>
                Email: support@wisedynamic.com<br>
                Phone: +1 (555) 123-4567
            </p>
            <p style="font-size: 12px; color: #999;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>