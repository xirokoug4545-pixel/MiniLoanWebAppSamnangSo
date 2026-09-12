@extends('Layouts.app')

@section('title', "{$customer->first_name} {$customer->last_name}")

@push('styles')
<style>
    body{font-family: Arial, Helvetica, sans-serif; padding: 20px}
    h1{margin-bottom: 20px}
    .card{max-width: 640px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;background: #fff}
    .row{display: flex;gap: 12px; margin-bottom: 12px;align-items: flex-start}
    .label{width: 160px; flex-shrink: 0;font-weight:600;color: #374151;font-size: 0.9rem}
    .value{color:#111827;font-size: 0.9rem}
    .badge-active { background-color: #d1fae5 !important; color: #065f46 !important; }
    .badge-inactive { background-color: #fef3c7 !important; color: #92400e !important; }
    .badge-resigned { background-color: #e0e7ff !important; color: #3730a3 !important; }
    .badge-terminated { background-color: #fee2e2 !important; color: #991b1b !important; }
</style>
@endpush

@section('main')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 font-weight-bold mb-0 text-dark">Customer Details</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">
        
        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Customer Code</div>
            <div class="col-sm-8 text-dark">{{ $customer->customer_code }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Full Name</div>
            <div class="col-sm-8 text-dark">{{ $customer->first_name }} {{ $customer->last_name }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Gender</div>
            <div class="col-sm-8 text-dark">{{ $customer->gender }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Date of Birth</div>
            <div class="col-sm-8 text-dark">{{ $customer->date_of_birth ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Email</div>
            <div class="col-sm-8 text-dark">{{ $customer->email }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Phone</div>
            <div class="col-sm-8 text-dark">{{ $customer->phone ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Address</div>
            <div class="col-sm-8 text-dark">{{ $customer->address ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">City</div>
            <div class="col-sm-8 text-dark">{{ $customer->city ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Status</div>
            <div class="col-sm-8">
                @php
                    $badgeClass = match($customer->status) {
                        'Active'     => 'badge-active',
                        'Inactive'   => 'badge-inactive',
                        'Resigned'   => 'badge-resigned',
                        'Terminated' => 'badge-terminated',
                        default      => 'bg-secondary',
                    };
                @endphp
                <span class="badge px-3 py-1 rounded-pill {{ $badgeClass }}">{{ $customer->status }}</span>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">Back to list</a>
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm text-white">Edit</a>
        </div>

    </div>
</div>
@endsection