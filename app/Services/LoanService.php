<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanSchedule;
use Carbon\Carbon;

class LoanService
{
    public function generateSchedule(Loan $loan): void
    {
        $principal = (float) $loan->principal_amount;
            $monthlyRate = (float) $loan->interest_rate / 100;
        $months = (int) $loan->term_months;

            $monthlyInstallment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months))
            / (pow(1 + $monthlyRate, $months) - 1);
        $balance = $principal;
        $startDate = $loan->disbursement_date ? Carbon::parse($loan->disbursement_date) : Carbon::today();

        for ($installmentNumber = 1; $installmentNumber <= $months; $installmentNumber++) {
            $interestForMonth = $balance * $monthlyRate;
            $principalForMonth = $monthlyInstallment - $interestForMonth;

            if ($installmentNumber === $months) {
                $principalForMonth = $balance;
                $monthlyInstallment = $principalForMonth + $interestForMonth;
            }

            LoanSchedule::create([
                'loan_id' => $loan->id,
                'installment_no' => $installmentNumber,
                'due_date' => $startDate->copy()->addMonths($installmentNumber),
                'principal_due' => round($principalForMonth, 2),
                'interest_due' => round($interestForMonth, 2),
                'total_due' => round($monthlyInstallment, 2),
                'status' => 'Pending',
            ]);

            $balance -= $principalForMonth;
        }
    }
}