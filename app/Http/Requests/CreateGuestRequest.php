<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|regex:/^[\p{L}\'\-\s]+$/u|max:100',
            'last_name' => 'required|string|regex:/^[\p{L}\'\-\s]+$/u|max:100',
            'email' => 'required|string|email|max:255|unique:guests,email',
            'phone' => 'required|regex:/^\+?[0-9\s\-\(\)]{7,15}$/|unique:guests,phone',
            'country' => 'nullable|string|size:2',
        ];
    }
}
