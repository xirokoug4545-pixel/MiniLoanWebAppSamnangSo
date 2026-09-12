<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('schedules:mark-overdue')]
#[Description('Mark unpaid schedules past their due date as overdue')]
class MarkOverdueSchedules extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\LoanSchedule::where('status', '!=', 'Paid')
        ->where('due_date', '<', now()->toDateString())
        ->update(['status' => 'Overdue']);

        $this->info('Overdue schedules updated successfully.');
    }
}
