<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisburseLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $loan = $this->route('loan');

            // Task 6 Validation: Prevent disbursing if already disbursed or not approved
            if ($loan->status === 'Disbursed') {
                $validator->errors()->add('status', 'This loan has already been disbursed.');
            } elseif ($loan->status !== 'Approved') {
                $validator->errors()->add('status', 'Only approved loans can be disbursed.');
            }
        });
    }
}