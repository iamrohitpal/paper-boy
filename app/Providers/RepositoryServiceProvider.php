<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\ExtraNewspaperRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Repositories\Interfaces\ExtraNewspaperRepositoryInterface;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\NewspaperRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\NewspaperRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\SubscriptionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NewspaperRepositoryInterface::class, NewspaperRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(ExtraNewspaperRepositoryInterface::class, ExtraNewspaperRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
