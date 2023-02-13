<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'firstname' => 'string|max:255|required',
            'lastname' => 'string|max:255|required',
            'phone' => 'string|max:20|required',
            'birthdate' => 'required|date|before_or_equal:' . now(),
            'country' => 'required|integer'
        ];
    }
}
