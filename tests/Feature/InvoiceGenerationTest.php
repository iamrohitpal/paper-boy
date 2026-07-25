<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ExtraNewspaper;
use App\Models\Invoice;
use App\Models\Newspaper;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_generation_logic(): void
    {
        $user = User::factory()->create();

        // 1. Create a customer
        $customer = Customer::factory()->create();

        // 2. Create a newspaper
        $newspaper = Newspaper::create([
            'name' => 'Times of India',
            'mrp' => 5.00,
            'purchase_price' => 4.00,
            'selling_price' => 5.00,
            'language' => 'English',
        ]);

        // 3. Create a subscription for the customer
        Subscription::create([
            'customer_id' => $customer->id,
            'newspaper_id' => $newspaper->id,
            'type' => 'Daily',
            'quantity' => 1,
            'price' => 5.00,
            'start_date' => '2023-01-01',
            'status' => 'Active',
        ]);

        // 4. Add an extra newspaper for this month
        ExtraNewspaper::create([
            'customer_id' => $customer->id,
            'newspaper_id' => $newspaper->id,
            'date' => date('Y-m-05'),
            'quantity' => 1,
            'price' => 5.00,
            'is_billed' => false,
        ]);

        // 5. Run the invoice generation for current month
        $invoiceRepository = app(InvoiceRepository::class);
        $month = date('Y-m');
        $invoiceRepository->generateMonthlyInvoices($month);

        // 6. Assert invoice was created
        $this->assertDatabaseCount('invoices', 1);
        $invoice = Invoice::first();

        $this->assertEquals($customer->id, $invoice->customer_id);
        $this->assertEquals('Unpaid', $invoice->status);

        // Ensure extra newspaper was marked as billed
        $this->assertDatabaseHas('extra_newspapers', [
            'customer_id' => $customer->id,
            'is_billed' => true,
        ]);

        // Assert items were added
        $this->assertTrue($invoice->items()->count() > 0);
    }
}
