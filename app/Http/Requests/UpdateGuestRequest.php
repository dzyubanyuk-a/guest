<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guestId = (int) $this->route('guest');

        return [
            'first_name' => 'sometimes|required|string|regex:/^[\p{L}\'\-\s]+$/u|max:100',
            'last_name' => 'sometimes|required|string|regex:/^[\p{L}\'\-\s]+$/u|max:100',
            'email' => 'sometimes|required|string|email|max:255|unique:guests,email,'.$guestId,
            'phone' => 'sometimes|required|regex:/^\+?[0-9\s\-\(\)]{7,15}$/|unique:guests,phone,'.$guestId,
            'country' => 'nullable|string|size:2',
        ];
    }
}
