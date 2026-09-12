<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanSchedule;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RepaymentController extends Controller
{
    public function storeForLoan(Request $request, Loan $loan): RedirectResponse
    {
        $schedule = $loan->schedules()
            ->where('status', '!=', 'Paid')
            ->orderBy('installment_no')
            ->firstOrFail();

        return $this->store($request, $schedule);
    }

    public function create(LoanSchedule $schedule): View
    {
        $outstandingAmount = max(
            (float) $schedule->total_due - (float) $schedule->repayments()->sum('amount_paid'),
            0,
        );

        return view('loans.repay', compact('schedule', 'outstandingAmount'));
    }

    public function store(Request $request, LoanSchedule $schedule): RedirectResponse
    {
        $request->validate([
            'amount_paid' => ['required', 'numeric', 'min:0.01'],
        ]);

        $amountPaid = (float) $request->input('amount_paid');
        $currentOutstandingAmount = max(
            (float) $schedule->total_due - (float) $schedule->repayments()->sum('amount_paid'),
            0,
        );

        if ($currentOutstandingAmount <= 0) {
            return back()->with('error', 'This installment is already settled.');
        }

        $schedules = LoanSchedule::with('repayments')
            ->where('loan_id', $schedule->loan_id)
            ->where('installment_no', '>=', $schedule->installment_no)
            ->where('status', '!=', 'Paid')
            ->orderBy('installment_no')
            ->get();

        $remainingBalance = $schedules->sum(function (LoanSchedule $loanSchedule): float {
            return max(
                (float) $loanSchedule->total_due - (float) $loanSchedule->repayments->sum('amount_paid'),
                0,
            );
        });

        if ($amountPaid > $remainingBalance) {
            return back()->with('error', 'Payment amount exceeds the remaining loan balance.');
        }

        DB::transaction(function () use ($schedules, $amountPaid): void {
            $remainingPayment = $amountPaid;

            foreach ($schedules as $loanSchedule) {
                if ($remainingPayment <= 0) {
                    break;
                }

                $outstandingAmount = max(
                    (float) $loanSchedule->total_due - (float) $loanSchedule->repayments->sum('amount_paid'),
                    0,
                );
                $allocatedAmount = min($remainingPayment, $outstandingAmount);

                if ($allocatedAmount <= 0) {
                    continue;
                }

                Repayment::create([
                    'loan_schedule_id' => $loanSchedule->id,
                    'amount_paid' => round($allocatedAmount, 2),
                    'payment_date' => now()->toDateString(),
                    'received_by' => Auth::id(),
                ]);

                if ($allocatedAmount >= $outstandingAmount) {
                    $loanSchedule->update(['status' => 'Paid']);
                }

                $remainingPayment -= $allocatedAmount;
            }
        });

        return redirect()
            ->route('schedules.show', $schedule->loan_id)
            ->with('success', 'Payment recorded successfully.');
    }
}
