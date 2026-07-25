# Paper Boy 📰

Paper Boy is a comprehensive Newspaper Agency Management System built on Laravel 13. It is designed to automate and streamline the operations of newspaper distributors and agencies, handling everything from daily subscriptions to vendor settlements.

## Features

- **Customer & Subscription Management:** Track customers, their active subscriptions, delivery charges, and manage their invoices.
- **Vendor Purchases (Distributor Ledger):** Record daily newspaper purchases from publishers (e.g., Amar Ujala, Dainik Jagran), manage returns of unsold copies, and track outstanding balances.
- **Automated Billing & Invoicing:** Easily generate monthly bills for all active subscriptions with a single click or automated cron job.
- **Payment Processing:** Record partial and full payments for customer invoices and settle outstanding vendor arrears.
- **Expense Tracking:** Log and monitor day-to-day agency expenses.
- **Intelligent Notifications:** Automated end-of-month system alerts notify administrators of any customers with unpaid invoices or vendors with pending balances.
- **Global Search:** Find any customer, invoice, or newspaper instantly across the entire system.
- **Modern UI:** Built with TailwindCSS and Alpine.js, featuring responsive design and Dark/Light mode support.

## Automated Scheduled Tasks

This system relies on Laravel's Scheduler for automated end-of-month routines. The following commands run automatically:

- `php artisan app:generate-invoices`
  - Runs at midnight on the 1st of every month to generate bills for all active customers.
- `php artisan notify:unpaid-balances`
  - Runs at 11:55 PM on the last day of the month to check for and notify administrators of unpaid customer invoices and pending vendor settlements.

To test these features locally without waiting, you can run the commands manually.

## Local Setup

1. **Clone the repository and install dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

2. **Environment Configuration:**
   Copy the `.env.example` file to `.env` and configure your database settings. Make sure `APP_URL` matches your local development environment (e.g., `http://localhost/paper-boy/public`).

3. **Database Setup:**
   Run migrations and seed the database with demo data:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Serve the Application:**
   If you're not using a local server like XAMPP or Valet, use Laravel's built-in server:
   ```bash
   php artisan serve
   ```

## Tech Stack
- **Backend:** Laravel 13 (PHP)
- **Frontend:** Blade, TailwindCSS, Alpine.js
- **Database:** MySQL
