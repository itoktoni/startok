<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPaidPayment;
use App\Models\Payment;
use Illuminate\Console\Command;

class CheckPaidPayments extends Command
{
    protected $signature = 'payment:process';
    protected $description = 'Find paid payments that need subscription/affiliate processing and dispatch jobs';

    public function handle(): int
    {
        $payments = Payment::where('payment_status', 'paid')
            ->get();

        $dispatched = 0;

        foreach ($payments as $payment) {
            ProcessPaidPayment::dispatch($payment->payment_id);
            $dispatched++;
        }

        if ($dispatched > 0) {
            $this->info("Dispatched {$dispatched} ProcessPaidPayment jobs.");
        } else {
            $this->line('No pending paid payments to process.');
        }

        return self::SUCCESS;
    }
}
