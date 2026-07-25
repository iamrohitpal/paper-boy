<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnpaidVendorNotification extends Notification
{
    use Queueable;

    protected $publisher;

    protected $amountOwed;

    public function __construct($publisher, $amountOwed)
    {
        $this->publisher = $publisher;
        $this->amountOwed = $amountOwed;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'vendor_unpaid',
            'title' => 'Unpaid Distributor Balance',
            'message' => 'You owe ₹'.number_format($this->amountOwed, 2).' to '.$this->publisher,
            'action_url' => route('purchases.index'),
        ];
    }
}
