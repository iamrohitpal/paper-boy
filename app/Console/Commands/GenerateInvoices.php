<?php

namespace App\Console\Commands;

use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use Illuminate\Console\Command;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-invoices {--date= : The month date to generate invoices for (Y-m)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate monthly invoices for all active customers';

    /**
     * Execute the console command.
     */
    public function handle(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->info('Starting automated invoice generation...');

        $date = $this->option('date') ?: date('Y-m');
        $this->info("Generating invoices for: {$date}");

        $generatedCount = $invoiceRepository->generateMonthlyInvoices($date);

        $this->info("Successfully generated {$generatedCount} invoices.");
    }
}
