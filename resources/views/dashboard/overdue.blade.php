@extends('Layouts.app')

@section('title', 'Overdue Loan Dashboard')

@section('main')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 font-weight-bold mb-0 text-danger">Overdue Loan Installments</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Loan ID</th>
                            <th>Installment</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($overdueSchedules as $schedule)
                            <tr class="table-danger">
                                <td>
                                    {{ $schedule->loan->customer->first_name }}
                                    {{ $schedule->loan->customer->last_name }}
                                </td>
                                <td>{{ $schedule->loan->customer->phone ?? 'N/A' }}</td>
                                <td>#{{ $schedule->loan_id }}</td>
                                <td>Month {{ $schedule->installment_no }}</td>
                                <td>{{ $schedule->due_date->format('M d, Y') }}</td>
                                <td>${{ number_format($schedule->total_due, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('loans.show', $schedule->loan_id) }}" class="btn btn-outline-danger btn-sm">
                                        View Loan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No overdue installments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($overdueSchedules->hasPages())
            <div class="card-footer bg-white border-top-0 pt-3">
                {{ $overdueSchedules->links() }}
            </div>
        @endif
    </div>
@endsection
