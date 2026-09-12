@extends('Layouts.app')

@section('title', 'Employees')

@push('style')
    <style>
        body{font-family: Arial, Helvetica, sans-serif;padding: 20px}
        h1{margin-bottom: 16px}
        .toolbar{margin-bottom: 14px}
        table{width: 100%; border-collapse: collapse}
        thead{background: #f3f4f6}
        th,td{padding: 10px 12px;border: 1px solid #d1d5db;text-align: left;font-size: 0.9rem}
        tr:hover td{background: #f9fafb}
        .badge{display: inline-block;padding: 2px 8px;border-radius: 12px;font-size: 0.8rem;font-weight: 600}
        .badge-active{background: #d1fae5;color: #065f46 !important;}
        .badge-inactive{background: #fef3c7;color: #92400e !important;}
        .badge-resigned{background: #e0e7ff;color: #3730a3 !important;}
        .badge-terminated{background: #fee2e2;color: #991b1b !important;}
        .btn{display: inline-block;padding: 5px 10px;border-radius: 4px;font-size: 0.82rem;text-decoration: none;cursor: pointer;font-family: inherit}
        .btn-primary{background: #2563eb;color: #fff}
        .btn-warning{background: #d97706;color: #fff}
        .btn-info{background: #0891b2;color: #fff}
        .btn-danger{background: #dc2626;color: #fff}
        .btn:hover{opacity: 0.85}
        .filter-bar{display: flex;flex-wrap: wrap;gap: 8px;align-items: flex-end;margin-bottom: 14px;padding: 12px;background: #f9fafb;border: 1px solid #e5e7eb;border-radius: 6px}
        .filter-bar label{font-size: 0.78rem;color: #374151;display: block;margin-bottom: 3px}
        .filter-bar input,.filter-bar select{padding: 5px 8px;border: 1px solid #d1d5db;border-radius: 4px;font-size: 0.85rem;font-family: inherit}
        .btn-secondary{background: #6b7280;color: #fff}
    </style>

@section('main')
    <h1>Employees</h1>

    @if (session('success'))
        <div class="alert-success">
            {{session('success')}}
        </div>
    @endif

    <div class="toolbar">
        <a href="{{route('employees.create')}}" class="btn btn-primary">+ Create employee</a>
    </div>

    <form method="GET" action="{{route('employees.index')}}" class="filter-bar">
        <div>
            <label for="first_name">First name</label>
            <input type="text" id="first_name" name="first_name" value="{{request('first_name')}}" placeholder="Search...">
        </div>
        <div>
            <label for="last_name">Last name</label>
            <input type="text" id="last_name" name="last_name" value="{{request('last_name')}}" placeholder="Search...">
        </div>
        <div>
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">All</option>
                <option value="Male" @selected(request('gender') === 'Male')>Male</option>
                <option value="Female" @selected(request('gender') === 'Female')>Female</option>
            </select>
        </div>
        <div>
            <label for="order_by">Order By</label>
            <select id="order_by" name="order_by">
                <option value="id" @selected(request('order_by', 'id') === 'id')>ID</option>
                <option value="first_name" @selected(request('order_by', 'first_name') === 'first_name')>First name</option>
                <option value="last_name" @selected(request('order_by', 'last_name') === 'last_name')>Last name</option>
                <option value="hire_date" @selected(request('order_by', 'hire_date') === 'hire_date')>Hire Date</option>
                <option value="salary" @selected(request('order_by', 'salary') === 'salary')>Salary</option>
            </select>
        </div>
        <div>
            <label for="order_dir">Direction</label>
            <select id="order_dir" name="order_dir">
                <option value="asc" @selected(request('order_dir', 'asc') === 'asc')>Asc</option>
                <option value="desc" @selected(request('order_dir', 'desc') === 'desc')>Desc</option>
            </select>
        </div>
        <div>
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{route('employees.index')}}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Position</th>
                <th>Department</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Hire Date</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        @if ($employee->photo)
                            <img src="{{asset('storage/'. $employee->photo)}}" alt="photo" style="height: 40px; width:40px;object-fit:cover;border-radius:50%">
                            
                        @else
                            <span style="color: #9ca3af">-</span>
                        @endif
                    </td>
                    <td>{{ $employee->first_name}} {{$employee->last_name}}</td>
                    <td>{{ $employee->gender}}</td>
                    <td>{{ $employee->position ?? '-'}}</td>
                    <td>{{ $employee->department ?? '-'}}</td>
                    <td>{{ $employee->email}}</td>
                    <td>{{ $employee->phone ?? '-'}}</td>
                    <td>{{ $employee->hire_date}}</td>
                    <td>{{ number_format($employee->salary, 2)}}</td>
                    <td>
                        @php
                            $badgeClass = match($employee->status) {
                                'Active'    => 'badge-active',
                                'Inactive'  => 'badge-inactive',
                                'Resigned'  => 'badge-resigned',
                                'Terminated'=> 'badge-terminated',
                                default     => '',  
                            }
                        @endphp
                        <span class="badge {{$badgeClass}}">{{$employee->status}}</span>
                    </td>
                    <td style="white-space:nowrap">
                        <a href="{{route('employees.show', $employee->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('employees.edit', $employee->id)}}" class="btn btn-warning">Edit</a>
                        <form method="POST" action="{{route('employees.destroy', $employee->id)}}" style="display: inline" onsubmit="return confirm('Delete this employee?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="empty">No employees found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 16px">{{$employees->links()}}</div>
    @endif
@endsection