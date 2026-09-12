<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function show(Loan $loan): View
    {
        $loan->load(['customer', 'schedules']);

        return view('loans.schedule', compact('loan'));
    }
}