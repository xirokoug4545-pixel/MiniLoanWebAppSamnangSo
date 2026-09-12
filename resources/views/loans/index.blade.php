@extends('Layouts.app')

@section('title', 'Loan Management')

@section('main')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 font-weight-bold mb-0 text-dark">Loan Management</h2>
        @if(auth()->user()->role === 'customer')
            <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> Apply New Loan</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 mb-3">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Loan Type</th>
                        <th>Amount</th>
                        <th>Interest</th>
                        <th>Term</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td><strong>#{{ $loan->id }}</strong></td>
                            <td>{{ $loan->customer->first_name }} {{ $loan->customer->last_name }}</td>
                            <td>{{ $loan->category?->name ?? 'Not specified' }}</td>
                            <td>${{ number_format($loan->principal_amount, 2) }}</td>
                            <td>{{ $loan->interest_rate }}% / month</td>
                            <td>{{ $loan->term_months }} Months</td>
                            <td>
                                @php
                                    $badgeClass = match($loan->status) {
                                        'Pending'   => 'bg-warning text-dark',
                                        'Approved'  => 'bg-info text-white',
                                        'Disbursed' => 'bg-success text-white',
                                        'Completed' => 'bg-secondary text-white',
                                        'Rejected'  => 'bg-danger text-white',
                                        default     => 'bg-light text-dark'
                                    };
                                    $badgeStyle = match($loan->status) {
                                        'Pending'   => 'background-color: #facc15; color: #713f12;',
                                        'Approved'  => 'background-color: #0ea5e9; color: #ffffff;',
                                        'Disbursed' => 'background-color: #16a34a; color: #ffffff;',
                                        'Completed' => 'background-color: #64748b; color: #ffffff;',
                                        'Rejected'  => 'background-color: #dc2626; color: #ffffff;',
                                        default     => 'background-color: #e2e8f0; color: #1e293b;'
                                    };
                                @endphp
                                <span class="badge px-3 py-1 rounded-pill {{ $badgeClass }}" style="{{ $badgeStyle }}">{{ $loan->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-secondary btn-sm">
                                        Details
                                    </a>
                                    {{-- View Schedule --}}
                                    @if(in_array($loan->status, ['Disbursed', 'Completed']))
                                        <a href="{{ route('schedules.show', $loan->id) }}" class="btn btn-info btn-sm text-white">
                                            <i class="bi bi-calendar-table"></i> Schedule
                                        </a>
                                    @endif

                                    {{-- Approve Button (Loan Officer/Admin) --}}
                                    @if(in_array(auth()->user()->role, ['admin', 'loan_officer'], true) && $loan->status === 'Pending')
                                        <form action="{{ route('loans.approve', $loan->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                    @endif

                                    {{-- Disburse Button (Admin/Officer) --}}
                                    @if(auth()->user()->role === 'admin' && $loan->status === 'Approved')
                                        <form action="{{ route('loans.disburse', $loan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Disburse</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No loan records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection