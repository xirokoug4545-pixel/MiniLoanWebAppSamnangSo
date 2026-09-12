@extends('Layouts.app')

@section('title', 'Apply Loan')

@section('main')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Apply for a Loan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="customer_id" class="block text-gray-700">Customer Name</label>
                        <select id="customer_id" name="customer_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select a customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                    {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->customer_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-gray-700">Loan Type</label>
                        <select id="category_id" name="category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select a loan type</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="principal_amount" class="block text-gray-700">Loan Amount ($)</label>
                        <input id="principal_amount" type="number" step="0.01" min="100" max="1000000" name="principal_amount" value="{{ old('principal_amount') }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="interest_rate" class="block text-gray-700">Interest Rate (% per month)</label>
                        <input id="interest_rate" type="number" step="0.01" name="interest_rate" value="{{ old('interest_rate', '2.00') }}" class="w-full border-gray-300 rounded-md bg-gray-100 shadow-sm" readonly required>
                        <p class="mt-1 text-sm text-gray-500">Up to $5,000: 2.00% · Up to $10,000: 1.70% · Above $10,000: 1.50%</p>
                    </div>

                    <div class="mb-4">
                        <label for="term_months" class="block text-gray-700">Term (Months)</label>
                        <input id="term_months" type="number" min="1" max="60" name="term_months" value="{{ old('term_months') }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Submit Application</button>
                </form>

                <script>
                    const principalAmountInput = document.getElementById('principal_amount');
                    const interestRateInput = document.getElementById('interest_rate');

                    principalAmountInput.addEventListener('input', () => {
                        const principalAmount = Number(principalAmountInput.value);
                        const interestRate = principalAmount <= 5000 ? 2 : principalAmount <= 10000 ? 1.7 : 1.5;

                        interestRateInput.value = Number.isFinite(principalAmount) && principalAmount > 0
                            ? interestRate.toFixed(2)
                            : '2.00';
                    });
                </script>

            </div>
        </div>
    </div>
@endsection