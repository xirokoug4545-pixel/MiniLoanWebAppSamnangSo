@extends('Layouts.app')

@section('title', "Loan #{$loan->id} Schedule")

@push('styles')
<style>
    .badge-pending { background-color: #fef3c7 !important; color: #92400e !important; }
    .badge-paid { background-color: #d1fae5 !important; color: #065f46 !important; }
    .badge-overdue { background-color: #fee2e2 !important; color: #991b1b !important; }
</style>
@endpush

@section('main')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="h4 font-weight-bold mb-0 text-dark">Repayment Schedule</h2>
        <span class="text-muted small">Loan #{{ $loan->id }} — {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}</span>
    </div>
    <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-sm">Back to Loans</a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 mb-3">{{ session('error') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th># Installment</th>
                    <th>Due Date</th>
                    <th>Principal</th>
                    <th>Interest</th>
                    <th>Total Due</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loan->schedules as $schedule)
                    <tr>
                        <td><strong>Month {{ $schedule->installment_no }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') }}</td>
                        <td>${{ number_format($schedule->principal_due, 2) }}</td>
                        <td>${{ number_format($schedule->interest_due, 2) }}</td>
                        <td><strong>${{ number_format($schedule->total_due, 2) }}</strong></td>
                        <td>
                            @php
                                $badgeClass = match($schedule->status) {
                                    'Pending' => 'badge-pending',
                                    'Paid'    => 'badge-paid',
                                    'Overdue' => 'badge-overdue',
                                    default   => 'bg-secondary'
                                };
                                $badgeStyle = match($schedule->status) {
                                    'Pending' => 'background-color: #facc15; color: #713f12;',
                                    'Paid'    => 'background-color: #16a34a; color: #ffffff;',
                                    'Overdue' => 'background-color: #dc2626; color: #ffffff;',
                                    default   => 'background-color: #64748b; color: #ffffff;'
                                };
                            @endphp
                            <span class="badge px-3 py-1 rounded-pill {{ $badgeClass }}" style="{{ $badgeStyle }}">{{ $schedule->status }}</span>
                        </td>
                        <td class="text-end">
                            @if($schedule->status !== 'Paid')
                                @if(in_array(auth()->user()->role, ['admin', 'cashier'], true))
                                    <a href="{{ route('repayments.create', $schedule->id) }}" class="btn btn-success btn-sm">
                                        Pay ${{ number_format($schedule->total_due, 2) }}
                                    </a>
                                @endif
                            @else
                                <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill"></i> Cleared</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection