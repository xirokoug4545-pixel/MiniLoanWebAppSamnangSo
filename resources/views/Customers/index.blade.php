@extends('Layouts.app')

@section('title', 'Customers')

@push('style')
<style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; padding: 24px; margin: 0 auto; max-width: 1400px; background: #f8fafc; color: #1e293b; }
    h1 { margin-bottom: 16px; font-size: 1.75rem; color: #0f172a; }
    .toolbar { margin-bottom: 14px; }
    
    /* Table Full Width */
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    thead { background: #f1f5f9; }
    th, td { padding: 12px 14px; border: 1px solid #e2e8f0; text-align: left; font-size: 0.9rem; }
    tr:hover td { background: #f8fafc; }

    /* Badges & Buttons */
    .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 600; }
    .badge-active { background: #d1fae5 !important; color: #065f46 !important;}
    .badge-inactive { background: #fef3c7 !important; color: #92400e !important; }
    .btn { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 0.82rem; text-decoration: none; cursor: pointer; font-family: inherit; border: none; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-warning { background: #d97706; color: #fff; }
    .btn-info { background: #0891b2; color: #fff; }
    .btn-danger { background: #dc2626; color: #fff; }
    .btn-secondary { background: #6b7280; color: #fff; }
    .btn:hover { opacity: 0.88; }

    /* Alerts & Filters */
    .alert-success { padding: 10px 14px; background: #d1fae5; color: #065f46; border-radius: 4px; margin-bottom: 14px; border: 1px solid #a7f3d0; }
    .filter-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; margin-bottom: 16px; padding: 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .filter-bar > div { flex: 1; min-width: 120px; }
    .filter-bar label { font-size: 0.78rem; font-weight: 600; color: #475569; display: block; margin-bottom: 4px; }
    .filter-bar input, .filter-bar select { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; font-family: inherit; }
    
    /* Summary Boxes */
    .summary-box { display: flex; gap: 16px; margin-bottom: 20px; }
    .summary-card { padding: 16px; border: 1px solid #e2e8f0; border-radius: 6px; flex: 1; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .summary-card h3 { margin: 0 0 8px 0; font-size: 0.9rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-card p { margin: 0; font-size: 1.75rem; font-weight: bold; }

    .pagination-container{
        margin-top:20px;
        text-align:center;
    }

    .pagination-summary{
        margin-bottom:10px;
    }

    .pagination-centered-buttons{
        display:flex;
        justify-content:center;
    }

    .pagination-container nav ul {
        display: flex;
        list-style: none;
        padding: 2;
        margin: 10;
        gap: 4px;
    }

    .pagination-container nav ul li a,
    .pagination-container nav ul li span {
        padding: 6px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        color: #2563eb;
        text-decoration: none;
    }

    .pagination-container nav ul li.active span {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }
</style>

@section('main')
<h1>Customers List</h1>

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Aggregate Summary Cards -->
<div class="summary-box">
    <div class="summary-card">
        <h3>Total Customers</h3>
        <p>{{ $totalCustomers }}</p>
    </div>

    <div class="summary-card">
        <h3>Active Customers</h3>
        <p style="color: #059669;">{{ $activeCustomers }}</p>
    </div>

    <div class="summary-card">
        <h3>Customers by City</h3>
        <ul style="margin: 0; padding-left: 18px; font-size: 0.88rem;">
            @forelse($customersByCity as $cityStat)
                <li>{{ $cityStat->city }}: <strong>{{ $cityStat->total }}</strong></li>
            @empty
                <li>No city data available</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="toolbar">
    <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Create customer</a>
</div>

<!-- Filter Bar -->
<form method="GET" action="{{ route('customers.index') }}" class="filter-bar">
    <div>
        <label for="customer_code">Customer Code</label>
        <input type="text" id="customer_code" name="customer_code" value="{{ request('customer_code') }}" placeholder="Search code...">
    </div>
    <div>
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="{{ request('first_name') }}" placeholder="Search name...">
    </div>
    <div>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="{{ request('last_name') }}" placeholder="Search name...">
    </div>
    <div>
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="{{ request('phone') }}" placeholder="Search phone...">
    </div>
    <div>
        <label for="city">City</label>
        <select id="city" name="city">
            <option value="">All Cities</option>
            @foreach($customersByCity as $cityStat)
                <option value="{{ $cityStat->city }}" @selected(request('city') === $cityStat->city)>
                    {{ $cityStat->city }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">All</option>
            <option value="Active" @selected(request('status') === 'Active')>Active</option>
            <option value="Inactive" @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
    </div>
    <div>
        <label for="order_by">Order By</label>
        <select id="order_by" name="order_by">
            <option value="id" @selected(request('order_by', 'id') === 'id')>ID</option>
            <option value="customer_code" @selected(request('order_by') === 'customer_code')>Customer Code</option>
            <option value="first_name" @selected(request('order_by') === 'first_name')>First name</option>
            <option value="last_name" @selected(request('order_by') === 'last_name')>Last name</option>
            <option value="created_at" @selected(request('order_by') === 'created_at')>Date Created</option>
        </select>
    </div>
    <div>
        <label for="order_dir">Direction</label>
        <select id="order_dir" name="order_dir">
            <option value="desc" @selected(request('order_dir', 'desc') === 'desc')>Desc (Newest First)</option>
            <option value="asc" @selected(request('order_dir') === 'asc')>Asc (Oldest First)</option>
        </select>
    </div>
    <div style="flex: 0 0 auto;">
        <label>&nbsp;</label>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- Table -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Code</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customers as $customer)
            <tr>
                <td>{{ $loop->iteration + ($customers->firstItem() - 1) }}</td>
                <td>{{ $customer->customer_code }}</td>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                <td>{{ $customer->gender }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ $customer->city ?? '-' }}</td>
                <td>
                    @php
                        $badgeClass = match($customer->status) {
                            'Active'    => 'badge-active',
                            'Inactive'  => 'badge-inactive',
                            default     => '',  
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $customer->status }}</span>
                </td>
                <td style="white-space:nowrap">
                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
                    <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" style="display: inline" onsubmit="return confirm('Delete this customer?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #94a3b8;">No customers found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Footer -->
@if ($customers instanceof \Illuminate\Pagination\LengthAwarePaginator && $customers->total() > 0)
    <div class="pagination-container">
        <div class="pagination-summary">
            Showing <strong>{{ $customers->firstItem() }}</strong>
            to <strong>{{ $customers->lastItem() }}</strong>
            of <strong>{{ $customers->total() }}</strong> customers
            (Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }})
        </div>

        <div class="pagination-centered-buttons">
            {{ $customers->links() }}
        </div>
    </div>
@endif
@endsection