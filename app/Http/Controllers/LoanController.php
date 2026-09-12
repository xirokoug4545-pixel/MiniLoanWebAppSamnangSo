<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Customer;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Http\Requests\DisburseLoanRequest;
use App\Models\LoanSchedule;
use App\Models\Category;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    public function index()
    {
        $query = Loan::with(['customer', 'category'])->latest();

        if (Auth::user()->role === 'customer') {
            $query->whereHas('customer', function ($customerQuery): void {
                $customerQuery->where('user_id', Auth::id())
                    ->orWhere('email', Auth::user()->email);
            });
        }

        $loans = $query->get();

        return view('loans.index', compact('loans'));
    }

    public function show(Loan $loan): View
    {
        $loan->load('customer');

        if (Auth::user()->role === 'customer'
            && $loan->customer
            && $loan->customer->user_id !== Auth::id()
            && $loan->customer->email !== Auth::user()->email) {
            abort(403);
        }

        $loan->load(['customer', 'schedules.repayments']);

        $remainingBalance = $loan->schedules->sum(function (LoanSchedule $schedule): float {
            return max(
                (float) $schedule->total_due - (float) $schedule->repayments->sum('amount_paid'),
                0,
            );
        });

        return view('loans.show', compact('loan', 'remainingBalance'));
    }

    public function pending()
    {
        $loans = Loan::with('customer')->where('status', 'Pending')->latest()->get();

        return view('loans.pending', compact('loans'));
    }

    public function overdue()
    {
        $overdueSchedules = LoanSchedule::with('loan.customer')
            ->where('status', 'Overdue')
            ->latest('due_date')
            ->paginate(15);

        return view('loans.overdue', compact('overdueSchedules'));
    }

    public function overdueDashboard(): View
    {
        $overdueSchedules = LoanSchedule::with('loan.customer')
            ->where('status', 'Overdue')
            ->latest('due_date')
            ->paginate(15);

        return view('dashboard.overdue', compact('overdueSchedules'));
    }

    public function approve(Loan $loan)
    {
        $loan->update(['status' => 'Approved']);

        return redirect()->route('loans.pending')->with('success', 'Loan approved successfully.');
    }

    // Display application form
    public function create()
    {
        $customersQuery = Customer::where('status', 'Active')
            ->orderBy('first_name')
            ->orderBy('last_name');

        if (Auth::user()->role === 'customer') {
            $customersQuery->where(function ($customerQuery): void {
                $customerQuery->where('user_id', Auth::id())
                    ->orWhere('email', Auth::user()->email);
            });
        }

        $customers = $customersQuery->get();
        $categories = Category::orderBy('name')->get();

        return view('loans.apply', compact('customers', 'categories'));
    }

    // Process new loan application
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'     => [
                'required',
                Rule::exists('customers', 'id')->where(fn ($query) => $query->where('status', 'Active')),
            ],
            'category_id'      => ['required', 'exists:categories,id'],
            'principal_amount' => ['required', 'numeric', 'min:100', 'max:1000000'],
            'term_months'     => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $principalAmount = (float) $validated['principal_amount'];
        $interestRate = $principalAmount <= 5000
            ? 2.00
            : ($principalAmount <= 10000 ? 1.70 : 1.50);

        if (Auth::user()->role === 'customer' && ! Customer::whereKey($validated['customer_id'])
            ->where(function ($customerQuery): void {
                $customerQuery->where('user_id', Auth::id())
                    ->orWhere('email', Auth::user()->email);
            })
            ->exists()) {
            abort(403);
        }

        Loan::create([
            'customer_id'      => $validated['customer_id'],
            'category_id'      => $validated['category_id'],
            'principal_amount' => $principalAmount,
            'interest_rate'   => $interestRate,
            'term_months'     => $validated['term_months'],
            'status'          => 'Pending',
            'created_by'      => Auth::id(),
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan application submitted successfully.');
    }


    // Task 4 & 5: Disburse Loan & Auto-Generate Schedule
    public function disburse(DisburseLoanRequest $request, Loan $loan)
    {
        DB::transaction(function () use ($loan): void {
            $loan->update([
                'status' => 'Disbursed',
                'disbursement_date' => now()->toDateString(),
            ]);

            $this->loanService->generateSchedule($loan);
        });

        // Redirect to Amortization Schedule view with success message
        return redirect()->route('schedules.show', $loan->id)
                        ->with('success', 'Loan disbursed and schedule generated successfully.');
    }
}