@extends('Layouts.app')

@section('title', 'Create Customer')

@push('style')
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        .form-group{margin-bottom:12px}
        label{display: block; margin-bottom: 4px; font-weight: 600}
        input[type="text"],input[type="email"],input[type="date"],input[type="number"],select,textarea
        {width: 100%; padding: 8px;border: 1px solid #ccc;border-radius: 4px}
        .error{color: #b91c1c;font-size: 0.95rem;margin-top: 4px}
        .actions{margin-top: 16px}
        .btn-secondary{background: #6b7280;color: #fff}
        .btn-primary { background: #2563eb; color: #fff; }
    </style>

@section('main')
    <h1>Create Customer</h1>

    @if ($errors->any())
        <div style="border: 1px solid #f5c6cb; background:#f8d7da;padding:10px; margin-bottom:16px;border-radius:4px">
            <strong>There are some problems with your input:</strong>
            <ul style="margin:8px 0 0 18px">
                @foreach ($errors->all() as $error)
                 <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customers.store')}}">
        @csrf

        <div class="form-group">
            <label for="customer_code">Customer Code</label>
            <input id="customer_code" name="customer_code" type="text" value="{{old('customer_code')}}" required>
            @error('customer_code')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="first_name">First name</label>
            <input id="first_name" name="first_name" type="text" value="{{old('first_name')}}" required>
            @error('first_name')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="last_name">Last name</label>
            <input id="last_name" name="last_name" type="text" value="{{old('last_name')}}" required>
            @error('last_name')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">--Select--</option>
                <option value="Male"{{old('gender')=='Male' ? 'selected' : ''}}>Male</option>
                <option value="Female"{{old('gender')=='Female' ? 'selected' : ''}}>Female</option>
            </select>
            @error('gender')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input id="date_of_birth" name="date_of_birth" type="date" value="{{old('date_of_birth')}}" required>
            @error('date_of_birth')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="text" value="{{old('phone')}}" required>
            @error('phone')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{old('email')}}" required>
            @error('email')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3">{{old('address')}}</textarea>
            @error('address')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <input id="city" name="city" type="text" value ="{{old('city')}}" required>
            @error('city')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="Active" {{old('status', 'Active')=='Active' ? 'selected' : ''}}>Active</option>
                <option value="Inactive" {{old('status')=='Inactive' ? 'selected' : ''}}>Inactive</option>
            </select>
            @error('status')<div class="error">{{$message}}</div>@enderror
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Create customer</button>
            <a href="{{route('customers.index')}}" style="margin-left:12px" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
