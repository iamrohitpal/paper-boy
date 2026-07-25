<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the automatic generation of invoices on the 1st of every month at midnight
Schedule::command('app:generate-invoices')->monthlyOn(1, '00:00');

// Schedule notification of unpaid balances on the last day of the month
Schedule::command('notify:unpaid-balances')->lastDayOfMonth('23:55');
