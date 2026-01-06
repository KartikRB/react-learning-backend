<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-\(\)]{7,10}$/'],
            'password'       => ['required', 'string', 'min:8'],
            'confirm_pass'   => ['required', 'same:password'],
            'address_line_1' => ['required', 'string', 'max:500'],
            'address_line_2' => ['nullable', 'string', 'max:500'],
            'city'           => ['required', 'string', 'max:255'],
            'state'          => ['required', 'string', 'max:255'],
            'country'        => ['required', 'string', 'max:255'],
            'zip_code'       => ['required', 'string', 'max:50'],
            'date_of_birth'  => ['required', 'date', 'before:today'],
            'profile_image'  => ['nullable', 'image', 'max:2048'],
        ];
    }
}
