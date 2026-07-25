<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnpaidCustomerNotification extends Notification
{
    use Queueable;

    protected $customerId;

    protected $customerName;

    protected $amountOwed;

    public function __construct($customerId, $customerName, $amountOwed)
    {
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->amountOwed = $amountOwed;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'customer_unpaid',
            'title' => 'Unpaid Customer Balance',
            'message' => $this->customerName.' has an outstanding balance of ₹'.number_format($this->amountOwed, 2),
            'action_url' => route('customers.show', $this->customerId),
        ];
    }
}
