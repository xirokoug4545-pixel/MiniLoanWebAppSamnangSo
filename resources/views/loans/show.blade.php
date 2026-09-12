@extends('Layouts.app')

@section('title', "Loan #{$loan->id}")

@section('main')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 font-weight-bold mb-0 text-dark">Loan #{{ $loan->id }}</h2>
            <span class="text-muted small">
                {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
            </span>
        </div>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">Back to Loans</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Principal</p>
                    <p class="h5 mb-0">${{ number_format($loan->principal_amount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Interest Rate</p>
                    <p class="h5 mb-0">{{ $loan->interest_rate }}% / month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Status</p>
                    <p class="h5 mb-0">{{ $loan->status }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Remaining Balance</p>
                    <p class="h5 mb-0 text-danger">${{ number_format($remainingBalance, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h3 class="h5 mb-0">Amortization Schedule</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Installment</th>
                            <th>Due Date</th>
                            <th>Principal</th>
                            <th>Interest</th>
                            <th>Total Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loan->schedules as $schedule)
                            <tr>
                                <td>Month {{ $schedule->installment_no }}</td>
                                <td>{{ $schedule->due_date->format('M d, Y') }}</td>
                                <td>${{ number_format($schedule->principal_due, 2) }}</td>
                                <td>${{ number_format($schedule->interest_due, 2) }}</td>
                                <td>${{ number_format($schedule->total_due, 2) }}</td>
                                <td>{{ $schedule->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No schedule generated yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white">
            <h3 class="h5 mb-0">Repayment History</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Installment</th>
                            <th>Amount Paid</th>
                            <th>Payment Date</th>
                            <th>Received By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loan->schedules->flatMap->repayments as $repayment)
                            <tr>
                                <td>Month {{ $repayment->schedule->installment_no }}</td>
                                <td>${{ number_format($repayment->amount_paid, 2) }}</td>
                                <td>{{ $repayment->payment_date->format('M d, Y') }}</td>
                                <td>{{ $repayment->receiver->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No repayments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
