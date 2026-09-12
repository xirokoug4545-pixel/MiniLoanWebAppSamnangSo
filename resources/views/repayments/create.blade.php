@extends('Layouts.app')

@section('title', 'Record Repayment')

@section('main')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 font-weight-bold mb-0 text-dark">Record Repayment</h2>
            <span class="text-muted small">Installment #{{ $schedule->installment_no }}</span>
        </div>
        <a href="{{ route('schedules.show', $schedule->loan_id) }}" class="btn btn-secondary btn-sm">Back to Schedule</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 mb-3">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-4">
                <p class="mb-1 text-muted">Total amount due</p>
                <p class="h3 mb-0">${{ number_format($schedule->total_due, 2) }}</p>
            </div>

            <form action="{{ route('repayments.store', $schedule->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="amount_paid" class="form-label">Amount paid</label>
                    <input
                        id="amount_paid"
                        type="number"
                        name="amount_paid"
                        class="form-control"
                        min="{{ $schedule->total_due }}"
                        step="0.01"
                        value="{{ old('amount_paid', $schedule->total_due) }}"
                        required
                    >
                    <div class="form-text">The payment must be at least the total amount due.</div>
                </div>

                <button type="submit" class="btn btn-success">Record Payment</button>
                <a href="{{ route('schedules.show', $schedule->loan_id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
