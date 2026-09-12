@extends('Layouts.app')

@section('title', "{$employee->first_name} {$employee->last_name}")

@push('styles')
<style>
    .badge-active { background-color: #d1fae5 !important; color: #065f46 !important; }
    .badge-inactive { background-color: #fef3c7 !important; color: #92400e !important; }
    .badge-resigned { background-color: #e0e7ff !important; color: #3730a3 !important; }
    .badge-terminated { background-color: #fee2e2 !important; color: #991b1b !important; }
</style>
@endpush

@section('main')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 font-weight-bold mb-0 text-dark">Employee Details</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 700px;">
    <div class="card-body p-4">

        @if ($employee->photo)
            <div class="mb-4 text-center text-sm-start">
                <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo"
                     class="rounded-circle border"
                     style="height: 100px; width: 100px; object-fit: cover;">
            </div>
        @endif

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Full Name</div>
            <div class="col-sm-8 text-dark">{{ $employee->first_name }} {{ $employee->last_name }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Gender</div>
            <div class="col-sm-8 text-dark">{{ $employee->gender }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Date of Birth</div>
            <div class="col-sm-8 text-dark">{{ $employee->date_of_birth ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Email</div>
            <div class="col-sm-8 text-dark">{{ $employee->email }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Phone</div>
            <div class="col-sm-8 text-dark">{{ $employee->phone ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Address</div>
            <div class="col-sm-8 text-dark">{{ $employee->address ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Position</div>
            <div class="col-sm-8 text-dark">{{ $employee->position ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Department</div>
            <div class="col-sm-8 text-dark">{{ $employee->department ?? '-' }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Hire Date</div>
            <div class="col-sm-8 text-dark">{{ $employee->hire_date }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Salary</div>
            <div class="col-sm-8 text-dark">${{ number_format($employee->salary, 2) }}</div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-4 font-weight-bold text-secondary">Status</div>
            <div class="col-sm-8">
                @php
                    $badgeClass = match($employee->status) {
                        'Active'     => 'badge-active',
                        'Inactive'   => 'badge-inactive',
                        'Resigned'   => 'badge-resigned',
                        'Terminated' => 'badge-terminated',
                        default      => 'bg-secondary',
                    };
                @endphp
                <span class="badge px-3 py-1 rounded-pill {{ $badgeClass }}">{{ $employee->status }}</span>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">Back to list</a>
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm text-white">Edit</a>
        </div>

    </div>
</div>
@endsection