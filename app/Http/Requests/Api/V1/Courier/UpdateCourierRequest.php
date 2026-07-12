<?php

namespace App\Http\Requests\Api\V1\Courier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('couriers', 'email')->ignore($this->route('courier')),
            ],
            'phone' => ['sometimes', 'required', 'string', 'max:15'],
            'level' => ['sometimes', 'required', 'integer', 'in:1,2,3,4,5'],
        ];
    }
}
