<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $loan = null;
        $nextSchedule = null;
        $remainingMonths = 0;
        $remainingBalance = 0;
        $adminLoanCount = 0;
        $adminCustomerCount = 0;
        $adminOverdueCount = 0;
        $adminLoans = collect();

        if (Auth::user()->role === 'customer') {
            $loan = Loan::with(['category', 'schedules.repayments'])
                ->whereHas('customer', fn ($query) => $query->where('user_id', Auth::id()))
                ->latest()
                ->first();

            if ($loan) {
                $unpaidSchedules = $loan->schedules
                    ->filter(fn ($schedule): bool => $schedule->status !== 'Paid')
                    ->sortBy('due_date')
                    ->values();

                $nextSchedule = $unpaidSchedules->first();
                $remainingMonths = $unpaidSchedules->count();
                $remainingBalance = $unpaidSchedules->sum(function ($schedule): float {
                    return max(
                        (float) $schedule->total_due - (float) $schedule->repayments->sum('amount_paid'),
                        0,
                    );
                });
            }
        }

        if (Auth::user()->role !== 'customer') {
            $adminLoans = Loan::with(['customer', 'category'])
                ->latest()
                ->get();
            $adminLoanCount = $adminLoans->count();
            $adminCustomerCount = $adminLoans->pluck('customer_id')->unique()->count();
            $adminOverdueCount = LoanSchedule::where('status', 'Overdue')->count();
        }

        return view('dashboard', compact(
            'loan',
            'nextSchedule',
            'remainingMonths',
            'remainingBalance',
            'adminLoanCount',
            'adminCustomerCount',
            'adminOverdueCount',
            'adminLoans',
        ));
    }
}
