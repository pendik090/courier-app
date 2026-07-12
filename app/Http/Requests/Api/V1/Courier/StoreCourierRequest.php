<?php

namespace App\Http\Requests\Api\V1\Courier;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:couriers,email'],
            'phone' => ['required', 'string', 'max:15'],
            'level' => ['required', 'integer', 'in:1,2,3,4,5'],
        ];
    }
}
