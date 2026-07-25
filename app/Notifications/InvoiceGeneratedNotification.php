<?php

namespace App\Notifications;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // For local development or database-only tracking we use 'database' or 'mail'
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your New Invoice is Ready - '.$this->invoice->invoice_number)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your invoice for the period '.Carbon::parse($this->invoice->billing_month)->format('F Y').' has been generated.')
            ->line('Total Amount Due: ₹'.number_format($this->invoice->total_amount, 2))
            ->action('View Invoice', route('invoices.show', $this->invoice->id))
            ->line('Thank you for your business!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->total_amount,
            'message' => 'Invoice '.$this->invoice->invoice_number.' generated for ₹'.number_format($this->invoice->total_amount, 2),
        ];
    }
}
