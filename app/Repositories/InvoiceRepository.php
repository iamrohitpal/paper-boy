<?php

namespace App\Repositories;

use App\Models\AgencyHoliday;
use App\Models\Customer;
use App\Models\CustomerLeave;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Notifications\InvoiceGeneratedNotification;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function searchAndPaginate($search = null, $perPage = 15)
    {
        $query = $this->model->with(['customer']);

        if ($search) {
            $query->where('invoice_number', 'like', '%'.$search.'%')
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function calculateUnbilledAmount(Customer $customer, Carbon $upToDate)
    {
        // 1. Determine fallback global period start (for invoice record)
        $lastInvoice = $customer->invoices()->latest('period_end')->first();
        if ($lastInvoice && $lastInvoice->period_end) {
            $globalPeriodStart = Carbon::parse($lastInvoice->period_end)->addDay();
        } else {
            // First time billing, find the earliest subscription or extra paper
            $earliestSub = $customer->subscriptions()->min('start_date');
            $earliestExtra = $customer->extraNewspapers()->where('is_billed', false)->min('date');

            if ($earliestSub && $earliestExtra) {
                $globalPeriodStart = Carbon::parse(min($earliestSub, $earliestExtra));
            } elseif ($earliestSub) {
                $globalPeriodStart = Carbon::parse($earliestSub);
            } elseif ($earliestExtra) {
                $globalPeriodStart = Carbon::parse($earliestExtra);
            } else {
                $globalPeriodStart = $upToDate->copy()->startOfMonth(); // Fallback
            }
        }

        $totalAmount = 0;
        $invoiceItems = [];
        $actualPeriodStart = $upToDate->copy();

        // 3. Fetch customer leaves for the period
        $customerLeaves = CustomerLeave::where('customer_id', $customer->id)
            ->where('is_billed', false)
            ->where('date', '<=', $upToDate)
            ->get();

        // 4. Calculate Subscription costs
        $subscriptions = $customer->subscriptions()
            ->whereIn('status', ['Active', 'Paused'])
            ->where('start_date', '<=', $upToDate)
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscriptionLineTotal = 0;
            $deliveredDaysCount = 0;
            
            $subPeriodStart = $subscription->last_billed_date 
                ? Carbon::parse($subscription->last_billed_date)->addDay() 
                : Carbon::parse($subscription->start_date);
                
            if ($subPeriodStart->gt($upToDate)) {
                continue;
            }
            
            if ($subPeriodStart->lt($actualPeriodStart)) {
                $actualPeriodStart = $subPeriodStart->copy();
            }

            $agencyHolidays = AgencyHoliday::whereBetween('date', [$subPeriodStart, $upToDate])
                ->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))->toArray();

            $currentDate = $subPeriodStart->copy();
            while ($currentDate->lte($upToDate)) {
                if ($subscription->end_date && $currentDate->gt($subscription->end_date)) {
                    $currentDate->addDay();

                    continue;
                }
                if ($subscription->status === 'Paused') {
                    $currentDate->addDay();

                    continue;
                }

                $isDeliveryDay = false;
                if ($subscription->delivery_days === 'Daily') {
                    $isDeliveryDay = true;
                } elseif ($subscription->delivery_days === 'Sunday Only' && $currentDate->isSunday()) {
                    $isDeliveryDay = true;
                } elseif ($subscription->delivery_days === 'Custom' && in_array($currentDate->format('l'), $subscription->custom_days ?? [])) {
                    $isDeliveryDay = true;
                }

                if (! $isDeliveryDay) {
                    $currentDate->addDay();

                    continue;
                }

                if (in_array($currentDate->format('Y-m-d'), $agencyHolidays)) {
                    $currentDate->addDay();

                    continue;
                }

                $isLeave = $customerLeaves->where('date', clone $currentDate)
                    ->filter(function ($leave) use ($subscription) {
                        return is_null($leave->newspaper_id) || $leave->newspaper_id == $subscription->newspaper_id;
                    })->isNotEmpty();

                if ($isLeave) {
                    $currentDate->addDay();

                    continue;
                }

                $price = $currentDate->isSunday() && $subscription->price_sunday ? $subscription->price_sunday : $subscription->price;

                $subscriptionLineTotal += ($price * $subscription->quantity);
                $deliveredDaysCount++;

                $currentDate->addDay();
            }

            if ($deliveredDaysCount > 0) {
                $totalAmount += $subscriptionLineTotal;
                $invoiceItems[] = [
                    'description' => 'Subscription: '.$subscription->newspaper->name.' ('.$deliveredDaysCount.' days)',
                    'quantity' => $deliveredDaysCount * $subscription->quantity,
                    'unit_price' => $subscriptionLineTotal / ($deliveredDaysCount * $subscription->quantity),
                    'total' => $subscriptionLineTotal,
                ];
            }
        }

        // 5. Calculate Extra Newspapers costs
        $extraNewspapers = $customer->extraNewspapers()
            ->where('is_billed', false)
            ->where('date', '<=', $upToDate)
            ->get();

        foreach ($extraNewspapers as $extra) {
            $lineTotal = $extra->price; // Total price as saved in DB
            $totalAmount += $lineTotal;

            $invoiceItems[] = [
                'description' => 'Extra: '.$extra->newspaper->name.' ('.Carbon::parse($extra->date)->format('d M').')',
                'quantity' => $extra->quantity,
                'unit_price' => $extra->quantity > 0 ? $extra->price / $extra->quantity : $extra->price,
                'total' => $lineTotal,
                'extra_newspaper_id' => $extra->id,
            ];
            
            $extraDate = Carbon::parse($extra->date);
            if ($extraDate->lt($actualPeriodStart)) {
                $actualPeriodStart = $extraDate->copy();
            }
        }
        
        $finalPeriodStart = $actualPeriodStart->lt($upToDate) ? $actualPeriodStart : $globalPeriodStart;
        if ($finalPeriodStart->gt($upToDate)) {
            $finalPeriodStart = $upToDate->copy()->startOfMonth();
        }

        return [
            'totalAmount' => $totalAmount,
            'invoiceItems' => $invoiceItems,
            'periodStart' => $finalPeriodStart,
            'leaves' => $customerLeaves,
            'extraNewspapers' => $extraNewspapers,
            'subscriptionsToUpdate' => $subscriptions,
        ];
    }

    public function generateInvoiceForCustomer(Customer $customer, Carbon $upToDate)
    {
        $unbilled = $this->calculateUnbilledAmount($customer, $upToDate);

        if ($unbilled['totalAmount'] <= 0) {
            return null; // Nothing to bill
        }

        $invoice = null;

        DB::transaction(function () use ($customer, $upToDate, $unbilled, &$invoice) {
            $invoice = Invoice::create([
                'customer_id' => $customer->id,
                'invoice_number' => 'INV-'.strtoupper(uniqid()),
                'billing_month' => $upToDate->format('Y-m-d'), // Keep for legacy compatibility
                'period_start' => $unbilled['periodStart']->format('Y-m-d'),
                'period_end' => $upToDate->format('Y-m-d'),
                'total_amount' => $unbilled['totalAmount'],
                'due_date' => $upToDate->copy()->addDays(5),
                'status' => 'Unpaid',
            ]);

            foreach ($unbilled['invoiceItems'] as $item) {
                // Remove the extra_newspaper_id tracker before inserting to InvoiceItem
                $extraId = $item['extra_newspaper_id'] ?? null;
                unset($item['extra_newspaper_id']);

                $item['invoice_id'] = $invoice->id;
                InvoiceItem::create($item);
            }

            // Mark leaves as billed
            foreach ($unbilled['leaves'] as $leave) {
                $leave->update(['is_billed' => true]);
            }

            // Mark extra newspapers as billed
            foreach ($unbilled['extraNewspapers'] as $extra) {
                $extra->update(['is_billed' => true]);
            }
            
            // Mark subscriptions as billed up to this date
            if (isset($unbilled['subscriptionsToUpdate'])) {
                foreach ($unbilled['subscriptionsToUpdate'] as $sub) {
                    $sub->update(['last_billed_date' => $upToDate->format('Y-m-d')]);
                }
            }

            // Notify Customer
            $customer->notify(new InvoiceGeneratedNotification($invoice));
        });

        return $invoice;
    }

    public function generateMonthlyInvoices($monthDate)
    {
        $parsedDate = Carbon::parse($monthDate)->startOfMonth();
        $generatedCount = 0;

        DB::transaction(function () use ($parsedDate, &$generatedCount) {
            $customers = Customer::where('status', 'Active')
                ->where('payment_frequency', 'Monthly')
                ->where(function ($q) use ($parsedDate) {
                    $q->whereHas('subscriptions', function ($sub) use ($parsedDate) {
                        $sub->where('status', 'Active')
                            ->where('start_date', '<=', $parsedDate->copy()->endOfMonth());
                    })
                        ->orWhereHas('extraNewspapers', function ($extra) use ($parsedDate) {
                            $extra->where('is_billed', false)
                                ->whereBetween('date', [$parsedDate->copy()->startOfMonth(), $parsedDate->copy()->endOfMonth()]);
                        });
                })->get();

            foreach ($customers as $customer) {
                $existingInvoice = Invoice::where('customer_id', $customer->id)
                    ->where('billing_month', $parsedDate->copy()->endOfMonth()->format('Y-m-d'))
                    ->first();

                if ($existingInvoice) {
                    continue;
                }

                $invoice = $this->generateInvoiceForCustomer($customer, $parsedDate->copy()->endOfMonth());
                if ($invoice) {
                    $generatedCount++;
                }
            }
        });

        return $generatedCount;
    }
}
