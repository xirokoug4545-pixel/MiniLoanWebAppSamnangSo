@extends('Layouts.app')

@section('title', 'Overdue Loans')

@section('main')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 font-weight-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Overdue Payments</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Loan ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Installment No</th>
                    <th>Due Date</th>
                    <th>Amount Due</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($overdueSchedules as $schedule)
                    <tr class="table-danger">
                        <td><strong>#{{ $schedule->loan_id }}</strong></td>
                        <td>{{ $schedule->loan->customer->first_name }} {{ $schedule->loan->customer->last_name }}</td>
                        <td>{{ $schedule->loan->customer->phone ?? 'N/A' }}</td>
                        <td>Month {{ $schedule->installment_no }}</td>
                        <td class="text-danger fw-bold">{{ \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') }}</td>
                        <td><strong>${{ number_format($schedule->total_due, 2) }}</strong></td>
                        <td class="text-end">
                            <a href="{{ route('schedules.show', $schedule->loan_id) }}" class="btn btn-outline-danger btn-sm">
                                View & Pay
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No overdue payments found. Great job!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($overdueSchedules->hasPages())
        <div class="card-footer bg-white border-top-0 pt-3">
            {{ $overdueSchedules->links() }}
        </div>
    @endif
</div>
@endsection