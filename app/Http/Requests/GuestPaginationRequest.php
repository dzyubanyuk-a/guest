<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestPaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer',
            'page' => 'nullable|integer',
        ];
    }
}
