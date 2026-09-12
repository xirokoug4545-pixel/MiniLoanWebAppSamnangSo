@extends('Layouts.app')

@section('title', 'Dashboard')

@section('main')
    @if (auth()->user()->role !== 'customer')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="h4 fw-bold mb-1">Operations Dashboard</h2>
                <p class="text-muted mb-0">Monitor loan applications and repayment status.</p>
            </div>
            <a href="{{ route('loans.index') }}" class="btn btn-outline-primary btn-sm">Open Loan Management</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Customers With Applications</p>
                        <h3 class="h2 mb-0">{{ $adminCustomerCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Loan Applications</p>
                        <h3 class="h2 mb-0">{{ $adminLoanCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Overdue Payments</p>
                        <h3 class="h2 mb-0 text-danger">{{ $adminOverdueCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h3 class="h5 mb-0">Loan Management</h3>
                <a href="{{ route('loans.index') }}" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#ID</th>
                            <th>Customer</th>
                            <th>Loan Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($adminLoans as $adminLoan)
                            <tr>
                                <td><strong>#{{ $adminLoan->id }}</strong></td>
                                <td>{{ $adminLoan->customer?->first_name }} {{ $adminLoan->customer?->last_name }}</td>
                                <td>{{ $adminLoan->category?->name ?? 'Not specified' }}</td>
                                <td>${{ number_format($adminLoan->principal_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ match ($adminLoan->status) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Approved' => 'bg-info',
                                        'Disbursed' => 'bg-success',
                                        'Completed' => 'bg-secondary',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                    } }}">{{ $adminLoan->status }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('loans.show', $adminLoan->id) }}" class="btn btn-secondary btn-sm">Details</a>
                                    @if ($adminLoan->status === 'Approved')
                                        @if (auth()->user()->role === 'admin')
                                        <form action="{{ route('loans.disburse', $adminLoan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Disburse</button>
                                        </form>
                                        @endif
                                    @elseif ($adminLoan->status === 'Pending' && in_array(auth()->user()->role, ['admin', 'loan_officer'], true))
                                        <form action="{{ route('loans.approve', $adminLoan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No loan applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif (! $loan)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h4 mb-2">Welcome, {{ auth()->user()->name }}</h2>
                <p class="text-muted mb-3">You do not have a loan application yet.</p>
                <a href="{{ route('loans.create') }}" class="btn btn-primary">Apply for a Loan</a>
            </div>
        </div>
    @else
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="h4 fw-bold mb-1">Welcome, {{ auth()->user()->name }}</h2>
                <p class="text-muted mb-0">
                    {{ $loan->category?->name ?? 'Loan' }} · Loan #{{ $loan->id }} · {{ $loan->status }}
                </p>
            </div>
            <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-outline-primary btn-sm">View Loan Details</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Next Payment</p>
                        @if ($nextSchedule)
                            <h3 class="h5 mb-1">{{ $nextSchedule->due_date->format('M d, Y') }}</h3>
                            <p class="text-primary fw-semibold mb-0">
                                ${{ number_format(max((float) $nextSchedule->total_due - (float) $nextSchedule->repayments->sum('amount_paid'), 0), 2) }}
                            </p>
                        @else
                            <h3 class="h5 mb-0 text-success">All paid</h3>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Months Remaining</p>
                        <h3 class="h4 mb-0">{{ $remainingMonths }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Amount Remaining</p>
                        <h3 class="h4 mb-0">${{ number_format($remainingBalance, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h3 class="h5 mb-0">Payment Schedule</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Month</th>
                            <th>Due Date</th>
                            <th>Principal</th>
                            <th>Interest</th>
                            <th>Total Due</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->schedules->sortBy('installment_no') as $schedule)
                            @php
                                $paidAmount = (float) $schedule->repayments->sum('amount_paid');
                                $scheduleBalance = max((float) $schedule->total_due - $paidAmount, 0);
                            @endphp
                            <tr>
                                <td>Month {{ $schedule->installment_no }}</td>
                                <td>{{ $schedule->due_date->format('M d, Y') }}</td>
                                <td>${{ number_format($schedule->principal_due, 2) }}</td>
                                <td>${{ number_format($schedule->interest_due, 2) }}</td>
                                <td>${{ number_format($schedule->total_due, 2) }}</td>
                                <td>${{ number_format($paidAmount, 2) }}</td>
                                <td>${{ number_format($scheduleBalance, 2) }}</td>
                                <td>
                                    <span class="badge {{ match ($schedule->status) {
                                        'Paid' => 'bg-success',
                                        'Overdue' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    } }}">{{ $schedule->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
