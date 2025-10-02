<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction, string $oldStatus, string $newStatus)
    {
        $this->transaction = $transaction;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get customer information
            $customer = $this->getCustomer();
            
            if (!$customer) {
                Log::warning('No customer found for payment notification', [
                    'transaction_id' => $this->transaction->id
                ]);
                return;
            }

            // Create in-app notification
            $this->createInAppNotification($customer);
            
            // Send email notification if customer has email
            if ($customer->email) {
                $this->sendEmailNotification($customer);
            }
            
            Log::info('Payment status notification sent', [
                'transaction_id' => $this->transaction->id,
                'customer_id' => $customer->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send payment status notification', [
                'transaction_id' => $this->transaction->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Get the customer associated with this transaction
     */
    private function getCustomer(): ?User
    {
        // Try to get customer from different order types
        if ($this->transaction->packageOrder && $this->transaction->packageOrder->user) {
            return $this->transaction->packageOrder->user;
        }
        
        if ($this->transaction->serviceOrder && $this->transaction->serviceOrder->user) {
            return $this->transaction->serviceOrder->user;
        }
        
        if ($this->transaction->fundRequest && $this->transaction->fundRequest->user) {
            return $this->transaction->fundRequest->user;
        }
        
        if ($this->transaction->customServiceRequest && $this->transaction->customServiceRequest->user) {
            return $this->transaction->customServiceRequest->user;
        }
        
        return null;
    }

    /**
     * Create in-app notification
     */
    private function createInAppNotification(User $customer): void
    {
        $title = $this->getNotificationTitle();
        $message = $this->getNotificationMessage();
        $type = $this->getNotificationType();
        
        Notification::create([
            'user_id' => $customer->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => json_encode([
                'transaction_id' => $this->transaction->id,
                'transaction_number' => $this->transaction->transaction_number,
                'amount' => $this->transaction->amount,
                'currency' => $this->transaction->currency,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'order_type' => $this->getOrderType(),
                'order_id' => $this->getOrderId()
            ]),
            'read_at' => null
        ]);
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(User $customer): void
    {
        try {
            $subject = $this->getEmailSubject();
            $emailData = [
                'customer_name' => $customer->name,
                'transaction_number' => $this->transaction->transaction_number,
                'amount' => $this->transaction->amount,
                'currency' => $this->transaction->currency,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'order_type' => $this->getOrderType(),
                'order_details' => $this->getOrderDetails(),
                'status_message' => $this->getStatusMessage()
            ];
            
            // Send email using Laravel's mail system
            Mail::send('emails.payment-status-update', $emailData, function ($message) use ($customer, $subject) {
                $message->to($customer->email, $customer->name)
                        ->subject($subject);
            });
            
        } catch (\Exception $e) {
            Log::error('Failed to send payment status email', [
                'transaction_id' => $this->transaction->id,
                'customer_email' => $customer->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get notification title based on status
     */
    private function getNotificationTitle(): string
    {
        switch ($this->newStatus) {
            case 'success':
            case 'completed':
                return 'Payment Confirmed';
            case 'failed':
                return 'Payment Failed';
            case 'pending':
                return 'Payment Processing';
            default:
                return 'Payment Status Updated';
        }
    }

    /**
     * Get notification message
     */
    private function getNotificationMessage(): string
    {
        $orderType = $this->getOrderType();
        $amount = $this->transaction->amount . ' ' . $this->transaction->currency;
        
        switch ($this->newStatus) {
            case 'success':
            case 'completed':
                return "Your payment of {$amount} for {$orderType} has been confirmed successfully.";
            case 'failed':
                return "Your payment of {$amount} for {$orderType} has failed. Please try again or contact support.";
            case 'pending':
                return "Your payment of {$amount} for {$orderType} is being processed. We'll notify you once it's confirmed.";
            default:
                return "Your payment status for {$orderType} has been updated to {$this->newStatus}.";
        }
    }

    /**
     * Get notification type
     */
    private function getNotificationType(): string
    {
        switch ($this->newStatus) {
            case 'success':
            case 'completed':
                return 'success';
            case 'failed':
                return 'error';
            case 'pending':
                return 'info';
            default:
                return 'info';
        }
    }

    /**
     * Get email subject
     */
    private function getEmailSubject(): string
    {
        $orderType = $this->getOrderType();
        
        switch ($this->newStatus) {
            case 'success':
            case 'completed':
                return "Payment Confirmed - {$orderType} Order #{$this->transaction->transaction_number}";
            case 'failed':
                return "Payment Failed - {$orderType} Order #{$this->transaction->transaction_number}";
            case 'pending':
                return "Payment Processing - {$orderType} Order #{$this->transaction->transaction_number}";
            default:
                return "Payment Status Update - {$orderType} Order #{$this->transaction->transaction_number}";
        }
    }

    /**
     * Get order type for display
     */
    private function getOrderType(): string
    {
        if ($this->transaction->packageOrder) {
            return 'Package';
        } elseif ($this->transaction->serviceOrder) {
            return 'Service';
        } elseif ($this->transaction->fundRequest) {
            return 'Fund Request';
        } elseif ($this->transaction->customServiceRequest) {
            return 'Custom Service';
        }
        
        return 'Order';
    }

    /**
     * Get order ID
     */
    private function getOrderId(): ?int
    {
        if ($this->transaction->packageOrder) {
            return $this->transaction->packageOrder->id;
        } elseif ($this->transaction->serviceOrder) {
            return $this->transaction->serviceOrder->id;
        } elseif ($this->transaction->fundRequest) {
            return $this->transaction->fundRequest->id;
        } elseif ($this->transaction->customServiceRequest) {
            return $this->transaction->customServiceRequest->id;
        }
        
        return null;
    }

    /**
     * Get order details for email
     */
    private function getOrderDetails(): array
    {
        if ($this->transaction->packageOrder) {
            return [
                'type' => 'Package',
                'name' => $this->transaction->packageOrder->package->name ?? 'Package Order',
                'duration' => $this->transaction->packageOrder->package->duration_days ?? null
            ];
        } elseif ($this->transaction->serviceOrder) {
            return [
                'type' => 'Service',
                'name' => $this->transaction->serviceOrder->service->name ?? 'Service Order',
                'duration' => $this->transaction->serviceOrder->service->duration_days ?? null
            ];
        } elseif ($this->transaction->fundRequest) {
            return [
                'type' => 'Fund Request',
                'name' => 'Account Balance Top-up',
                'amount' => $this->transaction->fundRequest->amount
            ];
        } elseif ($this->transaction->customServiceRequest) {
            return [
                'type' => 'Custom Service',
                'name' => $this->transaction->customServiceRequest->title ?? 'Custom Service Request',
                'description' => $this->transaction->customServiceRequest->description ?? null
            ];
        }
        
        return [
            'type' => 'Order',
            'name' => 'Transaction #' . $this->transaction->transaction_number
        ];
    }

    /**
     * Get status message for customer
     */
    private function getStatusMessage(): string
    {
        switch ($this->newStatus) {
            case 'success':
            case 'completed':
                return 'Your order has been activated and is now ready for use.';
            case 'failed':
                return 'Please contact our support team if you need assistance with your payment.';
            case 'pending':
                return 'We are processing your payment and will update you shortly.';
            default:
                return 'Thank you for your business.';
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Payment status notification job failed permanently', [
            'transaction_id' => $this->transaction->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'error' => $exception->getMessage()
        ]);
    }
}